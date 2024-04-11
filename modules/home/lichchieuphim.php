<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Thay đổi tên trang tương ứng
$data = [
    'ten_trang' => 'Lịch chiếu phim | Movie Booking'
];
layout('header', $data);

//Lấy ngày hiện tại 
$date = getdate();
$date = date('Y-m-d'); // định dạng ngày

//Tìm tổng số mẫu tin
$sql = "SELECT count(id_cachieu) AS SMT FROM ca_chieu WHERE ngay_chieu = '$date' ORDER BY id_cachieu";
$ket_qua = mysqli_query($configDTB, $sql);
$row = mysqli_fetch_array($ket_qua);
$tong_SMT = $row['SMT'];
if ($tong_SMT > 0) {
    //Tìm tổng số trang và truyền limit
    $vt_page = isset($_GET['page']) ? $_GET['page'] : 1;
    $limit = 10;

    //Tính tổng trang 

    $total_page = ceil($tong_SMT / $limit);

    //giới hạn vtri trang từ 1 -> tổng trang
    if ($vt_page > $total_page) {
        $vt_page = $total_page;
    } else {
        if ($vt_page < 1) {
            $vt_page = 1;
        }
    }

    //Tìm vị trí bắt đầu

    $start = ($vt_page - 1) * $limit;

    $sql = "SELECT danh_sach_phim.ten_phim, ca_chieu.gio_bd, ca_chieu.gio_kt, phong_chieu.ten_phong, phong_chieu.id_phong, ca_chieu.id_phim 
        FROM ca_chieu 
        INNER JOIN danh_sach_phim ON ca_chieu.id_phim = danh_sach_phim.id_phim 
        INNER JOIN phong_chieu ON ca_chieu.id_phong = phong_chieu.id_phong 
        WHERE ca_chieu.ngay_chieu = '$date'
        ORDER BY ca_chieu.gio_bd 
        LIMIT $limit OFFSET $start";

    $result = mysqli_query($configDTB, $sql);

    // Kiểm tra và hiển thị dữ liệu
    if ($smt = mysqli_num_rows($result) > 0) {
        $Phim = array(); // gán mảng chứa dữ liệu 
        while ($row = mysqli_fetch_array($result)) {
            $id_phong = $row['id_phong'];
            $tenPhim = $row["ten_phim"];
            $id_Phim = $row['id_phim'];
            $gio_bd = $row["gio_bd"];
            $gio_kt = $row["gio_kt"];
            $phongChieu = $row['ten_phong'];
            // Chuyển đổi thời gian sang timestamp
            $timestamp_1 = strtotime($gio_bd);
            // Định dạng giờ và phút
            $formattedTime_gio_bd = date('H:i', $timestamp_1);

            // Chuyển đổi thời gian sang timestamp
            $timestamp_2 = strtotime($gio_kt);
            // Định dạng giờ và phút
            $formattedTime_gio_kt = date('H:i', $timestamp_2);
            if (!isset($Phim[$id_Phim])) {
                $Phim[$id_Phim] = array();
            }
            if (!isset($Phim[$id_Phim][$tenPhim])) {
                $Phim[$id_Phim][$tenPhim] = array();
            }
            if (!isset($Phim[$id_Phim][$tenPhim][$formattedTime_gio_bd])) {
                $Phim[$id_Phim][$tenPhim][$formattedTime_gio_bd] = array();
            }
            if (!isset($Phim[$id_Phim][$tenPhim][$formattedTime_gio_bd][$formattedTime_gio_kt])) {
                $Phim[$id_Phim][$tenPhim][$formattedTime_gio_bd][$formattedTime_gio_kt] = array();
            }
            $Phim[$id_Phim][$tenPhim][$formattedTime_gio_bd][$formattedTime_gio_kt][] = $phongChieu;
        }
    }

?>
    <div class="danh_sach_lich">
        <div class="lich">
            <p class="lich_p">Giờ chiếu</p>
            <p class="lich_sp">Thời gian chiếu phim có thể chênh lệch 15 phút do chiếu quảng cáo, giới thiệu phim ra rạp.</p>
        </div>
        <!-- <div class="thoi_gian_hien_hanh">
            <form action="" method="post">
                <input type="date" id="dateInput" name="chon_ngay">
                <input type="submit" value="Chọn">
            </form>
            <script src="<?php echo _WEB_HOST_TEMPLATES ?>/js/date.js"></script>
        </div> -->
        <div>
            <?php
            foreach ($Phim as $id_Phim => $tenPhims) {
            ?>
                <div class="lich_chieu">
                    <?php
                    foreach ($tenPhims as $tenPhim => $formattedTime_gio_bds) {
                    ?>
                        <p class="ten_phim"><?php echo $tenPhim; ?>&nbsp;<a href="?module=home&action=chitietphim&phim=<?php echo $id_Phim; ?>">></a></p>
                    <?php
                    } // KT Foreach sub1
                    ?>
                    <div class="thoi_gian_view">
                        <?php
                        foreach ($formattedTime_gio_bds as $formattedTime_gio_bd => $formattedTime_gio_kts) {
                            foreach ($formattedTime_gio_kts as $formattedTime_gio_kt => $phongChieu_s) {
                                foreach ($phongChieu_s as $phongChieu) {
                        ?>
                                    <a href="?module=home&action=datve&phim=<?php echo $id_Phim; ?>&phong=<?php echo $id_phong; ?>" class="thoi_gian">
                                        <p><?php echo $phongChieu; ?></p>
                                        <hr>
                                        <span><?php echo $formattedTime_gio_bd; ?> ~ <?php echo $formattedTime_gio_kt; ?></span>
                                    </a>
                        <?php
                                }
                            } // KT Foreach sub2
                        } // KT Foreach sub3 
                        ?>
                    </div>
                </div>
            <?php
            } // KT Foreach tổng
            ?>
        </div>
    </div>
    <div class="phan_trang">
        <?php
        //Nếu vtri trang > 1 và tổng trang > 1 thì hiện nút quay lại
        if ($vt_page > 1 && $total_page > 1) {
            echo '<a href="?module=home&action=lichchieuphim&page=' . ($vt_page - 1) . '"> < </a>';
        }
        //Lập khoảng giữa 
        for ($i = 1; $i <= $total_page; $i++) {
            if ($i == $vt_page) {
                echo '<span>' . $i . '</span>';
            } else {
                echo '<a href="?module=home&action=lichchieuphim&page=' . $i . '">' . $i . '</a>';
            }
        }
        //Nếu vtri trang < tổng trang và tổng trang > 1 thì hiện nút trang tiếp
        if ($vt_page < $total_page && $total_page > 1) {
            echo '<a href="?module=home&action=lichchieuphim&page=' . ($vt_page + 1) . '"> > </a>';
        }
        ?>
    </div>

<?php

} else {
    echo "<p style='text-align:center;'>Lịch chiếu phim đang được cập nhật!</p>";
}
layout('footer');
?>