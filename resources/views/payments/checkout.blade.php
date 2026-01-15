@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-20 bg-white dark:bg-slate-800 p-8 rounded-2xl shadow-xl">

    <h2 class="text-2xl font-extrabold mb-4 text-gray-800 dark:text-white">
        Pay for {{ $session->title }}
    </h2>

    <p class="text-lg font-bold text-indigo-600 mb-6">
        Amount: LKR {{ number_format($session->base_fee) }}
    </p>

    {{-- Stripe Card Element --}}
    <div id="card-element"
         class="p-4 border rounded-lg dark:bg-slate-700 dark:border-slate-600 mb-4">
    </div>

    <div id="card-errors" class="text-red-500 text-sm mb-4"></div>

    <button id="payBtn"
            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl">
        Pay Now
    </button>

</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe("{{ config('services.stripe.key') }}");
    const elements = stripe.elements();
    const card = elements.create('card');
    card.mount('#card-element');

    const payBtn = document.getElementById('payBtn');
    const errorDiv = document.getElementById('card-errors');

    payBtn.addEventListener('click', async () => {

        payBtn.disabled = true;
        payBtn.textContent = 'Processing...';
        errorDiv.textContent = '';

        try {
            const response = await fetch("{{ url('/payments/create') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    session_id: {{ $session->id }}
                })
            });

            if (!response.ok) {
                throw new Error('Payment initialization failed');
            }

            const data = await response.json();

            const result = await stripe.confirmCardPayment(
                data.clientSecret,
                {
                    payment_method: { card: card }
                }
            );

            if (result.error) {
                errorDiv.textContent = result.error.message;
                payBtn.disabled = false;
                payBtn.textContent = 'Pay Now';
            } else if (result.paymentIntent.status === 'succeeded') {
                window.location.href = "{{ route('student.learning') }}";
            }

        } catch (error) {
            errorDiv.textContent = error.message;
            payBtn.disabled = false;
            payBtn.textContent = 'Pay Now';
        }
    });
</script>
@endsection
