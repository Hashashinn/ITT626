$(document).ready(function () {
    var table = $("#locationTable").dataTable({
        responsive: true,
    });

    $(document).on("click", "#addBtn", function () {
        $("#addModal").modal("show");
    });

    $(document).on("click", "#excelBtn", function () {
        $("#excelModal").modal("show");
    });

    $(document).on("click", "#editBtn", function () {
        var id = $(this).data("id");

        var dataRaw = getLocationById(id);

        dataRaw.done(function (data) {
            console.log(data);
            $("#location_name").val(data.location_name);
            $("#lat").val(data.lat);
            $("#lng").val(data.lng);
            $("#dId").val(data.id);
        });

        $("#editModal").modal("show");
    });

    function getLocationById(id) {
        return $.ajax({
            url: "/location/getLocationById/" + id,
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
                url: "/location/locationUpdate/" + id,
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
        $("#addForm").validate({
        //     // Specify validation rules
            rules: {
                location_name: {
                    required: true,
                },
                lat: {
                    required: true,
                },
                lng: {
                    required: true,
                },
            },

            messages: {
                location_name: {
                    required: "Location name is required.",
                },
                lat: {
                    required: "Latitude is required.",
                },
                lng: {
                    required: "Longitude is required.",
                },
            },
            submitHandler: function (form) {
        // alert("ss");
        requirejs(["sweetAlert2"], function (swal) {
            var data = new FormData(document.getElementById("addForm"));
            $("#saveBtn")
                .prop("disabled", true)
                .html('<i class="bi bi-arrow-repeat"></i> Loading...');
            // console.log(data);
            $.ajax({
                type: "POST",
                url: "/location/locationStore",
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
        },
        });
    });

    $("#submitExcelBtn").click(function (e) {
        // var id = $("#dId").val();
        // $("#editForm").validate({
        //     // Specify validation rules
        //     rules: {},

        //     messages: {},
        //     submitHandler: function (form) {
        // alert("ss");
        requirejs(["sweetAlert2"], function (swal) {
            var data = new FormData(document.getElementById("excelForm"));
            $("#submitExcelBtn")
                .prop("disabled", true)
                .html('<i class="bi bi-arrow-repeat"></i> Loading...');
            // console.log(data);
            $.ajax({
                type: "POST",
                url: "/uploadExcel/location",
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
                    $("#submitExcelBtn").prop("disabled", false).html("Submit");
                });
        });
    });

    $(document).on("click", "#deleteBtn", function () {
        id = $(this).data("id");
        requirejs(["sweetAlert2"], function (swal) {
            swal({
                title: "Are you sure?",
                type: "error",
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes!",
                cancelButtonText :"Cancel",
                showCancelButton: true,
                allowOutsideClick: true,
                allowEscapeKey: true,
            },function () {
                $.ajax({
                    type: "GET",
                    url: "/location/locationDelete/" + id,
                }).done(function (data) {
                    swal({
                        title: data.title,
                        text: data.msg,
                        type: data.type,
                        confirmButtonColor: "#3085d6",
                        confirmButtonText: "OK",
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
