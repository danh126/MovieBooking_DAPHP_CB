<?php

function layout($layoutName = 'header', $data = []) //$data dạng mảng để chuyển tên theo tab
{
    if (file_exists('templates/layout/' . $layoutName . '.php')) {
        require_once('templates/layout/' . $layoutName . '.php');
    }
}

// Trạng thái vé
function veDaDat($status)
{
    if ($status) {
        echo "disabled";
    } else {
        echo '';
    }
}

// Checked đặt vé và phòng
function checked($row, $key, $check)
{
    if (isset($_GET[$check])) {
        if ($row[$key] == $_GET[$check]) {
            echo 'checked=' . $row[$key];
        }
    }
}

// Kiểm tra login user
function checkLogin($key)
{
    $checkLogin = false;
    if (isset($_SESSION[$key])) {
        $checkLogin = true;
        return $checkLogin;
    }
}
