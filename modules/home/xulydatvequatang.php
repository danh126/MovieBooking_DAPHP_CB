<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}
//Thay đổi tên trang tương ứng
$data = [
    'ten_trang' => 'Đặt vé quà tặng | Movie Booking'
];
layout('header', $data);

if (!empty($_SERVER["REQUEST_METHOD"] == "POST")) { //kiểm tra biến toàn cục

    $name = $_POST['name'];
    $email = $_POST['email'];
    $sdt = $_POST['sdt'];
    $ghichu = $_POST['ghichu'];
    $tenve = $_POST['tensp'];
    $giaban = $_POST['giaban'];
    $soluong = $_POST['soluong'];
    $tongtien = $_POST['tongtien'];

    //tạo id tự động cho ve
    $ve_qt_id = "SELECT max(id_vequatang) AS id_vequatang_max FROM ve_qua_tang";
    $ket_qua = mysqli_query($configDTB, $ve_qt_id);
    $rows = mysqli_fetch_array($ket_qua);
    $id_veqt = $rows['id_vequatang_max'] + 1;


    $sql = "INSERT INTO ve_qua_tang (id_vequatang,Ten_kh,email,sdt,ghichu,ten_ve,giaban,soluong,tongtien)
   VALUES( '" . $id_veqt . "','" . $name . "','" . $email . "','" . $sdt . "','" . $ghichu . "','" . $tenve . "','" . $giaban . "','" . $soluong . "','" . $tongtien . "')";

    $ketqua = mysqli_query($configDTB, $sql);
    if ($ketqua) {
        echo "<div class = 'thong_bao'>Đặt vé quà tặng thành công! </div>";
    } else {
        echo "Thêm thất bại";
    }
} else {
    header('location: ?module=home&action=shopquatang');
}
layout('footer');
