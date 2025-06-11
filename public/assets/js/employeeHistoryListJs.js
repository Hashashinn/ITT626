function formatDateToDmy(date) {
    const day = date.getDate();
    const month = date.getMonth() + 1;
    const year = date.getFullYear();
    return `${day}/${month}/${year}`;
}
$(document).ready(function () {
    var titleReport = "Employees History Report";
    var currentDate = formatDateToDmy(new Date());

    $("#historyTable").dataTable({
        responsive: true,
        order: [[0, "desc"]],
        dom: "Bfrtip", 
        buttons: [
            {
                extend: "pdfHtml5",
                title: "Employees History Report",
                filename: titleReport + " " + currentDate,
                className: "btn btn-primary",
                customize: function (doc) {
                    var endDate = $("#max").val() || currentDate;
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
                            text: "Report For:  Employees History Record",
                            fontSize: 10,
                            margin: [10, 0, 0, 0],
                        },
                        {
                            text: "Date : " + endDate,
                            fontSize: 10,
                            margin: [10, 0, 0, 0],
                        },
                        {
                            text: "Generate on: " + new Date(),
                            fontSize: 10,
                            margin: [10, 20, 0, 30],
                        }
                    );
                    var columnsToInclude = [0, 1, 2, 3, 4,];
                    var columnWidths = [
                        "13%",
                        "15%",
                        "15%",
                        "10%",
                        "15%",
                    ]; // Adjust column widths as needed

                    var tableHeader = [];
                    $("#historyTable thead tr th").each(function (
                        index,
                        element
                    ) {
                        if (columnsToInclude.includes(index)) {
                            tableHeader.push($(element).text());
                        }
                    });

                    var tableData = [];
                    // Export data from all DataTable pages and add to tableData
                    var table = $("#historyTable").DataTable();
                    table.rows().every(function () {
                        var rowData = [];
                        var row = this.data();
                        for (var i = 0; i < columnsToInclude.length; i++) {
                            if (i === 4) { 
                                rowData.push(new Date(row[4]).toLocaleDateString());
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
            },
        ],
    });
});