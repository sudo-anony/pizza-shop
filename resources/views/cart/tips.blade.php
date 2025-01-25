<script src="https://cdn.jsdelivr.net/npm/cleave.js"></script>
<div class="p-4 bg-secondary">
    <div  class="row">
        <div class="col-md-7">
            <input  id="tipapplied" name="tipapplied" type="hidden" value="0">
            <input  id="tip" name="tip" type="currency"  class="form-control form-control-alternative" placeholder="{{ __('Tip')}}">
        </div>
        <div class="col-md-5">
            <button id="tip_btn" type="button" class="btn btn-outline-primary">{{ __('Add tip') }}</button>
        </div>
    </div>
</div>
<br/>

<script>
  new Cleave('#tip', {
    numeral: true,
    numeralThousandsGroupStyle: 'thousand',
    prefix: '€'
  });
</script>