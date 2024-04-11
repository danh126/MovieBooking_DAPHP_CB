<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}
//Gọi hàm kiểm tra đăng nhập
if (checkLogin('login_user')) {
    header('location: ?module=home&action=trangchu');
}

unset($_SESSION['login_user']);
unset($_SESSION['username']);
unset($_SESSION['id_user']);
header('location: ?module=home&action=trangchu');
