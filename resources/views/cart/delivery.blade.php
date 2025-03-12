<div class="card card-profile shadow">
    <div class="px-4">
        <div class="mt-5">
            <h3>{{ __('Delivery / Pickup') }}<span class="font-weight-light"></span></h3>
        </div>
        <div class="card-content border-top">
            <br />

            <div class="custom-control custom-radio mb-3">
                <input name="deliveryType" class="custom-control-input" id="deliveryTypeDeliver" type="radio" value="delivery" checked onclick="pick_discount_removed()">
                <label class="custom-control-label" for="deliveryTypeDeliver">{{ __('Delivery') }}</label>
            </div>

            @if (!empty($restorant->pick_up_discount))
                <div class="custom-control custom-radio mb-3">
                    <input name="deliveryType" class="custom-control-input" id="deliveryTypePickup" type="radio" value="pickup">
                    <label class="custom-control-label" for="deliveryTypePickup" onclick="pick_discount_applied({{ $restorant->pick_up_discount }})">
                        {{ __('Save') }} {{ $restorant->pick_up_discount }}% {{ __('by picking up your order') }} .
                    </label>
                </div>
            @else
                <div class="custom-control custom-radio mb-3">
                    <input name="deliveryType" class="custom-control-input" id="deliveryTypePickup" type="radio" value="pickup">
                    <label class="custom-control-label" for="deliveryTypePickup" onclick="pick_discount_applied(0)">
                        {{ __('Pickup') }}
                    </label>
                </div>
            @endif

        </div>
        <br />
        <br />
    </div>
</div>
<br />

<script>
  document.addEventListener("DOMContentLoaded", function () {
    setPickUpDeduction(cartTotal.totalPrice);
  });
  
  function pick_discount_applied(value) {
    let totalPrice = cartTotal.totalPrice; 
    let discountAmount = (totalPrice * value) / 100;
    let newTotal = totalPrice - discountAmount;
    setPickUpDeduction(newTotal);
  }
  
  function pick_discount_removed(){
    cartTotal.pickupdeduct = null;
    setPickUpDeduction(newTotal);
  }
</script>
