<?php
header('Content-Type: application/json');

$serverName = "localhost";
$userName = "root";
$password = "";
$conn = mysqli_connect($serverName, $userName, $password);

$createDatabase = "CREATE DATABASE IF NOT EXISTS prototype2";
mysqli_query($conn, $createDatabase);
mysqli_select_db($conn, 'prototype2');

$createTable = "CREATE TABLE IF NOT EXISTS weather(
    temperature FLOAT NOT NULL,
    wind_direction FLOAT NOT NULL,
    `condition` VARCHAR (50) NOT NULL,
    humidity FLOAT NOT NULL,
    wind_speed FLOAT NOT NULL,
    pressure FLOAT NOT NULL,
    city VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);";
mysqli_query($conn, $createTable);


if(isset($_GET['q'])){
    $cityName = $_GET['q'];
    // echo $cityName;
} else {
    $cityName = 'Nottingham';
}

$selectALLData = "SELECT * FROM weather WHERE city = '$cityName' ORDER BY created_at DESC LIMIT 1";
$result = mysqli_query($conn, $selectALLData);

$currentTime = time();
$rows = [];

if($row = mysqli_fetch_assoc($result)) {
    $dbTime = strtotime($row['created_at']);
    $timeDiff = $currentTime - $dbTime;

    if($timeDiff < 7200) {
        $rows[] = $row;
    }
}

if (empty($rows)) {
    $url = "Your API key here";
    $response = file_get_contents($url);
    $data = json_decode($response, true);

    $humidity = $data['main']['humidity'];
    $wind_speed = $data['wind']['speed'];
    $wind_direction = $data['wind']['deg'];
    $pressure = $data['main']['pressure'];
    $temperature = $data['main']['temp'];
    $condition = $data['weather'][0]['main'];
  

    $insertData = "INSERT INTO weather (city, humidity, wind_speed, wind_direction, pressure, temperature, `condition`, created_at)
        VALUES ('$cityName', '$humidity', '$wind_speed', '$wind_direction', '$pressure', '$temperature', '$condition', NOW())";
    mysqli_query($conn, $insertData);

    $result = mysqli_query($conn, $selectALLData);
    while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
    }
}


echo json_encode($rows);

?>
