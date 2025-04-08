<style>
    .custom-file-input-container {
    display: flex;
    gap: 10px;
    justify-content: center;
}

.custom-file-input-container button {
    font-size: 14px;
    padding: 5px 10px;
}

#item-image-preview {
    object-fit: cover;
    border-radius: 8px;
}

</style>
<!-- ADD Category -->
<div class="modal fade" id="modal-items-category" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modal-title-notification">{{ __('Add new category') }}</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="card bg-secondary shadow border-0">
                    <div class="card-body px-lg-5 py-lg-5">
                        <form role="form" method="post" action="{{ route('categories.store') }}">
                            @csrf
                            <input type="hidden" value="{{$restorant_id}}"  name="restaurant_id" />
                            <div class="form-group{{ $errors->has('category_name') ? ' has-danger' : '' }}">
                                <input class="form-control" name="category_name" id="category_name" placeholder="{{ __('Category name') }} ..." type="text" required>
                                @if ($errors->has('category_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('category_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('subtitle') ? ' has-danger' : '' }}">
                                <input class="form-control" name="subtitle" id="subtitle" placeholder="{{ __('Subtitle') }} ..." type="text">
                                @if ($errors->has('subtitle'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('subtitle') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary my-4">{{ __('Save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- EDIT Category -->
<div class="modal fade" id="modal-edit-category" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modal-title-notification">{{ __('Edit category') }}</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="card bg-secondary shadow border-0">
                    <div class="card-body px-lg-5 py-lg-5">
                        <form role="form" id="form-edit-category" method="post" action="">
                            @csrf
                            @method('put')
                            <input name="cat_id" id="cat_id" type="hidden" required>
                            <div class="form-group{{ $errors->has('category_name') ? ' has-danger' : '' }}">
                                <input class="form-control" name="category_name" id="cat_name" placeholder="{{ __('Category name') }} ..." type="text" required>
                                @if ($errors->has('category_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('category_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('subtitle') ? ' has-danger' : '' }}">
                                <input class="form-control" name="subtitle" id="cat_subtitle" placeholder="{{ __('Subtitle') }} ..." type="text">
                                @if ($errors->has('subtitle'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('subtitle') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary my-4">{{ __('Save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- NEW Language -->
<div class="modal fade" id="modal-new-language" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modal-title-notification">{{ __('Add new language') }}</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="card bg-secondary shadow border-0">
                    <div class="card-body px-lg-5 py-lg-5">
                        <form role="form" method="post" action="{{ route('admin.restaurant.storenewlanguage') }}">
                            @csrf
                            <input type="hidden" value="{{$restorant_id}}"  name="restaurant_id" />
                            @include('partials.select', ['class'=>"col-12", 'classselect'=>"noselecttwo",'name'=>"Language",'id'=>"locale",'placeholder'=>__("Select Language"),'data'=>config('config.env')[2]['fields'][0]['data'],'required'=>true])
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary my-4">{{ __('Add new language') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal-new-item" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">{{ __('Add new item') }}</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>×</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="card bg-secondary shadow border-0">
                    <div class="card-body px-lg-5 py-lg-5">
                        <form role="form" method="post" action="{{ route('items.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group{{ $errors->has('item_name') ? ' has-danger' : '' }}">
                                <input class="form-control" name="item_name" id="item_name" placeholder="{{ __('Item name') }} ..." type="text" required>
                                @if ($errors->has('item_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('item_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('item_description') ? ' has-danger' : '' }}">
                                <textarea class="form-control" id="item_description" name="item_description" rows="3" placeholder="{{ __('Item description') }} ..." required></textarea>
                                @if ($errors->has('item_description'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('item_description') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('item_price') ? ' has-danger' : '' }}">
                                <input class="form-control" name="item_price" id="item_price" placeholder="{{ __('Item Price') }} ..." type="number" step="any" required>
                                @if ($errors->has('item_price'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('item_price') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group text-center{{ $errors->has('item_image') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="item_image">{{ __('Item Image') }}</label>
                                <div class="text-center">
                                    <!-- Center the image preview container using flexbox -->
                                    <div class="d-flex justify-content-center mb-2" style="width: 100%; height: 150px;">
                                        <div class="img-thumbnail" style="width: 200px; height: 150px; overflow: hidden; display: flex; justify-content: center; align-items: center;">
                                            <img id="item-image-preview" src="{{ asset('images') }}/default/add_new_item_box.jpeg" alt="Preview" width="200" height="150">
                                        </div>
                                    </div>

                                    <!-- Custom File Input Button -->
                                    <div class="custom-file-input-container text-center">
                                        <button type="button" class="btn btn-primary" id="select-image">{{ __('Select image') }}</button>
                                        <button type="button" class="btn btn-warning" id="change-image" style="display:none;">{{ __('Change') }}</button>
                                        <button type="button" class="btn btn-danger" id="remove-image" style="display:none;">{{ __('Remove') }}</button>
                                        <input type="file" class="form-control-file" name="item_image" id="item_image" accept="image/png,image/jpeg,image/webp,image/gif" style="display:none;">
                                    </div>
                                </div>
                            </div>



                            <input name="category_id" id="category_id" type="hidden" required>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary my-4">{{ __('Save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-import-items" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modal-title-new-item">{{ __('Import items from CSV') }}</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="card bg-secondary shadow border-0">
                    <div class="card-body px-lg-5 py-lg-5">
                        <div class="col-md-10 offset-md-1">
                        <form role="form" method="post" action="{{ route('import.items') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group text-center{{ $errors->has('item_image') ? ' has-danger' : '' }}">
                                <label class="form-control-label" for="items_excel">{{ __('Import your file') }}</label>
                                <div class="text-center">
                                    <input type="file" class="form-control form-control-file" name="items_excel" accept=".csv, .ods, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
                                </div>
                            </div>
                            <input name="res_id" id="res_id" type="hidden" value="{{ $restorant_id }}" required>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary my-4">{{ __('Save') }}</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('item_image');
    const preview = document.getElementById('item-image-preview');
    const selectButton = document.getElementById('select-image');
    const changeButton = document.getElementById('change-image');
    const removeButton = document.getElementById('remove-image');

    // Select Image Button - Trigger file input
    selectButton.addEventListener('click', function () {
        input.click();
    });

    // Change Image Button - Trigger file input
    changeButton.addEventListener('click', function () {
        input.click();
    });

    // Remove Image Button - Clear the input and hide the image preview
    removeButton.addEventListener('click', function () {
        input.value = '';  // Clear the file input
        preview.src = '{{ asset('images') }}/default/add_new_item_box.jpeg';  // Reset preview image
        selectButton.style.display = 'inline-block';  // Show 'Select' button
        changeButton.style.display = 'none';  // Hide 'Change' button
        removeButton.style.display = 'none';  // Hide 'Remove' button
    });

    // On file input change (when user selects a file)
    input.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file && file.type.startsWith("image/")) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;  // Set preview image to selected file
            };
            reader.readAsDataURL(file);

            // Toggle visibility of buttons
            selectButton.style.display = 'none';
            changeButton.style.display = 'inline-block';
            removeButton.style.display = 'inline-block';
        }
    });
});

</script>

