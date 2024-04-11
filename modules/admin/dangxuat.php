<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Nếu không tồn tại loginAdmin thì quay về trang login
if (!checkLogin('login_admin')) {
    header('location: ?module=admin&action=dangnhap');
}

unset($_SESSION['login_admin']);
unset($_SESSION['admin_name']);
header('location: ?module=admin&action=dangnhap');
