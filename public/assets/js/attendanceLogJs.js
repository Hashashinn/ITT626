$(document).ready(function () {
    // $(document).on("click", "#outsideBtn", function () {
    // $("#outsideModal").modal("show");
    // });
    var awsUrl = $("#awsUrl").val();

    $(document).on("click", "#outsideBtn", function () {
        var id = $(this).data("id");

        var dataRaw = getActivityOutsideByAttId(id);

        dataRaw.done(function (data) {
            function createTableFromObjects(objects, properties) {
                // Create the table body element
                const tbody = $("<tbody></tbody>");

                // Create a row for each object and populate the cells
                objects.forEach((obj) => {
                    const row = $("<tr></tr>");
                    properties.forEach((property) => {
                        const cell = $("<td></td>");

                        // Check if the current property is 'occupation'
                        if (property === "attachment") {
                            const link = $("<a></a>");
                            link.attr(
                                "href",
                                awsUrl + obj[property]
                            );
                            link.attr("target", "_blank");
                            link.text(obj[property]);
                            cell.append(link);
                        } else {
                            cell.text(obj[property]);
                        }

                        row.append(cell);
                    });
                    tbody.append(row);
                });

                return tbody;
            }

            const propertiesToDisplay = [
                "lat",
                "lng",
                "punch_in_time",
                "attachment",
                "remarks",
            ];

            const table = createTableFromObjects(data, propertiesToDisplay);

            $("#table-container tbody").empty();

            // Append the table to a container element
            $("#table-container").append(table);
        });

        $("#outsideModal").modal("show");
        // $("#editModal").modal("show");
    });

    function getActivityOutsideByAttId(id) {
        return $.ajax({
            url: "/getActivityOutsideByAttId/" + id,
        });
    }

    function formatDateToDmy(date) {
        const day = date.getDate().toString().padStart(2, "0"); // Add leading zero if needed
        const month = (date.getMonth() + 1).toString().padStart(2, "0"); // Months are zero-based
        const year = date.getFullYear().toString().slice(-2); // Get last two digits of the year

        return `${day}-${month}-${year}`;
    }

    // Function to apply the filter
    function applyFilter() {
        var startDate = $("#min").val();
        var endDate = $("#max").val();
        var department_id = $("#departmentFilter").val();
        var location_id = $("#locationFilter").val();

        // Define your filter data as an object
        var filterData = {
            min: startDate,
            max: endDate,
            department_id: department_id,
            location_id: location_id,
        };

        var csrfToken = $("#csrfToken").val();

        // Make a POST request to your API endpoint
        $.ajax({
            type: "POST",
            url: "/searchAttendanceLog",
            data: JSON.stringify(filterData), // Convert the filterData object to a JSON string
            contentType: "application/json; charset=utf-8", // Set the content type as JSON
            headers: {
                "X-CSRF-TOKEN": csrfToken, // Include the CSRF token in the headers
            },
            success: function (data) {
                // Handle the response data and update your DataTable
                orderListTable.clear().rows.add(data.data).draw();
                $("#searchData").val("");
                $("#searchData").val(JSON.stringify(data.data));
                $("#departmentName").val(data.departmentName) ?? "All";
                $("#locationName").val(data.locationName) ?? "All";
                $("#totalLate").val(data.totalLate) ?? "";
                $("#totalOntime").val(data.totalOntime) ?? "";
                $("#totalEmployee").val(data.totalEmployee) ?? "";

                console.log(JSON.stringify(data.data)); // Log the data to the console, for example
            },
            error: function (error) {
                console.error("Error:", error);
            },
        });
    }
    $(".buttons-pdf").prop("disabled", true);

    function decimalNUmber(no) {
        return no.toLocaleString("en-US", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });
    }
    function formatDateToDmy(date) {
        const day = date.getDate().toString().padStart(2, "0"); // Add leading zero if needed
        const month = (date.getMonth() + 1).toString().padStart(2, "0"); // Months are zero-based
        const year = date.getFullYear().toString().slice(-2); // Get last two digits of the year

        return `${year}-${month}-${day}`;
    }

    function formatDate(inputDate) {
        const dateParts = inputDate.split(" ");
        if (dateParts.length === 2) {
            const date = dateParts[0];
            const time = dateParts[1];
            const datePartsFormatted = date.split("-");

            if (datePartsFormatted.length === 3) {
                const formattedDate = `${datePartsFormatted[0]}-${datePartsFormatted[1]}-${datePartsFormatted[2]}`;
                return formattedDate;
            }
        }
        return "2023-02-10 00:00:00";
    }

    var bearerToken = $("#UserToken").val();
    var titleReport = "Attendance Report";
    var currentDate = formatDateToDmy(new Date());
    $("#pdfButton,#csvButton").prop("disabled", true);

    orderListTable = $("#attendanceTable1").DataTable({
        paging: true,
        cache: false,
        dom: "Bfrtip",
        ordering: false,
        responsive: true,
        buttons: [
            {
                extend: "pdfHtml5",
                attr: {
                    style: "display:none",
                },
                title: "",
                filename: titleReport + " " + currentDate,
                className: "btn btn-primary pdfButton",
                processing: true,
                customize: function (doc) {
                    var dateStart = $("#min").val() ?? "-";
                    var dateEnd = $("#max").val() ?? "-";
                    var department = $("#departmentName").val() ?? "All";
                    var location = $("#locationName").val() ?? "All";
                    var totalLate = $("#totalLate").val() ?? "";
                    var totalOntime = $("#totalOntime").val() ?? "";
                    var totalEmployee = $("#totalEmployee").val() ?? "";
                    var companyAdd = $("#companyAdd").val() ?? "-";
                    var companyName = $("#companyName").val() ?? "-";

                    var section1Data = [
                        {
                            label: "Date Start",
                            value: dateStart,
                        },
                        {
                            label: "Date End ",
                            value: dateEnd,
                        },
                        {
                            label: "Department",
                            value: department,
                        },
                        {
                            label: "Location",
                            value: location,
                        },
                        {
                            label: "Total Late",
                            value: totalLate,
                        },
                        {
                            label: "Total On Time",
                            value: totalOntime,
                        },
                        {
                            label: "Total Employee ",
                            value: totalEmployee,
                        },
                    ];

                    var section1Body = [];

                    section1Data.forEach(function (data) {
                        section1Body.push([
                            {
                                text: data.label,
                                fontSize: 10,
                                border: [false, false, false, false],
                            },
                            {
                                text: ":",
                                fontSize: 10,
                                margin: [30, 0],
                                border: [false, false, false, false],
                            },
                            {
                                text: data.value,
                                fontSize: 10,
                                border: [false, false, false, false],
                            },
                        ]);
                    });

                    var section5Data = [
                        {
                            label1: "Date",
                            label2: "Location",
                            label3: "Department",
                            label4: "Employee Name",
                            label5: "Punch In Time",
                            label6: "Punch Out Time",
                            label7: "Status",
                            label8: "Latitude",
                            label9: "Logitude",
                        },
                    ];

                    var newData = JSON.parse($("#searchData").val());
                    for (let i = 0; i < newData.length; i++) {
                        var locationName =
                            newData[i]?.location?.location_name ?? "-";
                        var departmentName =
                            newData[i]?.department?.name ?? "-";
                        var status = "Late";
                        if (newData[i].schedule) {
                            if (
                                newData[i].attendance_timein >
                                newData[i].schedule.time_in
                            ) {
                                var status = "Late";
                            } else {
                                var status = "On Time";
                            }
                        }

                        section5Data.push({
                            label1: newData[i].attendance_date,
                            label2: locationName,
                            label3: departmentName,
                            label4: newData[i].user.name,
                            label5: newData[i].attendance_timein,
                            label6: newData[i].attendance_timeout,
                            label7: status,
                            label8: newData[i].lat,
                            label9: newData[i].lng,
                        });
                    }
                    // total section 5
                    // section5Data.push({
                    //     label1: "",
                    //     label2: "",
                    //     label3: "",
                    //     label4: "",
                    //     label5: "",
                    //     label6: "",
                    //     label7: "Total (RM)",
                    //     label8: decimalNUmber(totalTravelDistance),
                    //     label9: decimalNUmber(parseFloat(totalTravelPetrol)),
                    //     label10: decimalNUmber(parseFloat(totalTravelToll)),
                    //     label11: decimalNUmber(parseFloat(totalTravelParking)),
                    // });

                    var section5Body = [];
                    var totalRowIndex = section5Data.length - 1;
                    section5Data.forEach(function (data, index) {
                        var isTotalRow = index === totalRowIndex;
                        border = [true, true, true, true];
                        section5Body.push([
                            {
                                text: data.label1,
                                fontSize: 10,
                                border: border,
                            },
                            {
                                text: data.label2,
                                fontSize: 10,
                                border: border,
                            },
                            {
                                text: data.label3,
                                fontSize: 10,
                                border: border,
                            },
                            {
                                text: data.label4,
                                fontSize: 10,
                                border: border,
                            },
                            {
                                text: data.label5,
                                fontSize: 10,
                                border: border,
                            },
                            {
                                text: data.label6,
                                fontSize: 10,
                                border: border,
                            },
                            {
                                text: data.label7,
                                fontSize: 10,
                                border: border,
                            },
                            {
                                text: data.label8,
                                fontSize: 10,
                                border: border,
                            },
                            {
                                text: data.label9,
                                fontSize: 10,
                                border: border,
                            },
                        ]);
                    });

                    // Clear existing content to avoid duplication
                    doc.content.splice(0, doc.content.length);

                    // Add the title and other information to the first page
                    doc.content.push(
                        {
                            text: "AMS - " + companyName,
                            fontSize: 20,
                            margin: [0, 20, 0, 0],
                            alignment: "center",
                        },
                        {
                            text: companyAdd,
                            fontSize: 10,
                            margin: [0, 20, 0, 0],
                            alignment: "center",
                        },
                        {
                            text: " ", // Add some space after the image
                        },
                        {
                            canvas: [
                                {
                                    type: "line",
                                    x1: 0,
                                    y1: 0,
                                    x2: 520,
                                    y2: 0,
                                    lineWidth: 1,
                                    color: "#cccccc",
                                    opacity: 0.5,
                                },
                            ],
                        },
                        // section 1
                        {
                            table: {
                                widths: ["auto", "auto", "*"],
                                body: section1Body,
                            },
                            margin: [0, 10, 0, 0],
                        },
                        {
                            text: " ", // Add some space
                        },
                        {
                            canvas: [
                                {
                                    type: "line",
                                    x1: 0,
                                    y1: 0,
                                    x2: 520,
                                    y2: 0,
                                    lineWidth: 1,
                                    color: "#cccccc",
                                    opacity: 0.5,
                                },
                            ],
                        },

                        // section 5
                        {
                            table: {
                                widths: [
                                    "7%",
                                    "10%",
                                    "13%",
                                    "20%",
                                    "10%",
                                    "10%",
                                    "10%",
                                    "10%",
                                    "10%",
                                ],
                                body: section5Body,
                            },
                            margin: [0, 10, 0, 0],
                        }
                    );
                    // Define a page header function
                    doc["header"] = function (page, pages, content) {
                        var dateFormat = "D-M-YY h:mm A"; // Define the date format
                        var formattedDate = moment().format(dateFormat); // Use the moment.js library to format the date
                        return {
                            columns: [
                                {
                                    text: formattedDate, // Text for the left header
                                    alignment: "left",
                                    margin: [10, 10],
                                },
                                {
                                    text: "AMS " + companyName, // Text for the right header
                                    alignment: "right",
                                    margin: [10, 10],
                                },
                            ],
                        };
                    };

                    // Define a page footer function (optional)
                    doc["footer"] = function (page, pages, content) {
                        return {
                            columns: [
                                {
                                    text:
                                        page.toString() +
                                        "/" +
                                        pages.toString(), // Text for the right footer
                                    alignment: "right",
                                    margin: [10, 10],
                                },
                            ],
                        };
                    };

                    // $(".buttons-pdf").prop("disabled", false).html("PDF");
                },
            },
            {
                extend: "csv",
                attr: {
                    style: "display:none",
                },
                title: "Timeoff Report",
                filename: titleReport + " " + currentDate + " (Excel)",
                className: "btn btn-success",
                processing: true,
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 8, 9],
                },
            },
        ],
        ajax: {
            url: "/attendanceData",
            dataSrc: "data",
        },
        columns: [
            { data: "attendance_date" },
            {
                data: "id",
                render: function (data, type, row) {
                    if (row.location) {
                        return row.location.location_name;
                    } else {
                        return "-";
                    }
                },
            },
            {
                data: "id",
                render: function (data, type, row) {
                    if (row.department) {
                        return row.department.name;
                    } else {
                        return "-";
                    }
                },
            },
            {
                data: "id",
                render: function (data, type, row) {
                    if (row.user) {
                        return row.user.name;
                    } else {
                        return "-";
                    }
                },
            },
            { data: "attendance_timein" },
            { data: "attendance_timeout" },
            {
                data: "schedule",
                render: function (data, type, row) {
                    var att_timein = row?.attendance_timein;
                    var schedule_timein = data?.time_in;
                    if (schedule_timein < att_timein) {
                        return '<span class="badge badge-danger">Late</span>';
                    } else {
                        return '<span class="badge badge-success">On Time</span>';
                    }
                },
            },
            {
                data: "attachment",
                render: function (data, type, row) {
                    if (data) {
                        return (
                            '<a target="_blank" href="' +
                            awsUrl +
                            data +
                            '">' +
                            data +
                            "</a>"
                        );
                    } else {
                        return "-";
                    }
                },
            },
            { data: "lat" },
            { data: "lng" },
            {
                data: "id",
                render: function (data, type, row) {
                    return (
                        '<button class="btn btn-info mr-1" data-id="' +
                        data +
                        '" id = "outsideBtn">' +
                        "Outside Activity" +
                        "</button>"
                    );
                },
            },
        ],
        initComplete: function () {
            // Access the data from the DataTable
            var allData = orderListTable.data().toArray();

            var jsonData = JSON.stringify(allData);

            $("#searchData").val("");
            $("#searchData").val(jsonData);
            $("#pdfButton, #csvButton").prop("disabled", false);
        },
    });
    

    $('#pdf').click(function() {
        var formData = $('#filterForm').serialize();
        var baseUrl = determineBaseUrl();
        window.location.href = baseUrl +"/download-pdf?" + formData;
    
    });
   
    $('#csv').click(function() {
        var formData = $('#filterForm').serialize();
        var baseUrl = determineBaseUrl();
        window.location.href = baseUrl +"/download-csv?" + formData;
    });
    

    $("#csvButton").on("click", function () {
        $(".loading-container").fadeIn("slow");
        setTimeout(function () {
            // Trigger the PDF download
            orderListTable.buttons(1).trigger();

            // Hide the loading animation when the download is complete
            $(".loading-container").fadeOut("slow");
        }, 2000);
    });

    function determineBaseUrl() {
        var baseUrl = ""; // Default base URL
    
        // Check if the current hostname matches the staging environment
        if (window.location.hostname === "attendance.altusmalaysia.com") {
            baseUrl = "https://attendance.altusmalaysia.com";
        }
        // Check if the current hostname matches the live environment
        else if (window.location.hostname === "stagingattendance.nadibinari.com.my") {
            baseUrl = "https://stagingattendance.nadibinari.com.my";
        }else if(window.location.hostname === "attendance.test"){
            baseUrl = "http://attendance.test";
        }
    
        return baseUrl;
    }

    function convertToCSV() {
        // Get table element
        var table = document.getElementById("attendanceTable");
    
        if (!table) {
            console.error("Table element not found.");
            return;
        }
    
        // Initialize CSV string
        var csv = [];
    
        // Loop through table rows
        for (var i = 0; i < table.rows.length; i++) {
            var row = [];
            // Loop through each cell in the row
            for (var j = 0; j < table.rows[i].cells.length; j++) {
                // Push cell value to row array
                row.push(table.rows[i].cells[j].textContent.trim());
            }
            // Join row array into CSV string
            csv.push(row.join(","));
        }
    
        // Join CSV rows with newline character
        var csvString = csv.join("\n");
    
        try {
            // Create blob object from CSV string
            var blob = new Blob([csvString], { type: "text/csv;charset=utf-8;" });
    
            // Create temporary anchor element
            var link = document.createElement("a");
            if (link.download !== undefined) { // Feature detection
                // Set anchor attributes
                var url = URL.createObjectURL(blob);
                link.setAttribute("href", url);
                link.setAttribute("download", "attendance.csv");
    
                // Append anchor to body and click it
                document.body.appendChild(link);
                link.click();
    
                // Remove anchor from body
                document.body.removeChild(link);
            } else {
                throw new Error("Your browser does not support automatic download.");
            }
        } catch (error) {
            console.error("Error generating CSV:", error);
            alert("Error generating CSV. Please try again.");
        }
    }
    
    // Add event listener to the Apply Filter button
    $("#applyFilter").on("click", function () {
        applyFilter();
    });
});
