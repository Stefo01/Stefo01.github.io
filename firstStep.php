<?php
session_start();

// Rate limiting settings
$limit = 2;
$timeFrame = 60;

// Check for blocked IPs
$blocked_ips = ['192.168.1.1', '203.0.113.5'];
if (in_array($_SERVER['REMOTE_ADDR'], $blocked_ips)) {
    header('HTTP/1.1 403 Forbidden');
    echo "Access denied.";
    exit;
}

// Rate limiting logic
if (!isset($_SESSION['request_count'])) {
    $_SESSION['request_count'] = 0;
    $_SESSION['first_request_time'] = time();
}

if (time() - $_SESSION['first_request_time'] < $timeFrame) {
    $_SESSION['request_count']++;
} else {
    $_SESSION['request_count'] = 1;
    $_SESSION['first_request_time'] = time();
}

if ($_SESSION['request_count'] > $limit) {
    header('HTTP/1.1 429 Too Many Requests');
    echo "You have exceeded the request limit. Please try again later.";
    exit;
}

// Logging the request
file_put_contents('request_log.txt', date('Y-m-d H:i:s') . " - " . $_SERVER['REMOTE_ADDR'] . PHP_EOL, FILE_APPEND);

$EmailFirst = $_POST["mail"];
echo $EmailFirst;
// TODO invia mail

function generateUniqueRandomNumbers($count, $min, $max) {
    if ($count > ($max - $min + 1)) {
        throw new Exception("Count exceeds the range of unique numbers.");
    }

    $numbers = range($min, $max); // Create an array of numbers from min to max
    shuffle($numbers); // Shuffle the array to randomize
    return array_slice($numbers, 0, $count); // Return the first $count numbers
}

$randomNumbers = generateUniqueRandomNumbers(6, 0, 9);

echo "Unique Random Numbers: " . implode(", ", $randomNumbers);

// Your protected code goes here
echo "Request processed successfully.";

// TODO invia mail

