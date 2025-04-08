<div class="form-group text-center{{ $errors->has('item_image') ? ' has-danger' : '' }}">
    <label class="form-control-label" for="item_image">{{ $image['label'] }}</label>
    @isset($image['help'])
        <br /> <span class="small">{{ $image['help'] }}</span>
    @endisset
    <div class="text-center">
        <div class="d-flex justify-content-center mb-2" style="width: 100%; height: 200px;">
            <div class="img-thumbnail" style="width: 290px; height:200; overflow:hidden; display: flex; justify-content: center; align-items: center;">
                <img id="item-image-preview" src="{{ $image['value'] }}" alt="Preview" style="max-width: 100%; max-height: 100%; object-fit: contain;">
            </div>
        </div>
        <!-- Custom File Input Button -->
        <div class="custom-file-input-container d-flex justify-content-center">
            <button type="button" class="btn btn-warning" id="change-image" style="display:none;">{{ __('Select image') }}</button>
            <input type="file" class="form-control-file" name="{{ $image['name'] }}" id="item_image" accept="image/png,image/jpeg,image/webp,image/gif" style="display:none;">
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fileInput = document.getElementById('item_image'); // File input element
        const previewImg = document.getElementById('item-image-preview'); // Image preview element
        const changeButton = document.getElementById('change-image'); // Change button

        // Initially show the current image preview from the database
        const initialImage = '{{ $image['value'] }}'; // Get the image URL from the database
        previewImg.src = initialImage; // Set the preview image to the current one

        // Show the "Change" button only if there is an image
        if (initialImage && initialImage !== '{{ asset('images') }}/default/add_new_item_box.jpeg') {
            changeButton.style.display = 'inline-block'; // Show "Change" button
        }

        // Handle Change Image button - trigger file input
        changeButton.addEventListener('click', function () {
            fileInput.click(); // Open the file input dialog
        });

        // Handle file input change (when user selects a file)
        fileInput.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImg.src = e.target.result; // Set preview image to selected file
                };
                reader.readAsDataURL(file);

                // After an image is selected, show the Change button
                changeButton.style.display = 'inline-block'; // Show "Change" button
            } else {
                alert("Please select a valid image file.");
            }
        });
    });
</script>
