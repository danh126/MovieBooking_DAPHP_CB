<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Thay đổi tên trang tương ứng
$data = [
    'ten_trang' => 'Thông tin đặt vé ! Movie Booking'
];
layout('header', $data);

if (!empty($_SERVER["REQUEST_METHOD"] == "POST")) {
    if (isset($_POST['chon_ghe']) != '') {
        $_SESSION['ghe_da_chon'] = implode(' ', $_POST['chon_ghe']);
        $_SESSION['checked_ghe'] = $_POST['chon_ghe']; //Lưu biến session phục vụ cho việc checked ở trang thanh toán
?>
        <form action="?module=home&action=thanhtoan" method="post">
            <div class="xu_ly_dat_ve">
                <div class="thong_tin_ve">
                    <?php
                    $chonGhe =  $_POST['chon_ghe'];
                    $tongTien = 0;

                    foreach ($chonGhe as $maGhe) {
                        $sql = "SELECT gia FROM gia_ve WHERE loai_ghe = (SELECT loai_ghe FROM ghe WHERE so_ghe = ?)";
                        $ket_qua = mysqli_prepare($configDTB, $sql); //chuẩn bị truy vấn
                        mysqli_stmt_bind_param($ket_qua, "s", $maGhe); //Gắn kết tham số "s" kiểu chuỗi
                        mysqli_stmt_execute($ket_qua); //thực thi câu truy vấn với tham số được gắn kết
                        $GiaVeGhe = mysqli_stmt_get_result($ket_qua); //lấy kết quả của truy vấn đã thực hiện

                        $rowGiaVeGhe = mysqli_fetch_array($GiaVeGhe);

                        $tongTien += $rowGiaVeGhe['gia'];
                    }
                    $_SESSION['tong_tien'] = $tongTien;
                    $ngayChon = $_SESSION['ngay_da_chon'];
                    // Chuyển đổi thời gian sang timestamp
                    $timestamp = strtotime($ngayChon);
                    // Định dạng giờ và phút
                    $ngayChon_FM = date('d-m-Y', $timestamp);
                    ?>
                    <p>Chi tiết đặt vé&nbsp;<i class="fa-solid fa-ticket"></i></p>
                    <label for="">Ngày chiếu:</label>
                    <input type="text" value="<?php echo $ngayChon_FM; ?>" readonly>
                    <label for="">Phim:</label>
                    <input type="text" value="<?php echo $_SESSION['phim_da_chon']; ?>" readonly>
                    <label for="">Phòng chiếu:</label>
                    <input type="text" value="<?php echo $_SESSION['phong_da_chon']; ?>" readonly>
                    <label for="">Thời gian bắt đầu:</label>
                    <input type="text" value="<?php echo $_SESSION['gio_bd']; ?>" readonly>
                    <label for="">Thời gian kết thúc:</label>
                    <input type="text" value="<?php echo $_SESSION['gio_kt']; ?>" readonly>
                    <label for="">Ghế đã chọn: </label>
                    <input type="text" value="<?php echo $_SESSION['ghe_da_chon']; ?>" readonly>
                    <span class="so_luong_ve">Số lượng vé: <?php echo count($chonGhe) ?></span>
                    <span class="tong_tien">Tổng tiền: <span class="gia"><?php echo $tongTien ?> VNĐ</span></span>
                </div>
                <div class="thong_tin_KH">
                    <?php
                    if (checkLogin('login_user')) {
                        if (!empty($_SESSION['id_user'])) {
                            $id_user = $_SESSION['id_user'];
                            $sql = "SELECT ho_ten, email FROM login_user WHERE id_user = '$id_user'";
                            $ket_qua = mysqli_query($configDTB, $sql);
                            $row = mysqli_fetch_array($ket_qua);
                        }
                    }
                    ?>
                    <p>Thông tin khách hàng</p>
                    <label for="">Tên tài khoản:</label>
                    <input type="text" name="name_KH" value="<?php echo !empty($row['ho_ten']) ? $row['ho_ten'] : '' ?>" required readonly>
                    <label for="">Email:</label>
                    <input type="email" name="email_KH" value="<?php echo !empty($row['email']) ? $row['email'] : '' ?>" required readonly>
                    <label for="">Điện thoại:</label>
                    <input type="text" name="phone_KH" value="" required>
                    <label for="">Ghi chú khách hàng:</label>
                    <textarea name="note_KH" cols="50" rows="5"></textarea>
                </div>
            </div>
            <div class="dat_ve">
                <button type="submit" style="width: 200px;">Đặt vé</button>
            </div>
        </form>
        <a href="?module=home&action=huydatve" class="close">Hủy đặt vé</a>
<?php
    }
    mysqli_close($configDTB); //Đóng database
} else {
    header('location: ?module=home&action=datve');
}
layout('footer');
?>