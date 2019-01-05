$(document).ready(function(){
    $(".update").on("click",function(e){
    var id = $(this).data("up");
    console.log(id);
    $.ajax({
        url : "ajaxuseridfetch.php",
        type : "POST",
        data : {
            btn : true,
            id : id
        },
        success : function(data){
            var podaci = data.userdata;
            console.log(podaci);
            $("#roles").val(podaci[0].role_id);
            $("#firstname").val(podaci[0].firstname);
            $("#lastname").val(podaci[0].lastname);
            $("#email").val(podaci[0].email);
            $("#phone").val(podaci[0].phonenumber);
            $("#home").val(podaci[0].homeaddress);
            $("#zip").val(podaci[0].zipcode);
            $("#country").val(podaci[0].country);
            $("#csc").val(podaci[0].csc);
            $("#cardnumber").val(podaci[0].cardnumber);
            $("#idUserUpdate").val(podaci[0].user_id);
        }
    });
    });
});