$(document).ready(function(){
    $("#voteresultscontainer").hide();
    $(window).scroll(function(){
        if($(window).scrollTop()>90){
            $(".contact-left").animate({opacity:1},700);
            $(".contact-right").animate({opacity:1},700);
        }
    });
var name;
var regName = /^[A-Z][a-z]{2,12}(\s[A-Z][a-z]{3,22})+$/;
var email;
var regEmail = /^[A-z][\w.]{1,}\@[a-z][\w]{1,}(\.)+[a-z.]{1,}$/;
document.getElementById("name").addEventListener("blur",function(){
    name = document.getElementById("name").value;
    if(!regName.test(name)){
        document.getElementById("nameSpan").textContent="Wrong format";
        document.getElementById("name").style.border = "1px solid red";
    }
    else {
        document.getElementById("nameSpan").textContent="";
        document.getElementById("name").style.border = "1px solid #dcdee0";
    }
});
document.getElementById("email").addEventListener("blur",function(){
    email = document.getElementById("email").value;
    if(!regEmail.test(email)){
        document.getElementById("emailSpan").textContent="Wrong format";
        document.getElementById("email").style.border = "1px solid red";
    }
    else {
        document.getElementById("emailSpan").textContent="";
        document.getElementById("email").style.border = "1px solid #dcdee0";
    }
});
document.getElementById("txtmessage").addEventListener("blur",function(){
    message = document.getElementById("txtmessage").value;
    if(!message){
        document.getElementById("txtmessage").style.border = "1px solid red";
    }
    else {
        document.getElementById("txtmessage").style.border = "1px solid #dcdee0";
    }
});
$("#sendmessage").click(function(e){
    e.preventDefault();
    name = document.getElementById("name").value;
    email = document.getElementById("email").value;
    message = document.getElementById("txtmessage").value;
    var arrayofincorrectness = [];
        name = document.getElementById("name").value;
        if(!regName.test(name)){
            arrayofincorrectness.push("Wrong name format");
        }
        email = document.getElementById("email").value;
        if(!regEmail.test(email)){
            arrayofincorrectness.push("Wrong email format");
        }
        if(!message){
            arrayofincorrectness.push("Message box is empty");
        }
    if(arrayofincorrectness.length){
        alert("Incorrect data");
    }
    else{
        var obj = {
            tbname : name,
            tbemail : email,
            message : document.getElementById("txtmessage").value,
            bsubmit : true
        };
        $.ajax({
            type : "POST",
            url : "logic/ajaxcontact.php",
            data : obj,
            success: function(data,xhr){
                alert("Success");
                $("form").trigger("reset");
            },
            error: function(xhr,statusTxt,error){
                alert("Failure");
            }
        });
        alert("Data sent");
    }
});
    $(".votebtn").click(function(){
        var btnSelected = $(this).val();
        $.ajax({
            url : "logic/ajaxvoting.php",
            type : "POST",
            data : {
                vote : btnSelected
            },
            dataType : "json",
            success : function(data){
                $(".votebtn").prop("disabled",true);
                var results="";
                console.log(data);
                for(var i=0; i<data.length; i++){
                    results += '<tr>';
                    results += '<td>' + data[i].name +'</td>';
                    results += '<td>' + data[i].numberofvotes +'</td>';
                    results += '</tr>';
                }
                $("#voteresultscontainer .table").html(results);
                $("#voteresultscontainer").fadeIn(800);
            },
            error : function(xhr, statusTxt, error){
                switch(xhr.status){    
                case 401:
                    alert("You are not logged in");
                    break;
                case 409:
                    alert("You've already voted");
                    $(".votebtn").prop("disabled",true);
                    break;
                case 500:
                    alert("Server error");
                    break;
                }
            }
        });
    });
});