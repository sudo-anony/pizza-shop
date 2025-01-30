<!-- STRIPE -->
@if (config('settings.stripe_key') && config('settings.enable_stripe'))
    <div  id="stripe-payment-form" style="display: {{ config('settings.default_payment')=="stripe" ? "block" : "none" }};" >    
        <input type="hidden" value="{{ __( 'Pay Now' ) }}" id="ali">
        <div class="text-center" id="totalSubmitStripe">
            <i id="indicatorStripe" style="display: none" class="fa fa-spinner fa-spin"></i>
            <button type="submit" id="stripeSend" class="btn btn-success mt-4 paymentbutton">
            </button>
        </div>

    </div>

    <form action="/charge" method="post" id="stripe-payment-form" style="display: {{ config('settings.default_payment')=="stripe" ? "block" : "none" }};" >

        <div class="text-center" id="totalSubmitStripe">
            <i id="indicatorStripe" style="display: none" class="fa fa-spinner fa-spin"></i>
            <button type="submit" id="stripeSend1" class="btn btn-success mt-4 paymentbutton"  style="display: none">
                {{ __('Pay Now') }} - <span id="totalPriceText"></span>
            </button>
        </div>

    </form>


@endif

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  $(document).ready(function() {
    let button = $("#stripeSend");

    button.click(function(event) {
        // Prevent the default button action
        event.preventDefault();
        
        // Trigger the second form submission
        $("#stripe-payment-form").submit();
    });
  });
</script>
