<?php
$serverName = 'localhost';
$userName = 'root';
$pass = '';
$databaseName = 'moviebooking';

//Kết nối database

$configDTB = mysqli_connect($serverName, $userName, $pass, $databaseName);
// if ($configDTB) {
//     echo 'Kết nối thành công';
// } else {
//     echo 'Kết nối không thành công';
// }
