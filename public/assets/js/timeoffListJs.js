$(document).ready(function () {
    function formatDateToDmy(date) {
        const day = date.getDate();
        const month = date.getMonth() + 1;
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
    }
    var titleReport = "Time Off Report";
    var currentDate = formatDateToDmy(new Date());
    $("#timeofTable").DataTable({
        responsive: true,
        order: [[0, "desc"]],
        dom: "Bfrtip",
        buttons: [
            {
                extend: "pdfHtml5",
                title: "Timeoff Report",
                filename: titleReport + " " + currentDate,
                className: "btn btn-primary",
                customize: function (doc) {
                    var endDate = $("#max").val() || currentDate;
                    var totalUsers = $("#timeofTable tbody tr").length;
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
                            text: "Report For:  Time Off Record",
                            fontSize: 10,
                            margin: [10, 0, 0, 0],
                        },
                        {
                            text: "Date : " + endDate,
                            fontSize: 10,
                            margin: [10, 0, 0, 0],
                        },
                        {
                            text: "Total Time Off: " + totalUsers,
                            fontSize: 10,
                            margin: [10, 0, 0, 0],
                        },
                        {
                            text: "Generate on: " + new Date(),
                            fontSize: 10,
                            margin: [10, 20, 0, 30],
                        }
                    );

                    var columnsToInclude = [0, 1, 2, 3, 4, 5, 6, 7, 8];
                    var columnWidths = [
                        "13%",
                        "10%",
                        "15%",
                        "10%",
                        "10%",
                        "10%",
                        "8%",
                        "10%",
                        "10%",
                    ]; // Adjust column widths as needed

                    var tableHeader = [];
                    $("#timeofTable thead tr th").each(function (
                        index,
                        element
                    ) {
                        if (columnsToInclude.includes(index)) {
                            tableHeader.push($(element).text());
                        }
                    });

                    var tableData = [];
                    // Export data from all DataTable pages and add to tableData
                    var table = $("#timeofTable").DataTable();
                    table.rows().every(function () {
                        var rowData = [];
                        var row = this.data();
                        for (var i = 0; i < columnsToInclude.length; i++) {
                            if (i === 0) {
                                // Format the date to display only the date part
                                rowData.push(
                                    new Date(row[0]).toLocaleDateString()
                                );
                            } else {
                                rowData.push(row[columnsToInclude[i]]);
                            }
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
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 8],
                },
            },
        ],
    });
});
