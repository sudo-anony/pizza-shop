@extends('layouts.app', ['title' => __('My Addresses')])

@section('content')
    @include('addresses.partials.modals')
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    </div>

    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col">
                <div class="card shadow">
                    <div class="card-header border-0 d-none d-md-block">
                        <div class="row align-items-center">
                            <div class="col-10">
                                <h3 class="mb-0">{{ __('My Addresses') }}</h3>
                            </div>
                            <div class="col-2 text-end">
                                <button data-toggle="modal" onclick="openAddressModal()" data-target="#modal-new-address"  class=" btn btn-success mt-4 btn-sm w-100" >New Address</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-header border-0 overflow-auto d-block d-md-none">
                        <div class="row align-items-center flex-nowrap">
                            <div class="col-auto">
                                <h3 class="mb-0">{{ __('My Addresses') }}</h3>
                            </div>
                            <div class="col-auto ms-auto">
                                <button data-toggle="modal" onclick="openAddressModal()" data-target="#modal-new-address" class="btn btn-success mt-4 btn-sm" style="white-space: nowrap;">
                                    {{ __('New Address') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        @include('partials.flash')
                    </div>
                    @if(count($addresses))
                    <div class="table-responsive d-none d-md-block">
                        <table class="table align-items-center table-flush">
                            <thead class="thead-light">
                                <tr>
                                    <!-- <th scope="col">{{ __('Address') }}</th> -->
                                    <th scope="col">{{ __('Company') }}</th>
                                    <th scope="col">{{ __('Department') }}</th>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Email') }}</th>
                                    <th scope="col">{{ __('Street') }}</th>
                                    <th scope="col">{{ __('Zip Code') }}</th>
                                    <th scope="col">{{ __('City') }}</th>
                                    <th scope="col">{{ __('Google Plus Code') }}</th>
                                    <th scope="col">{{ __('Phone') }}</th>
                                    <th scope="col"></th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($addresses as $address)
                                    <tr>
                                        <!-- <td>{{$address->address}}</td> -->
                                        <td>{{$address->companyname}}</td>
                                        <td>{{$address->departmentname}}</td>
                                        <td>{{$address->name}}</td>
                                        <td>{{$address->email}}</td>
                                        <td>{{$address->street}}</td>
                                        <td>{{$address->zip}}</td>
                                        <td>{{$address->city}}</td>
                                        <td>{{$address->plusCode}}</td>
                                        <td>{{$address->mobileFormat}} {{$address->phone}}</td>
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <form action="{{ route('addresses.destroy', $address) }}" method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="dropdown-item">
                                                            {{ __('Delete') }}
                                                        </button>
                                                    </form>
                                                    <button class="dropdown-item" style="cursor:pointer" onclick="openAddressModal({{ json_encode($address) }})">
                                                        {{ __('Edit') }}
                                                    </button>
                                                </div>
                                            
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-block d-md-none">
                        @foreach ($addresses as $address)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title">{{ $address->name }}</h5>
                                        <div class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <form action="{{ route('addresses.destroy', $address) }}" method="post">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="dropdown-item">
                                                            {{ __('Delete') }}
                                                        </button>
                                                    </form>
                                                    <button class="dropdown-item" style="cursor:pointer" onclick="openAddressModal({{ json_encode($address) }})">
                                                        {{ __('Edit') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="card-text">
                                        <strong>{{ __('Company') }}:</strong> {{ $address->companyname }}<br>
                                        <strong>{{ __('Department') }}:</strong> {{ $address->departmentname }}<br>
                                        <strong>{{ __('Email') }}:</strong> {{ $address->email }}<br>
                                        <strong>{{ __('Street') }}:</strong> {{ $address->street }}<br>
                                        <strong>{{ __('Zip Code') }}:</strong> {{ $address->zip }}<br>
                                        <strong>{{ __('Google Plus Code') }}:</strong> {{ $address->plusCode }}<br>
                                        <strong>{{ __('Phone') }}:</strong> {{ $address->mobileFormat }} {{ $address->phone }}
                                    </p>
                                    
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @endif
             
                    <div class="card-footer py-4">
                        @if(count($addresses))
                            <nav class="d-flex justify-content-end" aria-label="...">
                            </nav>
                        @else
                        <div class="text-center">
                                {{ __('You don`t have any addresses') }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection

@section('js')
    <script async defer
        src= "https://maps.googleapis.com/maps/api/js?key=<?php echo config('settings.google_maps_api_key'); ?>&callback=initMapA">
    </script>
    <script type="text/javascript">
        "use strict";
        var map, infoWindow, marker, lng, lat;
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
            } 

            map.addListener('click', function(event) {
                var currPos = new google.maps.LatLng(event.latLng.lat(),event.latLng.lng());
                marker.setPosition(currPos);
               

                lat = event.latLng.lat()
                lng = event.latLng.lng();
            });
        }

        $("#submitNewAddress").on("click",function() {
            return 
            saveLocation(lat, lng);
        });

        function saveLocation(lat, lng){
            var new_address = $('#new_address').val();
            var zip = $('#zip').val();
            var street = $('#street').val();
            var location = $('#location').val();
            if(new_address.length > 0){

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type:'POST',
                    url: '/addresses',
                    dataType: 'json',
                    data: {
                        new_address: new_address,
                        lat: lat,
                        lng: lng
                    },
                    success:function(response){
                        if(response.status){
                            
                            window.location.href = "/addresses";
                        }
                    }
                })
            }
        }

        function handleLocationError(browserHasGeolocation, infoWindow, pos) {
            infoWindow.setPosition(pos);
            infoWindow.setContent(browserHasGeolocation ? 'Error: The Geolocation service failed.' : 'Error: Your browser doesn\'t support geolocation.');
            infoWindow.open(map);
        }
    </script>
@endsection
