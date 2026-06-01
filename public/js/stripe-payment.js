var stripeKey = document.getElementById('stripe-key').value;
var stripe = Stripe(stripeKey);
var elements = stripe.elements();
var cardElement = elements.create('card');
cardElement.mount('#card-element');

var form = document.getElementById('payment-form');
var submitBtn = document.getElementById('submit-btn');
var originalText = submitBtn ? submitBtn.innerText : 'Pay';

if (form) {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        submitBtn.disabled = true;
        submitBtn.textContent = 'Processing...';

        var name = document.querySelector('input[name="cardholder_name"]').value;

        stripe.createPaymentMethod({
            type: 'card',
            card: cardElement,
            billing_details: { name: name }
        }).then(function(result) {
            if (result.error) {
                var errorElement = document.getElementById('card-errors');
                if (errorElement) {
                    errorElement.textContent = result.error.message;
                }
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            } else {
                // Remove any previously appended hidden input to avoid duplicates
                var existing = form.querySelector('input[name="payment_method_id"]');
                if (existing) existing.remove();

                var hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'payment_method_id');
                hiddenInput.setAttribute('value', result.paymentMethod.id);
                form.appendChild(hiddenInput);

                form.submit();
            }
        });
    });
}