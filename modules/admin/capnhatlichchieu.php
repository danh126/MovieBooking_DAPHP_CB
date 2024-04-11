<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Nếu không tồn tại loginAdmin thì quay về trang login
if (!checkLogin('login_admin')) {
    header('location: ?module=admin&action=dangnhap');
}

if (!empty($_SERVER["REQUEST_METHOD"] == "POST")) {
    $id_ca_chieu = $_POST['id_cachieu'];
    $id_phim = $_POST['id_phim'];
    $ten_phim = $_POST['ten_phim'];
    $phong_chieu = $_POST['id_phong'];
    $ngay_Chieu = $_POST['ngay_chieu'];
    $gio_bd = $_POST['gio_bd'];
    $gio_kt = $_POST['gio_kt'];

    //Kiểm tra trùng lịch chiếu khi cùng ngày chiếu, mã phòng và cùng giờ bắt đầu 
    $sql = "SELECT * FROM ca_chieu WHERE id_phim = $id_phim AND ngay_chieu = '$ngay_Chieu' AND gio_bd = '$gio_bd' AND gio_kt = '$gio_kt' AND id_phong = '$phong_chieu' ";
    $kt_trung = mysqli_query($configDTB, $sql);
    $smt = mysqli_num_rows($kt_trung);
    if ($smt > 0) {
        $_SESSION['id_cachieu'] = $_POST['id_cachieu'];
        $_SESSION['id_phim'] = $_POST['id_phim'];
        // $_SESSION['id_phim'] = $_POST['ten_phim'];
        $_SESSION['id_phong'] = $_POST['id_phong'];
        $_SESSION['ngay_chieu'] = $_POST['ngay_chieu'];
        $_SESSION['gio_bd'] = $_POST['gio_bd'];
        $_SESSION['gio_kt'] = $_POST['gio_kt'];

        $_SESSION['error_lichchieu_edit'] = "Lịch chiếu đã tồn tại!";

        header('location: ?module=admin&action=xulyeditlichchieu');
    } else {

        $sql = "UPDATE ca_chieu SET ngay_chieu = '$ngay_Chieu', gio_bd = '$gio_bd', gio_kt = '$gio_kt' WHERE id_cachieu = $id_ca_chieu ";
        $ket_qua = mysqli_query($configDTB, $sql);
        if ($ket_qua) {

            $_SESSION['update_thanh_cong_lich_chieu'] = "Cập nhật lịch chiếu cho phim có id ca chiếu là:<span style='color:blue;'> $id_ca_chieu </span> thành công!";
            header('location: ?module=admin&action=editlichchieu');
        }
    }
} else {
    header('location: ?module=admin&action=editlichchieu');
}
mysqli_close($configDTB);
