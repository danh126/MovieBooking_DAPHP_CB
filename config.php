<?php
//Thiết lập host

define('_WEB_HOST', 'http://' . $_SERVER['HTTP_HOST'] . '/MovieBooking_DAPHP');
define('_WEB_HOST_TEMPLATES', _WEB_HOST . '/templates');

// tạo hằng số dùng const

const _MODULE = 'home';
const _ACTION = 'trangchu';

const _CODE = true; //Kiểm soát truy cập của người dùng có hợp lệ không