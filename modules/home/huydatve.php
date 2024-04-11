<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

unset($_SESSION['ngay_da_chon']);
unset($_SESSION['phim_da_chon']);
unset($_SESSION['phong_da_chon']);
unset($_SESSION['gio_bd']);
unset($_SESSION['gio_kt']);
unset($_SESSION['ghe_da_chon']);
unset($_SESSION['tong_tien']);
header('location: ?module=home&action=trangchu');
