@extends('layouts.app', ['title' => __('Updates')])

@section('content')
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
</div>
<div class="container-fluid mt--7">
    <div class="row">
        <div class="col-xl-6 order-xl-1">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <h3 class="mb-0">{{ __('Updates Management') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    

                      
                </div>
            </div>
        </div>

        @if (config('settings.enalbe_change_log_in_update'))
            <div class="col-xl-6 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <div class="col-4">
                                <h3 class="mb-0">{{ __('Change log') }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        {{ Illuminate\Mail\Markdown::parse($theChangeLog) }} 
                        
                    </div>
                </div>
            </div>
        @endif
        


    </div>
</div>
<br/><br/>
</div>
@endsection




