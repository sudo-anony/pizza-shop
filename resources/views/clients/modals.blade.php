<style>
    #modal-new-address input#phone{
        padding-left: 90px !important;
    }
</style>

<div class="modal fade" id="modal-order-new-address" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-large" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modal-title-notification">sss{{ __('Add new address') }}</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="card bg-secondary shadow border-0">
                    <div class="card-body px-lg-5 py-lg-5" id="new_address_checkout_body">
                        <form role="form">
                            @csrf
                            <div class="form-group" id="new_address_checkout_holder">
                                <label class="form-control-label" for="new_address_checkout">{{ __('Address') }}</label>
                                <select class="noselecttwo form-control" id="new_address_checkout">
                                </select>
                            </div>

                            <div class="form-group">
                                <div id="new_address_map" class="form-control"></div>
                            </div>

                            <div id="address-info">

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group{{ $errors->has('address') ? ' has-danger' : '' }}">
                                            <input name="address" id="address" type="text" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" placeholder="{{ __('Address')}}">
                                            @if ($errors->has('address'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('address') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group{{ $errors->has('address_number') ? ' has-danger' : '' }}">
                                            <input name="address_number" id="address_number" type="text" class="form-control{{ $errors->has('address_number') ? ' is-invalid' : '' }}" placeholder="{{ __('Address number')}}">
                                            @if ($errors->has('address_number'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('address_number') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group{{ $errors->has('number_apartment') ? ' has-danger' : '' }}">
                                            <input name="number_apartment" id="number_apartment" type="text" class="form-control{{ $errors->has('number_apartment') ? ' is-invalid' : '' }}" placeholder="{{ __('Apartment number')}}">
                                            @if ($errors->has('number_apartment'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('number_apartment') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <input type="hidden" id="lat" name="lat" />

                                    </div>
                                    <div class="col">
                                        <div class="form-group{{ $errors->has('number_intercom') ? ' has-danger' : '' }}">
                                            <input name="number_intercom" id="number_intercom" type="text" class="form-control{{ $errors->has('number_intercom') ? ' is-invalid' : '' }}" placeholder="{{ __('Intercom')}}">
                                            @if ($errors->has('number_intercom'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('number_intercom') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group{{ $errors->has('floor') ? ' has-danger' : '' }}">
                                            <input name="floor" id="floor" type="text" class="form-control{{ $errors->has('floor') ? ' is-invalid' : '' }}" placeholder="{{ __('Floor')}}">
                                            @if ($errors->has('floor'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('floor') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group{{ $errors->has('entry') ? ' has-danger' : '' }}">
                                            <input name="entry" id="entry" type="text" class="form-control{{ $errors->has('entry') ? ' is-invalid' : '' }}" placeholder="{{ __('Entry number')}}">
                                            @if ($errors->has('entry'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('entry') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <input type="hidden" id="lng" name="lng" />
                                    </div>
                                </div>




                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-link" data-dismiss="modal">{{ __('Close') }}</button>
                <button type="button" id="submitNewAddress" class="btn btn-outline-success">{{ __('Save') }}</button>
              </div>

        </div>
    </div>
</div>







<div class="modal fade" id="modal-order-new-address-new" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modal-title-notification">{{ __('Add new address') }}</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="card bg-secondary shadow border-0">
                    <div class="card-body ">
                        <form>
                            <div class="form-group{{ $errors->has('new_address') ? ' has-danger' : '' }}">
                                <!-- <input class="form-control my-3" name="new_address" id="new_address" placeholder="{{ __( 'New address here' ) }} ..." type="text" required> -->
                                <input class="form-control my-3" name="name" id="name_new" placeholder="{{ __( 'New name here' ) }} ..." type="text" required>
                                <input class="form-control my-3" name="email" id="email" placeholder="{{ __( 'New email here' ) }} ..." type="email" required>
                                <input class="form-control my-3" name="phone" id="phone" placeholder="{{ __( 'New phone here' ) }} ..." type="number" required>
                                <input class="form-control my-3" name="companyname" id="companyname" placeholder="{{ __( 'New company name here' ) }} ..." type="text" required>
                                <input class="form-control my-3" name="departmentname" id="departmentname" placeholder="{{ __( 'New department name here' ) }} ..." type="text" required>
                                <input class="form-control my-3" name="street" id="street" placeholder="{{ __( 'New street here' ) }} ..." type="text" required>
                                <input class="form-control my-3" name="zip" id="zip" placeholder="{{ __( 'New Zip Code here' ) }} ..." type="number" required>
                                <input class="form-control my-3" name="location" id="location" placeholder="{{ __( 'New location here' ) }} ..." type="text" required>
                                <input class="form-control my-3" name="plusCode" id="plusCode" placeholder="{{ __( 'New plus code here' ) }} ..." type="text" required>
                                <input class="form-control my-3" name="address_id" id="address_id" type="hidden" value="">
                                @if ($errors->has('category_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('category_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!-- <div id="map2" class="form-control form-control-alternative"></div> -->
                            <div class="text-center">
                                <button type="button" id="submitNewAddressnew" class="btn btn-primary my-4">{{ __('Save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
