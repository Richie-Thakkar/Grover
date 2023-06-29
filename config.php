<?php
require_once 'vendor/autoload.php';

$db_name = "mysql:host=localhost;dbname=shop_db";
$username = "root";
$password = "Inquistivepizza@55";

$conn = new PDO($db_name, $username, $password);
$client_id = "571802560195-gs056k8o77p1vgohk67l66son6dsiheh.apps.googleusercontent.com";
$client_secret="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";
$redirect_url="http://localhost/gs/home.php";
$clientID = $client_id;
$clientSecret = $client_secret;
$redirectUri = $redirect_url;

// create Client Request to access Google API
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

?>