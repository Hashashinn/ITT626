$(document).ready(function () {
    var table = $("#attendanceTable").dataTable({
        responsive: true,
    });

    $(document).on("click", "#addBtn", function () {
        $("#addModal").modal("show");
    });

    $(document).on("click", "#editBtn", function () {
        var id = $(this).data("id");

        var dataRaw = getAttendanceById(id);

        dataRaw.done(function (data) {
            console.log(data);
            $("#attendanceName").val(data.name);
            $("#dId").val(data.id);
        });

        $("#editModal").modal("show");
    });

    $("#punchOutBtn").prop("disabled", false);

    function getTodayAttendanceById() {
        return $.ajax({
            url: "/attendance/getTodayAttendanceById",
        });
    }

    var dataRaw = getTodayAttendanceById();

    dataRaw.done(function (data) {
        // Get the current time
        var currentTime = new Date();

        // Set the target time (8 hours from now)
        var targetTime = new Date(data.created_at);
        targetTime.setHours(targetTime.getHours() + 8);

        // Calculate the time difference in milliseconds
        var timeDifference = targetTime - currentTime;

        // Convert milliseconds to hours
        var timeDifferenceHours = timeDifference / (1000 * 60 * 60);

        // Check if the time difference is less than 8 hours
        if (timeDifferenceHours < 8) {
            // $("#punchOutBtn").prop("disabled", true);
        }
    });
    // $(document).on("submit", "#updateBtn", function () {
    $("#punchOutBtn").click(function (e) {
        var id = $(this).data("id");
        // $("#editForm").validate({
        //     // Specify validation rules
        //     rules: {},

        //     messages: {},
        //     submitHandler: function (form) {
        // alert("ss");
        requirejs(["sweetAlert2"], function (swal) {
            var data = new FormData(document.getElementById("addForm"));
            $("#updateBtn")
                .prop("disabled", true)
                .html('<i class="bi bi-arrow-repeat"></i> Loading...');
            // console.log(data);
            $.ajax({
                type: "POST",
                url: "/attendance/punchOut/" + id,
                data: data,
                processData: false,
                contentType: false,
            })
                .done(function (data) {
                    swal(
                        {
                            title: data.title,
                            text: data.msg,
                            type: data.type,
                            confirmButtonColor: "#3085d6",
                            confirmButtonText: "OK",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                        },
                        function () {
                            if (data.type == "error") {
                            } else {
                                location.reload();
                                // window.location.href = "/dashboard";
                            }
                        }
                    );
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
                url: "/storeActivityUser",
                data: data,
                processData: false,
                contentType: false,
            })
                .done(function (data) {
                    swal(
                        {
                            title: data.title,
                            text: data.msg,
                            type: data.type,
                            confirmButtonColor: "#3085d6",
                            confirmButtonText: "OK",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                        },
                        function () {
                            if (data.type == "error") {
                            } else {
                                //User after punch out , user can not pinch in outside
                                if (data.data && data.data.attendance_timeout) {
                                    $("#saveBtn").prop("disabled", true);
                                    $("#lampiran").show();
                                    $("#attachment").prop("disabled", false);
                                } else {
                                    // User has not punch out, enable  capture outside
                                    $("#saveBtn").prop("disabled", false);
                                    $("#lampiran").hide();
                                    $("#attachment").prop("disabled", true);
                                }

                                location.reload();
                                // window.location.href = "/dashboard";
                            }
                        }
                    );
                })
                .always(function () {
                    // Re-enable the button and hide the loading animation
                    $("#saveBtn").prop("disabled", false).html("Punch In");
                });
        });
        // },
        // });
    });

    $(document).on("click", "#deleteBtn", function () {
        id = $(this).data("id");
        requirejs(["sweetAlert2"], function (swal) {
            swal(
                {
                    title: "Are you sure?",
                    type: "error",
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes!",
                    showCancelButton: true,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                },
                function () {
                    $.ajax({
                        type: "GET",
                        url: "/attendance/delete/" + id,
                    }).done(function (data) {
                        swal(
                            {
                                title: data.title,
                                text: data.msg,
                                type: data.type,
                                confirmButtonColor: "#3085d6",
                                confirmButtonText: "OK",
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                            },
                            function () {
                                if (data.type == "error") {
                                } else {
                                    location.reload();
                                }
                            }
                        );
                    });
                }
            );
        });
    });

    var x = document.getElementById("demo");

    getLocation();

    function getLocation() {
        if (navigator.geolocation) {
            var timeout = 2000; // 3 seconds

            var timeoutId = setTimeout(function () {
                // If it takes longer than 3 seconds, get location from local storage
                var locationDataJSON = localStorage.getItem("userLocation");
                var locationData = JSON.parse(locationDataJSON);

                if (locationData) {
                    var latitude = locationData.latitude;
                    var longitude = locationData.longitude;
                    console.log("previous");
                } else {
                    console.log("No location data found in local storage.");
                }
            }, timeout);

            navigator.geolocation.getCurrentPosition(
                function (position) {
                    // Clear the timeout because we got the location within 3 seconds
                    clearTimeout(timeoutId);

                    var latitude = position.coords.latitude;
                    var longitude = position.coords.longitude;

                    console.log("current");

                    // Store the current location in local storage
                    var locationData = {
                        latitude: latitude,
                        longitude: longitude,
                    };
                    localStorage.setItem(
                        "userLocation",
                        JSON.stringify(locationData)
                    );
                },
                function (error) {
                    // Handle any errors as needed
                    console.error("Error getting location: " + error.message);
                }
            );
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            console.error("Geolocation is not supported by this browser.");
        }
    }

    function showPosition(position) {
        //Location for user
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;
        $("#lat_user").val(latitude);
        $("#lng_user").val(longitude);
        // var latitude = 3.1321197658658404;
        // var longitude = 101.63130953868286;
        // 3.1321197658658404, 101.63130953868286
        x.innerHTML = latitude + "," + longitude;

        // Define the target coordinates (center of the radius)
        //location for office
        var targetLatitude = $("#lat").val();
        var targetLongitude = $("#lng").val();

        // Define the radius in meters
        var radius = 50;

        // Calculate the distance between the user's location and the target coordinates
        var distance = calculateDistance(
            latitude,
            longitude,
            targetLatitude,
            targetLongitude
        );
        $("#lampiran").show();

        // function getPlaceName(latitude, longitude) {
        //     const apiKey = "AIzaSyBrpC--MgBerN_rppH__SKOIeXEPwo8El8"; // Replace with your Google Maps API key

        //     // Create the geocoding request URL
        //     const geocodingUrl = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${latitude},${longitude}&key=${apiKey}`;

        //     // Send the geocoding request
        //     $.get(geocodingUrl, function (data) {
        //         if (data.results.length > 0) {
        //             const placeName = data.results[0].formatted_address;
        //             console.log(placeName);
        //             // Do something with the place name
        //         } else {
        //             console.log("No results found");
        //         }
        //     });
        // }

        // getPlaceName(latitude, longitude);
    }

    // Helper function to calculate the distance between two sets of coordinates using the Haversine formula
    function calculateDistance(lat1, lon1, lat2, lon2) {
        var R = 6371; // Radius of the earth in kilometers
        var dLat = deg2rad(lat2 - lat1);
        var dLon = deg2rad(lon2 - lon1);
        var a =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(deg2rad(lat1)) *
                Math.cos(deg2rad(lat2)) *
                Math.sin(dLon / 2) *
                Math.sin(dLon / 2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        var distance = R * c * 1000; // Distance in meters
        return distance;
    }

    // Helper function to convert degrees to radians
    function deg2rad(deg) {
        return deg * (Math.PI / 180);
    }

    var videoStream;
    var videoElement = document.getElementById("camera-view");
    var canvasElement = document.getElementById("canvas");
    var fileInput = document.getElementById("image-input");

    function captureImage() {
        event.preventDefault();
        if (videoStream) {
            var context = canvasElement.getContext("2d");
            canvasElement.width = videoElement.videoWidth;
            canvasElement.height = videoElement.videoHeight;
            context.drawImage(
                videoElement,
                0,
                0,
                canvasElement.width,
                canvasElement.height
            );

            canvasElement.toBlob(function (blob) {
                var file = new File([blob], "captured_image.png", {
                    type: "image/png",
                });
                var fileInputList = new DataTransfer();
                fileInputList.items.add(file);
                fileInput.files = fileInputList.files;
            }, "image/png");
        }
    }

    function openCamera() {
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices
                .getUserMedia({
                    video: true,
                })
                .then(function (stream) {
                    videoStream = stream;
                    videoElement.srcObject = stream;
                    videoElement.play();
                })
                .catch(function (error) {
                    console.log("Unable to access camera: " + error);
                });
        }
    }

    $("#captureImage").click(function () {
        captureImage();
    });
    $("#openCamera").click(function () {
        event.preventDefault();
        $("#camera-view").show();
        openCamera();
    });
});
