"use strict";

window.onload?window.onload():console.log("No other windowonload foound");
window.onload = function () {
    checkPrivacyPolicy();
    initAddress();
    initCOD();
    disableFunctions();

    if (ENABLE_STRIPE) {
        
        initStripePayment();
         
    }
}
var disableFunctions=function(){
    if(SYSTEM_IS_WP=="1"){
       
        disableFunctionsWP();
    }
    if(SYSTEM_IS_QR=="1"){
        
        disableFunctionsQR();
    }
    
}
var disableFunctionsWP=function(){
    var DISABLE_DELIVERY=(RESTORANT.can_deliver == 0);
    var DISABLE_PICKUP=(RESTORANT.can_pickup == 0);
    if(DISABLE_DELIVERY){
        $('input:radio[name=deliveryType][value=delivery]').attr('disabled', true);
    }
    if(DISABLE_PICKUP){
        $('input:radio[name=deliveryType][value=pickup]').attr('disabled', true);
    }
    if(DISABLE_DELIVERY||DISABLE_PICKUP){
        $("input:radio[name=deliveryType]:not(:disabled):first").attr('checked', true);
        orderTypeSwither($('input[name="deliveryType"]:checked').val());
    }
}
var disableFunctionsQR=function(){
    
    var DISABLE_DELIVERY=(RESTORANT.can_deliver == 0);
    var DISABLE_PICKUP=(RESTORANT.can_pickup == 0);
    var DISABLE_DINEIN=(RESTORANT.can_dinein==0)


    //dineType
    if(DISABLE_DELIVERY){
      $('input:radio[name=dineType][value=delivery]').attr('disabled', true);
    }
    if(DISABLE_PICKUP){
      $('input:radio[name=dineType][value=takeaway]').attr('disabled', true);
    }
    if(DISABLE_DINEIN){
      $('input:radio[name=dineType][value=dinein]').attr('disabled', true);
    }
    if(DISABLE_DELIVERY||DISABLE_PICKUP||DISABLE_DINEIN){
        $("input:radio[name=dineType]:not(:disabled):first").attr('checked', true);
        //alert($('input[name="dineType"]:checked').val());
        $('.delTimeTS').hide();
        $('.picTimeTS').show();
        dineTypeSwitch($('input[name="dineType"]:checked').val());
        //$("input:radio[name=dineType]").trigger("change");
    }

  
   
   // $("input:radio[name=deliveryType]:not(:disabled):first").attr('checked', true);

  
    
    //$("input:radio[name=deliveryType]").trigger("change");
  }

var checkPrivacyPolicy = function(){
    if (!$('#privacypolicy').is(':checked')) {

        $('.paymentbutton').attr("disabled", true);
    }
}

$("#privacypolicy").change(function() {
    if(this.checked) {
        $('.paymentbutton').attr("disabled", false);
    }else{
        $('.paymentbutton').attr("disabled", true);
    }
});

var validateAddressInArea = function(positions, area){
    var paths = [];

    if(area !== null){
        area.forEach(location =>
            paths.push(new google.maps.LatLng(location.lat, location.lng))
        );
    }
    var delivery_area = new google.maps.Polygon({ paths: paths });

    if(area != null){
        Object.keys(positions).map(function(key, index) {
            setTimeout(function() {
                var belongsToArea = google.maps.geometry.poly.containsLocation(new google.maps.LatLng(positions[key].lat, positions[key].lng), delivery_area);

                if(belongsToArea === false){
                    $('#address'+key).attr('disabled', 'disabled');
                }
            }, 100);
        });
    }
}




//JS FORM Validate functions
var validateOrderFormSubmit=function(){
    var deliveryMethod=$('input[name="deliveryType"]:checked').val();

    //If deliverty, we need to have selected address
    if(deliveryMethod=="delivery"){
        if ($("#addressID").val()) {
            return true;
        }else{
            alert("Please select address");
            return false;
        }
    }else{
        return true;
    }
}

var initCOD=function(){
    
     // Handle form submission  - for card.
     var form = document.getElementById('order-form');
     form.addEventListener('submit', async function(event) {
         event.preventDefault();
         
         //IF delivery - we need to have selected address
         if(validateOrderFormSubmit()){
            
            form.submit();
         }
    });
}

/**
 *
 * Payment Functions
 *
 */
var initStripePayment=function(){

    

    //On select payment method
    $('input:radio[name="paymentType"]').change(

        function(){
            //HIDE ALL
            $('#totalSubmitCOD').hide()
            $('#totalSubmitStripe').hide()
            $('#stripe-payment-form').hide()

            if($(this).val()=="cod"){
                //SHOW COD
                $('#totalSubmitCOD').show();
            }else if($(this).val()=="stripe"){
                //SHOW STRIPE
                $('#totalSubmitStripe').show();
                $('#stripe-payment-form').show()
            }
        }
    );

    var form = document.getElementById('stripe-payment-form');
    form.addEventListener('submit', async function(event) {
        event.preventDefault();
        if(validateOrderFormSubmit()){
            debugger;
            var cartItems = Object.values(cartContent.items); 
            var formattedItems = cartItems.map(item => {
                return {
                  id: item.id,
                  name: item.name,
                  price: item.price,
                  quantity: item.quantity,
                  image: item.attributes.image,
                  friendly_price: item.attributes.friendly_price
                };
              });
            var name = formattedItems[0].name;
            var quantities = formattedItems.map(item => parseInt(item.quantity)); 
            var totalQuantity = quantities.reduce((sum, quantity) => sum + quantity, 0);
            var totalPrice = total.totalPrice;
            var form = document.getElementById('order-form');
            var formData = new FormData(form);
            var formObject = {};
            formData.forEach((value, key) => {
                formObject[key] = value;
            });
            localStorage.setItem('orderForm', JSON.stringify(formObject));
            console.log(RESTORANT);
            console.log('Form saved to localStorage:', formObject);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const response = await fetch('/create-checkout-session', {
                method: 'POST',
                body: JSON.stringify({
                    totalPrice: totalPrice,
                    name: name,
                    totalQuantity: totalQuantity,
                    formDetails: formObject,
                    cartItems: formattedItems,
                    deliveryMethod: $('input[name="deliveryType"]:checked').val(),
                    restaurant_id: RESTORANT.id,
                    tip: $('#tip').val(),
                }),
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
            });
            
            const { url, error } = await response.json();
            if (error) {
                localStorage.removeItem('orderForm');
                console.log('Form submission successful, removed from localStorage');
                alert('Failed to create a Checkout session: ' + error);
                return;
            }
            if (url) {
                window.location.href = url;
            }
        }
    });

}

var initAddress=function(){
    

    var start = "/images/pin.png"
    var map = null;
    var markerData = null;
    var marker = null;

    $("#new_address_map").hide();
    $("#address").hide();
    $("#new_address_spinner").hide();
    $("#address-info").hide();
    $("#submitNewAddress").hide();

    //Change on Place entering
    $('select[id="new_address_checkout"]').change(function(){
        $("#new_address_checkout_holder").hide();
        var place_id = $("#new_address_checkout option:selected").val();
        var place_name = $("#new_address_checkout option:selected").text();
        

        $("#address").show();
        $("#address").val(place_name);
        $("#new_address_map").show();
        $("#new_address_spinner").show();
        $("#address-info").show();
        $("#submitNewAddress").show();

        //Get Place lat/lng
        getPlaceDetails(place_id, function(isFetched, data){
            if(isFetched){
                var latAdd = data.lat;
                var lngAdd = data.lng;

                $('#lat').val(latAdd);
                $('#lng').val(lngAdd);


                var mapAddress = new google.maps.Map(document.getElementById('new_address_map'), {
                    zoom: 17,
                    center: new google.maps.LatLng(data.lat, data.lng)
                });

                var markerDataAddress = new google.maps.LatLng(data.lat, data.lng);
                var markerAddress = new google.maps.Marker({
                    position: markerDataAddress,
                    map: mapAddress,
                    icon: start,
                    title: data.name
                });

                mapAddress.addListener('click', function(event) {
                    var data = new google.maps.LatLng(event.latLng.lat(), event.latLng.lng());
                    markerAddress.setPosition(data);

                    var latAdd = event.latLng.lat();
                    var lngAdd = event.latLng.lng();

                    $('#lat').val(latAdd);
                    $('#lng').val(lngAdd);
                });
            }
        });

    });

    //Save on click for location
    $("#submitNewAddress").on("click",function() {
        var address_name = $("#address").val();
        var address_number = $("#address_number").val();
        var number_apartment = $("#number_apartment").val();
        var number_intercom = $("#number_intercom").val();
        var entry = $("#entry").val();
        var floor = $("#floor").val();

        var lat = $("#lat").val();
        var lng = $("#lng").val();

        var doSubmit=true;
        var message="";
        if(address_number.length<1){
            doSubmit=false;
            message+="\nPlease enter address number";
        }

        if(!doSubmit){
            alert(message);
            return false;
        }else{


        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: '/addresses',
                data: {
                    new_address: address_number.length != 0 ? address_number + ", " + address_name : address_name,
                    lat: lat,
                    lng: lng,
                    apartment: number_apartment,
                    intercom: number_intercom,
                    entry: entry,
                    floor: floor
                },
                success:function(response){
                    if(response.status){
                        window.location.reload();
                    }
                }, error: function (response) {
                }
            })
        }

    });
}


/**
 * Fetch lat / lng for specific google place id
 * @param {*} place_id
 * @param {*} callback
 */
function getPlaceDetails(place_id, callback){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: 'POST',
        url: '/new/address/details',
        data: { place_id: place_id },
        success:function(response){
            if(response.status){
                return callback(true, response.result)
            }
        }, error: function (response) {
        }
    })
}