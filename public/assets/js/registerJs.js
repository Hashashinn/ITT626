$(document).ready(function () {
    $("#saveBtn").click(function (e) {
        $("#registerForm").validate({
            rules: {
                "user[email]": {
                    required: true,
                    email: true,
                },
                "user[name]": {
                    required: true,
                },
                "user[password]": {
                    required: true,
                    minlength: 6,
                },
            },
            messages: {
                "user[email]": {
                    required: "Please enter an email address.",
                    email: "Please enter a valid email address.",
                },
                "user[name]": {
                    required: "Please enter your full name.",
                },
                "user[password]": {
                    required: "Please enter a password.",
                    minlength:
                        "Your password must be at least 6 characters long.",
                },
            },
            submitHandler: function (form) {
                // Handle the form submission here
                requirejs(["sweetAlert2"], function (swal) {
                    var data = new FormData(
                        document.getElementById("registerForm")
                    );
                    $("#saveBtn")
                        .prop("disabled", true)
                        .html('<i class="bi bi-arrow-repeat"></i> Loading...');
                    $.ajax({
                        type: "POST",
                        url: "/auth/registerStore",
                        data: data,
                        processData: false,
                        contentType: false,
                    })
                        .done(function (response) {
                            swal({
                                title: response.title,
                                text: response.msg,
                                type: response.type,
                                confirmButtonColor: "#3085d6",
                                confirmButtonText: "OK",
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                            }).then(function (result) {
                                if (
                                    result.value &&
                                    result.value.type === "error"
                                ) {
                                    alert("ss");
                                } else {
                                    // location.reload();
                                    window.location.href = "/login";
                                }
                            });
                        })
                        .always(function () {
                            // Re-enable the button and hide the loading animation
                            $("#saveBtn")
                                .prop("disabled", false)
                                .html("Submit");
                        });
                });
            },
        });
    });
});
