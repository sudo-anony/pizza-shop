<style>
.select2-container {
    box-sizing: border-box;
    display: inline-block;
    margin: 0;
    position: relative;
    vertical-align: middle;
    width: 100% !important;
}

</style>
<div class="card card-profile shadow">
    <div class="px-4">
      <div class="mt-5">
        <h3>{{ __('Delivery Address') }}<span class="font-weight-light"></span></h3>
      </div>
      <div class="card-content border-top">
        <br />
        <input type="hidden" value="{{$restorant->id}}" id="restaurant_id"/>
        <div class="form-group{{ $errors->has('addressID') ? ' has-danger' : '' }}">
            @if(count($addresses))
            
                <select name="addressID" id="addressID" class="form-control{{ $errors->has('addressID') ? ' is-invalid' : '' }}  noselecttwo" required onchange="handleAddressChange(this)">
                    <option disabled selected value> {{__('-- select an option -- ')}}</option>
                    @foreach ($addresses as $key => $address)
                        @if(config('settings.enable_cost_per_distance'))
                            <option data-cost={{ $address->cost_per_km}} id="{{ 'address'.$address->id }}"   value={{ $address->id }}>{{$address->address." - ".money( $address->cost_per_km, config('settings.cashier_currency'),config('settings.do_convertion')) }} @if (!$address->inRadius)- {{ __('Not in range') }}@endif</option>
                        @else
                    <option data-cost={{ config('global.delivery')}} id="{{ 'address'.$address->id }}"   value={{ $address->id }}>{{$address->name . " - " . $address->phone . " - " . $address->companyname . " - " . $address->departmentname . " - " . $address->street . " - " . $address->zip . " - " . $address->city }} @if (!$address->inRadius)- {{ __('Not in range') }}@endif</option>
                        @endif
                    @endforeach
                </select>
                @if ($errors->has('addressID'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('addressID') }}</strong>
                    </span>
                @endif
            @else
                <h6 id="address-complete-order">{{ __('You don`t have any address. Please add new one') }}.</h6>
            @endif
        </div>
        <div class="form-group">
            <button type="button" data-toggle="modal" data-target="#modal-order-new-address-new"  class="btn btn-outline-success">{{ __('Add new') }}</button>
        </div>
        <input type="hidden" name="deliveryCost" id="deliveryCost" value="0" />
      </div>
      <br />
      <br />
    </div>
  </div>
  <br />

  <script>
function handleAddressChange(select) {
    console.log("Selected Value:", select.value);
    let paymentButton = document.querySelector('.paymentbutton');
    if (select.value) {
        if ($('#deliveryTypeDeliver').is(':checked')){
            $('.paymentbutton').attr("disabled", false);
            return 
        }
       $('.paymentbutton').attr("disabled", true);
    } else {
       $('.paymentbutton').attr("disabled", true);
    }
}

</script>