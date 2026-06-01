// Wait for DOM to load
document.addEventListener('DOMContentLoaded', function() {
    // Get elements
    var stripeKey = document.getElementById('stripe-key').value;
    var totalAmount = document.getElementById('total-amount').value;
    var processUrl = document.getElementById('process-url').value;
    var csrfToken = document.getElementById('csrf-token').value;
    
    // Initialize Stripe
    var stripe = Stripe(stripeKey);
    var elements = stripe.elements();
    var card = elements.create('card');
    card.mount('#card-element');
    
    // Form elements
    var form = document.getElementById('payment-form');
    var submitBtn = document.getElementById('submit-btn');
    var cardErrors = document.getElementById('card-errors');
    
    // Handle form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Disable button and show processing
        submitBtn.disabled = true;
        submitBtn.textContent = 'Processing...';
        cardErrors.textContent = '';
        
        var cardholderName = document.getElementById('cardholder_name').value;
        
        // Create payment method
        stripe.createPaymentMethod({
            type: 'card',
            card: card,
            billing_details: {
                name: cardholderName
            }
        }).then(function(result) {
            if (result.error) {
                // Show error
                cardErrors.textContent = result.error.message;
                submitBtn.disabled = false;
                submitBtn.textContent = 'Pay $' + parseFloat(totalAmount).toFixed(2);
            } else {
                // Send to server
                fetch(processUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        payment_method_id: result.paymentMethod.id,
                        cardholder_name: cardholderName
                    })
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    if (data.success) {
                        // Redirect to confirmation page
                        window.location.href = data.redirect;
                    } else {
                        // Show server error
                        cardErrors.textContent = data.error;
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Pay $' + parseFloat(totalAmount).toFixed(2);
                    }
                })
                .catch(function(err) {
                    // Show network error
                    cardErrors.textContent = 'Network error. Please try again.';
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Pay $' + parseFloat(totalAmount).toFixed(2);
                });
            }
        });
    });
});