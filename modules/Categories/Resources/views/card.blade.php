<br />
<div class="card card-profile shadow" style="margin-right: 10px">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col-8">
                <h5 class="h3 mb-0">{{ __('Categories') }}</h5>
            </div>
            <div class="col-4 text-right">
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="table-responsive">
        <?php 
            $categories=\App\Models\Posts::where('post_type','category')->get(); 
            $selected=$restorant->systemcategories()->pluck('posts.id')->toArray();

            ?>
        <form method="post" action="{{ route('systemcategories.set',$restorant)}}" autocomplete="off" enctype="multipart/form-data">
            @csrf
        <table class="table align-items-center">
            <thead class="thead-light">
                <tr>
                    
                    <th scope="col" class="sort" data-sort="name">{{ __('Name') }}</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody class="list">    
                    @foreach($categories as $category)
                        <tr>
                            <th scope="row">{{ $category->title }}</th>
                            <th scope="row">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class=""  @if (in_array($category->id,$selected))
                                        checked
                                    @endif name="categories[{{  $category->id }}]">
                                </div>
                                
                            </th>

                        </tr>
                    @endforeach
                   
            
            </tbody>
        </table>
        <div class="text-center mb-2">
            <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
        </div>
        </form>
    </div>
    <!-- end content -->
</div>