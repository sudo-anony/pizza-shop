<div class='info' >
    <div class='box-infos bg-white p-3 shadow-lg hover:shadow-xl rounded-lg'>
        <div class='head align-center'>
            <p class="text-lg  text-slate-700 font-bold mb-4"><strong>{{ __('Shopping Cart') }}</strong></p>
            
        </div>

        <div class='content' id="{{$id}}">
            
                <div v-for="item in items" class="items col-xs-12 col-sm-12 col-md-12 col-lg-12 clearfix">
                    <div class=" clearfix mt-2" v-cloak>
                        
                        <h6 class="product-item_title">@{{ item.name }}</h6>
                        <p class="product-item_quantity">@{{ item.quantity }} x @{{ item.attributes.friendly_price }}</p>
                        
                        
                        <div class="d-flex flex-row-reverse mb-2">

                            <button type="button" v-on:click="remove(item.id)" :value="item.id" class="focus:outline-none hover:text-red-500 transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>

                            <button type="button" v-on:click="incQuantity(item.id)" :value="item.id" class="focus:outline-none hover:text-green-500 transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>

                            <button type="button" v-on:click="decQuantity(item.id)" :value="item.id" class="focus:outline-none hover:text-gray-500 transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>

                        </div>
                        <hr />

                    </div>
                </div>
        </div>

        
        
       
            
            <div id="{{ $idtotal }}">
                
                <div  v-if="totalPrice==0" class=' head align-center'>
                    <p><small>{{ __('Cart is empty') }}!</small></p><br />
                </div>


                
                <div v-if="totalPrice" class='actionsCart' style="margin-top: 0px">
                    <span v-if="totalPrice" class="text-lg text-slate-700 font-bold mt-2">{{ __('Subtotal') }}:</span>
                    <span v-if="totalPrice" class="ammount text-lg text-slate-700 font-bold mt-2">@{{ totalPriceFormat }}</span>
                    <br /><br />
                    <a href="/cart-checkout" type="button" class="w-full py-3 px-6 text-base font-medium text-white bg-green-500 hover:bg-green-600 rounded-lg shadow-md transition duration-200 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 flex items-center justify-center no-underline hover:no-underline">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        {{ __('Checkout') }}
                    </a>
                </div>

            </div>
       
        

    

    </div>
</div>
<br />