$(document).ready(function () {
    var table = $("#departmentTable").dataTable({
        responsive: true,
    });

    $(document).on("click", "#addBtn", function () {
        $("#addModal").modal("show");
    });

    $(document).on("click", "#editBtn", function () {
        var id = $(this).data("id");

        var dataRaw = getDepartmentById(id);

        dataRaw.done(function (data) {
            console.log(data);
            $("#departmentName").val(data.name);
            $("#dId").val(data.id);
        });

        $("#editModal").modal("show");
    });

    function getDepartmentById(id) {
        return $.ajax({
            url: "/department/getDepartmentById/" + id,
        });
    }
    // $(document).on("submit", "#updateBtn", function () {
    $("#updateBtn").click(function (e) {
        var id = $("#dId").val();
        // $("#editForm").validate({
        //     // Specify validation rules
        //     rules: {},

        //     messages: {},
        //     submitHandler: function (form) {
        // alert("ss");
        requirejs(["sweetAlert2"], function (swal) {
            var data = new FormData(document.getElementById("editForm"));
            $("#updateBtn")
                .prop("disabled", true)
                .html('<i class="bi bi-arrow-repeat"></i> Loading...');
            // console.log(data);
            $.ajax({
                type: "POST",
                url: "/department/departmentUpdate/" + id,
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
                            location.reload();
                            // window.location.href = "/dashboard";
                        }
                    });
                })
                .always(function () {
                    // Re-enable the button and hide the loading animation
                    $("#updateBtn").prop("disabled", false).html("Update");
                });
        });
        // },
        // });
    });

    $("#saveBtn").click(function (e) {
        // var id = $("#dId").val();
        // $("#editForm").validate({
        //     // Specify validation rules
        //     rules: {},

        //     messages: {},
        //     submitHandler: function (form) {
        // alert("ss");
        requirejs(["sweetAlert2"], function (swal) {
            var data = new FormData(document.getElementById("addForm"));
            $("#saveBtn")
                .prop("disabled", true)
                .html('<i class="bi bi-arrow-repeat"></i> Loading...');
            // console.log(data);
            $.ajax({
                type: "POST",
                url: "/department/departmentStore",
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
                            location.reload();
                            // window.location.href = "/dashboard";
                        }
                    });
                })
                .always(function () {
                    // Re-enable the button and hide the loading animation
                    $("#addForm").prop("disabled", false).html("Submit");
                });
        });
        // },
        // });
    });

    $(document).on("click", "#deleteBtn", function () {
        id = $(this).data("id");
        requirejs(["sweetAlert2"], function (swal) {
            swal({
                title: "Are you sure?",
                type: "error",
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes!",
                showCancelButton: true,
                allowOutsideClick: true,
                allowEscapeKey: true,
            },function () {
                $.ajax({
                    type: "GET",
                    url: "/department/departmentDelete/" + id,
                }).done(function (data) {
                    swal({
                        title: data.title,
                        text: data.msg,
                        type: data.type,
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "OK",
                        cancelButtonText :"Cancel",
                        allowOutsideClick: true,
                        allowEscapeKey: true,
                    },function () {
                        if (data.type == "error") {
                        } else {
                            location.reload();
                        }
                    });
                });
            });
        });
    });
});
