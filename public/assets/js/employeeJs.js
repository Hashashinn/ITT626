$(document).ready(function () {
    var table = $("#departmentTable").dataTable({
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

        var dataRaw = getUserById(id);

        dataRaw.done(function (data) {
            console.log(data);
            $("#departmentName").val(data.name);
            $("#empId").val(data.id);
            $("#nameEdit").val(data.name);
            $("#positionEdit").val(data.position);
            $("#emailEdit").val(data.email);
            $("#roleEdit").val(data.role.role_id);
            $("#locationEdit").val(data.location_id);
            $("#departmentEdit").val(data.department_id);
            $("#scheduleEdit").val(data.schedule_id);
        });

        $("#editModal").modal("show");
    });

    function getUserById(id) {
        return $.ajax({
            url: "/employee/getemployeeById/" + id,
        });
    }
    // $(document).on("submit", "#updateBtn", function () {
    $("#updateBtn").click(function (e) {
        var id = $("#empId").val();
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
                url: "/employee/employeeUpdate/" + id,
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
        $("#addForm").validate({
            rules: {
                "role[role_id]": {
                    required: true
                }
            },
            messages: {
                "role[role_id]": {
                    required: "Please select a role for the employee."
                }
            },
            submitHandler: function (form) {
                requirejs(["sweetAlert2"], function (swal) {
                    var data = new FormData(document.getElementById("addForm"));
                    $("#saveBtn")
                        .prop("disabled", true)
                        .html('<i class="bi bi-arrow-repeat"></i> Loading...');
                    $.ajax({
                        type: "POST",
                        url: "/employee/employeeStore",
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
                                    // Handle error case if needed
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
                url: "/uploadExcel/Employee",
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
                allowEscapeKey: true
            },function () {
                $.ajax({
                    type: "GET",
                    url: "/employee/employeeDelete/" + id,
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


    function formatDateToDmy(date) {
        const day = date.getDate().toString().padStart(2, "0"); // Add leading zero if needed
        const month = (date.getMonth() + 1).toString().padStart(2, "0"); // Months are zero-based
        const year = date.getFullYear().toString().slice(-2); // Get last two digits of the year

        return `${day}-${month}-${year}`;
    }

    var titleReport = "Employee Report";
    var currentDate = formatDateToDmy(new Date());
    orderListTable = $("#employeeTable").DataTable({
        dom: "Bfrtip",
        responsive: true,
        buttons: [
            {
                extend: "pdfHtml5",
                title: "",
                filename: titleReport + " " + currentDate,
                className: "btn btn-primary",
                customize: function (doc) {
                    var totalEmployee = $("#totalEmployee").val();

                    // Clear existing content to avoid duplication
                    doc.content.splice(0, doc.content.length);

                    // Add the title and other information to the first page
                    doc.content.push(
                        {
                            text: titleReport,
                            fontSize: 12,
                            margin: [0, 0, 0, 30],
                            alignment: "center",
                        },
                        {
                            text: "Report For:  Employee Record",
                            fontSize: 10,
                            margin: [10, 0, 0, 0],
                        },
                        {
                            text: "Total Employee: " + totalEmployee,
                            fontSize: 10,
                            margin: [10, 0, 0, 0],
                        },
                        {
                            text: "Generate on: " + new Date(),
                            fontSize: 10,
                            margin: [10, 20, 0, 30],
                        }
                    );

                    var columnsToInclude = [0, 1, 2, 3, 4, 5, 7]; // Replace with the column indices you want to include

                    var columnWidths = [
                        "10%",
                        "15%",
                        "15%",
                        "10%",
                        "10%",
                        "10%",
                        "10%",
                    ]; // Adjust column widths as needed

                    var tableHeader = [];
                    $("#employeeTable thead tr th").each(function (
                        index,
                        element
                    ) {
                        if (columnsToInclude.includes(index)) {
                            tableHeader.push($(element).text());
                        }
                    });

                    var tableData = [];

                    // Export data from all DataTable pages and add to tableData
                    var table = $("#employeeTable").DataTable();
                    table.rows().every(function () {
                        var rowData = [];
                        var row = this.data();
                        for (var i = 0; i < columnsToInclude.length; i++) {
                            rowData.push(row[columnsToInclude[i]]);
                        }
                        tableData.push(rowData);
                    });

                    // Add the table with all data to the PDF
                    doc.content.push({
                        table: {
                            widths: columnWidths,
                            headerRows: 1,
                            body: [tableHeader].concat(tableData),
                        },
                        layout: "headerBorders",
                    });
                },
            },

            {
                extend: "csv",
                title: "Timeoff Report",
                filename: titleReport + " " + currentDate + " (Excel)",
                className: "btn btn-success",
            },
        ],
    });
});
