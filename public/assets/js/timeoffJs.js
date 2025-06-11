$(document).ready(function () {
    $("#punchOutBtn").prop("disabled", false);

    $("#punchOutBtn").click(function (e) {
        var id = $(this).data("id");
        $("#addForm").validate({
            // Specify validation rules
            rules: {},

            messages: {},
            submitHandler: function (form) {
                requirejs(["sweetAlert2"], function (swal) {
                    var data = new FormData(document.getElementById("addForm"));
                    $("#punchOutBtn")
                        .prop("disabled", true)
                        .html('<i class="bi bi-arrow-repeat"></i> Loading...');
                    $.ajax({
                        type: "POST",
                        url: "/updateTimeoff/" + id,
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
                            $("#punchOutBtn")
                                .prop("disabled", false)
                                .html("End Time Off");
                        });
                });
            },
        });
    });

    $("#saveBtn").click(function (e) {
        $("#addForm").validate({
            rules: {
                remarks: {
                    required: true,
                },
            },

            messages: {
                remarks: {
                    required: "Please enter remarks.",
                },
            },
            submitHandler: function (form) {
                requirejs(["sweetAlert2"], function (swal) {
                    var data = new FormData(document.getElementById("addForm"));
                    $("#saveBtn")
                        .prop("disabled", true)
                        .html('<i class="bi bi-arrow-repeat"></i> Loading...');

                    // console.log(data);
                    $.ajax({
                        type: "POST",
                        url: "/storeTimeoff",
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
                            $("#saveBtn")
                                .prop("disabled", false)
                                .html("Start Time Off");
                        });
                });
            },
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
        $("#attachment").prop("disabled", false);

        // Check if the user is within the specified radius
        if (distance <= radius) {
            $("#lampiran").hide();
            $("#attachment").prop("disabled", true);
        } else {
        }
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
});
