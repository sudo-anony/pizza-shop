
"use strict";
var map, infoWindow, marker, lng, lat;
function initMap() {}

function initMapR(){
    map = new google.maps.Map(document.getElementById('map'), {center: {lat: -34.397, lng: 150.644}, zoom: 15 });
    marker = new google.maps.Marker({ position: {lat: -34.397, lng: 150.644}, map: map, title: 'Click to zoom'});
    infoWindow = new google.maps.InfoWindow;

    getLocation(function(isFetched, currPost){
        if(isFetched){
            if(currPost.lat != 0 && currPost.lng != 0){
                map.setCenter(currPost);
                marker.setPosition(currPost);
            }else{
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                      var pos = { lat: position.coords.latitude, lng: position.coords.longitude };
                      map.setCenter(pos);
                      marker.setPosition(pos);
                      changeLocation(pos.lat, pos.lng);
                    }, function() {
                    });
                } else {
                }
            }
        }
    });

    map.addListener('click', function(event) {
        var currPos = new google.maps.LatLng(event.latLng.lat(),event.latLng.lng());
        marker.setPosition(currPos);
        changeLocation(event.latLng.lat(), event.latLng.lng());
    });
}

function getLocation(callback){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type:'GET',
        url: '/get/rlocation/'+$('#rid').val(),
        success:function(response){
            if(response.status){
                return callback(true, response.data)
            }
        }, error: function (response) {
           return callback(false, response.responseJSON.errMsg);
        }
    })
}

function changeLocation(lat, lng){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type:'POST',
        url: '/updateres/location/'+$('#rid').val(),
        dataType: 'json',
        data: {
            lat: lat,
            lng: lng
        },
        success:function(response){
            if(response.status){
                
            }
        }, error: function (response) {
        }
    })
}

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

$("#submitNewAddress").on("click",function() {
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
    var addressInfo = $('#addressinfo').val();
    var plusCode = $('#plusCode').val();
    const dialCodeElement = document.querySelector('.iti__selected-dial-code');
    const dialCode = dialCodeElement.textContent.trim();

    let hasError = false;
    // Clear previous errors
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

    // if (!street.trim() || !zip.trim() || !location.trim() || !name.trim() || !email.trim() || !phone.trim()) {
    //     alert("Please fill in all required fields.");
    //     return;
    // }
    
    let url = "/addresses";
    let type = 'POST';
    if (addressId.length > 0 && addressId !== 'undefined'){
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
            street: street,
	    city: city,
            country: country,
            location: location,
            plusCode: plusCode,
            name: name ,
            email: email,
            companyname: companyname,
            departmentname: departmentname,
            phone: phone,
            mobileFormat: dialCode,
            addressInfo: addressInfo
        },
        success:function(response){
            if(response.status){
                window.location.href = "/addresses";
            }
        }, error: function (response) {
        }
    })
}


function openAddressModal(data = {}) {
    const modalTitle = document.getElementById("modal-title-notification");
    const addressId = document.getElementById("address_id");
    // const newAddressInput = document.getElementById("new_address");
    const streetInput = document.getElementById("street");
    const zipInput = document.getElementById("zip");
    const locationInput = document.getElementById("location");
    const phone = document.getElementById("phone");
    const email = document.getElementById("email");
    const departmentname = document.getElementById("departmentname");
    const city = document.getElementById("city");
    const country = document.getElementById("country");
    const name = document.getElementById("name");
    const companyname = document.getElementById("companyname");
    const plusCode = document.getElementById("plusCode");
    const addressinfo = document.getElementById("addressinfo");
    const el = { mobileFormat: data.mobileFormat || "" };
    selectCountryByMobileFormat(el);
    modalTitle.textContent = "Edit Address";
    addressId.value = data.id;
    streetInput.value = data.street || "";
    zipInput.value = data.zip || "";
    // locationInput.value = data.location || "";
    phone.value = data.phone || "";
    email.value = data.email || "";
    departmentname.value = data.departmentname || "";
    city.value = data.city || "";
    country.value = data.country || "";
    plusCode.value = data.plusCode || "";
    name.value = data.name || "";
    companyname.value = data.companyname || "";
    // locationInput.value = data.location || "";
    addressinfo.value = data.addressinfo || "";
    lat = data.lat; lng = data.lng;
    setTimeout(() => {
        initMapA();
    }, 300); 
    const modalElement = document.getElementById('modal-new-address');
    const modal = new bootstrap.Modal(modalElement);
    modal.show();
}



function selectCountryByMobileFormat(data) {
    if (data){
        const dialCode = data.mobileFormat.replace('+', '');

        const observer = new MutationObserver(() => {
            const countryElement = document.querySelector(`li[data-dial-code="${dialCode}"]`);
            if (countryElement) {
                countryElement.setAttribute('tabindex', '0');
                countryElement.focus();
                countryElement.classList.add('iti__active', 'iti__highlight');
    
                const dropdown = document.querySelector('.iti__selected-dial-code');
                if (dropdown) {
                    dropdown.textContent = `+${dialCode}`;
                }
    
                console.log(`Selected country: ${countryElement.innerText}`);
                observer.disconnect();
            }
            const flagElement = document.querySelector('.iti__flag');
            if (flagElement) {
                const countryCode = countryElement.getAttribute('data-country-code');
                flagElement.className = `iti__flag iti__${countryCode}`;
            }
        });
        observer.observe(document.body, { childList: true, subtree: true });
    }
}


function handleLocationError(browserHasGeolocation, infoWindow, pos) {
    infoWindow.setPosition(pos);
    infoWindow.setContent(browserHasGeolocation ? 'Error: The Geolocation service failed.' : 'Error: Your browser doesn\'t support geolocation.');
    infoWindow.open(map);
}

