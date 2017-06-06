<?php
/**
 * Created by PhpStorm.
 * User: aline
 * Date: 2016-11-05
 * Time: 6:05 PM
 */

$servername = "localhost";
$username = "f6team16_admin";
$password = "georgebrown";
$dbname = "f6team16_iseaterdb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
};
