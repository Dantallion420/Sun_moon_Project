<?php
session_start();
require_once("pdo.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    return;
}

// Replace with your ipgeolocation.io API key
$apiKey = '925c4ca95b824cf1b5875605223c784f';

// Default latitude and longitude (New York)
$latitude = isset($_GET['lat']) ? $_GET['lat'] : '40.7128';
$longitude = isset($_GET['long']) ? $_GET['long'] : '-74.0060';

// Get current date
$date = date('Y-m-d');

// Construct the API endpoint
$url = "https://api.ipgeolocation.io/astronomy?apiKey={$apiKey}&date={$date}&lat={$latitude}&long={$longitude}";

// Fetch data from API
$response = file_get_contents($url);

// Check if API request was successful
if ($response === false) {
    // Handle error if API request fails
    $responseData = [
        'error' => 'Failed to fetch data from API'
    ];
} else {
    // Decode JSON response
    $data = json_decode($response, true);
    

    // Prepare response data with all available fields
    $responseData = [
        'sunrise' => isset($data['sunrise']) ? $data['sunrise'] : '--',
        'sunset' => isset($data['sunset']) ? $data['sunset'] : '--',
        'solar_noon' => isset($data['solar_noon']) ? $data['solar_noon'] : '--',
        'day_length' => isset($data['day_length']) ? $data['day_length'] : '--',
        'sun_altitude' => isset($data['sun_altitude']) ? $data['sun_altitude'] : '--',
        'sun_distance' => isset($data['sun_distance']) ? $data['sun_distance'] : '--',
        'sun_azimuth' => isset($data['sun_azimuth']) ? $data['sun_azimuth'] : '--',
        'moonrise' => isset($data['moonrise']) ? $data['moonrise'] : '--',
        'moonset' => isset($data['moonset']) ? $data['moonset'] : '--',
        'moon_altitude' => isset($data['moon_altitude']) ? $data['moon_altitude'] : '--',
        'moon_distance' => isset($data['moon_distance']) ? $data['moon_distance'] : '--',
        'moon_azimuth' => isset($data['moon_azimuth']) ? $data['moon_azimuth'] : '--',
        'moon_parallactic_angle' => isset($data['moon_parallactic_angle']) ? $data['moon_parallactic_angle'] : '--'
        // Add more fields as needed from the API response
    ];
}

// Output as JSON
header('Content-Type: application/json');
echo json_encode($responseData);
?>