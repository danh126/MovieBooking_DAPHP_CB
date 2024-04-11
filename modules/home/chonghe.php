<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Thay đổi tên trang tương ứng
$data = [
    'ten_trang' => 'Chọn ghế | Movie Booking'
];
layout('header', $data);

if (!empty($_SERVER["REQUEST_METHOD"] == "POST")) {

    // Kiểm tra login
    if (!checkLogin('login_user')) {
        header('location: ?module=home&action=dangnhap');
    }

    $_SESSION['gio_bd'] = $_POST['gio_bd'];
    $_SESSION['gio_kt'] = $_POST['gio_kt'];
?>
    <div class="chon_ghe">
        <p class="man_hinh">Màn hình</p>
        <form action="?module=home&action=xulydatve" method="post">
            <div class="checkbox">
                <?php
                // Sử dụng một vòng lặp để lặp qua các cột
                for ($i = 1; $i <= 5; $i++) {
                    // Truy vấn cơ sở dữ liệu
                    $sql = "SELECT * FROM ghe WHERE cot = $i";
                    $result = mysqli_query($configDTB, $sql);
                    // Kiểm tra xem có dữ liệu trả về không
                    if (mysqli_num_rows($result) > 0) {
                        echo "<div class='cot$i'>";
                        // Duyệt qua các hàng dữ liệu trả về
                        while ($row = mysqli_fetch_array($result)) {
                            $maGhe = $row['so_ghe'];
                            $status = $row['trang_thai'];
                ?>
                            <label for="ma_<?php echo $maGhe ?>">
                                <input type="checkbox" name="chon_ghe[]" id="ma_<?php echo $maGhe ?>" value="<?php echo $maGhe; ?>" <?php veDaDat($status); ?>>
                                <?php echo $row['so_ghe']; ?>
                            </label>
                <?php
                        }
                        echo "</div>";
                    }
                }
                ?>
            </div>
            <div class="note">
                <span>Ghi chú:</span>
                <label for="" class="status"><input type="checkbox" disabled>Đã đặt</label>
                <span><i class="fa-solid fa-square-check"></i>&nbsp;Ghế bạn chọn</span>
                <span class="ghe_thuong">Ghế thường</span>
                <span class="ghe_vip">Ghế vip</span>
            </div>
            <?php
            mysqli_close($configDTB);
            //Nếu không chọn ghế thì sẽ thông báo lỗi khi nhấn tiếp tục
            echo !empty($_SESSION['thong_bao_loi']) ? '<p class= "error" > ' . $_SESSION['thong_bao_loi'] : '' . '</p>';
            unset($_SESSION['thong_bao_loi']); //xóa lỗi sau khi thông báo
            ?>
            <div class="submit">
                <button type="submit">Tiếp tục</button>
            </div>
        </form>
    </div>
<?php
} else {
    header('location: ?module=home&action=datve');
}
layout('footer');
?>