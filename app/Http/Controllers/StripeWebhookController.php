<?php

namespace App\Http\Controllers;


// app/Http/Controllers/StripeWebhookController.php

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();

        if ($payload['type'] === 'payment_intent.succeeded') {

            $intent = $payload['data']['object'];
            $paymentId = $intent['metadata']['payment_id'] ?? null;

            if (!$paymentId) {
                return response()->json(['error' => 'Missing payment ID'], 400);
            }

            DB::transaction(function () use ($paymentId) {

                $payment = Payment::find($paymentId);
                if (!$payment || $payment->status === 'paid') return;

                // ✅ Mark payment paid
                $payment->update(['status' => 'paid']);

                // ✅ Create enrollment
                DB::table('enrollments')->insert([
                    'session_id'  => $payment->session_id,
                    'student_id'  => $payment->user_id,
                    'paid_amount' => $payment->amount,
                    'status'      => 'paid',
                    'created_at'  => now(),
                    'updated_at'  => now()
                ]);
            });
        }

        return response()->json(['status' => 'ok']);
    }
}
