@extends('layouts.front', ['class' => ''])
@section('content')
    <section class="section-profile-cover section-shaped my--1 d-none d-md-none d-lg-block d-lx-block">
        <!-- Circles background -->
        <img class="bg-image " src="{{ config('global.restorant_details_cover_image') }}" style="width: 100%;">
        <!-- SVG separator -->
        <div class="separator separator-bottom separator-skew">

        </div>
    </section>
    <section class="section bg-secondary">

      <div class="container">


        @include('notify::components.notify')

          <div class="row">

            <!-- Left part -->
            <div class="col-md-7">

              <!-- List of items -->
              @include('cart.items')

                <form id="order-form" role="form" method="post" action="{{route('order.store')}}" autocomplete="off" enctype="multipart/form-data">
                @csrf
                @if(!config('settings.social_mode'))

                    @if (config('app.isft')&&count($timeSlots)>0)
                    <!-- FOOD TIGER -->
                        <!-- Delivery method -->
                        @if($restorant->can_pickup == 1)
                            @if($restorant->can_deliver == 1)
                              @include('cart.delivery')
                            @endif
                        @endif

                        <div id="restinfo" style="display: none">
                            @include('cart.restaurant')
                         </div>

                        <!-- Delivery time slot -->
                       

                        <!-- Delivery address -->
                        <div id='addressBox'>
                            @include('cart.address')
                        </div>

                        @include('cart.time')
                       
                        <!-- Custom Fields -->
                        @include('cart.customfields')

                        <!-- Comment -->
                        
                        @include('cart.comment')
                    @elseif(config('app.isag'))  
                        @if(count($timeSlots)>0)
                            <!-- Delivery method -->
                            @include('cart.delivery')

                            <!-- Delivery time slot -->
                           

                            <!-- Custom Fields  -->
                            @include('cart.customfields')

                            <!-- Delivery adress -->
                            @include('cart.newaddress')

                            <!-- Client informations -->
                            @include('cart.newclient')

                            <!-- Comment -->
                            <div id="restinfo" style="display: none">
                            @include('cart.restaurant')
                         </div>
                         @include('cart.time')
                            @include('cart.comment')
                        @endif

                    @elseif(config('app.isqrsaas')&&count($timeSlots)>0)

                      <!-- QRSAAS -->
                      
                      <!-- DINE IN OR TAKEAWAY -->
                      @if (config('settings.enable_pickup'))
                      
                          @if (in_array("poscloud", config('global.modules',[])) || in_array("deliveryqr", config('global.modules',[])) )
                            <!-- We have POS in QR -->
                            @include('cart.localorder.dineiintakeawaydeliver')

                            <!-- Delivery adress -->
                            <div class="qraddressBox" style="display: none">
                              @include('cart.newaddress')
                              <br />
                            </div>
                            
                            
                           
                          @else
                             <!-- Simple QR -->
                            @include('cart.localorder.dineiintakeaway')
                          @endif
                          
                          <!-- Takeaway time slot -->
                          <div class="takeaway_picker" style="display: none">
                              @include('cart.time')
                          </div>
                      @endif
                     
                      <!-- LOCAL ORDERING -->
                      @include('cart.localorder.table')

                      <!-- Local Order Phone -->
                      @include('cart.localorder.phone')

                      <!-- Custom Fields -->
                      @include('cart.customfields')

                      <!-- Comment -->
                      <div id="restinfo" style="display: none">
                            @include('cart.restaurant')
                         </div>
                      @include('cart.comment')
                        

                    @endif
                @else
                    <!-- Social MODE -->

                    @if(count($timeSlots)>0)
                        <!-- Delivery method -->
                        @include('cart.delivery')

                        <!-- Delivery time slot -->
                        

                        <!-- Custom Fields  -->
                        @include('cart.customfields')

                        <!-- Delivery adress -->
                        @include('cart.newaddress')

                        <!-- Client informations -->
                        @include('cart.newclient')

                        <!-- Comment -->
                        <div id="restinfo" style="display: none">
                            @include('cart.restaurant')
                         </div>
                         @include('cart.time')
                        @include('cart.comment')
                    @endif
                @endif

              <!-- Restaurant -->
             
            </div>


          <!-- Right Part -->
          <div class="col-md-5">

            @if (count($timeSlots)>0)
                <!-- Payment -->
                @include('cart.payment')
            @else
                <!-- Closed restaurant -->
                @include('cart.closed')
            @endif


          </div>
        </div>


    </div>
    @include('clients.modals')
  </section>
@endsection
@section('js')

  <script async defer src= "https://maps.googleapis.com/maps/api/js?key=<?php echo config('settings.google_maps_api_key'); ?>&callback=initAddressMap"></script>
  <!-- Stripe -->
  <script src="https://js.stripe.com/v3/"></script>
  <script>
    "use strict";
    var RESTORANT = <?php echo json_encode($restorant) ?>;
    var STRIPE_KEY="{{ config('settings.stripe_key') }}";
    var ENABLE_STRIPE="{{ config('settings.enable_stripe') }}";
    var SYSTEM_IS_QR="{{ config('app.isqrexact') }}";
    var SYSTEM_IS_WP="{{ config('app.iswp') }}";
    var initialOrderType = 'delivery';
    if(RESTORANT.can_deliver == 1 && RESTORANT.can_pickup == 0){
        initialOrderType = 'delivery';
    }else if(RESTORANT.can_deliver == 0 && RESTORANT.can_pickup == 1){
        initialOrderType = 'pickup';
    }
  </script>
  <script src="{{ asset('custom') }}/js/checkout.js"></script>
  <script async defer
        src= "https://maps.googleapis.com/maps/api/js?key=<?php echo config('settings.google_maps_api_key'); ?>&callback=initMapA">
    </script>
    <script type="text/javascript">
        "use strict";
        var map, infoWindow, marker, lng, lat;
        function initMapA() {
            if (lat && lng) {
                map = new google.maps.Map(document.getElementById('map2'), {center: {lat: lat, lng: lng}, zoom: 15 });
                marker = new google.maps.Marker({ position: {lat: lat, lng: lng}, map: map, title: 'Click to zoom'});
            }else {
                map = new google.maps.Map(document.getElementById('map2'), {center: {lat: -34.397, lng: 150.644}, zoom: 15 });
                marker = new google.maps.Marker({ position: {lat: -34.397, lng: 150.644}, map: map, title: 'Click to zoom'});
            }
        
            infoWindow = new google.maps.InfoWindow;
            if (lat && lng) {   
                var pos = { lat: parseFloat(lat), lng: parseFloat(lng) };
                map.setCenter(pos);
                marker.setPosition(pos);
            } else if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var pos = { lat: position.coords.latitude, lng: position.coords.longitude };
                    map.setCenter(pos);
                    marker.setPosition(pos);
                    lat = position.coords.latitude;
                    lng = position.coords.longitude;
                }, function() {
                    console.error("Geolocation permission denied or error occurred.");
                });
            }    
            map.addListener('click', function(event) {
                var currPos = new google.maps.LatLng(event.latLng.lat(),event.latLng.lng());
                marker.setPosition(currPos);

                lat = event.latLng.lat()
                lng = event.latLng.lng();
                console.log(lat,lng);
            });
        }

        $("#submitNewAddressnew").on("click",function() {
            saveLocation(lat, lng);
        });

        var isSubmitting = false;
      
        function saveLocation(lat, lng){
            if (isSubmitting) {
                return;
            }
            var new_address = $('#new_address').val();
            var zip = $('#zip').val();
            var street = $('#street').val();
            var location = $('#location').val();
            var addressId = $('#address_id').val();
            var name = $('#name').val();
            var email = $('#email').val();
            var phone = $('#phone').val();
            var city = $('#city').val();
            var country = $('#country').val();
            var companyname = $('#companyname').val();
            var departmentname = $('#departmentname').val();
            var plusCode = $('#plusCode').val();
            const dialCodeElement = document.querySelector('.iti__selected-dial-code');
            const dialCode = dialCodeElement.textContent.trim();
            
            let hasError = false;
            $('#name-error, #email-error, #phone-error, #street-error, #zip-error, #city-error, #plusCode-error').text('');
            
            // Validate Name
            if ($('#name').val().trim() === "") {
                $('#name-error').text('Name is required.');
                hasError = true;
            }

            // Validate Email
            if ($('#email').val().trim() === "") {
                $('#email-error').text('Email is required.');
                hasError = true;
            }

            // Validate Phone
            if ($('#phone').val().trim() === "") {
                $('#phone-error').text('Phone is required.');
                hasError = true;
            }

            // Validate Street
            if ($('#street').val().trim() === "") {
                $('#street-error').text('Street is required.');
                hasError = true;
            }

            // Validate Zip
            if ($('#zip').val().trim() === "") {
                $('#zip-error').text('Zip Code is required.');
                hasError = true;
            }

            // Validate Location
            if ($('#city').val().trim() === "") {
                $('#city-error').text('City is required.');
                hasError = true;
            }

            // Validate Plus Code
            if ($('#plusCode').val().trim() === "") {
                $('#plusCode-error').text('Plus code is required.');
                hasError = true;
            }

            // If no errors, proceed with form submission or AJAX call
            if (hasError) {
                // You can either submit the form or perform an AJAX request here.
                // For example, using jQuery:
                // $('form').submit();
                return;
            }
            let url = "/addresses";
            let type = 'POST';
            if (addressId.length > 0){
                url = `/addresses/${addressId}`;
                type = 'PUT';
            }
            isSubmitting = true;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: type,
                url: url,
                dataType: 'json',
                data: {
                    new_address: new_address,
                    lat: lat,
                    lng: lng,
                    zip: zip, 
                    city: city,
                    country: country,
                    street: street,
                    location: location,
                    plusCode: plusCode,
                    name: name ,
                    email: email,
                    companyname: companyname,
                    departmentname: departmentname,
                    phone: phone,
                    mobileFormat: dialCode
                },
                success:function(response){
                    if(response.status){
                        window.location.href = "/addresses";
                    }
                }, error: function (response) {
                }
            })
        }


        function handleLocationError(browserHasGeolocation, infoWindow, pos) {
            infoWindow.setPosition(pos);
            infoWindow.setContent(browserHasGeolocation ? 'Error: The Geolocation service failed.' : 'Error: Your browser doesn\'t support geolocation.');
            infoWindow.open(map);
        }
    </script>
@endsection

