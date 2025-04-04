@if (strlen(config('settings.recaptcha_site_key'))>2)
    @section('head')
    {!! htmlScriptTagJsApi([]) !!}
    @endsection
@endif
<!-- Section 1 -->
<section class="relative w-full bg-center bg-cover" style="background-image:url('/taxi/bg.jpeg')">

    <div class="absolute inset-0 bg-gradient-to-br from-blue-900 via-blue-700 to-blue-400 opacity-90"></div>

    <div class="mx-auto max-w-7xl">

        <div class="relative flex items-center justify-between h-24 px-10">
            <a href="/" class="flex items-center mb-4 font-medium text-gray-100 lg:order-none lg:w-auto lg:items-center lg:justify-center md:mb-0">
                <span class="text-2xl font-black leading-none text-gray-100 select-none logo">{{ strtolower(config('global.site_name','FindMeTaxi')) }}</span>
            </a>

            <a href="/" class="relative text-lg font-medium tracking-wide text-blue-100 transition duration-150 ease-out hover:text-white" x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false">
                <span class="block">{{ __('taxi.home')}}</span>
                <span class="absolute bottom-0 left-0 inline-block w-full h-1 -mb-1 overflow-hidden">
                    <span x-show="hover" class="absolute inset-0 inline-block w-full h-1 h-full transform border-t-2 border-blue-300" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="scale-0" x-transition:enter-end="scale-100" x-transition:leave="transition ease-out duration-300" x-transition:leave-start="scale-100" x-transition:leave-end="scale-0" style="display: none;"></span>
                </span>
            </a>
            
        </div>

        <div class="flex flex-col items-center px-10 pt-20 pb-40 lg:flex-row">
            <div class="relative w-full max-w-2xl bg-cover lg:w-7/12">
                <div class="relative flex flex-col items-center justify-center w-full h-full lg:pr-10">
                    <div class="flex flex-col items-start space-y-8">
                        <div class="relative">
                            <h1 class="text-5xl font-extrabold leading-tight text-gray-100 sm:text-7xl md:text-8xl">{{ __('taxi.sign_up_drivers_slogan')}}</h1>
                        </div>
                        <p class="text-2xl text-blue-300">{{ __('taxi.sign_up_drivers_slogan_description')}}</p>
                        <p class="text-s text-blue-200 font-medium opacity-90"><a href="#pricing" class="mr-1 underline">{{ __('taxi.view_pricing')}}</a> | <a href="#faq" class="mr-1 underline">{{ __('taxi.fr_as_qu')}}</a> </p>
                    </div>
                </div>
            </div>

            <div class="relative z-10 w-full max-w-2xl mt-20 lg:mt-0 lg:w-5/12">
                <div class="flex flex-col items-start justify-start p-10 bg-white shadow-2xl rounded-xl">
                    <h4 class="w-full text-3xl font-bold">{{ __('taxi.signup')}}</h4>
                    @if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('status') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                    @endif
                    <div class="relative w-full mt-6 space-y-8">
                        <form  id="{{ getFormId() }}" method="post" action="{{ route('newrestaurant.store') }}" autocomplete="off">
                            @csrf
                            <div class="relative py-3">
                                <label class="font-medium text-gray-900">{{ __('taxi.company_or_brand_name')}}</label>
                                <input value="{{ old('name')?old('name'):''}}" name="name" id="name" type="text" class="block w-full px-4 py-4 mt-2 text-xl placeholder-gray-400 bg-gray-100 rounded-lg focus:outline-none focus:ring-4 focus:ring-blue-600 focus:ring-opacity-50" >
                                @if ($errors->has('name'))
                                    <p class="text-xs text-red-900 mt-2 font-medium opacity-90">{{ $errors->first('name') }}</p>
                                @endif
                            </div>
                            <div class="relative py-3">
                                <label class="font-medium text-gray-900">{{ __('taxi.personal_name')}}</label>
                                <input value="{{ old('name_owner')?old('name_owner'):''}}" name="name_owner" id="name_owner" type="text" class="block w-full px-4 py-4 mt-2 text-xl placeholder-gray-400 bg-gray-100 rounded-lg focus:outline-none focus:ring-4 focus:ring-blue-600 focus:ring-opacity-50" >
                                @if ($errors->has('name_owner'))
                                    <p class="text-xs text-red-900 mt-2 font-medium opacity-90">{{ $errors->first('name_owner') }}</p>
                                @endif
                            </div>
                            <div class="relative py-3">
                                <label class="font-medium text-gray-900">{{ __('Email Address')}}</label>
                                <input value="{{ old('email_owner')?old('email_owner'):''}}" name="email_owner" id="email_owner" type="email" class="block w-full px-4 py-4 mt-2 text-xl placeholder-gray-400 bg-gray-100 rounded-lg focus:outline-none focus:ring-4 focus:ring-blue-600 focus:ring-opacity-50">
                                @if ($errors->has('email_owner'))
                                    <p class="text-xs text-red-900 mt-2 font-medium opacity-90">{{ $errors->first('email_owner') }}</p>
                                @endif
                            </div>
                            <div class="relative py-3">
                                <label class="font-medium text-gray-900">{{ __('Phone')}}</label><br />
                                <input name="phone_owner" id="phone_owner" type="text" class="block w-full px-4 py-4 mt-2 text-xl placeholder-gray-400 bg-gray-100 rounded-lg focus:outline-none focus:ring-4 focus:ring-blue-600 focus:ring-opacity-50">
                                @if ($errors->has('phone_owner'))
                                    <p class="text-xs text-red-900 mt-2 font-medium opacity-90">{{ $errors->first('phone_owner') }}</p>
                                @endif
                            </div>
                            <div class="relative py-3">
                                
                                    <button type="submit" class="inline-block w-full px-5 py-4 text-lg font-medium text-center text-white transition duration-200 bg-blue-600 rounded-lg hover:bg-blue-700 ease">{{ __('Create account')}}</button>
                                

                               
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

</section>
