<div class="card card-profile shadow mt--300">
    <div class="px-4">
      <div class="mt-5">
        <h3>{{ __('Checkout') }}<span class="font-weight-light"></span></h3>
      </div>
      <div  class="border-top">
        <!-- Price overview -->
        <div id="totalPrices" v-cloak>
            <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <span v-if="itemsCount==0">{{ __('Cart is empty') }}!</span>

                            <span v-if="itemsCount"><strong>{{ __('Subtotal') }}:</strong></span>
                            <span v-if="itemsCount" class="ammount"><strong>@{{ totalPriceFormat }}</strong></span>
                            @if(config('app.isft')||config('settings.is_whatsapp_ordering_mode')|| in_array("poscloud", config('global.modules',[])) || in_array("deliveryqr", config('global.modules',[])) )
                                <span v-if="itemsCount&&deliveryPrice>0"><br /><strong>{{ __('Delivery') }}:</strong></span>
                                <span v-if="itemsCount&&deliveryPrice>0" class="ammount"><strong>@{{ deliveryPriceFormated }}</strong></span><br />
                            @endif
                            <br />
			    <div v-if="pickupdeduct"> 
                                <span v-if="pickupdeduct">{{ __('Pickup discount by') }} {{ $restorant->pick_up_discount }} % = </span>
                                <span v-if="pickupdeduct" class="">@{{ pickdeductFormat }}</span>
                                <br />  
                            </div>  
                            <div v-if="deduct"> 
                                <span v-if="deduct">{{ __('Applied coupon discount') }}:</span>
                                <span v-if="deduct" class="ammount">@{{ deductFormat }}</span>
                                <br />  
                            </div>

                            <div v-if="tip"> 
                                <span v-if="tip">{{ __('Applied tip') }}:</span>
                                <span v-if="tip" class="">@{{ tipFormat }}</span>
                                <br />  
                            </div>
                           
                            <span v-if="itemsCount"><strong>{{ __('TOTAL') }}:</strong></span>
                            <span v-if="itemsCount" class="ammount"><strong>@{{ withDeliveryFormat   }}</strong></span>
                            <input v-if="itemsCount" type="hidden" id="tootalPricewithDeliveryRaw" :value="withDelivery" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End price overview -->

        @if(in_array("coupons", config('global.modules',[])))
            <!-- Coupons -->
            @include('cart.coupons')
            <!-- End coupons -->
        @endif

        @if(in_array("tips", config('global.modules',[])))
            <!-- Tip -->
            @include('cart.tips')
            <!-- End tip -->
        @endif


        <!-- Payment  Methods -->
        <div class="cards">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <!-- Errors on Stripe -->
                        @if (session('error'))
                            <div role="alert" class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        @if(!config('settings.is_whatsapp_ordering_mode'))
                        <!-- COD -->
                        @if (!config('settings.hide_cod'))
                            <div class="custom-control custom-radio mb-3" id="simple_cod">
                                <input name="paymentType" class="custom-control-input" id="cashOnDelivery1" type="radio" value="cod" 
                                    {{ config('settings.default_payment')=="cod"?"checked":""}}>
                                <label class="custom-control-label" for="cashOnDelivery1">
                                    <span class="delTime">{{ config('app.isqrsaas')?__('Cash / Card Terminal'): __('Cash on delivery') }}</span>
                                    <span class="picTime">{{ __('Cash on pickup') }}</span>
                                </label>
                            </div>

                            <div class="custom-control custom-radio mb-3" id="special_cod">
                                <input name="paymentType" class="custom-control-input" id="cashOnDelivery2" type="radio" value="cod">
                                <input name="specialCod" class="custom-control-input specialCodInput" id="specialCod" type="hidden" value="1">
                                <label class="custom-control-label" for="cashOnDelivery2">
                                    <span class="delTime">{{ config('app.isqrsaas')?__('Cash / Card Terminal'): __('Pay by card by delivery') }}</span>
                                    <span class="picTime">{{ __('Pay by card by pickup') }}</span>
                                </label>
                            </div>
                        @endif

                        @if($enablePayments)

                            <!-- STIPE CART -->
                            @if (config('settings.stripe_key')&&config('settings.enable_stripe'))
                                <div class="custom-control custom-radio mb-3">
                                    <input name="paymentType" class="custom-control-input" id="paymentStripe" type="radio" value="stripe" {{ config('settings.default_payment')=="stripe"?"checked":""}}>
                                    <label class="custom-control-label" for="paymentStripe">{{ __('Pay with card') }}</label>
                                </div>
                            @endif

                            <!-- Extra Payments ( Via module ) -->
                            @foreach ($extraPayments as $extraPayment)
                                @include($extraPayment.'::selector')
                            @endforeach


                        @endif

                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- END Payment -->

        <div class="text-center">
            <div class="custom-control custom-checkbox mb-3">
                <input class="custom-control-input" id="privacypolicy" type="checkbox">
                <!--<label class="custom-control-label" for="privacypolicy">{{ __('I agree to the Terms and Conditions and Privacy Policy') }}</label>-->
                <label class="custom-control-label" for="privacypolicy">
                    &nbsp;&nbsp;{{__('I agree to the')}}
                    <a href="{{config('settings.link_to_ts')}}" target="_blank" style="text-decoration: underline;">{{__('Terms of Service')}}</a> {{__('and')}}
                    <a href="{{config('settings.link_to_pr')}}" target="_blank" style="text-decoration: underline;">{{__('Privacy Policy')}}</a>.
                </label>
            </div>
        </div><br />

        <!-- Payment Actions -->
        @if(!config('settings.social_mode')||config('settings.do_regular_checkout_social',false))

            <!-- COD -->
            @include('cart.payments.cod')

            <!-- Extra Payments ( Via module ) -->
            @foreach ($extraPayments as $extraPayment)
                @include($extraPayment.'::button')
            @endforeach

            </form>

            <!-- Stripe -->
            @include('cart.payments.stripe')

            

        @elseif(config('settings.is_whatsapp_ordering_mode'))
            @include('cart.payments.whatsapp')
        @endif
        <!-- END Payment Actions -->

        <br/>
        

      </div>
      <br />
      <br />
    </div>
  </div>

  @if(config('settings.is_demo') && config('settings.enable_stripe'))
    @include('cart.democards')
  @endif


  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>

$(document).ready(function() {
    updatePrice();
    setInterval(updatePrice, 1000);
});

document.addEventListener("DOMContentLoaded", function () {
        const simpleCod = document.getElementById("cashOnDelivery1");
        const specialCod = document.getElementById("cashOnDelivery2");
        const specialCodInput = document.getElementById("specialCod");

        simpleCod.addEventListener("change", function () {
            if (specialCodInput) {
                specialCodInput.remove();
            }
        });

        specialCod.addEventListener("change", function () {
            let hiddenInput = document.createElement("input");
            hiddenInput.type = "hidden";
            hiddenInput.name = "specialCod";
            hiddenInput.id = "specialCod";
            hiddenInput.value = "1";
            specialCod.parentElement.appendChild(hiddenInput);
        });
    });


function updatePrice() {
    let total = cartTotal.withDeliveryFormat; 
    let totalPrice = total;
    var payNowText =  $("#ali").val();
    if (totalPrice) {
        var element = $(".paymentbutton");
        element.text(`${payNowText} ${totalPrice}`);
    } else {
        console.log("No value found for totalPrice");
    }
}

</script>

