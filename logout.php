<?php

@include 'config.php';

session_start();
session_unset();
session_destroy();
/*$googleClient->revokeToken();*/
header('location:login.php');

?>