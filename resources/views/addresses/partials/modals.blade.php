<style>
    #modal-new-address input#phone{
        padding-left: 90px !important;
    }
</style>
<div class="modal fade" id="modal-new-address" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
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
                                <input class="form-control my-3" name="name" id="name" placeholder="{{ __( 'Enter name here' ) }} ..." type="text" required>
                                <input class="form-control my-3" name="email" id="email" placeholder="{{ __( 'Enter email here' ) }} ..." type="email" required>
                                <input class="form-control my-3" name="phone" id="phone" placeholder="{{ __( 'Enter phone here' ) }} ..." type="phone" required>
                                <input class="form-control my-3" name="companyname" id="companyname" placeholder="{{ __( 'Enter company name here' ) }} ..." type="text" required>
                                <input class="form-control my-3" name="departmentname" id="departmentname" placeholder="{{ __( 'Enter department name here' ) }} ..." type="text" required>
                                <input class="form-control my-3" name="street" id="street" placeholder="{{ __( 'Enter street here' ) }} ..." type="text" required>
                                <input class="form-control my-3" name="zip" id="zip" placeholder="{{ __( 'Enter Zip Code here' ) }} ..." type="number" required>
                                <input class="form-control my-3" name="location" id="location" placeholder="{{ __( 'Enter location here' ) }} ..." type="text" required>
                                <input class="form-control my-3" name="plusCode" id="plusCode" placeholder="{{ __( 'Enter plus code here' ) }} ..." type="text" required>
                                <input class="form-control my-3" name="address_id" id="address_id" type="hidden" value="">
                                @if ($errors->has('category_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('category_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <!-- <div id="map2" class="form-control form-control-alternative"></div> -->
                            <div class="text-center">
                                <button type="button" id="submitNewAddress" class="btn btn-primary my-4">{{ __('Save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
