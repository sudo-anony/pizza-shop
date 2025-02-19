<div class="p-4 bg-secondary">
    <div class="row">
        <div class="col-md-7">
            <input id="tipapplied" name="tipapplied" type="hidden" value="0">
            <input id="tip" name="tip" type="text" class="form-control form-control-alternative" placeholder="{{ __('Tip') }}">
        </div>
        <div class="col-md-5">
            <button id="tip_btn" type="button" class="btn btn-outline-primary">{{ __('Add tip') }}</button>
        </div>
    </div>
</div>
<br/>

<script>
var LOCALE = "<?php echo  App::getLocale() ?>";
var CASHIER_CURRENCY = "<?php echo  config('settings.cashier_currency') ?>";

// Function to format the price based on the currency
function formatPrice(price) {
  var locale = LOCALE;
  if (CASHIER_CURRENCY.toUpperCase() === "USD") {
    locale = locale + "-US";
  }

  var formatter = new Intl.NumberFormat(locale, {
    style: 'currency',
    currency: CASHIER_CURRENCY,
  });

  var formatted = formatter.format(price);
  return formatted;
}

// Function to remove non-numeric characters except for decimal and commas
function cleanInput(value) {
  return value.replace(/[^0-9.,-]/g, '');
}

const tipInput = document.querySelector('#tip');

// Format on blur (when user finishes typing)
tipInput.addEventListener('blur', function () {
  const price = parseFloat(cleanInput(tipInput.value));
  if (!isNaN(price)) {
    tipInput.value = formatPrice(price);
  }
});

// While typing, just clean the input without formatting it
tipInput.addEventListener('input', function () {
  tipInput.value = cleanInput(tipInput.value); // Allow raw input
});

// Ensure initial value is formatted correctly if it's pre-filled
if (tipInput.value) {
  const price = parseFloat(cleanInput(tipInput.value));
  if (!isNaN(price)) {
    tipInput.value = formatPrice(price);
  }
}
</script>
