<div id="productModal"  z-index="9999" tabindex="-1" role="dialog"  class="fixed top-0 left-0 right-0 z-50 hidden p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full ">
  <div class="relative w-full max-w-md max-h-full bg-white rounded-3xl shadow-xl overflow-hidden max-h-[80vh] overflow-y-auto">
    <!-- Add close button -->
    <button type="button" class="absolute top-4 right-4 z-50 bg-white rounded-full p-2 hover:bg-gray-100 focus:outline-none" onclick="productModal.toggle()">
      <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
      </svg>
    </button>
    <div class='max-w-md mx-auto'>
        <div class='relative h-[200px]' id="productImage" style='background-size:cover;background-position:center'>
            <!-- Add gradient overlay - changed direction to bottom-to-top -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
            <!-- Product title at bottom of image -->
            <p class='absolute bottom-4 left-4 right-4 font-bold text-white text-[22px] leading-7' id="modalTitle"></p>
        </div>
        <div class='p-4 sm:p-6'>
          <input id="modalID" type="hidden"></input>
          <div class='flex flex-row'>
            <p class='text-[17px] font-bold text-[#0FB478]' id="modalPrice"></p>
          </div>
          <p class='text-[#7C7C80] font-[15px] mt-6' id="productDescription"></p>

          <div class="px-2">
            <div id="variants-area" class=" w-full ">
                <label class="form-control-label">{{ __('Select your options') }}</label>
                <div id="variants-area-inside" class="[&_.btn-group]:flex [&_.btn-group]:flex-wrap [&_.btn-group]:gap-2 [&_.btn]:px-4 [&_.btn]:py-2 [&_.btn]:rounded-lg [&_.btn]:border [&_.btn]:border-gray-200 [&_.btn]:transition-all [&_.btn]:duration-200 [&_.btn]:text-gray-600 [&_.btn]:text-sm [&_.btn]:shadow-sm [&_.btn-outline-primary.active]:bg-green-500 [&_.btn-outline-primary.active]:text-white [&_.btn-outline-primary.active]:border-green-500 [&_.btn-outline-primary.active]:shadow-md [&_.btn]:hover:bg-gray-50 [&_.btn]:hover:border-gray-300 [&_.btn-outline-primary]:border-gray-200 [&_.btn-outline-primary]:bg-white [&_.btn-outline-primary]:rounded-lg [&_.form-control-label]:font-medium [&_.form-control-label]:text-gray-700 [&_.form-control-label]:mb-3 [&_.form-control-label]:block !important"></div>
              </div>
    
              <div id="exrtas-area">
                <br />
                <label class="form-control-label" for="quantity">{{ __('Extras') }}</label>
                <div id="exrtas-area-inside">
                </div>
              </div>


              @if(!(isset($canDoOrdering)&&!$canDoOrdering) )
                                <div class="quantity-area">
                                    <div class="form-group">
                                        <br />
                                        <label class="form-control-label" for="quantity">{{ __('Quantity') }}</label>
                                            <input
                                                    type="number"
                                                    oninput="validateInput(this)"
                                                    min="1"
                                                    step="1"
                                                    onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                                    name="quantity" 
                                                    id="quantity" 
                                                    class="form-control form-control-alternative rounded-lg border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 w-24" 
                                                    placeholder="1" 
                                                    value="1" 
                                                    required 
                                                    autofocus
                                            >
                                    </div>
                                    <script>
                          
                                        
                                        function validateInput(input) {
                                          if (input.value > currentItem.qty) {
                                            if(currentItem.qty==0){
                                                alert('The item is out of stock');
                                            }else{
                                                alert('The number must not be greater than '+currentItem.qty);
                                            }
                                          
                                            input.value=currentItem.qty;
                                          } 
                                        }
                                      </script>
                                    <div class="quantity-btn">
                                        <div id="addToCart1">
                                            <button class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200 ease-in-out transform hover:scale-105 shadow-md" v-on:click='addToCartAct' onclick="productModal.hide()">{{ __('Add To Cart') }}</button>
                                        </div>
                                    </div>
                                   
                                </div>
        @endif
    
          </div>

         
          
                                <!-- Inform if closed -->
                                @if (isset($openingTime)&&!empty($openingTime))
                                        <br />
                                        <span class="closed_time">{{__('Opens')}} {{ $openingTime }}</span>
                                        @if(!(isset($canDoOrdering)&&!$canDoOrdering))
                                        <br />
                                        <span class="text-muted">{{__('Pre orders are possible')}}</span>
                                        @endif
                                    @endif
                                <!-- End inform -->

        </div>


            
          
        </div>
      </div>
  </div>
</div>


<div class="modal fade" id="modal-import-restaurants" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
  <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h3 class="modal-title" id="modal-title-new-item">{{ __('Import restaurants from CSV') }}</h3>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
          </div>
          <div class="modal-body p-0">
              <div class="card bg-secondary shadow border-0">
                  <div class="card-body px-lg-5 py-lg-5">
                      <div class="col-md-10 offset-md-1">
                      <form role="form" method="post" action="{{ route('import.restaurants') }}" enctype="multipart/form-data">
                          @csrf
                          <div class="form-group text-center{{ $errors->has('item_image') ? ' has-danger' : '' }}">
                              <label class="form-control-label" for="resto_excel">Import your file</label>
                              <div class="text-center">
                                  <input type="file" class="form-control form-control-file" name="resto_excel" accept=".csv, .ods, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
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
</div>
@isset($restorant)
<div class="modal fade" id="modal-restaurant-info" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
  <div class="modal-dialog modal- modal-dialog-centered modal-lg" role="document" >
      <div class="modal-content">
          <div class="modal-header">
              <h5 id="modalRestaurantTitle"  class="modal-title notranslate">{{ $restorant->name }}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
              </button>
          </div>
          <div class="modal-body p-0">
              <div class="card">
                  <div class="card-header bg-white text-center">
                      <img class="rounded img-center" src="{{ $restorant->icon }}" width="90px" height="90px"></img>
                      <h4 class="heading mt-4">{{ $restorant->name }} &nbsp;@if(count($restorant->ratings))<span><i class="fa fa-star" style="color: #dc3545"></i> <strong>{{ number_format($restorant->averageRating, 1, '.', ',') }} <span class="small">/ 5 ({{ count($restorant->ratings) }})</strong></span></span>@endif</h4>
                      <p class="description">{{ $restorant->description }}</p>
                      @if(!empty($openingTime) && !empty($closingTime))
                          <p class="description">{{ __('Open') }}: {{ $openingTime }} - {{ $closingTime }}</p>
                      @endif
                  </div>
                  <div class="card-body">
                      <div class="nav-wrapper">
                          <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                              <li class="nav-item">
                                  <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true">{{ __('About') }}</a>
                              </li>
                              <li class="nav-item">
                                  <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#tabs-icons-text-2" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false">{{ __('Reviews') }}</a>
                              </li>
                          </ul>
                      </div>
                      <div class="tab-content" id="myTabContent">
                          <div class="tab-pane fade show active" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
                              <div class="row">
                                  <div class="col-md-6">
                                      <h6 class="heading-small">{{ __('Phone') }}</h6>
                                      <p class="heading-small text-muted">{{ $restorant->phone }}</p>
                                      <br/>
                                      <h6 class="heading-small">{{ __('Address') }}</h6>
                                      <p class="heading-small text-muted">{{ $restorant->address }}</p>
                                  </div>
                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <div id="map3" class="form-control form-control-alternative"></div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <div class="tab-pane fade" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                              @if(count($restorant->ratings) != 0)
                                  <br/>
                                  <h5>{{ count($restorant->ratings) }} {{ count($restorant->ratings) == 1 ? __('Review') : __('Reviews')}}</h5>
                                  <hr />
                                  
                                  @foreach($restorant->ratings as $rating)
                                      <div class="strip">
                                          <span class="res_title"><b>{{ $rating->user->name }}</b></span><span class="float-right"><i class="fa fa-star" style="color: #dc3545"></i> <strong>{{ number_format($rating->rating, 1, '.', ',') }} <span class="small">/ 5</strong></span></span><br />
                                          <span class="text-muted"> {{ $rating->created_at->format(env('DATETIME_DISPLAY_FORMAT','d M Y')) }}</span><br/>
                                          <br/>
                                          <span class="res_description text-muted">{{ $rating->comment }}</span><br />
                                      </div>
                                  @endforeach
                              @else
                                <p>{{ __('There are no reviews yet.') }}<p>
                              @endif
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>
@endisset


