<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Thay đổi tên trang tương ứng
$data = [
    'ten_trang' => 'Phim sắp chiếu | Movie Booking'
];
layout('header', $data);

//Tìm tổng số mẫu tin
$sql = "SELECT count(id_phim) AS SMT FROM danh_sach_phim WHERE hien_trang = 0";
$ket_qua = mysqli_query($configDTB, $sql);
$row = mysqli_fetch_array($ket_qua);
$tong_SMT = $row['SMT'];

if ($tong_SMT > 0) {

    //Tìm tổng số trang và truyền limit
    $vt_page = isset($_GET['page']) ? $_GET['page'] : 1;
    $limit = 8;

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

    //Truy vấn dữ liệu

    $sql = "SELECT * FROM danh_sach_phim WHERE hien_trang = 0 ORDER BY id_phim DESC LIMIT $start,$limit";
    $ket_qua = mysqli_query($configDTB, $sql);
?>
    <!--Hiện trạng phim-->
    <div class="movie">
        <a href="?module=home&action=trangchu">Phim đang chiếu</a>
        <a href="?module=home&action=phimsapchieu">Phim sắp chiếu</a>
    </div>

    <!--Nội dung phim-->
    <?php
    while ($row = mysqli_fetch_array($ket_qua)) :
    ?>
        <div class="content_movie" id="c1">
            <?php
            $n = 4;
            for ($i = 1; $i <= $n; $i++) {
                echo "<td>";
                //Nếu có mẫu tin thì view dữ liệu
                if ($row != false) {
            ?>
                    <div>
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
                                <span class="movie_block">Chưa mở</span>
                            </p>
                            <p class="click_ct_dv">
                                <a href="?module=home&action=chitietphim&phim=<?php echo $row['id_phim'] ?>">Chi tiết</a>
                                <!-- <span><a href="">Đặt vé</a></span> -->
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
    ?>
    <div class="phan_trang">
        <?php
        //Nếu vtri trang > 1 và tổng trang > 1 thì hiện nút quay lại
        if ($vt_page > 1 && $total_page > 1) {
            echo '<a href="?module=home&action=phimsapchieu&page=' . ($vt_page - 1) . '"> < </a>';
        }
        //Lập khoảng giữa 
        for ($i = 1; $i <= $total_page; $i++) {
            if ($i == $vt_page) {
                echo '<span>' . $i . '</span>';
            } else {
                echo '<a href="?module=home&action=phimsapchieu&page=' . $i . '">' . $i . '</a>';
            }
        }
        //Nếu vtri trang < tổng trang và tổng trang > 1 thì hiện nút trang tiếp
        if ($vt_page < $total_page && $total_page > 1) {
            echo '<a href="?module=home&action=phimsapchieu&page=' . ($vt_page + 1) . '"> > </a>';
        }
        ?>
    </div>
<?php
} else {
    echo "<p style='text-align:center;'> Không có dữ liệu! <p>";
}
layout('footer');
?>