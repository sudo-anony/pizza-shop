@extends('layouts.app', ['title' => __('Restaurant Menu Management')])
@section('admin_title')
    {{__('Menu')}}
@endsection
@section('content')
    @include('items.partials.modals', ['restorant_id' => $restorant_id])
    
    <div class="header bg-gradient-primary pb-7 pt-5 pt-md-8">
        <div class="container-fluid">
            <div class="header-body">
            <div class="row align-items-center py-4">
                <!--<div class="col-lg-6 col-7">
                </div>-->
                <div class="col-lg-12 col-12 text-right">
                    @if (isset($hasMenuPDf)&&$hasMenuPDf)
                        <a target="_blank" href="{{ route('menupdf.download')}}" class="btn btn-sm btn-danger"><i class="fas fa-file-pdf"></i> {{ __('PDF Menu') }}</a>
                    @endif
                    <button class="btn btn-icon btn-1 btn-sm btn-info" type="button" data-toggle="modal" data-target="#modal-items-category" data-toggle="tooltip" data-placement="top" title="{{ __('Add new category')}}">
                        <span class="btn-inner--icon"><i class="fa fa-plus"></i> {{ __('Add new category') }}</span>
                    </button>
                    @if($canAdd)
                    <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modal-import-items" onClick=(setRestaurantId({{ $restorant_id }}))>
                        <span class="btn-inner--icon"><i class="fa fa-file-excel"></i> {{ __('Import from CSV') }}</span>
                    </button>
                    @endif
                    @if(config('settings.enable_miltilanguage_menus'))
                        @include('items.partials.languages')
                    @endif
                </div>
            </div>
            </div>
        </div>
    </div>
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col">
                                        <h3 class="mb-0">{{ __('Restaurant Menu Management') }} @if(config('settings.enable_miltilanguage_menus')) ({{ $currentLanguage}}) @endif</h3>
                                    </div>
                                    <div class="col-auto">
                                        <!--<button class="btn btn-icon btn-1 btn-sm btn-primary" type="button" data-toggle="modal" data-target="#modal-items-category" data-toggle="tooltip" data-placement="top" title="{{ __('Add new category')}}">
                                            <span class="btn-inner--icon"><i class="fa fa-plus"></i> {{ __('Add new category') }}</span>
                                        </button>
                                        @if($canAdd)
                                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-import-items" onClick=(setRestaurantId({{ $restorant_id }}))>
                                                <span class="btn-inner--icon"><i class="fa fa-file-excel"></i> {{ __('Import from CSV') }}</span>
                                            </button>
                                        @endif
                                        @if(config('settings.enable_miltilanguage_menus'))
                                            @include('items.partials.languages')
                                        @endif-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="col-12">
                        @include('partials.flash')
                    </div>
                    <div class="card-body">
                        @if(count($categories)==0)
                            <div class="col-lg-3" >
                                <a  data-toggle="modal" data-target="#modal-items-category" data-toggle="tooltip" data-placement="top" title="{{ __('Add new category')}}">
                                    <div class="card">
                                        <img class="card-img-top" src="{{ asset('images') }}/default/add_new_item.jpg" alt="...">
                                        <div class="card-body">
                                            <h3 class="card-title text-primary text-uppercase">{{ __('Add first category') }}</h3> 
                                        </div>
                                    </div>
                                </a>
                                <br />
                            </div>
                        @endif
                       
                        @foreach ($categories as $index => $category)
                        @if($category->active == 1)
                        <div class="alert alert-default">
                            <div class="row">
                                <div class="col">
                                    <span class="h1 font-weight-bold mb-0 text-white">{{ $category->name }}</span>
                                    @if($category->subtitle)
                                        <span class="h4 font-weight-light text-white-50 ml-3">{{ $category->subtitle }}</span>
                                        @if($category->subtitle)
                                            <span class="h4 font-weight-light text-white-50 ml-3">{{ $category->subtitle }}</span>
                                        @endif
                                    @endif
                                </div>
                                <div class="d-flex">
                                    <div class="col-auto">
                                        <form action="{{ route('categories.toggle', $category) }}" method="POST" >
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-link p-0 mr-2" data-toggle="tooltip" data-placement="top" title="{{ $category->active ? __('Disable category') : __('Enable category') }}">
                                                @if($category->active == 1)
                                                    <img src="{{ asset('custom/img/switch-on.png') }}" alt="..." style="width: 40px; height: 40px;">
                                                @else
                                                    <img src="{{ asset('custom/img/switch-off.png') }}" alt="..." style="width: 40px; height: 40px;">
                                                @endif
                                            </span>
                                        </form>
                                    </div>
                                    <div class="col-auto">
                                        <div class="row">
                                            <script>
                                                function setSelectedCategoryId(id){
                                                    $('#category_id').val(id);
                                                }

                                                function setRestaurantId(id){
                                                    $('#res_id').val(id);
                                                }

                                            </script>
                                            @if($canAdd)
                                                <button class="btn btn-icon btn-1 btn-sm btn-primary" type="button" data-toggle="modal" data-target="#modal-new-item" data-toggle="tooltip" data-placement="top" title="{{ __('Add item in') }} {{$category->name}}" onClick=(setSelectedCategoryId({{ $category->id }})) >
                                                    <span class="btn-inner--icon"><i class="fa fa-plus"></i></span>
                                                </button>
                                            @else
                                                <a href="{{ route('plans.current')}}" class="btn btn-icon btn-1 btn-sm btn-warning" type="button"  >
                                                    <span class="btn-inner--icon"><i class="fa fa-plus"></i> {{ __('Menu size limit reaced') }}</span>
                                                </a>
                                            @endif
                                            <button class="btn btn-icon btn-1 btn-sm btn-warning" type="button" id="edit" data-toggle="modal" data-target="#modal-edit-category" data-toggle="tooltip" data-placement="top" title="{{ __('Edit category') }} {{ $category->name }}" data-id="{{ $category->id }}" data-name="{{ $category->name }}" data-subtitle="{{ $category->subtitle }}" >
                                                <span class="btn-inner--icon"><i class="fa fa-edit"></i></span>
                                            </button>

                                            <form action="{{ route('categories.destroy', $category) }}" method="post">
                                                @csrf
                                                @method('delete')
                                                <button class="btn btn-icon btn-1 btn-sm btn-danger" type="button" onclick="confirm('{{ __("Are you sure you want to delete this category?") }}') ? this.parentElement.submit() : ''" data-toggle="tooltip" data-placement="top" title="{{ __('Delete') }} {{$category->name}}">
                                                    <span class="btn-inner--icon"><i class="fa fa-trash"></i></span>
                                                </button>
                                            </form>

                                            @if(count($categories)>1)
                                                <div style="margin-left: 10px; margin-right: 10px">|</div>
                                            @endif

                                            <!-- UP -->
                                            @if ($index!=0)
                                                <a href="{{ route('items.reorder',['up'=>$category->id]) }}"  class="btn btn-icon btn-1 btn-sm btn-success" >
                                                    <span class="btn-inner--icon"><i class="fas fa-arrow-up"></i></span>
                                                </a>
                                            @endif
                                            

                                            <!-- DOWN -->
                                            @if ($index+1!=count($categories))
                                                <a href="{{ route('items.reorder',['up'=>$categories[$index+1]->id]) }}" class="btn btn-icon btn-1 btn-sm btn-success">
                                                    <span class="btn-inner--icon"><i class="fas fa-arrow-down"></i></span>
                                                </a>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="alert alert-default">
                            <div class="row">
                                <div class="col">
                                    <span class="h1 font-weight-bold mb-0 text-white">{{ $category->name }}</span>
                                    <span class="badge badge-danger ml-2 text-white">{{ __('Offline') }}</span>
                                </div>
                                <div class="d-flex">
                                    <div class="col-auto">
                                        <form action="{{ route('categories.toggle', $category) }}" method="POST" >
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-link p-0 mr-2" data-toggle="tooltip" data-placement="top" title="{{ $category->active ? __('Disable category') : __('Enable category') }}">
                                                @if($category->active == 1)
                                                    <img src="{{ asset('custom/img/switch-on.png') }}" alt="..." style="width: 40px; height: 40px;">
                                                @else
                                                    <img src="{{ asset('custom/img/switch-off.png') }}" alt="..." style="width: 40px; height: 40px;">
                                                @endif
                                            </span>
                                        </form>
                                    </div>
                                    <div class="col-auto">
                                        <div class="row">
                                            <script>
                                                function setSelectedCategoryId(id){
                                                    $('#category_id').val(id);
                                                }

                                                function setRestaurantId(id){
                                                    $('#res_id').val(id);
                                                }

                                            </script>
                                            @if($canAdd)
                                                <button class="btn btn-icon btn-1 btn-sm btn-primary" type="button" data-toggle="modal" data-target="#modal-new-item" data-toggle="tooltip" data-placement="top" title="{{ __('Add item in') }} {{$category->name}}" onClick=(setSelectedCategoryId({{ $category->id }})) >
                                                    <span class="btn-inner--icon"><i class="fa fa-plus"></i></span>
                                                </button>
                                            @else
                                                <a href="{{ route('plans.current')}}" class="btn btn-icon btn-1 btn-sm btn-warning" type="button"  >
                                                    <span class="btn-inner--icon"><i class="fa fa-plus"></i> {{ __('Menu size limit reaced') }}</span>
                                                </a>
                                            @endif
                                            <button class="btn btn-icon btn-1 btn-sm btn-warning" type="button" id="edit" data-toggle="modal" data-target="#modal-edit-category" data-toggle="tooltip" data-placement="top" title="{{ __('Edit category') }} {{ $category->name }}" data-id="<?= $category->id ?>" data-name="<?= $category->name ?>" >
                                                <span class="btn-inner--icon"><i class="fa fa-edit"></i></span>
                                            </button>

                                            <form action="{{ route('categories.destroy', $category) }}" method="post">
                                                @csrf
                                                @method('delete')
                                                <button class="btn btn-icon btn-1 btn-sm btn-danger" type="button" onclick="confirm('{{ __("Are you sure you want to delete this category?") }}') ? this.parentElement.submit() : ''" data-toggle="tooltip" data-placement="top" title="{{ __('Delete') }} {{$category->name}}">
                                                    <span class="btn-inner--icon"><i class="fa fa-trash"></i></span>
                                                </button>
                                            </form>

                                            @if(count($categories)>1)
                                                <div style="margin-left: 10px; margin-right: 10px">|</div>
                                            @endif

                                            <!-- UP -->
                                            @if ($index!=0)
                                                <a href="{{ route('items.reorder',['up'=>$category->id]) }}"  class="btn btn-icon btn-1 btn-sm btn-success" >
                                                    <span class="btn-inner--icon"><i class="fas fa-arrow-up"></i></span>
                                                </a>
                                            @endif
                                            

                                            <!-- DOWN -->
                                            @if ($index+1!=count($categories))
                                                <a href="{{ route('items.reorder',['up'=>$categories[$index+1]->id]) }}" class="btn btn-icon btn-1 btn-sm btn-success">
                                                    <span class="btn-inner--icon"><i class="fas fa-arrow-down"></i></span>
                                                </a>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($category->active == 1)
                        <div class="row justify-content-center">
                            <div class="col-lg-12">
                                <div class="row row-grid">
                                @php
                                    $sortedItems = $category->items->sortBy(function ($item) {
                                        return is_numeric($item->name) ? [(int)$item->name, ''] : [INF, $item->name];
                                    });
                                @endphp

                               
                                <div class="table-responsive">
                                    <table class="table align-items-center">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width: 15%;" class="table-web" scope="col">{{ __('Image') }}</th>
                                            <th style="width: 35%;" class="table-web" scope="col">{{ __('Name') }}</th>
                                            <th style="width: 25%;" class="table-web" scope="col">{{ __('Price') }}</th>
                                            <th style="width: 25%;" class="table-web" scope="col">{{ __('Stock') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($sortedItems as $item)
                                    <tr>
                                        <td style="width: 15%;">
                                            <a href="{{ route('items.edit', $item) }}">
                                                <img class="rounded" style="width: 70px; height: 50px;" src="{{ $item->logom }}" alt="...">
                                            </a>
                                        </td>
                                        <td style="width: 35%;">
                                            <a href="{{ route('items.edit', $item) }}">
                                                <h3 class="card-title text-primary text-uppercase">{{ $item->name }}</h3>
                                            </a>
                                        </td>
                                        <td style="width: 25%;">
                                            <a href="{{ route('items.edit', $item) }}">
                                                @money($item->price, config('settings.cashier_currency'), config('settings.do_convertion'))
                                            </a>
                                        </td>
                                        <td style="width: 25%;">
                                            <a href="{{ route('items.edit', $item) }}">
                                                @if ($item->available == 1)
                                                    <span class="text-success mr-2">{{ __("AVAILABLE") }}</span>
                                                @else
                                                    <span class="text-danger mr-2">{{ __("UNAVAILABLE") }}</span>
                                                @endif
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                    </table>
                                </div>
                               

                                    <!-- @if($canAdd)
                                    <div class="col-lg-3" >
                                        <a   data-toggle="modal" data-target="#modal-new-item" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" onclick=(setSelectedCategoryId({{ $category->id }}))>
                                            <div class="card">
                                                <img class="card-img-top" src="{{ asset('images') }}/default/add_new_item.jpg" alt="...">
                                                <div class="card-body">
                                                    <h3 class="card-title text-primary text-uppercase">{{ __('Add item') }}</h3>
                                                </div>
                                            </div>
                                        </a>
                                        <br />
                                    </div>
                                    @endif -->
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="row justify-content-center">
                            <div class="col-lg-12">
                                <div class="row row-grid">
                                @php
                                    $sortedItems = $category->items->sortBy(function ($item) {
                                        return is_numeric($item->name) ? [(int)$item->name, ''] : [INF, $item->name];
                                    });
                                @endphp
                                     <div class="table-responsive">
                                    <table class="table align-items-center">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width: 15%;" class="table-web" scope="col">{{ __('Image') }}</th>
                                            <th style="width: 35%;" class="table-web" scope="col">{{ __('Name') }}</th>
                                            <th style="width: 25%;" class="table-web" scope="col">{{ __('Price') }}</th>
                                            <th style="width: 25%;" class="table-web" scope="col">{{ __('Stock') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($sortedItems as $item)
                                    <tr>
                                    <td style="width: 15%;">
                                        <a href="{{ route('items.edit', $item) }}">
                                            <img class="rounded" style="width: 70px; height: 50px;" src="{{ $item->logom }}" alt="...">
                                        </a>
                                    </td>
                                    <td style="width: 35%;">
                                        <a href="{{ route('items.edit', $item) }}">
                                            <h3 class="card-title text-primary text-uppercase">{{ $item->name }}</h3>
                                        </a>
                                    </td>
                                    <td style="width: 25%;">
                                        <a href="{{ route('items.edit', $item) }}">
                                            @money($item->price, config('settings.cashier_currency'), config('settings.do_convertion'))
                                        </a>
                                    </td>
                                    <td style="width: 25%;">
                                        <a href="{{ route('items.edit', $item) }}">
                                            @if ($item->available == 1)
                                                <span class="text-success mr-2">{{ __("AVAILABLE") }}</span>
                                            @else
                                                <span class="text-danger mr-2">{{ __("UNAVAILABLE") }}</span>
                                            @endif
                                        </a>
                                    </td>
                                </tr>
                                    @endforeach
                                    </tbody>
                                    </table>
                                </div>
                                    <!-- @if($canAdd)
                                    <div class="col-lg-3" >
                                        <a   data-toggle="modal" data-target="#modal-new-item" data-toggle="tooltip" data-placement="top" href="javascript:void(0);" onclick=(setSelectedCategoryId({{ $category->id }}))>
                                            <div class="card">
                                                <img class="card-img-top" src="{{ asset('images') }}/default/add_new_item.jpg" alt="...">
                                                <div class="card-body">
                                                    <h3 class="card-title text-primary text-uppercase">{{ __('Add item') }}</h3>
                                                </div>
                                            </div>
                                        </a>
                                        <br />
                                    </div>
                                    @endif -->
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
  $("[data-target='#modal-edit-category']").on('click',function() {
    var id = $(this).attr('data-id');
    var name = $(this).attr('data-name');
    var subtitle = $(this).attr('data-subtitle');

    
    $('#cat_name').val(name);
    $('#cat_subtitle').val(subtitle); // Added line to set subtitle
    $("#form-edit-category").attr("action", "/categories/"+id);
})
</script>
@endsection