 <!-- header -->
<div class="flex justify-center items-center flex-row" style="height: 400px;">
    <div style="background-position: 50%; background-image: url('{{ $restorant->coverm  }}'); height: 100%;"
        class="w-full h-screen bg-cover bg-center">

        <!--  Content -->
        <div class="flex flex-col justify-center items-center w-full h-full  bg-black/40 backdrop-brightness-100 ">

            <!-- Inner content -->
            <div class="flex flex-col text-white text-center justify-center items-center px-10 space-y-1">

                <!-- Icon -->
                <div class="mt-20">
                    <picture
                        class="flex object-none overflow-hidden relative mx-0 mt-0 mb-4 w-20 h-20 border-4 border-white border-solid"
                        style="border-radius: 50%;"><img loading="lazy" src="{{ $restorant->logom }}"
                            class="block relative p-0 m-0 max-w-full align-middle border-0 border-none" /></picture>
                </div>

                <!-- Name -->
                <div class="">
                    <h1 class="text-4xl font-semibold text-white">
                        {{ $restorant->name }}
                    </h1>
                </div>

                <!-- Desciption -->
                <div class="">
                    <p class="text-sm font-medium">
                        {{ $restorant->description }}
                        
                    </p>
                </div>

                 <!-- Dirrections -->
                <div class="mb-10">
                    <a href="https://maps.google.com/maps?q={{ $restorant->address }}"
                        target="_blank"
                        class="font-semibold"
                        style='text-decoration: none; '>
                        {{ __('Get Directions') }}</a>
                </div>


                <!-- Buttons -->
                <div  style="overflow-x: auto; white-space: nowrap;"  class="flex flex-row   max-w-md	lg:max-w-lg    ">

                    <a href="#place-menu "
                            class="current menu-tab mr-2 mt-5 flex mb-10 inline-block items-center py-2 px-4 m-0 text-slate-700  leading-none  bg-white rounded-md border-0 cursor-pointer">
                            <svg class="w-6 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                              </svg>
                              
                            {{ __('Menu') }}
                        </a>

                    <a  href="#place-info"
                        class="menu-tab mr-2 mt-5 flex mb-10 inline-block items-center py-2 px-4 m-0 text-slate-700  leading-none bg-white rounded-md border-0 cursor-pointer">
                       
                        <svg  class="w-6 mr-1"  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                          </svg>

                          
                        
                        {{ __('About') }}
                    </a>


                   

                    @if (!$restorant->getConfig('disable_callwaiter',false))
                         <!-- Waiter -->
                        <a data-toggle="modal" data-target="#modal-form" href='javascript:;'
                            class="mt-5 mr-2 flex mb-10 inline-block items-center py-2 px-4 m-0 text-slate-700  leading-none  bg-white rounded-md border-0 cursor-pointer">
                            <svg class="w-6 mr-1"  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0M3.124 7.5A8.969 8.969 0 015.292 3m13.416 0a8.969 8.969 0 012.168 4.5" />
                              </svg>
                              
                            {{ __('Waiter') }}
                        </a>
                    @endif

                    @if (\Akaunting\Module\Facade::has('cards')&&$restorant->getConfig('enable_loyalty', false))
                        <!-- Loyalty  -->
                        <a href="{{ route('loyalty.landing',['alias'=>$restorant->subdomain])}}"
                            class="mt-5 mr-2 flex mb-10 inline-block items-center py-2 px-4 m-0 text-slate-700  leading-none  bg-white rounded-md border-0 cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 109.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1114.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                              </svg>
                            {{  __('loyalty.loyalty_program') }}
                        </a>
                    @endif

                   

                   @if ($canDoOrdering&&$restorant->getConfig('clients_enable','false')!='false')
                        <a href="{{ route('login')}}?showCreate=true"
                                    class="mt-5 mr-2 flex mb-10 inline-block items-center py-2 px-4 m-0 text-slate-700  leading-none  bg-white rounded-md border-0 cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                    </svg>
                                    
                                    {{  __('My Orders')  }}
                        </a>
                    @elseif(isset($hasGuestOrders)&&$hasGuestOrders&&$canDoOrdering)
                        <!-- Guest order  -->
                        <a href="{{ route('guest.orders')}}"
                            class="mt-5 mr-2 flex mb-10 inline-block items-center py-2 px-4 m-0 text-slate-700  leading-none  bg-white rounded-md border-0 cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                            </svg>
                            
                            {{  __('My Orders')  }}
                        </a>
                            
                   @endif

                   
                    
                   

                   

                </div>

            </div>
        </div>
    </div>
</div>