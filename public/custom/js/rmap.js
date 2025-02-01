
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
    map = new google.maps.Map(document.getElementById('map2'), {center: {lat: -34.397, lng: 150.644}, zoom: 15 });
    marker = new google.maps.Marker({ position: {lat: -34.397, lng: 150.644}, map: map, title: 'Click to zoom'});
    infoWindow = new google.maps.InfoWindow;

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
        var pos = { lat: position.coords.latitude, lng: position.coords.longitude };

            map.setCenter(pos);
            marker.setPosition(pos);
            lat = position.coords.latitude;
            lng = position.coords.longitude;
        }, function() {
        });
    } else {
    }

    map.addListener('click', function(event) {
        var currPos = new google.maps.LatLng(event.latLng.lat(),event.latLng.lng());
        marker.setPosition(currPos);

        lat = event.latLng.lat()
        lng = event.latLng.lng();
    });
}

$("#submitNewAddress").on("click",function() {
    saveLocation(lat, lng);
});
var isSubmitting = false;
function saveLocation(lat, lng){
    lat = 0; lng = 0;
    // const plusCode = OpenLocationCode.encode(lat, lng);
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
    var companyname = $('#companyname').val();
    var departmentname = $('#departmentname').val();
    var plusCode = $('#plusCode').val();
    const dialCodeElement = document.querySelector('.iti__selected-dial-code');
    const dialCode = dialCodeElement.textContent.trim();
    
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
    const name = document.getElementById("name");
    const companyname = document.getElementById("companyname");
    const plusCode = document.getElementById("plusCode");
    const el = { mobileFormat: data.mobileFormat || "" };
    selectCountryByMobileFormat(el);
    modalTitle.textContent = "Edit Address";
    addressId.value = data.id;
    streetInput.value = data.street || "";
    zipInput.value = data.zip || "";
    locationInput.value = data.location || "";
    phone.value = data.phone || "";
    email.value = data.email || "";
    departmentname.value = data.departmentname || "";
    plusCode.value = data.plusCode || "";
    name.value = data.name || "";
    companyname.value = data.companyname || "";
    locationInput.value = data.location || "";
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

