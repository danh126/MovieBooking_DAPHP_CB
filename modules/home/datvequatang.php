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

if (checkLogin('login_user')) {
    if (!empty($_SERVER["REQUEST_METHOD"] == "POST")) {

        if (!empty($_SESSION['id_user'])) {
            $id_user = $_SESSION['id_user'];
            $sql = "SELECT ho_ten, email FROM login_user WHERE id_user = '$id_user'";
            $ket_qua = mysqli_query($configDTB, $sql);
            $row = mysqli_fetch_array($ket_qua);
        }
        $maqt = $_POST['id_quatang'];
        $soluong = $_POST['so_luong'];

        $sql = "SELECT * FROM quatang WHERE id_quatang = '" . $maqt . "' ";
        $ket_qua = mysqli_query($configDTB, $sql);
?>
        <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/shopquatang.css?ver=<?php echo rand(); ?>">

        <div class="datvequatang">
            <!-- <h2>Đặt vé quà tặng</h2> -->
            <form method="post" action="?module=home&action=xulydatvequatang">
                <div class="tongthe">
                    <div class="thongtin_kh">
                        <h2>Thông tin người dùng</h2>
                        <label for="name">Tên:</label>
                        <input type="text" id="name" name="name" value="<?php echo !empty($row['ho_ten']) ? $row['ho_ten'] : '' ?>" readonly>
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo !empty($row['email']) ? $row['email'] : '' ?>" readonly>
                        <label for="phone">Số điện thoại:</label>
                        <input type="text" id="phone" name="sdt" required>
                        <label for="ghichu">Ghi chú:</label>
                        <textarea id="ghichu" name="ghichu" cols="50" rows="5"></textarea>
                    </div>
                    <div class="chitietdatve">
                        <h2>Thông tin vé</h2>
                        <?php
                        $sql1 = "SELECT * FROM chitietquatang WHERE id_quatang = '" . $maqt . "' ";
                        $ket_qua1 = mysqli_query($configDTB, $sql1);
                        while ($rows = mysqli_fetch_array($ket_qua1)) // vong lap while 
                        {
                        ?>
                            <img src="<?php echo _WEB_HOST_TEMPLATES ?>/images/quatang<?php echo $rows['hinhanh'] ?>" alt="" width="280px">
                            <label><input class="tensp" type="text" name="tensp" value="<?php echo $rows['tensp'] ?>"></label>

                            <label class="giaban"><b>Giá bán online &emsp;&emsp;:&nbsp;</b><input class="input_ve" type="text" name="giaban" value="<?php echo $rows['giaban'] ?> " readonly>VNĐ</label>
                            <label><b> Số lượng &emsp;&emsp;&emsp;&emsp;&nbsp; : &nbsp;</b><input class="input_ve" type="text" name="soluong" value="<?php echo $_POST['so_luong'] ?> "> Vé</label>

                            <?php
                            $giaban = $rows['giaban'];
                            $soluong = $_POST['so_luong'];
                            $tongtien = $giaban * $soluong;
                            ?>
                            <label class="tongtien"><b><?php echo "Tổng tiền &emsp;&emsp;&emsp;&emsp; : </b>" ?><input class="input_ve" type="text" name="tongtien" value="<?php echo " $tongtien " ?> " readonly>VNĐ</label>
                        <?php
                        } // ket thuc while
                        ?>
                    </div>
                </div>
                <div class="submit">
                    <button type="submit">Đặt vé</button>
                    <a href="?module=home&action=shopquatang">Quay lại</a>
                </div>
            </form>
        </div>
<?php
    } else {
        header('location: ?module=home&action=shopquatang');
    }
} else {
    header('location: ?module=home&action=dangnhap');
}
layout('footer');
?>