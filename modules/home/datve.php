<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Thay đổi tên trang tương ứng
$data = [
    'ten_trang' => 'Đặt vé xem phim | Movie Booking'
];
layout('header', $data);

$sql = "SELECT id_phim, ten_phim FROM danh_sach_phim WHERE hien_trang = 1 ORDER BY id_phim DESC";
$ket_qua = mysqli_query($configDTB, $sql);
?>
<div class="booking" id="c1">
    <?php
    echo !empty($_SESSION['dat_ve_thanh_cong']) ? '<p class= "dat_ve_thanh_cong" > ' . $_SESSION['dat_ve_thanh_cong'] : '' . '</p>';
    unset($_SESSION['dat_ve_thanh_cong']); //xóa lỗi sau khi thông báo
    ?>
    <div class="form">
        <form action="" method="post">
            <div class="list_movie">
                <p>Danh sách phim</p>
                <div class="list_srcoll">
                    <?php while ($row = mysqli_fetch_array($ket_qua)) : ?>
                        <li>
                            <label for="">
                                <input type="radio" name="ten_phim" value="<?php echo $row['ten_phim']; ?>" <?php checked($row, 'id_phim', 'phim'); ?> required>
                                <?php echo $row['ten_phim'] ?>
                            </label>
                        </li>
                    <?php endwhile; ?>
                </div>
            </div>
            <div class="chon_ngay">
                <label for="" class="day">Chọn ngày</label>
                <input type="date" name="date_mv" id="dateInput" class="input_day" required>
            </div>
            <script src="<?php echo _WEB_HOST_TEMPLATES ?>/js/date.js"></script>
            <div class="phong">
                <label>Chọn phòng</label>
                <?php
                $sql = "SELECT id_phong, ten_phong FROM phong_chieu";
                $tenPhong = mysqli_query($configDTB, $sql);
                while ($row = mysqli_fetch_array($tenPhong)) {
                ?>
                    <span><input type="radio" name="chon_phong" value="<?php echo $row['ten_phong']; ?>" <?php checked($row, 'id_phong', 'phong'); ?> required><?php echo $row['ten_phong']; ?></span>
                <?php
                }
                ?>
            </div>
            <div class="chon_ca_chieu">
                <button type="submit" class="btn">Chọn ca chiếu</button>
            </div>
        </form>
        <?php
        if (isset($_POST['ten_phim']) == null || isset($_POST['date_mv']) == null || isset($_POST['chon_phong']) == null) {
            echo "<div class='thong_bao_loi'> <p>Quý khách vui lòng chọn đầy đủ thông tin ngày, phòng, phim.Để xem ca chiếu tại rạp ! </p> </div> ";
        }
        if (isset($_POST['date_mv']) != null && isset($_POST['ten_phim']) != null && isset($_POST['chon_phong']) != null) {
            //khởi tạo session cho các mục chọn
            $ngayChon = $_POST['date_mv'];
            // Chuyển đổi thời gian sang timestamp
            $timestamp = strtotime($ngayChon);
            // Định dạng giờ và phút
            $ngayChon_FM = date('d-m-Y', $timestamp);
            $_SESSION['ngay_da_chon'] =  $ngayChon;
            $_SESSION['phim_da_chon'] = $_POST['ten_phim'];
            $_SESSION['phong_da_chon'] = $_POST['chon_phong'];
        ?>
            <form action="?module=home&action=datve" method="post">
                <div class="list_chon">
                    <p>Ngày chiếu: &nbsp;<input type="text" readonly value="<?php echo !empty($ngayChon_FM) ? $ngayChon_FM : '' ?>"></p>
                    <p>Phim đã chọn: &nbsp;<input type="text" readonly value="<?php echo !empty($_SESSION['phim_da_chon']) ? $_SESSION['phim_da_chon'] : '' ?>"></p>
                    <p>Phòng đã chọn: &nbsp;<input type="text" readonly value="<?php echo !empty($_SESSION['phong_da_chon']) ? $_SESSION['phong_da_chon'] : '' ?>"></p>
                    <span class="chon_lai"><input type="submit" name="chonLai" value="Chọn lại"></span>
                </div>
            </form>
            <?php
            //Nếu người dùng click chọn lại thì sẽ xóa session các mục đã chọn
            if (isset($_POST['chonLai'])) {
                unset($_SESSION['ngay_da_chon']);
                unset($_SESSION['phim_da_chon']);
                unset($_SESSION['phong_da_chon']);
            }
            ?>
            <form action="?module=home&action=chonghe" method="post">
                <div class="time_movie">
                    <?php
                    $tenPhim =  $_POST['ten_phim'];
                    $ngayChieu = $_POST['date_mv'];
                    $phongChieu = $_POST['chon_phong'];
                    //    $ngayChieu = $_POST['date_mv'];
                    //    $chonPhong = $_POST['chon_phong'];
                    $sql = "SELECT danh_sach_phim.id_phim, ca_chieu.gio_bd, ca_chieu.gio_kt, phong_chieu.ten_phong 
                                FROM ca_chieu 
                                INNER JOIN danh_sach_phim ON ca_chieu.id_phim = danh_sach_phim.id_phim 
                                INNER JOIN phong_chieu ON ca_chieu.id_phong = phong_chieu.id_phong 
                                WHERE danh_sach_phim.ten_phim = '" . $tenPhim . "' AND ca_chieu.ngay_chieu = '" . $ngayChieu . "' AND phong_chieu.ten_phong = '" . $phongChieu . "'";

                    $ket_qua = mysqli_query($configDTB, $sql);
                    // Kiểm tra và hiển thị dữ liệu
                    if ($smt = mysqli_num_rows($ket_qua) > 0) {
                        $Phim = array(); // gán mảng chứa dữ liệu 
                        while ($row = mysqli_fetch_array($ket_qua)) {
                            $ten_Phim = $_POST['ten_phim'];
                            $gio_bd = $row["gio_bd"];
                            $gio_kt = $row["gio_kt"];
                            $Phong = $row['ten_phong'];
                            // Chuyển đổi thời gian sang timestamp
                            $timestamp_1 = strtotime($gio_bd);
                            // Định dạng giờ và phút
                            $formattedTime_gio_bd = date('H:i', $timestamp_1);

                            // Chuyển đổi thời gian sang timestamp
                            $timestamp_2 = strtotime($gio_kt);
                            // Định dạng giờ và phút
                            $formattedTime_gio_kt = date('H:i', $timestamp_2);

                            if (!isset($Phim[$ten_Phim])) {
                                $Phim[$ten_Phim] = array();
                            }
                            if (!isset($Phim[$ten_Phim][$formattedTime_gio_bd])) {
                                $Phim[$ten_Phim][$formattedTime_gio_bd] = array();
                            }
                            if (!isset($Phim[$ten_Phim][$formattedTime_gio_bd][$formattedTime_gio_kt])) {
                                $Phim[$ten_Phim][$formattedTime_gio_bd][$formattedTime_gio_kt] = array();
                            }
                            $Phim[$ten_Phim][$formattedTime_gio_bd][$formattedTime_gio_kt][] = $Phong;
                        }
                        // $_SESSION['thoi_gian_chieu'] = $formattedTime;
                    ?>
                        <h2>Giờ chiếu</h2>
                        <?php
                        foreach ($Phim as $ten_Phim => $formattedTime_gio_bds) :
                        ?>
                            <div>
                                <p style="text-transform: uppercase;"><?php echo $ten_Phim; ?></p>
                                <div class="time">
                                    <?php
                                    foreach ($formattedTime_gio_bds as $formattedTime_gio_bd => $formattedTime_gio_kts) :
                                        foreach ($formattedTime_gio_kts as $formattedTime_gio_kt => $phongChieu) :
                                    ?>
                                            <button type="submit" class="list">
                                                <input type="hidden" name="gio_bd" value="<?php echo $formattedTime_gio_bd; ?>">
                                                <input type="hidden" name="gio_kt" value="<?php echo $formattedTime_gio_kt; ?>">
                                                <span><?php echo implode(",", $phongChieu); ?></span>
                                                <hr>
                                                <span><?php echo $formattedTime_gio_bd; ?> - <?php echo $formattedTime_gio_kt; ?> </span>
                                            </button>
                                    <?php
                                        endforeach;
                                    endforeach;
                                    ?>
                                </div>
                            </div>
                    <?php
                        endforeach;
                        // echo "<button type='submit'>Tiếp tục</button>";
                    } else {
                        echo "<span class='thong_bao_loi' style='margin-top:10px;'>Không có ca chiếu cho sự lựa chọn của bạn. Vui lòng thử lại sau!</span>";
                    }
                    ?>
                </div>
            </form>
        <?php
        } //kt if kiểm tra null
        mysqli_close($configDTB);
        ?>
    </div>
</div>

<?php
layout('footer');
?>