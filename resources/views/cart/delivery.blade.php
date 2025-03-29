<div class="card card-profile shadow">
    <div class="px-4">
        <div class="mt-5">
            <h3>{{ __('Delivery / Pickup') }}<span class="font-weight-light"></span></h3>
        </div>
        <div class="card-content border-top">
            <br />
            @if (!empty($restorant->pick_up_discount && $restorant->pick_up_discount > 0))
                <div class="custom-control custom-radio mb-3">
                    <input name="deliveryType" class="custom-control-input" id="deliveryTypeDeliver" type="radio" value="delivery" onclick="pick_discount_removed()">
                    <label class="custom-control-label" for="deliveryTypeDeliver">{{ __('Delivery') }}</label>
                </div>
                <div class="custom-control custom-radio mb-3">
                    <input name="deliveryType" class="custom-control-input" id="deliveryTypePickup" type="radio" checked value="pickup">
                    <label class="custom-control-label" for="deliveryTypePickup" onclick="pick_discount_applied({{ $restorant->pick_up_discount }})">
                        {{ _('Save') }} {{ $restorant->pick_up_discount }}% {{ _('by picking up your order') }} .
                    </label>
                </div>
            @else
                <div class="custom-control custom-radio mb-3">
                    <input name="deliveryType" class="custom-control-input" id="deliveryTypeDeliver" type="radio" value="delivery" checked onclick="pick_discount_removed()">
                    <label class="custom-control-label" for="deliveryTypeDeliver">{{ __('Delivery') }}</label>
                </div>
                <div class="custom-control custom-radio mb-3">
                    <input name="deliveryType" class="custom-control-input" id="deliveryTypePickup" type="radio" value="pickup">
                    <label class="custom-control-label" for="deliveryTypePickup" onclick="pick_discount_removed()">
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
    let pickupDiscount = {{ $restorant->pick_up_discount ?? 0 }};
    setTimeout(() => {
      if (cartTotal && cartTotal.totalPrice) {
        setPickUpDeduction(cartTotal.totalPrice);
        if (pickupDiscount > 0) {
          orderTypeSwither('pickup');
          pick_discount_applied(pickupDiscount);
        }
      }
    }, 1000); 
  });
  
  function pick_discount_applied(value) {
    $("#privacypolicy").prop("checked", false);
    $('.paymentbutton').attr("disabled", true);
    let totalPrice = cartTotal.totalPrice; 
    let discountAmount = (totalPrice * value) / 100;
    setPickUpDeduction(discountAmount);
    setTimeout(() => {
      let form = document.querySelector("#order-form"); 
      if (!form) {
        console.error("Form element not found");
        return;
      }

      let hiddenField = document.getElementById("pickup_discount");
      if (!hiddenField) {
        hiddenField = document.createElement("input");
        hiddenField.type = "hidden";
        hiddenField.id = "pickup_discount";
        hiddenField.name = "pickup_discount"; 
        form.appendChild(hiddenField);
      }
      hiddenField.value = discountAmount;
    }, 500);
  }
  
  function pick_discount_removed(){
    $("#privacypolicy").prop("checked", false);
    $('.paymentbutton').attr("disabled", true);
    cartTotal.pickupdeduct = null;
    setPickUpDeduction(null);
    let hiddenField = document.getElementById("pickup_discount");
    if (hiddenField) {
      hiddenField.remove();
    }
  }
</script>