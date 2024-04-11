<?php
ob_start(); // Bắt đầu bộ đệm đầu ra
session_start(); //Khởi tạo session
include_once('../MovieBooking_DAPHP/config.php');
include_once('../MovieBooking_DAPHP/includes/connect.php');
include_once('../MovieBooking_DAPHP/includes/function.php');

$module = _MODULE;
$action = _ACTION;

//Điều hướng module 

//Kiểm tra giá trị module có tồn tại hay không
if (!empty($_GET['module'])) {
    // Kiểm tra xem module có phải dạng chuỗi hay không
    if (is_string($_GET['module'])) {
        $module = trim($_GET['module']);
    }
}

//Kiểm tra giá trị action có tồn tại hay không
if (!empty($_GET['action'])) {
    // Kiểm tra xem action có phải dạng chuỗi hay không
    if (is_string($_GET['action'])) {
        $action = trim($_GET['action']);
    }
}

//Đường dẫn đến file muốn chạy

$path = 'modules/' . $module . '/' . $action . '.php';

//Dùng hàm file_exists kiểm tra file $path có tồn tại hay không
if (file_exists($path)) {
    require_once($path);
} else {
    require_once 'modules/error/404.php';
}
