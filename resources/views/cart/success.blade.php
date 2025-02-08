<script>
    document.addEventListener('DOMContentLoaded', function() {
        var PAYMENTID = <?php echo json_encode($paymentMethodId); ?>;
        var ID = <?php echo json_encode($id); ?>;
        var savedData = JSON.parse(localStorage.getItem('orderForm'));

        if (savedData) {
            var form = document.getElementById('order-form');
            
            if (form) {
                var paymentInput = document.createElement('input');
                paymentInput.setAttribute('type', 'hidden');
                paymentInput.setAttribute('name', 'stripePaymentId');
                paymentInput.setAttribute('value', PAYMENTID);
                form.appendChild(paymentInput);

                var randomID = document.createElement('input');
                randomID.setAttribute('type', 'hidden');
                randomID.setAttribute('name', 'randomID');
                randomID.setAttribute('value', ID);
                form.appendChild(randomID);

                Object.keys(savedData).forEach(key => {
                    var input = document.createElement('input');
                    input.setAttribute('type', 'hidden');
                    input.setAttribute('name', key);
                    input.setAttribute('value', savedData[key]);
                    form.appendChild(input);
                });
                localStorage.removeItem('orderForm');
                form.submit();
            } else {
                console.error('Form not found in DOM.');
            }
        }
    });
</script>
<form id="order-form" role="form" method="post" action="{{ route('order.store') }}" style="display: none;" autocomplete="off" enctype="multipart/form-data">
    @csrf
</form>
