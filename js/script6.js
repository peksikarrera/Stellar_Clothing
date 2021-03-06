$(document).ready(function(){
    function initializeSlider(){
        var minprice = Number($("#pricemin").val());
        var maxprice = Number($("#pricemax").val());
            $( "#slider-range" ).slider({
                    range: true,
                    min: minprice,
                    max: maxprice,
                    values: [ minprice, maxprice ],
                    slide: function( event, ui ) {  $( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
                    }
            });
        $( "#amount" ).val( "$" + $( "#slider-range" ).slider( "values", 0 ) + " - $" + $( "#slider-range" ).slider( "values", 1 ) );
    }
    $("#clearfilterbtn").on("click",function(){
        $("#sortbtn").val(0);
        initializeSlider();
    });
    $("#filterbtn").on("click",function(e){
        e.preventDefault();
        /*var discountchb = document.getElementsByName("discountchb");
        var selectedrangesdiscount = [];
        for(var i=0; i<discountchb.length;i++){
            if(discountchb[i].checked){
                selectedrangesdiscount.push(discountchb[i].value);
            }
        }*/
        //console.log(selectedrangesdiscount);
        var typechb = document.getElementsByName("typechb");
        var selectedtypes = [];
        for(var j=0; j<typechb.length;j++){
            if(typechb[j].checked){
                selectedtypes.push(typechb[j].value);
            }
        }
        console.log(selectedtypes);
        var sortSelected = document.getElementById("sortbtn").value;
        console.log(sortSelected);
        var selectedPriceRange = $("#amount").val().split("-");
        var selectedPriceMin = Number(selectedPriceRange[0].split("$")[1].trim());
        var selectedPriceMax = Number(selectedPriceRange[1].split("$")[1].trim());
        console.log(selectedPriceMax);
        $.ajax({
            type: "POST",
            url : "logic/processproducts.php",
            dataType: "json",
            data : {
                filterbtn: true,
                //selectedrangesdiscount : selectedrangesdiscount,
                selectedtypes : selectedtypes,
                pricemin : selectedPriceMin,
                pricemax : selectedPriceMax,
                selectedsort : sortSelected,
                category : $("#category").val(),
                subcategory : $("#subcategory").val()
            },
            success: function(data){
                console.log(data);
                var ispis = "";
                if(data.length){
                    for(var i=0; i<data.length; i++){
                        ispis += '<a href="index.php?page=single&product='+ data[i].productID + '"><div class="product-grid"><div class="more-product"><span></span></div><div class="product-img b-link-stripe b-animate-go thickbox"><img src="' + data[i].source + '" class="img-responsive" alt="' + data[i].alt + '"><div class="b-wrapper"><h4 class="b-animate b-from-left  b-delay03"><button> View </button></h4></div></div></a><div class="product-info simpleCart_shelfItem"><div class="product-info-cust prt_name"><h4>Product #'+ (i+1) +'</h4><span class="item_price">€'+ data[i].price +'</span><div class="ofr"><!--<p class="pric1"><del>Rs 280</del></p>!--><p class="disc">['+ Math.ceil(data[i].discount) +'% Off]</p></div><a href="index.php?page=single&product='+ data[i].productID +'" class="view_item">View</a><div class="clearfix"> </div></div></div></div>';
                    }
                }
                else ispis = "There's no product which fits your query";
                $(".product-model-sec").html(ispis);
            }
        });
    });
});