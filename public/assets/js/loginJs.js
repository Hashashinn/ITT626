$(document).ready(function () {
    $("#loginBtn").click(function (e) {
        $("#loginForm").validate({
            // Specify validation rules
            rules: {
                email: {
                    required: true,
                    email: true,
                },
                password: {
                    required: true,
                    minlength: 6,
                },
            },
            messages: {
                email: {
                    required: "Please enter an email address.",
                    email: "Please enter a valid email address.",
                },
                password: {
                    required: "Please enter a password.",
                    minlength:
                        "Your password must be at least 6 characters long.",
                },
            },
            submitHandler: function (form) {
                requirejs(["sweetAlert2"], function (swal) {
                    var data = new FormData(
                        document.getElementById("loginForm")
                    );
                    $("#loginBtn")
                        .prop("disabled", true)
                        .html('<i class="bi bi-arrow-repeat"></i>Loading...');
                    // console.log(data);
                    $.ajax({
                        type: "POST",
                        url: "/auth/loginProcess",
                        data: data,
                        processData: false,
                        contentType: false,
                    })
                        .done(function (data) {
                            swal(
                                {
                                    title: data.title,
                                    text: data.msg,
                                    type: data.type,
                                    confirmButtonColor: "#3085d6",
                                    confirmButtonText: "OK",
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                },
                                function () {
                                    if (data.type == "error") {
                                    } else {
                                        // location.reload();
                                        window.location.href = "/admin";
                                    }
                                }
                            );
                        })
                        .always(function () {
                            // Re-enable the button and hide the loading animation
                            $("#loginBtn")
                                .prop("disabled", false)
                                .html("Login");
                        });
                });
            },
        });
    });
});
