<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReceiveWebhook;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function receive(Request $request): JsonResponse
    {
        $raw = $request->getContent();
        $payload = json_decode($raw, true);

        if (!is_array($payload)) {
            Log::warning('[Webhook] JSON parse failed', ['raw' => substr($raw, 0, 500)]);
            return response()->json(['status' => 'invalid_json'], 200);
        }

        if (isset($payload['body']) && is_array($payload['body'])) {
            $payload = $payload['body'];
        }

        $data = $payload['data'] ?? $payload;

        $this->saveWebhook($payload, $data);

        Log::info('[Webhook Evolution] Payload recebido:', [
            'event' => $payload['event'] ?? 'none',
            'instance' => $payload['instance'] ?? 'none',
        ]);

        return response()->json(['status' => 'ok']);
    }

    private function saveWebhook(array $payload, array $data): void
    {
        try {
            $phone = null;
            $senderPn = $data['key']['senderPn'] ?? null;
            $remoteJid = $data['key']['remoteJid'] ?? null;

            if ($senderPn) {
                $phone = str_replace(['@s.whatsapp.net', '@lid'], '', $senderPn);
                $phone = preg_replace('/\D/', '', $phone);
            } elseif ($remoteJid) {
                $phone = str_replace(['@s.whatsapp.net', '@lid'], '', $remoteJid);
                $phone = preg_replace('/\D/', '', $phone);
            }

            $message = $data['message']['conversation']
                ?? $data['message']['extendedTextMessage']['text']
                ?? null;

            ReceiveWebhook::create([
                'instance' => $payload['instance'] ?? null,
                'event' => $payload['event'] ?? null,
                'sender_phone' => $phone,
                'remote_jid' => $remoteJid,
                'from_me' => $data['key']['fromMe'] ?? false,
                'message_content' => $message ? trim($message) : null,
                'payload' => $payload,
            ]);
        } catch (\Exception $e) {
            Log::error('[Webhook] Erro ao salvar: ' . $e->getMessage());
        }
    }
}
