<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Mail\ContactMessageReceived;
use App\Models\Message;
use App\Models\Profile;
use App\Services\SpamDetectorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * Store incoming contact inquiry and send email notification.
     */
    public function store(StoreContactRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // Multi-Layer Anti-Bot & Anti-Spam Verification
        $spamCheck = SpamDetectorService::check($request, $validated);
        if ($spamCheck['is_spam']) {
            // Return silent 200 OK so bots believe their payload succeeded,
            // but discard it without polluting database or firing emails.
            return response()->json([
                'status' => 'success',
                'message' => 'Terima kasih! Pesan Anda telah diterima. Saya biasanya membalas dalam waktu kurang dari 24 jam.',
            ], 200);
        }

        $contactMessage = Message::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'project_type' => $validated['project_type'],
            'budget_range' => $validated['budget_range'] ?? null,
            'message' => $validated['message'],
            'ip_address' => $request->ip(),
        ]);

        // Send email notification to portfolio owner
        try {
            $recipient = Profile::query()->value('email') ?? config('mail.from.address');
            if ($recipient) {
                Mail::to($recipient)->send(new ContactMessageReceived($contactMessage));
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to send contact notification email: ' . $e->getMessage());
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Terima kasih! Pesan Anda telah diterima. Saya biasanya membalas dalam waktu kurang dari 24 jam.',
        ], 201);
    }
}

