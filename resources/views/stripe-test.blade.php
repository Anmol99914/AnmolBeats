<!-- i love uuuuuuuu stripe test file -->
<!DOCTYPE html>
<html>
<head>
    <title>Stripe Test</title>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    <h1>Simple Stripe Test</h1>
    
    <form id="payment-form">
        @csrf
        <div id="card-element"></div>
        <div id="card-errors"></div>
        <button type="submit" id="submit-btn">Pay $10.00</button>
    </form>
    
    <div id="result"></div>

    <script>
        var stripe = Stripe('{{ config("services.stripe.key") }}');
        var elements = stripe.elements();
        var card = elements.create('card');
        card.mount('#card-element');
        
        var form = document.getElementById('payment-form');
        var submitBtn = document.getElementById('submit-btn');
        
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            submitBtn.disabled = true;
            submitBtn.textContent = 'Processing...';
            document.getElementById('result').innerHTML = 'Creating payment method...';
            
            const { paymentMethod, error } = await stripe.createPaymentMethod({
                type: 'card',
                card: card,
            });
            
            if (error) {
                document.getElementById('result').innerHTML = 'Error: ' + error.message;
                submitBtn.disabled = false;
                submitBtn.textContent = 'Pay $10.00';
            } else {
                document.getElementById('result').innerHTML = 'Payment Method ID: ' + paymentMethod.id + '<br>Sending to server...';
                
                // Send to your server
                fetch('{{ route("stripe.test.process") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        payment_method_id: paymentMethod.id
                    })
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('result').innerHTML = 'Response: ' + JSON.stringify(data);
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Pay $10.00';
                })
                .catch(err => {
                    document.getElementById('result').innerHTML = 'Fetch error: ' + err.message;
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Pay $10.00';
                });
            }
        });
    </script>
</body>
</html>