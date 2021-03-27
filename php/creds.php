<?php

$host = 'localhost';
$db = 'shopping';
$user = 'bibi';
$pass = 'coucou';

$myConnection = mysqli_connect($host, $user, $pass, $db);

if (mysqli_connect_error()) {
    die("Connection to database failed.<br>");
}
