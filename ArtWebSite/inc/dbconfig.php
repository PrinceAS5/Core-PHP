<?php

$host = "localhost";
$username = "root";
$password = "";
$db = "artexample";

$connect = mysqli_connect($host, $username, $password, $db);

if(!$connect):
    die("Connection Failed: " . mysqli_connect_error());
endif;
