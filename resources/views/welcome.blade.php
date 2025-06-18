@include('layouts.welcome')

<div class="flex-center position-ref full-height">
    <div class="top-right links color-white">
        <a style="color: #060606" href="/auth/loginView">Login</a>
    </div>

    <div class="content">
        <div class="title m-b-md text-center">
            <h3>Welcome to Attendance Management System</h3>
        </div>
    </div>
</div>
<script>
    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;

            // Create an object to store the coordinates
            var locationData = {
                latitude: latitude,
                longitude: longitude
            };

            // Convert the object to a JSON string
            var locationDataJSON = JSON.stringify(locationData);

            // Save the location data in local storage
            localStorage.setItem("userLocation", locationDataJSON);

            // You can retrieve this data later using localStorage.getItem("userLocation")
        }, function(error) {
            // Handle any errors as needed
            console.error("Error getting location: " + error.message);
        });
    } else {
        console.error("Geolocation is not available in this browser.");
    }
</script>
