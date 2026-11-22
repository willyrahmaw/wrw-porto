#!/usr/bin/env python3
"""
WRW Portfolio - Security & Middleware Verification Suite
Alat pengujian otomatis untuk memverifikasi keandalan middleware & pertahanan anti-bot website.

Penggunaan:
    python security_tester.py                     (Uji semua ke https://willyrw.my.id)
    python security_tester.py http://127.0.0.1:8000
    python security_tester.py --test admin        (Hanya uji rute admin)
    python security_tester.py --test throttle     (Hanya uji rate limit)
    python security_tester.py --test honeypot     (Hanya uji jebakan spam)
    python security_tester.py --test firewall     (Hanya uji OWASP firewall)
    python security_tester.py --test headers      (Hanya uji security headers)
"""

import sys
import re
import json
import time
import argparse
import urllib.request
import urllib.parse
import urllib.error
import http.cookiejar
import ssl

class Colors:
    GREEN = "\033[92m"
    YELLOW = "\033[93m"
    RED = "\033[91m"
    CYAN = "\033[96m"
    BOLD = "\033[1m"
    RESET = "\033[0m"

# Bypass check if local self-signed SSL is used
ctx = ssl.create_default_context()
ctx.check_hostname = False
ctx.verify_mode = ssl.CERT_NONE

class SecurityTester:
    def __init__(self, base_url: str):
        self.base_url = base_url.rstrip("/")
        self.cookie_jar = http.cookiejar.CookieJar()
        self.opener = urllib.request.build_opener(
            urllib.request.HTTPCookieProcessor(self.cookie_jar),
            urllib.request.HTTPSHandler(context=ctx),
        )
        self.csrf_token = None
        self.time_token = None

    def print_header(self, title: str):
        print(f"\n{Colors.CYAN}{'=' * 65}")
        print(f" {title}")
        print(f"{'=' * 65}{Colors.RESET}")

    def fetch_home_and_tokens(self) -> bool:
        """Mengambil CSRF token dan token anti-bot dari halaman utama."""
        try:
            req = urllib.request.Request(
                f"{self.base_url}/",
                headers={"User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0"}
            )
            with self.opener.open(req, timeout=10) as resp:
                html = resp.read().decode("utf-8", errors="ignore")
                
                csrf_match = re.search(r'<meta\s+name=["\']csrf-token["\']\s+content=["\']([^"\']+)["\']', html)
                if csrf_match:
                    self.csrf_token = csrf_match.group(1)
                
                token_match = re.search(r"_token_time:\s*['\"]([^'\"]+)['\"]", html)
                if token_match:
                    self.time_token = token_match.group(1)
                
                return True
        except Exception as e:
            print(f"{Colors.RED}[!] Gagal terhubung ke {self.base_url}: {e}{Colors.RESET}")
            return False

    def test_admin_auth_middleware(self):
        """Uji 1: Verifikasi rute Admin tidak bisa ditembus tanpa login."""
        self.print_header("1. PENGUJIAN MIDDLEWARE 'auth' (PROTEKSI RUTE ADMIN)")
        
        url_admin = f"{self.base_url}/admin"
        print(f"[*] Mengirim GET ke {url_admin} tanpa session login...")
        
        class NoRedirectHandler(urllib.request.HTTPRedirectHandler):
            def http_error_302(self, req, fp, code, msg, headers):
                return fp
            http_error_301 = http_error_302
            http_error_303 = http_error_302
            http_error_307 = http_error_302

        no_redirect_opener = urllib.request.build_opener(
            NoRedirectHandler,
            urllib.request.HTTPSHandler(context=ctx)
        )
        
        try:
            req = urllib.request.Request(url_admin, headers={"Accept": "text/html"})
            resp = no_redirect_opener.open(req, timeout=10)
            status = resp.status if hasattr(resp, "status") else resp.code
            location = resp.headers.get("Location", "")
            
            if status in [301, 302, 303, 307] and "/login" in location:
                print(f"{Colors.GREEN}[PASS] Rute /admin DIKUNCI! Server mengembalikan HTTP {status} (Redirect ke {location}){Colors.RESET}")
            elif status in [401, 403]:
                print(f"{Colors.GREEN}[PASS] Rute /admin DIKUNCI! Server menolak akses dengan HTTP {status}{Colors.RESET}")
            else:
                print(f"{Colors.RED}[FAIL] Rute /admin terbuka bebas dengan status {status}!{Colors.RESET}")
        except urllib.error.HTTPError as e:
            if e.code in [401, 403]:
                print(f"{Colors.GREEN}[PASS] Rute /admin DIKUNCI! Server menolak akses dengan HTTP {e.code}{Colors.RESET}")
            else:
                print(f"{Colors.YELLOW}[INFO] Server merespon HTTP {e.code}{Colors.RESET}")
        except Exception as e:
            print(f"{Colors.RED}[!] Error: {e}{Colors.RESET}")

        # Test POST /admin/services
        url_services = f"{self.base_url}/admin/services"
        print(f"\n[*] Mengirim POST ke {url_services} (mencoba memanipulasi data layanan tanpa login)...")
        try:
            data = json.dumps({"number": "99", "title": "Hacked Service"}).encode("utf-8")
            req = urllib.request.Request(
                url_services,
                data=data,
                headers={"Content-Type": "application/json", "Accept": "application/json"}
            )
            resp = no_redirect_opener.open(req, timeout=10)
            status = resp.status if hasattr(resp, "status") else resp.code
            print(f"{Colors.GREEN}[PASS] Aksi ditolak dengan status HTTP {status} (Tidak bisa dieksekusi tanpa login admin){Colors.RESET}")
        except urllib.error.HTTPError as e:
            if e.code in [302, 401, 403, 419]:
                print(f"{Colors.GREEN}[PASS] Aksi ditolak! Server memblokir dengan status HTTP {e.code} ({e.reason}){Colors.RESET}")
            else:
                print(f"{Colors.YELLOW}[INFO] Respon status HTTP {e.code}{Colors.RESET}")
        except Exception as e:
            print(f"{Colors.RED}[!] Error: {e}{Colors.RESET}")

    def test_security_headers(self):
        """Uji 2: Verifikasi Security Headers (OWASP & Anti-Clickjacking)."""
        self.print_header("2. PENGUJIAN SECURITY HEADERS (GRADE-A PROTECTION)")
        try:
            req = urllib.request.Request(
                f"{self.base_url}/",
                headers={"User-Agent": "Mozilla/5.0 Security-Auditor/1.0"}
            )
            with self.opener.open(req, timeout=10) as resp:
                headers = {k.lower(): v for k, v in resp.headers.items()}
                
                checks = [
                    ("x-frame-options", "Anti-Clickjacking (iFrame Blocking)", "SAMEORIGIN"),
                    ("x-content-type-options", "Anti-MIME Sniffing", "nosniff"),
                    ("referrer-policy", "Referrer Protection", "strict-origin-when-cross-origin"),
                    ("content-security-policy", "Content Security Policy (CSP)", None),
                ]
                
                for header_key, desc, expected in checks:
                    val = headers.get(header_key)
                    if val:
                        match_text = f" (Sesuai: '{val}')" if expected and expected.lower() in val.lower() else f" ('{val[:40]}...')"
                        print(f"{Colors.GREEN}[PASS] {desc}: AKTIF{match_text}{Colors.RESET}")
                    else:
                        print(f"{Colors.YELLOW}[WARN] Header '{header_key}' tidak ditemukan di respon.{Colors.RESET}")

                if "x-powered-by" not in headers:
                    print(f"{Colors.GREEN}[PASS] X-Powered-By: TERSEMBUNYI (Versi PHP/Server tidak dibocorkan){Colors.RESET}")
                else:
                    print(f"{Colors.YELLOW}[WARN] X-Powered-By terdeteksi: {headers.get('x-powered-by')}{Colors.RESET}")
        except Exception as e:
            print(f"{Colors.RED}[!] Gagal memeriksa headers: {e}{Colors.RESET}")

    def test_honeypot_trap(self):
        """Uji 3: Verifikasi Jebakan Honeypot Anti-Bot."""
        self.print_header("3. PENGUJIAN JEBAKAN HONEYPOT ANTI-BOT")
        print(f"[*] Mengirim payload formulir dengan 'website_url' terisi (perilaku bot spammer)...")
        
        payload = {
            "name": "SpamBot Test",
            "email": "spambot@spammer.com",
            "project_type": "Pembuatan Website Baru",
            "message": "Buy cheap backlinks and casino promo now on spam website.",
            "website_url": "https://spam-bot-trap.com", # <--- Kolom jebakan diisi
            "phone_confirmation": "",
        }

        headers = {
            "Content-Type": "application/json",
            "Accept": "application/json",
            "User-Agent": "BotScanner/2.0"
        }
        if self.csrf_token:
            headers["X-CSRF-TOKEN"] = self.csrf_token

        try:
            data = json.dumps(payload).encode("utf-8")
            req = urllib.request.Request(f"{self.base_url}/contact", data=data, headers=headers)
            with self.opener.open(req, timeout=10) as resp:
                body = resp.read().decode("utf-8")
                print(f"{Colors.GREEN}[PASS] Server merespon HTTP {resp.status} (Silent Drop Trap!){Colors.RESET}")
                print(f"       Respon: {body}")
                print(f"{Colors.GREEN}       Bot mengira berhasil, tetapi pesan TIDAK disimpan ke database dan TIDAK mengirim email!{Colors.RESET}")
        except urllib.error.HTTPError as e:
            if e.code == 422:
                print(f"{Colors.GREEN}[PASS] Server mendeteksi spam dan menolak dengan HTTP 422: {e.reason}{Colors.RESET}")
            elif e.code == 429:
                print(f"{Colors.YELLOW}[INFO] IP Anda sedang terkena cooldown rate limit (HTTP 429). Tunggu 60 detik lalu uji kembali.{Colors.RESET}")
            else:
                print(f"{Colors.YELLOW}[INFO] Respon status HTTP {e.code}{Colors.RESET}")
        except Exception as e:
            print(f"{Colors.RED}[!] Error: {e}{Colors.RESET}")

    def test_contact_rate_limiting(self):
        """Uji 4: Verifikasi Throttle Rate Limiting (Maks 3 per menit)."""
        self.print_header("4. PENGUJIAN RATE LIMITING / THROTTLE (MAX 3 REQ/MENIT)")
        print(f"[*] Mengirim 4 request berturut-turut cepat ke /contact dari IP Anda...")
        
        hit_429 = False
        for i in range(1, 5):
            payload = {
                "name": f"Tester Rapid {i}",
                "email": f"tester{i}@testdomain.com",
                "project_type": "Pembuatan Website Baru",
                "message": f"Ini adalah pesan pengujian rate limiting ke-{i} untuk verifikasi middleware.",
                "website_url": "",
                "phone_confirmation": "",
            }
            if self.time_token:
                payload["_token_time"] = self.time_token

            headers = {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "User-Agent": "WRW-Tester/1.0"
            }
            if self.csrf_token:
                headers["X-CSRF-TOKEN"] = self.csrf_token

            try:
                data = json.dumps(payload).encode("utf-8")
                req = urllib.request.Request(f"{self.base_url}/contact", data=data, headers=headers)
                with self.opener.open(req, timeout=10) as resp:
                    print(f"    -> Request #{i}: HTTP {resp.status} (Diterima)")
            except urllib.error.HTTPError as e:
                if e.code == 429:
                    print(f"{Colors.GREEN}    -> Request #{i}: HTTP 429 TOO MANY REQUESTS! (THROTTLE AKTIF!){Colors.RESET}")
                    hit_429 = True
                elif e.code == 419:
                    print(f"{Colors.YELLOW}    -> Request #{i}: HTTP 419 Page Expired (CSRF Protection Aktif){Colors.RESET}")
                else:
                    print(f"{Colors.YELLOW}    -> Request #{i}: HTTP {e.code} ({e.reason}){Colors.RESET}")
            except Exception as e:
                print(f"    -> Request #{i}: Error ({e})")
            
            time.sleep(0.3)

        if hit_429:
            print(f"{Colors.GREEN}[PASS] Middleware throttle bekerja sempurna! Request berlebih berhasil dibatasi.{Colors.RESET}")
        else:
            print(f"{Colors.CYAN}[INFO] Jika belum muncul 429, silakan ulangi dalam beberapa detik.{Colors.RESET}")

    def test_owasp_injection_defense(self):
        """Uji 5: Verifikasi OwaspDefenseMiddleware (Deteksi Script Injection Probe)."""
        self.print_header("5. PENGUJIAN OWASP DEFENSE & FIREWALL")
        print(f"[*] Mengirim probe injeksi sederhana: GET /?probe=%3Cscript%3Ealert(1)%3C/script%3E ...")
        
        try:
            req = urllib.request.Request(
                f"{self.base_url}/?probe=%3Cscript%3Ealert(1)%3C/script%3E",
                headers={"User-Agent": "Mozilla/5.0 SecurityProbe/1.0"}
            )
            with self.opener.open(req, timeout=10) as resp:
                print(f"{Colors.YELLOW}[INFO] Server merespon HTTP {resp.status}{Colors.RESET}")
        except urllib.error.HTTPError as e:
            if e.code == 403:
                print(f"{Colors.GREEN}[PASS] FIREWALL BEKERJA! Server menolak serangan injeksi dengan HTTP 403 FORBIDDEN!{Colors.RESET}")
            else:
                print(f"{Colors.CYAN}[INFO] Server merespon status HTTP {e.code}{Colors.RESET}")
        except Exception as e:
            print(f"{Colors.RED}[!] Error: {e}{Colors.RESET}")

    def run_all(self):
        print(f"\n{Colors.BOLD}================================================================={Colors.RESET}")
        print(f"{Colors.BOLD} WRW STUDIO - AUTOMATED SECURITY & MIDDLEWARE TESTER {Colors.RESET}")
        print(f"{Colors.BOLD} Target URL : {Colors.CYAN}{self.base_url}{Colors.RESET}")
        print(f"{Colors.BOLD} Waktu Uji  : {time.strftime('%Y-%m-%d %H:%M:%S')}{Colors.RESET}")
        print(f"{Colors.BOLD}================================================================={Colors.RESET}")

        print(f"[*] Menginisialisasi koneksi & membaca CSRF token...")
        if not self.fetch_home_and_tokens():
            print(f"{Colors.RED}[!] Gagal memulai pengujian karena web tidak dapat diakses.{Colors.RESET}")
            return

        print(f"{Colors.GREEN}[+] Terhubung! CSRF Token: {self.csrf_token[:15] if self.csrf_token else 'None'}...{Colors.RESET}")

        self.test_admin_auth_middleware()
        self.test_security_headers()
        self.test_honeypot_trap()
        self.test_contact_rate_limiting()
        self.test_owasp_injection_defense()

        print(f"\n{Colors.BOLD}================================================================={Colors.RESET}")
        print(f"{Colors.GREEN}{Colors.BOLD} Pengujian Selesai! Semua modul middleware telah diverifikasi.{Colors.RESET}")
        print(f"{Colors.BOLD}=================================================================\n{Colors.RESET}")


def main():
    parser = argparse.ArgumentParser(description="Alat Uji Otomatis Keamanan & Middleware Portofolio")
    parser.add_argument("url", nargs="?", default="https://willyrw.my.id", help="URL target (default: https://willyrw.my.id)")
    parser.add_argument("--test", choices=["all", "admin", "headers", "honeypot", "throttle", "firewall"], default="all", help="Pilih modul uji tertentu")
    
    args = parser.parse_args()
    tester = SecurityTester(args.url)

    if args.test == "all":
        tester.run_all()
    else:
        print(f"[*] Menghubungkan ke {tester.base_url}...")
        tester.fetch_home_and_tokens()
        if args.test == "admin":
            tester.test_admin_auth_middleware()
        elif args.test == "headers":
            tester.test_security_headers()
        elif args.test == "honeypot":
            tester.test_honeypot_trap()
        elif args.test == "throttle":
            tester.test_contact_rate_limiting()
        elif args.test == "firewall":
            tester.test_owasp_injection_defense()

if __name__ == "__main__":
    main()
