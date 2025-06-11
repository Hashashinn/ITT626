$(document).ready(function () {
    $("#resetBtn").click(function (e) {
        $("#resetPasswordForm").validate({
            // Specify validation rules
            rules: {},
            messages: {},
            submitHandler: function (form) {
                requirejs(["sweetAlert2"], function (swal) {
                    var data = new FormData(
                        document.getElementById("resetPasswordForm")
                    );
                    $("#resetBtn")
                        .prop("disabled", true)
                        .html('<i class="bi bi-arrow-repeat"></i> Loading...');
                    // console.log(data);
                    $.ajax({
                        type: "POST",
                        url: "/auth/sendResetPasswordEmail",
                        data: data,
                        processData: false,
                        contentType: false,
                    })
                        .done(function (data) {
                            swal({
                                title: data.title,
                                text: data.msg,
                                type: data.type,
                                confirmButtonColor: "#3085d6",
                                confirmButtonText: "OK",
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                            },function () {
                                if (data.type == "error") {
                                } else {
                                    // location.reload();
                                    window.location.href = "/admin";
                                }
                            });
                        })
                        .always(function () {
                            // Re-enable the button and hide the loading animation
                            $("#resetBtn")
                                .prop("disabled", false)
                                .html("Send Password Reset Link");
                        });
                });
            },
        });
    });

    $("#saveBtn").click(function (e) {
        var resetPassToken = $("#reset_pass_token").val();
        $("#ConfirmPasswordForm").validate({
            // Specify validation rules
            rules: {
                password: {
                    required: true,
                    minlength: 6,
                },
                password_confirmation: {
                    required: true,
                    equalTo: "#password",
                },
            },
            messages: {
                password: {
                    required: "Please enter a new password",
                    minlength:
                        "Your password must be at least 6 characters long",
                },
                password_confirmation: {
                    required: "Please confirm your new password",
                    equalTo: "Passwords do not match",
                },
            },
            submitHandler: function (form) {
                requirejs(["sweetAlert2"], function (swal) {
                    var data = new FormData(
                        document.getElementById("ConfirmPasswordForm")
                    );
                    $("#saveBtn")
                        .prop("disabled", true)
                        .html('<i class="bi bi-arrow-repeat"></i> Loading...');
                    // console.log(data);
                    $.ajax({
                        type: "POST",
                        url: "/updateResetPassword/" + resetPassToken,
                        data: data,
                        processData: false,
                        contentType: false,
                    })
                        .done(function (data) {
                            swal({
                                title: data.title,
                                text: data.msg,
                                type: data.type,
                                confirmButtonColor: "#3085d6",
                                confirmButtonText: "OK",
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                            },function () {
                                if (data.type == "error") {
                                } else {
                                    // location.reload();
                                    window.location.href = "/";
                                }
                            });
                        })
                        .always(function () {
                            // Re-enable the button and hide the loading animation
                            $("#saveBtn")
                                .prop("disabled", false)
                                .html("Reset Password");
                        });
                });
            },
        });
    });

    $("#changePassBtn").click(function (e) {
        $("#changePasswordForm").validate({
            // Specify validation rules
            rules: {
                password: {
                    required: true,
                    minlength: 6,
                },
                password_confirmation: {
                    required: true,
                    equalTo: "#password",
                },
            },
            messages: {
                password: {
                    required: "Please enter a new password",
                    minlength:
                        "Your password must be at least 6 characters long",
                },
                password_confirmation: {
                    required: "Please confirm your new password",
                    equalTo: "Passwords do not match",
                },
            },
            submitHandler: function (form) {
                requirejs(["sweetAlert2"], function (swal) {
                    var data = new FormData(
                        document.getElementById("changePasswordForm")
                    );
                    $("#changePassBtn")
                        .prop("disabled", true)
                        .html('<i class="bi bi-arrow-repeat"></i> Loading...');
                    // console.log(data);
                    $.ajax({
                        type: "POST",
                        url: "/changePassword",
                        data: data,
                        processData: false,
                        contentType: false,
                    })
                        .done(function (data) {
                            swal({
                                title: data.title,
                                text: data.msg,
                                type: data.type,
                                confirmButtonColor: "#3085d6",
                                confirmButtonText: "OK",
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                            },function () {
                                if (data.type == "error") {
                                } else {
                                    // location.reload();
                                    window.location.href = "/admin";
                                }
                            });
                        })
                        .always(function () {
                            // Re-enable the button and hide the loading animation
                            $("#changePassBtn")
                                .prop("disabled", false)
                                .html("Change Password");
                        });
                });
            },
        });
    });
});
