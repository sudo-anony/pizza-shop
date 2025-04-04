"use strict";
var items=[];
var currentItem=null;
var currentItemSelectedPrice=null;
var lastAdded=null;
var previouslySelected=[];
var extrasSelected=[];
var variantID=null;
var debug=true;

function debugMe(title,message){
    if(debug){
        
        
        
    }
}

/*
* Price formater
* @param {Nummber} price
*/
function formatPrice(price){
    var locale=LOCALE;
    if(CASHIER_CURRENCY.toUpperCase()=="USD"){
        locale=locale+"-US";
    }

    var formatter = new Intl.NumberFormat(locale, {
        style: 'currency',
        currency:  CASHIER_CURRENCY,
    });

    var formated=formatter.format(price);

    return formated;
}

/**
 * Load extras for variant
 * @param {Number} variant_id the variant id
 * */
function loadExtras(variant_id){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'GET',
            url: '/items/variants/' + variant_id + '/extras',
            success: function (response) {
                if (response.status) {
                    response.data.sort((a, b) => {
                        const regex = /^[^a-zA-Z]/; 
                        const aIsSpecial = regex.test(a.name);
                        const bIsSpecial = regex.test(b.name);
        
                        if (aIsSpecial && !bIsSpecial) return -1;
                        if (!aIsSpecial && bIsSpecial) return 1;
        
                        return a.name.localeCompare(b.name);
                    });
        
                    $('#exrtas-area-inside').empty();
                    response.data.forEach(element => {
                        $('#exrtas-area-inside').append(
                            '<div class="custom-control custom-checkbox mb-3">' +
                            '<input onclick="recalculatePrice(' + element.item_id + ');" class="custom-control-input" id="' + element.id + '" name="extra" value="' + element.price + '" type="checkbox">' +
                            '<label class="custom-control-label" for="' + element.id + '">' + element.name + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;+' + formatPrice(element.price) + '</label>' +
                            '</div>'
                        );
                    });
        
                    $('#exrtas-area').show();
                    if (response.data.length > 0) {
                        $('#theExtrasLabel').show();
                    } else {
                        $('#theExtrasLabel').hide();
                    }
                }
            },
            error: function (response) {}
        });
        
}




/**
 *
 * Set the selected variant, set price and shows qty area and calls load extras
 * */
function setSelectedVariant(element){

    $('#modalPrice').html(formatPrice(element.price));

    console.log("Set selected variant",element);

    //Set current item price
    currentItemSelectedPrice=element.price;

    //Show QTY
    $('.quantity-area').show();

    //Set variantID
    variantID=element.id;

    //Empty the extras, and call it
    $('#exrtas-area-inside').empty();
    loadExtras(variantID);

    if(element.enable_qty){
        currentItem.qty=element.qty;
    }else{
        currentItem.qty=100;
    }

   

}

function getTheDataForTheFoundVariable(){
    
    var comparableObject={};
    previouslySelected.forEach(element => {
        comparableObject[element.option_id]=element.name.trim().toLowerCase().replace(/\s/g , "-");
    });
    comparableObject=JSON.stringify(comparableObject)
    currentItem['variants'].forEach(element => {
        if(comparableObject==JSON.stringify(JSON.parse(element.options))){
            setSelectedVariant(element);
        }
    });

}


function checkIfVariableExists(forOption,optionValue){

    var newElement={"option_id":forOption,"name":optionValue};

    var possibleSelection=JSON.parse(JSON.stringify(previouslySelected));
    possibleSelection.push(newElement);

    var filteredObjects=[];
        currentItem.variants.forEach(theVariant => {
            var theOptions=JSON.parse(theVariant.optionsiconv?theVariant.optionsiconv:theVariant.options);
            var ok=true;
            Object.keys(theOptions).map((key)=>{
                possibleSelection.forEach(element => {
                    if(key==element.option_id){
                        if(theOptions[key]+""!=element.name.trim().toLowerCase().replace(/\s/g , "-")+""){
                            ok=false;
                        }
                    }
                });

            })

            if(ok){
                    filteredObjects.push(theVariant);
                }
            });



    return filteredObjects.length>0;

}

function appendOption(name,id){
    lastAdded=id;
    $('#variants-area-inside').append('<div id="variants-area-'+id+'"><br /><label class="form-control-label"><b>'+name+'<b></label><div><div id="variants-area-inside-'+id+'" class="flex-wrap btn-group btn-group-toggle" data-toggle="buttons"> </div></div>');
}

function optionChanged(option_id,name){
    
    var newElement={"option_id":option_id,"name":name};
    debugMe("selected option",JSON.stringify(newElement));
    console.log(newElement);
    
    //Append / insert the new selectioin
    var newSelectionState=[];
    var userClickedOnAlreadySelectedOption=false;
    previouslySelected.forEach(element => {

        if(userClickedOnAlreadySelectedOption){
            $( "#variants-area-"+element.option_id ).remove();
        }

        if(element.option_id!=newElement.option_id){
            //If we haven't yet found the item add this in the selection
            if(!userClickedOnAlreadySelectedOption){newSelectionState.push(element);}
        }else{
            userClickedOnAlreadySelectedOption=true;
        }

        
    });



    if(userClickedOnAlreadySelectedOption&&lastAdded!=newElement.option_id){
        //remove also last inserted, and readded it
        $( "#variants-area-"+lastAdded ).remove();
    }

    newSelectionState.push(newElement);
    previouslySelected=newSelectionState;
    debugMe("Selection",JSON.stringify(previouslySelected));
    setVariants();


}

function appendOptionValue(name,value,enabled,option_id){
    $('#variants-area-inside-'+option_id).append('<label style="opacity: '+(enabled?1:0.5)+'" class="btn btn-outline-primary"><input  onchange="optionChanged('+option_id+',\''+value+'\')"  '+ (enabled?"":"disabled") +' type="radio" name="option_'+option_id+'" value="option_'+option_id+"_"+name+'" autocomplete="off" />'+js.trans(name)+'</label>')
}

function setVariants(){
    //1. Determine previously selected variants

   //HIDE QTY
   $('.quantity-area').show();
   $('#exrtas-area-inside').empty();
   $('#exrtas-area').hide();

    //2. Get the new option to show
    var newOptionToShow=null;
    debugMe("previouslySelected length",previouslySelected.length);
    newOptionToShow=currentItem.options[previouslySelected.length];
    debugMe("newOptionToShow",JSON.stringify(newOptionToShow));

    if(newOptionToShow!=undefined){
        //2.1 Add the options in the table
        appendOption(newOptionToShow.name,newOptionToShow.id);


        var values=(newOptionToShow.optionsiconv?newOptionToShow.optionsiconv:newOptionToShow.options).split(",");
        var titles=(newOptionToShow.options).split(",");

        for (let index = 0; index < values.length; index++) {
            const theValue = values[index];
            const theTitle = titles[index];

            if(checkIfVariableExists(newOptionToShow.id,theValue)){
                //Next variable exists
                appendOptionValue(theTitle,theValue,true,newOptionToShow.id);
            }else{
                //Varaiable with the next option value doens't exists
                appendOptionValue(theTitle,theValue,false,newOptionToShow.id);
            }

        }

    }else{
        
        getTheDataForTheFoundVariable();
    }




    //3. Add the new option options
    //3.1 If new option is null, show the variant price
}


function setCurrentItem(id, alergies){

    // find closest .allergens element
    // copy the elemnt and add it to the modal
    // with #allergensContainer as the parent

    console.log("---- ALLERGENS ----", alergies);
    const alerg = JSON.parse(alergies);
    $('#allergensContainer').empty();

    let content = '<div class="allergens" style="text-align: left; pading-top: 20px;">';

    content += '<h5>Allergies</h5>';

    content += '<ul style="list-style-type: none; padding: 0;">';

    for (let allergen of alerg) {
        content += `
            <li style="display: flex; align-items: center; margin-bottom: 2px;">
                <img src="/uploads/restorants/${allergen.image}_large.jpg" style="width: 20px; height: 20px; margin-right: 10px;" />
                <span class="text-sm" data-toggle="tooltip" data-placement="bottom" title="${allergen.title}">${allergen.title}</span>
            </li>`;
    }

    content += '</ul></div>';

    $('#allergensContainer').append(content);

    var item = items[id];
    console.log("---- ITEM ----");  
    console.log(item); 
    
    currentItem=item;
    previouslySelected=[];
    debugger;
    $('#modalTitle').text(item.name);
    $('#modalsubTitle').text(getSubtitleText(item));
    $('#modalName').text(item.name);
    $('#modalPrice').html(item.price);
    $('#modalID').text(item.id);
    $('#quantity').val(1);

    if(item.image != "/default/restaurant_large.jpg"){
        $("#modalImg").attr("src",item.image);
        $("#modalDialogItem").addClass("modal-lg");
        $("#modalImgPart").show();

        $("#modalItemDetailsPart").removeClass("col-sm-6 col-md-6 col-lg-6 offset-3");
        $("#modalItemDetailsPart").addClass("col-sm col-md col-lg");
    }else{
        $("#modalImgPart").hide();
        $("#modalItemDetailsPart").removeClass("col-sm col-md col-lg");
        $("#modalItemDetailsPart").addClass("col-sm-6 col-md-6 col-lg-6 offset-3");

        $("#modalDialogItem").removeClass("modal-lg");
        $("#modalDialogItem").addClass("col-sm-6 col-md-6 col-lg-6 offset-3");
    }

    $('#modalDescription').html(item.description);


    if(item.has_variants){
        //Vith variants
        //Hide the counter, and extrasts
        $('.quantity-area').hide();

       //Now show the variants options
       $('#variants-area-inside').empty();
       $('#variants-area').show();
       setVariants();




    }else{
        //Normal
        currentItemSelectedPrice=item.priceNotFormated;
        $('#variants-area').hide();
        $('.quantity-area').show();
    }


    $('#productModal').modal('show');

    extrasSelected=[];

    variantID=null;

    //Now set the extras
    if(item.extras.length==0||item.has_variants){
        
        $('#exrtas-area-inside').empty();
        $('#exrtas-area').hide();
    }else{
        
        $('#exrtas-area-inside').empty();
        const sortedExtras = sortExtras(item.extras);
        sortedExtras.forEach(element => {
            
            $('#exrtas-area-inside').append('<div class="custom-control custom-checkbox mb-3"><input onclick="recalculatePrice('+id+');" class="custom-control-input" id="'+element.id+'" name="extra"  value="'+element.price+'" type="checkbox"><label class="custom-control-label" for="'+element.id+'">'+element.name+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;+'+element.priceFormated+'</label></div>');
        });
        $('#exrtas-area').show();
    }
}

function sortExtras(extras) {
    return extras.sort((a, b) => {
        if (a.special && !b.special) return -1;
        if (!a.special && b.special) return 1;
        return a.name.localeCompare(b.name);
    });
}


function getSubtitleText(item) {
    let subtitleText = null; 

    try {
        if (item && item.subtitle) {
            let subtitleData = JSON.parse(item.subtitle); 
            subtitleText = subtitleData.de || null; 
        }
    } catch (error) {
        console.error("Error parsing subtitle JSON:", error);
    }
    debugger
    return subtitleText;
}


function recalculatePrice(id,value){
    var mainPrice=parseFloat(currentItemSelectedPrice);
    extrasSelected=[];

    //Get the selected check boxes
    $.each($("input[name='extra']:checked"), function(){
        mainPrice+=parseFloat(($(this).val()+""));
        extrasSelected.push($(this).attr('id'));
    });
    $('#modalPrice').html(formatPrice(mainPrice));

}

function getLocation(callback){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type:'GET',
        url: '/get/rlocation/'+$('#rid').val(),
        success:function(response){
            if(response.status){
                return callback(true, response.data)
            }
        }, error: function (response) {
        return callback(false, response.responseJSON.errMsg);
        }
    })
}

function initializeMap(lat, lng){
    var map_options = {
        zoom: 13,
        center: new google.maps.LatLng(lat, lng),
        mapTypeId: "terrain",
        scaleControl: true
    }

    map_location = new google.maps.Map( document.getElementById("map3"), map_options );
}

function initializeMarker(lat, lng){
    var markerData = new google.maps.LatLng(lat, lng);
    marker = new google.maps.Marker({
        position: markerData,
        map: map_location,
        icon: start
    });
}

function showInitProduct(){
    if(PID!="" && PID!=null && PID!=undefined){
        setCurrentItem(PID);
    }
}


var start = "/images/pin.png"
var area = "/images/green_pin.png"
var map_location = null;
var map_area = null;
var marker = null;
var infoWindow = null;
var lat = null;
var lng = null;
var circle = null;
var isClosed = false;
var poly = null;
var markers = [];
var markerArea = null;
var markerIndex = null;
var path = null;

window.onload?window.onload():null;

window.onload = function () {

    getLocation(function(isFetched, currPost){
        if(isFetched){


            if(currPost.lat != 0 && currPost.lng != 0){
                //initialize map
                initializeMap(currPost.lat, currPost.lng)

                //initialize marker
                initializeMarker(currPost.lat, currPost.lng)
            }
        }
    });

    showInitProduct();
}







    $(".nav-item-category").on('click', function() {
        $(".nav-item-category .nav-link").removeClass("active");
        $(this).find(".nav-link").addClass("active");
        $.each(categories, function( index, value ) {
            $("."+value).show();
        });
        if ($(this).attr("class").split(" ").includes("all-category-classs")){
            $("#offer_category_div").removeClass("d-none");
            return;
        }
        var id = $(this).attr("id");
        var category_id = id.substr(id.indexOf("_")+1, id.length);
        if ($(this).attr("class").split(" ").includes("offer-classs")){
            $("#offer_category_div").removeClass("d-none");
        }else{
            $("#offer_category_div").addClass("d-none");
        }
        $.each(categories, function( index, value ) {
            if(value != category_id){
                $("."+value).hide();
            }
        });
    });