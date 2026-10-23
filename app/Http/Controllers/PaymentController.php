<?php



namespace App\Http\Controllers;



use App\Models\LearningSession;

use Illuminate\Http\Request;

use App\Models\Payment;

use Illuminate\Support\Facades\Auth;

use Stripe\Stripe;

use Stripe\PaymentIntent;



class PaymentController extends Controller

{

    public function checkout($id)

    {

        $session = LearningSession::findOrFail($id);



        if ((float)$session->base_fee <= 0) {

            return redirect()

                ->route('student.sessions.show', $id)

                ->with('error', 'This is a free session.');

        }



        return view('payments.checkout', compact('session'));

    }



    public function create(Request $request)

    {

        $request->validate([

            'session_id' => 'required|exists:learning_sessions,id'

        ]);



        // ✅ FIXED: Session::findOrFail -> LearningSession::findOrFail

        $session = LearningSession::findOrFail($request->session_id);



        if ((float)$session->base_fee <= 0) {

            abort(403, 'Free session');

        }



        Stripe::setApiKey(config('services.stripe.secret'));



        $payment = Payment::create([

            'user_id' => Auth::id(),

            'session_id' => $session->id,

            'amount' => $session->base_fee,

            'status' => 'pending'

        ]);



        $intent = PaymentIntent::create([

            'amount' => (int) ($session->base_fee * 100),

            'currency' => 'usd',

            'metadata' => [

                'payment_id' => $payment->id

            ]

        ]);



        $payment->update([

            'payment_intent_id' => $intent->id

        ]);



        return response()->json([

            'clientSecret' => $intent->client_secret

        ]);

    }

}