<!-- STRIPE -->
@if (config('settings.stripe_key') && config('settings.enable_stripe'))
    <form  id="stripe-payment-form" style="display: {{ config('settings.default_payment')=="stripe" ? "block" : "none" }};" >    
        <input type="hidden" value="{{ __( 'Pay Now' ) }}" id="ali">
        <div class="text-center" id="totalSubmitStripe">
            <i id="indicatorStripe" style="display: none" class="fa fa-spinner fa-spin"></i>
            <button type="submit" id="stripeSend" class="btn btn-success mt-4 paymentbutton">
            </button>
        </div>

    </form>
@endif

