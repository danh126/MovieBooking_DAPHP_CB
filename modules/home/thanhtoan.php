<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Thay đổi tên trang tương ứng
$data = [
    'ten_trang' => 'Thanh toán ! Movie Booking'
];
layout('header', $data);
//$_POST từ trang xử lý đặt vé
if (!empty($_SERVER["REQUEST_METHOD"] == "POST")) {

    //Lấy thông tin đặt vé
    $tenKH = $_POST['name_KH'];
    $email_KH = $_POST['email_KH'];
    $phone_KH = $_POST['phone_KH'];
    $note_KH = $_POST['note_KH'];
    $ngayChieu = $_SESSION['ngay_da_chon'];
    $phim = $_SESSION['phim_da_chon'];
    $phong = $_SESSION['phong_da_chon'];
    $thoiGianBd = $_SESSION['gio_bd'];
    $thoiGianKt = $_SESSION['gio_kt'];
    $gheDaChon = $_SESSION['ghe_da_chon'];
    $tong_tien = $_SESSION['tong_tien'];
    $checked_ghe = $_SESSION['checked_ghe'];

    //Checked ghế khách hàng đã đặt
    $checked = array();
    $checked = count($checked_ghe);
    for ($i = 0; $i < $checked; $i++) {
        $update_check = $_SESSION['checked_ghe'][$i];

        $sql = "UPDATE ghe SET trang_thai = 1 WHERE so_ghe = '" . $update_check . "'";
        $ket_qua = mysqli_query($configDTB, $sql);
    }
    // die();
    //Tạo id tự động
    $sql_id = "SELECT MAX(id_dat_ve) AS id_max FROM thong_tin_dat_ve";
    $result_id = mysqli_query($configDTB, $sql_id);
    $rows = mysqli_fetch_array($result_id);
    $id_max = $rows['id_max'] + 1;

    //Insert dữ liệu vào bảng thông tin đặt vé
    $sql = "INSERT INTO thong_tin_dat_ve (id_dat_ve, ten_KH, email_KH, dien_thoai_KH, ghi_chu, ngay_chieu, ten_phim, ten_phong, thoi_gian_bd, thoi_gian_kt, ghe_da_chon, tong_tien) 
            VALUES (" . $id_max . ",'" . $tenKH . "', '" . $email_KH . "', " . $phone_KH . ", '" . $note_KH . "','" . $ngayChieu . "','" . $phim . "','" . $phong . "','" . $thoiGianBd . "','" . $thoiGianKt . "','" . $gheDaChon . "'," . $tong_tien . " );";
    $ket_qua = mysqli_query($configDTB, $sql);
    if ($ket_qua) {
        // echo "<p class='thanh_toan'>Đặt vé thành công! </p>" . '<br>';
        // echo "<span class='thanh_toan'>Tính năng thanh toán online đang được cập nhật!</span>";
        //Sau khi insert xong xóa session đặt vé
        unset($_SESSION['ngay_da_chon']);
        unset($_SESSION['phim_da_chon']);
        unset($_SESSION['phong_da_chon']);
        unset($_SESSION['gio_bd']);
        unset($_SESSION['gio_kt']);
        unset($_SESSION['ghe_da_chon']);
        unset($_SESSION['tong_tien']);
        unset($_SESSION['checked_ghe']);

        $_SESSION['dat_ve_thanh_cong'] = "Đặt vé thành công!";
        header('location: ?module=home&action=datve');
    } else {
        echo "Đặt vé không thành công, vui lòng thử lại sau!";
    }
} else {
    header('location: ?module=home&action=datve');
}
layout('footer');
