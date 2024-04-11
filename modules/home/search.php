<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Thay đổi tên trang tương ứng
$data = [
    'ten_trang' => 'Tìm kiếm phim | Movie Booking'
];
layout('header', $data);

//lấy dữ liệu từ form tìm kiếm ở trang chủ
// $noiDungTK = isset($_POST['txtTenPhim']) ? $_POST['txtTenPhim'] : '';

if (!empty($_SERVER["REQUEST_METHOD"] == "POST")) {
    if ($_POST['txtTenPhim'] != '') {
        $noiDungTK = $_POST['txtTenPhim'];
        //Truy vấn tìm kiếm
        $sql = "SELECT * FROM danh_sach_phim WHERE ten_phim LIKE '%$noiDungTK%' and hien_trang = 1";
        $ket_qua = mysqli_query($configDTB, $sql);
        $mauTin = mysqli_num_rows($ket_qua);
    } else {
        header('location: ?module=home&action=trangchu');
    }
    if ($mauTin > 0) {
?>
        <div class="tieu_de">
            <p>Kết quả tìm kiếm</p>
        </div>
        <?php
        while ($row = mysqli_fetch_array($ket_qua)) :
        ?>
            <div class="content_movie_2" id="c1">
                <?php
                $n = 4;
                for ($i = 1; $i <= $n; $i++) {
                    echo "<td>";
                    //Nếu có mẫu tin thì view dữ liệu
                    if ($row != false) {
                ?>
                        <div class="search">
                            <li class="rol_1">
                                <img src="<?php echo _WEB_HOST_TEMPLATES;
                                            echo $row['hinh_anh']; ?>" alt="database error" class="imgMovie">
                                <p class="name_movie">
                                    <a href="?module=home&action=chitietphim&phim=<?php echo $row['id_phim'] ?>"><?php echo $row['ten_phim']; ?></a>
                                    <!-- </p>
                                <p class="day_time"> -->
                                    <br><br>
                                    <i class="fa-regular fa-calendar-check"></i>&nbsp;
                                    <?php
                                    $ngayChieu = $row['ngay_chieu'];
                                    echo date("d-m-Y", strtotime($ngayChieu));
                                    ?>
                                    <span style="text-transform: none;"><i class="fa-regular fa-clock"></i>&nbsp;<?php echo $row['thoi_luong']; ?></span>
                                </p>
                                <p class="click_ct_dv">
                                    <a href="?module=home&action=chitietphim&phim=<?php echo $row['id_phim'] ?>">Chi tiết</a>
                                    <span><a href="?module=home&action=datve&phim=<?php echo $row['id_phim'] ?>">Đặt vé</a></span>
                                </p>
                            </li>
                        </div>
                <?php
                    } //kt if 
                    else {
                        echo "&nbsp;"; //nếu không có mẫu tin hiện ra khoảng trắng
                    }
                    if ($i != $n) {
                        $row = mysqli_fetch_array($ket_qua);
                    }
                } // kt for
                ?>
            </div>
<?php
        endwhile;
        mysqli_close($configDTB);
    } //KT if $mautin
    else {
        echo " <div class='tieu_de'>
                    <p>Kết quả tìm kiếm</p>
                    <span>Không tìm thấy kết quả!</span>
                </div>";
    }
} else {
    header('location: ?module=home&action=trangchu');
}
?>
<?php
layout('footer');
?>