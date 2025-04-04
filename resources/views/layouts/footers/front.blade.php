<style>
 .footer-alignment {
    /* width: 100%; */
   
}

@media (max-width: 767px) {
    .footer-alignment {
        margin-left: 0 !important;
    }
}

@media (min-width: 768px) and (max-width: 1024px) {
    .footer-alignment {
        
    }
}

</style>
<footer class="footer notranslate">
    <div class="container">
      <div class="row align-items-center justify-content-md-between">
        <div class="col-md-6">
          <div class="copyright">
            &copy; {{ date('Y') }} <a href="" target="_blank">{{ config('global.site_name', 'mResto') }}</a>.
          </div>
          <div class="footer-alignment">
                <div class="mb-0  text-muted" style="font-size: 0.8rem;">{{ __('All prices in euros and incl. VAT/illustrations may vary') }}</div>
                <div class="mb-0  text-muted" style="font-size: 0.8rem;">{{ __('Information on the ingredients can be found on the product') }}</div>
            </div>
        </div>
        <div class="col-md-6">
          <div class="d-flex flex-column justify-content-md-end align-items-md-center">
           
            <ul id="footer-pages" class="nav nav-footer justify-content-end">
              <li v-for="page in pages" class="nav-item" v-cloak>
                  <a :href="'/pages/' + page.id" class="nav-link">@{{ page.title }}</a>
              </li>

              @if (!config('settings.single_mode')&&config('settings.restaurant_link_register_position')=="footer")
              <li class="nav-item">
                <a  target="_blank" class="button nav-link nav-link-icon" href="{{ route('newrestaurant.register') }}">{{ __(config('settings.restaurant_link_register_title')) }}</a>
              </li>
              @endif
              @if (config('app.isft')&&config('settings.driver_link_register_position')=="footer")
              <li class="nav-item">
                <a target="_blank" class="button nav-link nav-link-icon" style="margin-right: 10px;" href="{{ route('driver.register') }}">{{ __(config('settings.driver_link_register_title')) }}</a>
              </li>
              @endif

            </ul>
          </div>
        </div>
      </div>
    </div>
  </footer>
