<style>
    .select2-container {
        box-sizing: border-box;
        display: inline-block;
        margin: 0;
        position: relative;
        vertical-align: middle;
        width: 100% !important;
    }
</style>

@extends('layouts.app', ['title' => __('Orders')])

@section('content')
    <div class="modal fade" id="modal-new-extras" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
        <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="modal-title-new-extras">{{ __('Add new extras') }}</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <div class="card bg-secondary shadow border-0">
                        <div class="card-body px-lg-5 py-lg-5">
                            <form role="form" method="post" action="{{ route('extras.store', $item) }}" enctype="multipart/form-data">
                                @csrf

                                @include('partials.input',['name'=>'Name','id'=>'extras_name','required'=>true,'placeholder'=>'Name'])
                                @include('partials.input',['type'=>"number",'name'=>'Price','id'=>'extras_price','required'=>true,'placeholder'=>'Price'])

                                @include('partials.input',['type'=>"hidden",'name'=>'','id'=>'extras_id','required'=>false,'placeholder'=>'id'])


                                @if ($item->has_variants)
                                <div class="form-group">

                                    <label for="variantsSelector">{{ __('Select variant or leave empty for all') }}</label><br />
                                    <select name="variantsSelector[]" multiple="multiple" class="form-control noselecttwo"  id="variantsSelector">
                                        @foreach ($item->uservariants as $variant)
                                            <option id="var{{$variant->id}}" value="{{$variant->id}}">{{ "#".$variant->id.": ".$variant->optionsList }}</option>
                                        @endforeach
                                    </select>
                                  </div>
                                @endif
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
    <script>
        function setRestaurantId(id){
            $('#res_id').val(id);
        }
    </script>
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    </div>
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-6">
                <br/>
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h3 class="mb-0">{{ __('Item Management') }}</h3>
                            </div>
                            <div class="col-6 text-right">
                                @if(auth()->user()->hasRole('owner'))
                                    <a href="{{ route('items.index') }}" class="btn btn-sm btn-primary">{{ __('Back to items') }}</a>
                                    <a onclick="copyToClipboard('{{ $restorant->getLinkAttribute().'?pid='.$item->id}}')" href="#" class="btn btn-sm btn-primary">{{ __('Copy URL') }}</a>
                                @elseif(auth()->user()->hasRole('admin'))
                                    <a href="{{ route('items.admin', $restorant) }}" class="btn btn-sm btn-primary">{{ __('Back to items') }}</a>
                                @endif
                                @if (route::has('cloner.cloneitem'))
                                    <a href="{{route('cloner.cloneitem',$item->id ) }}" class="btn btn-sm btn-secondary">{{ __('Clone it') }}</a>
                               @endif
                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="col-12">
                        @include('partials.flash')
                    </div>
                    <div class="card-body">
                        <h6 class="heading-small text-muted mb-4">{{ __('Item information') }}</h6>
                        <div class="pl-lg-4">
                            <form method="post" action="{{ route('items.update', $item) }}" autocomplete="off" enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="row">
                                    
                                    <div class="col-md-12">
                                        <div class="form-group{{ $errors->has('item_uid') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="item_uid">UID</label>
                                            <input type="text" name="item_uid" id="item_uid" class="form-control form-control-alternative{{ $errors->has('item_uid') ? ' is-invalid' : '' }}" placeholder="UID" value="{{ old('item_uid', $item->uid) }}" required autofocus>
                                            @if ($errors->has('item_uid'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('item_uid') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group{{ $errors->has('item_name') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="item_name">{{ __('Item Name') }}</label>
                                            <input type="text" name="item_name" id="item_name" class="form-control form-control-alternative{{ $errors->has('item_name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('item_name', $item->name) }}" required >
                                            @if ($errors->has('item_name'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('item_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        {{-- Add Form group for subtitle --}}
                                        <div class="form-group {{ $errors->has('item_subtitle') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="item_subtitle">{{ __('Item Subtitle') }}</label>
                                            <input type="text" name="item_subtitle" id="item_subtitle" class="form-control form-control-alternative{{ $errors->has('item_subtitle') ? ' is-invalid' : '' }}" placeholder="{{ __('Subtitle') }}" value="{{ old('item_subtitle', $item->subtitle) }}"  >
                                            @if ($errors->has('item_subtitle'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('item_subtitle') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                        <label class="form-control-label">Type</label>
                                        <select name="type" id="type" required class="form-control">
                                            <option value="" disabled selected>Select type</option>
                                            <option value="FOOD" selected>Food</option>
                                            <option value="NONFOOD">Non-Food</option>
                                        </select>
                                        </div>
                                        @include('partials.select', ['name'=>"Category",'id'=>"category_id",'placeholder'=>"Select category",'data'=>$categories,'required'=>true, 'value'=>$item->category_id])
                                        <div class="form-group{{ $errors->has('item_description') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="item_description">{{ __('Item Description') }}</label>
                                            <textarea id="item_description" name="item_description" class="form-control form-control-alternative{{ $errors->has('item_description') ? ' is-invalid' : '' }}" placeholder="{{ __('Item Description here ... ') }}" value="{{ old('item_description', $item->description) }}"  autofocus rows="3">{{ old('item_description', $item->description) }}</textarea>
                                            @if ($errors->has('item_description'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('item_description') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="form-group{{ $errors->has('item_price') ? ' has-danger' : '' }}">
                                            <label class="form-control-label" for="item_price">{{ __('Item Price') }}</label>
                                            <input type="number" step="any" name="item_price" id="item_price" class="form-control form-control-alternative{{ $errors->has('item_price') ? ' is-invalid' : '' }}" placeholder="{{ __('Price') }}" value="{{ old('item_price', $item->price) }}" required autofocus>
                                            @if ($errors->has('item_price'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('item_price') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        @include('partials.input',['id'=>'discounted_price','name'=>__('Discounted price'),'placeholder'=>__('0'),'value'=>$item->discounted_price,'required'=>false,'type'=>'number'])

                                        @if ($restorant->getConfig('hide_tax_input',"false")!="false")
                                            <!-- Hidden -->
                                            @include('partials.input',['id'=>'vat','name'=>__('VAT percentage( calculated into item price )'),'placeholder'=>__('Item VAT percentage'),'value'=>$item->vat==""?$restorant->getConfig('default_tax_value',0):$item->vat,'required'=>false,'type'=>'hidden'])
                                        @else
                                            @include('partials.input',['id'=>'vat','name'=>__('VAT percentage( calculated into item price )'),'placeholder'=>__('Item VAT percentage'),'value'=>$item->vat==""?$restorant->getConfig('default_tax_value',0):$item->vat,'required'=>false,'type'=>'number'])
                                        @endif
                                        
                                        <?php $image=['name'=>'item_image','label'=>__('Item Image'),'value'=> $item->logom,'style'=>'width: 290px; height:200']; ?>
                                        @include('partials.images',$image)
                                        @include('partials.toggle',['id'=>'itemAvailable','name'=>'Item available','checked'=>($item->available == 1)])
                                        @include('partials.toggle',['id'=>'has_variants','name'=>'Enable variants','checked'=>($item->has_variants==1)])
                                        @if($item->has_variants==1)
                                            @include('partials.toggle',['additionalInfo'=>'Missing variants will have the same price as the item','id'=>'enable_system_variants','name'=>'Enable System Variants','checked'=>($item->enable_system_variants==1)])
                                        @endif
                                        @if($restorant->getConfig('stock_enabled',"false")!="false")
                                            @include('partials.toggle',['id'=>'qty_management','name'=>'Enable QTY Managment','checked'=>($item->qty_management==1)])

                                            @if ($item->qty_management==1)
                                                @if ($item->has_variants==1)
                                                    @include('partials.input',['readonly'=>true,'id'=>'qty','name'=>__('Item QTY - determined as sum of the QTY of the variants'),'placeholder'=>__('Item QTY'),'value'=>$item->qty,'required'=>false,'type'=>'number'])
                                                @else
                                                    @include('partials.input',['id'=>'qty','name'=>__('Item QTY'),'placeholder'=>__('Item QTY'),'value'=>$item->qty,'required'=>false,'type'=>'number'])
                                                @endif
                                                
                                                
                                            @endif
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                    </div>
                                </div>
                                <div class="text-center">
                                   <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                                </div>
                            </form>
                            <div class="text-center">
                                <form action="{{ route('items.destroy', $item) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="button" class="btn btn-danger mt-4" onclick="confirm('{{ __("Are you sure you want to delete this item?") }}') ? this.parentElement.submit() : ''">{{ __('Delete') }}</button>
                                </form>
                            </div>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="col-xl-6 mb-6 mb-xl-0">
                    <br/>

                    @if ($item->has_variants==1)
                        <div class="card card-profile shadow">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col-6">
                                        <h5 class="h3 mb-0">{{ __('Variants') }}</h5>
                                    </div>
                                    <div class="col-6 text-right">
                                        <a class="btn btn-secondary btn-sm" href="{{  route('items.options.index',$item->id)}}">{{ __('Edit Options')}}</a>
                                        <a class="btn btn-success btn-sm" href="{{  route('items.variants.create',['item'=>$item->id])}}">{{ __('Add Variant')}}</a>
                                    </div>

                                </div>
                            </div>
                            <div class="card-body">



                            @include('items.variants.content')
                            <div class="table-responsive">
                                <table class="table align-items-center table-flush">
                                    <thead class="thead-light">
                                        @yield('thead')
                                    </thead>
                                    <tbody>
                                        @yield('tbody')
                                    </tbody>
                                </table>
                            </div>


                            </div>

                        </div>

                        <br />
                    @endif
                    <div class="card card-profile shadow">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h5 class="h3 mb-0">{{ __('Extras') }}</h5>
                                </div>
                                <div class="col-4 text-right">
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-new-extras" onClick=(setRestaurantId({{ $restorant_id }}))>{{ __('Add') }}</button>
                                </div>
                            </div>
                        </div>
                            <div class="table-responsive">
                                <table class="table align-items-center">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col" class="sort" data-sort="name">{{ __('Name') }}</th>
                                            <th scope="col" class="sort" data-sort="name">{{ __('Price') }}</th>
                                            @if ($item->has_variants==1)<th scope="col">{{ __('For') }}</th>@endif
                                            <th scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="list">
					@php
                                            $sortedExtras = $item->extras->sort(function ($a, $b) {
                                                $regex = '/^[^a-zA-Z]/'; 
                                                $aIsSpecial = preg_match($regex, $a->name);
                                                $bIsSpecial = preg_match($regex, $b->name);

                                                if ($aIsSpecial && !$bIsSpecial) return -1;
                                                if (!$aIsSpecial && $bIsSpecial) return 1;

                                                return strcasecmp($a->name, $b->name);
                                            });
                                        @endphp
                                        <script>
                                            var extras=<?php echo $item->extras->load('variants') ?>;
                                            
                                        </script>
                                        @foreach($sortedExtras as $extra)
                                            <tr>
                                                <th scope="row">{{ $extra->name }}</th>
                                                <td class="budget">@money( $extra->price, config('settings.cashier_currency'),config('settings.do_convertion'))</td>
                                                @if ($item->has_variants==1)
                                                    <td class="budget">
                                                        @if ($extra->extra_for_all_variants==0)
                                                            @foreach ($extra->variants as $variant)
                                                                <span class="badge badge-primary">{{ $variant->optionsList }}</span>
                                                            @endforeach
                                                        @else
                                                            {{ $extra->extra_for_all_variants?__('All variants'):__('Selected') }}
                                                        @endif
                                                    </td>
                                                @endif
                                                <td class="text-right">
                                                    <div class="dropdown">
                                                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">

                                                            <button type="button" class="dropdown-item" data-toggle="modal" data-target="#modal-new-extras" onClick=(setExtrasId({{ $extra->id }}))>Edit</button>
                                                            <form action="{{ route('extras.destroy',[$item, $extra]) }}" method="post">
                                                                @csrf
                                                                @method('delete')

                                                                <button type="button" class="dropdown-item" onclick="confirm('{{ __("Are you sure you want to delete this extras?") }}') ? this.parentElement.submit() : ''">
                                                                    {{ __('Delete') }}
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                    </div>

                    <!-- Included views -->
                    @foreach ($extraViews as $extraView )
                        @include($extraView['route'])
                    @endforeach


            </div>
        </div>
        @include('layouts.footers.auth')
    </div>
@endsection

@section('js')
    <script type="text/javascript">
        "use strict";
        function setExtrasId(id){


            //Remove all seleted
            
            

            $('option', $('#variantsSelector')).each(function(element) {
                $(this).removeAttr('selected').prop('selected', false);
            });
            
            extras.forEach(element => {

                if(element.id==id){
                    
                    $('#modal-title-new-extras').html("Edit option")
                    $('#extras_id').val(element.id);
                    $('#extras_name').val(element.name);
                    $('#extras_price').val(element.price);
                    element.variants.forEach(variant => {
                        $('#var'+variant.id).attr('selected',true)
                    });

                 }
                }
            );
        }

        function copyToClipboard(url) {
            event.preventDefault();
            navigator.clipboard.writeText(url)
                .then(() => {
                    js.notify('{{ __("Product URL copied to clipboard") }}' , "success");
                
                })
                .catch(err => {
                    console.error('Could not copy text: ', err);
                });
        }

    </script>
@endsection
