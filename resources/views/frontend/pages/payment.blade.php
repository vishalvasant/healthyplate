<!DOCTYPE html>
<html>
<head>
    <title>Square Payment</title>
    <script src="https://js.squareup.com/v2/paymentform"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const paymentForm = new SqPaymentForm({
                applicationId: "{{ env('SQUARE_APPLICATION_ID') }}",
                locationId: "{{ env('SQUARE_LOCATION_ID') }}",
                inputClass: 'sq-input',
                autoBuild: false,
                cardNumber: {
                    elementId: 'sq-card-number',
                    placeholder: 'Card Number',
                },
                cvv: {
                    elementId: 'sq-cvv',
                    placeholder: 'CVV',
                },
                expirationDate: {
                    elementId: 'sq-expiration-date',
                    placeholder: 'MM/YY',
                },
                postalCode: {
                    elementId: 'sq-postal-code',
                    placeholder: 'Postal',
                },
                callbacks: {
                    cardNonceResponseReceived: function (errors, nonce, cardData) {
                        if (errors) {
                            alert("Error: " + JSON.stringify(errors));
                        } else {
                            document.getElementById('card-nonce').value = nonce;
                            document.getElementById('payment-form').submit();
                        }
                    }
                }
            });
            paymentForm.build();

            document.getElementById('submit-button').addEventListener('click', function (event) {
                event.preventDefault();
                paymentForm.requestCardNonce();
            });
        });
    </script>
</head>
<body>

<form id="payment-form" action="{{ route('payment.create') }}" method="post">
    @csrf
    <div id="sq-card-number"></div>
    <div id="sq-expiration-date"></div>
    <div id="sq-cvv"></div>
    <div id="sq-postal-code"></div>

    <input type="hidden" id="card-nonce" name="nonce">

    <button id="submit-button">Pay</button>
</form>

</body>
</html>
