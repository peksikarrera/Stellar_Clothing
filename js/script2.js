$(document).ready(function () {
    setTimeout(function () {
        $(".login-left").animate({ right: "0", opacity: "1" }, 900);
        $(".login-right").animate({ left: "0", opacity: "1" }, 900);
    }, 700);
    var regEmail = /^[A-z][\w.]{1,}\@[a-z][\w]{1,}(\.)+[a-z.]{1,}$/;
    var regPasswd = /^[0-9A-z\!\#\$\%\^\&\*\/]{7,20}$/;
    document.getElementById("email").addEventListener("blur", function () {
        var email = $("#email").val();
        if (!regEmail.test(email)) {
            document.getElementById("email").style.border = "1px solid red";
            $("#emailSpan").html("Wrong email format");
        }
        else {
            document.getElementById("email").style.border = "1px solid #555";
            $("#emailSpan").html("");
        }
    });
    document.getElementById("password").addEventListener("blur", function () {
        var password = $("#password").val();
        if (!regPasswd.test(password)) {
            document.getElementById("password").style.border = "1px solid red";
            $("#passwordSpan").html("Wrong password format");
        }
        else {
            document.getElementById("password").style.border = "1px solid #555";
            $("#passwordSpan").html("");
        }
    });
    $("#loginbtn").click(function (e) {
        e.preventDefault();
        var mail, password, arrayincorrect = [];
        email = document.getElementById("email").value;
        password = document.getElementById("password").value;
        var arrayofincorrectness = [];
        if (!regEmail.test(email)) {
            arrayofincorrectness.push("Wrong email format");
            $("#emailSpan").html("Wrong email format");
        }
        else {
            $("#emailSpan").html("");
        }
        if (!regPasswd.test(password)) {
            arrayofincorrectness.push("Wrong password format");
            $("#passwordSpan").html("Wrong password format");
        }
        else {
            $("#passwordSpan").html("");
        }
        if (!arrayofincorrectness.length) {
            var obj = {
                tbemail: email,
                tbpassword: password,
                loginbtn : true
            };
            $.ajax({
                type: "POST",
                url: "logic/ajaxlogin.php",
                data: obj,
                success: function (data, xhr) {
                    //console.log(data);
                    alert("Success");
                    window.location.replace("index.php");
                },
                error: function (xhr, statusTxt, error) {
                    switch(xhr.status){
                        case 422:
                        $(".login-page").html("Data format is not correct");
                        break;
                        case 409:
                        $(".login-page").html("Email and password don't match or your account is not activated");
                        break;
                    }
                }
            });
        }
    });
});
