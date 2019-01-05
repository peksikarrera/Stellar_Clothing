$("#dropdown1").click(function(e){
    e.preventDefault();
    $("#dropdown1items").stop(true,true);
    if($("#dropdown1items").is(":visible")){
        $("#dropdown1items").fadeOut(5);
    }
    $("#dropdown2items").fadeOut();
    $("#dropdown3items").fadeOut();
    $("#dropdown1items").css({width:"0",height:"0"});
    $("#dropdown1items").show();
    $("#dropdown1items").animate({width:"+=55",height:"+=145"},1300);

    $("#dropdown1items").blur(function(){$("#dropdown1items").fadeOut()});
});

$("#dropdown2").click(function(e){  
    e.preventDefault();
    $("#dropdown2items").stop(true,true);
    if($("#dropdown2items").is(":visible")){
        $("#dropdown2items").fadeOut(5);
    }
    $("#dropdown1items").fadeOut();
    $("#dropdown3items").fadeOut();
    $("#dropdown2items").css({width:"0",height:"0"});
    $("#dropdown2items").show();
    $("#dropdown2items").animate({width:"+=55",height:"+=145"},1300);

    $("#dropdown2items").blur(function(){$("#dropdown2items").fadeOut()});
});
$(".sb-search-submit").click(function(e){
    if(!$(".sb-search-input").val())
        e.preventDefault();
    $(".sb-search-input").fadeIn("fast");
    });