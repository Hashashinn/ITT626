var assets = "";
requirejs.config({
    baseUrl: "/",
    paths: {
        login: "assets/js/login",
        departmentJs: "assets/js/departmentJs",
        roleJs: "assets/js/roleJs",
        registerJs: "assets/js/registerJs",
        attendanceJs: "assets/js/attendanceJs",
        locationJs: "assets/js/locationJs",
        employeeJs: "assets/js/employeeJs",
        loginJs: "assets/js/loginJs",
        attendanceOutsideJs: "assets/js/attendanceOutsideJs",
        attendanceLogJs: "assets/js/attendanceLogJs",
        scheduleJs: "assets/js/scheduleJs",
        resetPasswordFormJs: "assets/js/resetPasswordFormJs",
        timeoffJs: "assets/js/timeoffJs",
        timeoffListJs: "assets/js/timeoffListJs",
        employeeHistoryListJs: "assets/js/employeeHistoryListJs",

        // webNotification: "assets/js/webNotification",

        // 'main': 'assets/backend/js/main',
        sweetAlert2:
            "https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min",
    },
});

// var datatableCss = '//cdn.datatables.net/1.10.15/css/jquery.dataTables.css';
var sweetalertCss =
    assets +
    "https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css";
    
// var dateTimePickerCss = '/plugins/bootstrap/css/bootstrap-datetimepicker.min.css';

loadCss(sweetalertCss, sweetalertCss);
requirejs();
// requirejs(["webNotification"]);

if ($("#login").length > 0) {
    requirejs(["login"]);
}

if ($("#departmentJs").length > 0) {
    requirejs(["departmentJs"]);
}

if ($("#roleJs").length > 0) {
    requirejs(["roleJs"]);
}

if ($("#registerJs").length > 0) {
    requirejs(["registerJs"]);
}
if ($("#attendanceJs").length > 0) {
    requirejs(["attendanceJs"]);
}
if ($("#locationJs").length > 0) {
    requirejs(["locationJs"]);
}
if ($("#employeeJs").length > 0) {
    requirejs(["employeeJs"]);
}

if ($("#loginJs").length > 0) {
    requirejs(["loginJs"]);
}

if ($("#attendanceOutsideJs").length > 0) {
    requirejs(["attendanceOutsideJs"]);
}

if ($("#attendanceLogJs").length > 0) {
    requirejs(["attendanceLogJs"]);
}

if ($("#scheduleJs").length > 0) {
    requirejs(["scheduleJs"]);
}

if ($("#resetPasswordFormJs").length > 0) {
    requirejs(["resetPasswordFormJs"]);
}

if ($("#timeoffJs").length > 0) {
    requirejs(["timeoffJs"]);
}

if ($("#timeoffListJs").length > 0) {
    requirejs(["timeoffListJs"]);
}

if ($("#employeeHistoryListJs").length > 0) {
    requirejs(["employeeHistoryListJs"]);
}

// Global function
function ajax(ajaxFunction, params, async = true) {
    var path = "/ajaxController/" + ajaxFunction;
    return $.ajax({
        type: "POST",
        data: {
            func: ajaxFunction,
            param: params,
        },
        url: path,
        success: function (data) {},
        async: async,
    });
}

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

function loadCss(id, path) {
    if (!document.getElementById(id)) {
        var head = document.getElementsByTagName("head")[0];
        var link = document.createElement("link");
        link.id = id;
        link.rel = "stylesheet";
        link.type = "text/css";
        link.href = path;
        link.media = "all";
        head.appendChild(link);
    }
}
