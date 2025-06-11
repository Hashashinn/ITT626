function trackUserLocation() {

    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        function(position) {
          var latitude = position.coords.latitude;
          var longitude = position.coords.longitude;
           
          // Send latitude and longitude to the server via AJAX
        //   $.ajax({
        //     url: '/update-user-location',
        //     method: 'POST',
        //     data: {
        //       latitude: latitude,
        //       longitude: longitude
        //     },
        //     success: function(response) {
        //       console.log('User location updated successfully');
        //     },
        //     error: function(xhr, status, error) {
        //       console.log('Error updating user location:', error);
        //     }
        //   });
        },
        function(error) {
          console.log('Error getting user location:', error);
        }
      );
    } else {
      console.log('Geolocation is not supported by this browser.');
    }
  }
  
  // Attach click event to the "Track Location" button
  $(document).on('click', '#trackLocationBtn', function() {
    trackUserLocation();
  });
  