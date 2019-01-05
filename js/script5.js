$(document).ready(function(){
    var productID = $("#productID").val();
    var sizeSelected = false;
    var colorSelected = false;
    var productsizeExists = typeof($(".productsize")) == 'undefined' ? false : true;
    var productcolorExists = typeof($(".productcolor")) == 'undefined' ? false : true;
    var sizeID;
    var colorID;
    $(".productsize").on("click", function (e) {
        sizeSelected = true;
        e.preventDefault();
        $(".productsize").css("color", "black");
        $(this).css("color", "orange");
        sizeID = $(this).attr("value");
        $.ajax({
            url : "logic/checkavailablecolors.php",
            type : "POST",
            dataType: "JSON",
            data : {
                productID : productID,
                sizeselected : true,
                sizeID : sizeID
            },
            success: function(data,xhr){
                var ispis = "";
                for(var i=0; i<data.length; i++){
                    ispis += '<li><a href="#"><div class="productcolor" id="' + data[i].color +'" value="' + data[i].color_id +'"></div></a></li>';
                }
                $("#fetchcolors").html(ispis);
            },
            error: function(xhr,statusTxt){
                console.log(xhr.status);
            }
        });
    })
    $(document).on("click",".productcolor",function(e){
        colorSelected = true;
        e.preventDefault();
        $(".productcolor").css("border", "0.5px solid black");
        $(this).css("border", "1px solid orange");
        colorID = $(this).attr("value");
        $.ajax({
            url : "logic/checkquantity.php",
            type : "POST",
            dataType: "JSON",
            data : {
                productID : productID,
                colorselected : true,
                colorID : colorID,
                sizeID : sizeID
            },
            success: function(data,xhr){
                console.log(data);
                $("#quantity").attr("max",data.quantity);
                $("#quantity").on("blur",function(){
                    if(Number($("#quantity").val()) <= 0 || Number($("#quantity").val()) > Number($("#quantity").attr("max"))){
                        $("#quantity").css("border","1px solid red");
                    }
                    else{
                        $("#quantity").css("border","1px solid gray");
                    }
                });
            },
            error: function(xhr,statusTxt){
                console.log(xhr.status);
            }
        });
    });
    $(".rating span").on("click", ratingfun);
    function ratingfun() {
        $(".rating span").css("color", "black");
        $(this).css("color", "gold");
        $(this).nextAll().css("color", "gold");
        var rating = $(this).nextAll().length + 1;
        console.log(rating);
        $.ajax({
            url : "logic/rating.php",
            type : "POST",
            data : {
                rated : true,
                rate : rating,
                productID : productID
            },
            success : function(){
                console.log("Ajax working");
            },
            error : function(xhr){
                switch(xhr.status){
                    case 403:
                        alert("You are not logged in");
                        break;
                    case 401:
                        alert("You don't have a permission for doing this action");
                        break;
                    case 409:
                        alert("You 've already voted");
                        break;
                    case 422:
                        alert("Incorrect data");
                        break;
                    case 500:
                        alert("Server error");
                        break;
                }
            }
        });
        $(".rating span").off("click", ratingfun);
    }
    $(".add-cart.item_add").on("click", function (e) {
        e.preventDefault();
        var errors = false;
        var quantity = $("#quantity").val();
        if(productcolorExists){
            if(!colorSelected){
                $(".chooseoption h3").animate({letterSpacing:"2rem"},500);
                $(".chooseoption h3").animate({letterSpacing:"0rem"},600);
                errors = true;
            }
        }
        if(productsizeExists){
            if(!sizeSelected){
                $(".chooseoption h3").animate({letterSpacing:"2rem"},500);
                $(".chooseoption h3").animate({letterSpacing:"0rem"},600);
                errors = true;
            }
        }
        if(quantity < 1){
            errors = true;
            $(".qty").animate({letterSpacing:"2rem"},500);
            $(".qty").animate({letterSpacing:"0rem"},600);
        }
        if(!errors){
            addToCart();
        }
    });
    function addToCart(){
        var itemprice = $("#price").val();
        var quantity = $("#quantity").val();
        $.ajax({
            type : "POST",
            url : "logic/addtocart.php",
            dataType: "JSON",
            data : {
                productID : productID,
                quantity : quantity,
                price : itemprice,
                colorID : colorID,
                sizeID : sizeID,
                addcart : true
            },
            success: function(data){
                $("#popupproduct").slideDown();
                $("#popupproduct p").text(data);
            },
            error: function(xhr){
                switch(xhr.status){
                    case 401:
                        pojavljivanjePopUp();
                        $("#popupproduct p").text("You have to be logged in to add an item to the shopping cart");
                        break;
                }
            }
        });
    }
    function pojavljivanjePopUp(){
        $("#popupproduct").slideToggle();
    }
    function nestajanjePopUp() {
        $("#popupproduct").animate({ width: "0rem", opacity: "0" }, 1000);
        setTimeout(function () {$("#popupproduct").hide(); $("#popupproduct").css("width","50rem"); $("#popupproduct").css("opacity","1");}, 1500);
    }
    $(".closeButton").click(nestajanjePopUp);
    $("#okdugme").click(nestajanjePopUp);
    $(".sb-search-submit").click(function (e) {
        if (!$(".sb-search-input").val())
            e.preventDefault();
        $(".sb-search-input").fadeIn("fast");
    });
});