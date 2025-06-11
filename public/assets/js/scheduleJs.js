$(document).ready(function () {
    var table = $("#roleTable").dataTable({
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

        var dataRaw = getScheduleById(id);

        dataRaw.done(function (data) {
            console.log(data);
            $("#name").val(data.slug);
            $("#edit_time_in").val(data.time_in);
            $("#edit_time_out").val(data.time_out);
            $("#dId").val(data.id);
        });

        $("#editModal").modal("show");
    });

    function getScheduleById(id) {
        return $.ajax({
            url: "/getScheduleById/" + id,
        });
    }
    $(document).on("click", "#updateBtn", function (e) {
        e.preventDefault();
        // $("#updateBtn").click(function (e) {
        var id = $("#dId").val();
        // $("#editForm").validate({
        //     // Specify validation rules
        //     rules: {},

        //     messages: {},
        //     submitHandler: function (form) {
        // alert("ss");
        requirejs(["sweetAlert2"], function (swal) {
            var data = new FormData(document.getElementById("updateForm"));
            $("#updateBtn")
                .prop("disabled", true)
                .html('<i class="bi bi-arrow-repeat"></i> Loading...');
            // console.log(data);
            $.ajax({
                type: "POST",
                url: "/scheduleUpdate/" + id,
                data: data,
                processData: false,
                contentType: false,
            }).done(function (data) {
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
            });
        }).always(function () {
            // Re-enable the button and hide the loading animation
            $("#updateBtn").prop("disabled", false).html("Submit");
        });
    });

    $("#saveBtn").click(function (e) {
        // console.log("Submit button clicked!");
        // var id = $("#dId").val();
        $("#addForm").validate({
            //Specify validation rules
            rules: {
                'schedule[slug]': {
                    required: true
                },
                'schedule[time_in]':  {
                    required: true
                },
                'schedule[time_out]':  {
                    required: true
                }
            },
            messages: {
                'schedule[slug]': {
                    required: 'Shift is required.'
                },
                'schedule[time_in]': {
                    required: 'Time In is required.'
                },
                'schedule[time_out]': {
                    required: 'Time Out is required.'
                }
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
                url: "/scheduleStore",
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
                    $("#saveBtn").prop("disabled", false).html("Submit");
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
                url: "/uploadExcel/role",
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
                showCancelButton: true,
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
            },function () {
                $.ajax({
                    type: "GET",
                    url: "/scheduleDelete/" + id,
                }).done(function (data) {
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
                        }
                    });
                });
            });
        });
    });
});
