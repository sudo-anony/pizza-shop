
                           
                                <div class="col-12">
                                    <div class="owl-carousel owl-theme categories">
                                        <?php $categories=\App\Models\Posts::where('post_type','category')->get(); ?>
                                        <ul class="nav nav-pills bg-white mb-4">
                                            <li class="nav-item nav-item-category" onclick="showAllVendors()">
                                                <a class="nav-link mb-sm-3 mb-md-0">{{__('All')}}</a>
                                            </li>
                                            @foreach ($categories as $category)
                                                <li class="nav-item nav-item-category" onclick="showVendorsByCategory({{$category->id}})">
                                                    <a class="nav-link mb-sm-3 mb-md-0">{{ $category->title }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <script>
                                    function showAllVendors(){
                                        $('.resitems').show();
                                    }
                                    function showVendorsByCategory(category){
                                        $('.resitems').hide();
                                        $('.cat'+category).show();
                                    }
                                </script>
                           
                            <br /><br /><br />
                            
                  