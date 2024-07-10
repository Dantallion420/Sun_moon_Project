// Function to fetch sun and moon data using AJAX
function fetchSunAndMoonData(latitude, longitude) {
    $.ajax({
        url: 'fetch_moon_data.php', // Ensure this URL is correct and exists
        type: 'GET', // Use 'GET' method as appropriate
        dataType: 'json',
        data: {
            lat: latitude,
            long: longitude
        },
        success: function(data) {
            updateSunAndMoonInfo(data); // Assuming this function updates sun and moon information

            // Update displayed city
            // Assuming lat and long are passed to this function correctly
            $('#city-display').text('City: (' + latitude + ', ' + longitude + ')');
        },
        error: function(xhr, status, error) {
            console.error('Error fetching sun and moon data:', error);
        }
    });
}

// Function to fetch astronomy data using AJAX
function fetchAstronomyData(city) {
    $.ajax({
        url: 'fetch_astronomy_data.php', // Check if this is correct and exists
        type: 'POST', // Adjust method if needed
        dataType: 'json',
        data: { city: city },
        success: function(data) {
            updateSunAndMoonInfo(data); // Extracted common code to a function
            // Update displayed city
            $('#city-display').text('City: ' + city);
        },
        
        error: function(xhr, status, error) {
            console.error('Error fetching astronomy data:', error);
        }
    });
}

// Function to update Sun and Moon information on the page
function updateSunAndMoonInfo(data) {
    // Update Sun Information
    $('#sunrise').text('Sunrise: ' + (data.sunrise !== '-' ? data.sunrise : 'Not available'));
    $('#sunset').text('Sunset: ' + (data.sunset !== '-' ? data.sunset : 'Not available'));
    $('#solar_noon').text('Solar Noon: ' + (data.solar_noon !== '-' ? data.solar_noon : 'Not available'));
    $('#day_length').text('Day Length: ' + (data.day_length !== '-' ? data.day_length : 'Not available'));
    $('#sun_altitude').text('Sun Altitude: ' + (data.sun_altitude !== '-' ? data.sun_altitude : 'Not available'));
    $('#sun_distance').text('Sun Distance: ' + (data.sun_distance !== '-' ? data.sun_distance : 'Not available'));
    $('#sun_azimuth').text('Sun Azimuth: ' + (data.sun_azimuth !== '-' ? data.sun_azimuth : 'Not available'));

    // Update Moon Information
    $('#moonrise').text('Moonrise: ' + (data.moonrise !== '-' ? data.moonrise : 'Not available'));
    $('#moonset').text('Moonset: ' + (data.moonset !== '-' ? data.moonset : 'Not available'));
    $('#moon_altitude').text('Moon Altitude: ' + (data.moon_altitude !== '-' ? data.moon_altitude : 'Not available'));
    $('#moon_distance').text('Moon Distance: ' + (data.moon_distance !== '-' ? data.moon_distance : 'Not available'));
    $('#moon_azimuth').text('Moon Azimuth: ' + (data.moon_azimuth !== '-' ? data.moon_azimuth : 'Not available'));
    $('#moon_parallactic_angle').text('Moon Parallactic Angle: ' + (data.moon_parallactic_angle !== '-' ? data.moon_parallactic_angle : 'Not available'));
}

// Event listener for city form submission
$('#city-form').on('submit', function(event) {
    event.preventDefault();
    var city = $('#city').val().trim();
    if (city.length > 0) {
        fetchAstronomyData(city);// Update displayed city
        $('#city-display').text('City: ' + city);
    } else {
        console.error('City name is empty or invalid.');
    }
});
function findCityName(geoData) {
    for (var i = 0; i < geoData.results.length; i++) {
        for (var j = 0; j < geoData.results[i].address_components.length; j++) {
            var component = geoData.results[i].address_components[j];
            if (component.types.includes('locality') || component.types.includes('administrative_area_level_1')) {
                return component.long_name;
            }
        }
    }
    return 'Unknown';
}

// Function to get user's geolocation and fetch sun and moon data
function getUserLocationAndFetchData() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;
            console.log('Latitude:', latitude, 'Longitude:', longitude);

            // Call function to fetch sun and moon data with latitude and longitude
            fetchSunAndMoonData(latitude, longitude);
        }, function(error) {
            console.error('Error getting user location:', error);
        });
    } else {
        console.error('Geolocation is not supported by this browser.');
    }
}

// Example of calling getUserLocationAndFetchData when the page loads
$(document).ready(function() {
    getUserLocationAndFetchData();
});