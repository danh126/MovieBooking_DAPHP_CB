<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Thay đổi tên trang tương ứng
$data = [
    'ten_trang' => 'Chi tiết phim | Movie Booking'
];
layout('header', $data);

if (isset($_GET['phim'])) {
    $maPhim = $_GET['phim'];
    $sql = "SELECT * FROM chi_tiet_phim WHERE id_phim = '" . $maPhim . "'";
    $ket_qua = mysqli_query($configDTB, $sql);
    $smt = mysqli_num_rows($ket_qua);
    if ($smt > 0) {
        $rows = mysqli_fetch_array($ket_qua);
        $ngayChieu = $rows['ngay_chieu'];
?>
        <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/chitietphim.css?ver=<?php echo rand(); ?>">
        <div class="video_trailer">
            <?php
            $sql = "SELECT video_trailer, img_trailer_1, img_trailer_2, img_trailer_3 FROM chi_tiet_phim WHERE id_phim = '" . $maPhim . "'";
            $ket_qua = mysqli_query($configDTB, $sql);
            $row = mysqli_fetch_array($ket_qua);
            if ($row['video_trailer'] != '' || $row['img_trailer_1'] != '' || $row['img_trailer_2'] != '' || $row['img_trailer_3'] != '') {
            ?>
                <div class="slideshow-container">
                    <?php
                    if ($row['video_trailer'] != '') {
                    ?>
                        <div class="mySlides">
                            <video controls class="size">
                                <source src="<?php echo _WEB_HOST_TEMPLATES ?>/Video/<?php echo $row['video_trailer']; ?>" type="video/mp4">
                            </video>
                        </div>
                    <?php
                    }
                    ?>
                    <?php
                    if ($row['img_trailer_1'] != '') {
                    ?>
                        <div class="mySlides">
                            <img src="<?php echo _WEB_HOST_TEMPLATES ?>/images/trailer/<?php echo $row['img_trailer_1']; ?>" alt="Database error">
                        </div>
                    <?php
                    }
                    ?>
                    <?php
                    if ($row['img_trailer_2'] != '') {
                    ?>
                        <div class="mySlides">
                            <img src="<?php echo _WEB_HOST_TEMPLATES ?>/images/trailer/<?php echo $row['img_trailer_2']; ?>" alt="Database error">
                        </div>
                    <?php
                    }
                    ?>
                    <?php
                    if ($row['img_trailer_3'] != '') {
                    ?>
                        <div class="mySlides">
                            <img src="<?php echo _WEB_HOST_TEMPLATES ?>/images/trailer/<?php echo $row['img_trailer_3']; ?>" alt="Database error">
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                <a class="next" onclick="plusSlides(1)">&#10095;</a>
            <?php
            }
            ?>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="<?php echo _WEB_HOST_TEMPLATES ?>/js/slides_chitietphim.js"></script>
        <div class="containor">
            <div class="table">
                <div class="img">
                    <img src="<?php echo _WEB_HOST_TEMPLATES;
                                echo $rows['hinh_anh'] ?>" alt="database error" class="image">
                </div>
                <div class="infos">
                    <p class="filmname"><?= $rows['ten_phim'] ?></p>
                    <p class="daodien"><span style="font-weight: 600;">Xếp hạng:</span> <?= $rows['xep_hang'] ?></p>
                    <p class="daodien"><span style="font-weight: 600;">Thể loại:</span> <?= $rows['the_loai'] ?></p>
                    <p class="ngaykhoichieu"><span style="font-weight: 600;">Ngày khởi chiếu:</span> <?= date('d-m-Y', strtotime($ngayChieu)) ?></p>
                    <p class="basicinfos"><span style="font-weight: 600;">Thông tin:</span> <?= $rows['thong_tin'] ?></p>
                </div>
            </div>
            <?php
            $sql = "SELECT hien_trang FROM danh_sach_phim WHERE id_phim = '$maPhim'";
            $ket_qua = mysqli_query($configDTB, $sql);
            $row = mysqli_fetch_array($ket_qua);
            if ($row['hien_trang'] == 1) {
            ?>
                <button class="datve"><a href="?module=home&action=datve&phim=<?php echo $rows['id_phim'] ?>">Đặt vé</a></button>
            <?php
            }
            ?>
            <div class="tomtat">
                <p>Tóm tắt</p>
                <div class="bang"><?= $rows['tom_tat'] ?></div>
            </div>
            <?php
            // mysqli_close($configDTB); //đóng data
            ?>
            <div class="comment">
                <form class='comment' method='post' action=''>
                    <div class="title">Đánh giá phim</div>
                    <input type='hidden' name='id_phim' value='<?php echo $maPhim; ?>'>
                    <input type='hidden' name='user_name' value='<?php echo $_SESSION['username'] ?>'>
                    <!-- <input type='hidden' name='uid' value='Anonymous'> -->
                    <div class="cmt">
                        <input type='text' name='binh_luan' id='idtext' class='text' placeholder='Hãy binh luận về cảm nghĩ về phim' limit='1000'></input>
                        <button name='SubmitCmt' class='binhluan' type='submit'>Bình luận</button>
                    </div>
                </form>
            </div>
            <?php

            if (!empty($_SERVER["REQUEST_METHOD"] == "POST")) {
                if (checkLogin('login_user')) {
                    if ($_POST['binh_luan'] != '' && $_POST['id_phim'] != '') {
                        $id_Phim = $_POST['id_phim'];
                        $user_name = $_POST['user_name'];
                        $noi_dung = $_POST['binh_luan'];

                        //Tạo id tự động
                        $sql_id = "SELECT MAX(id_binh_luan) AS id_max FROM binh_luan";
                        $result_id = mysqli_query($configDTB, $sql_id);
                        $rows = mysqli_fetch_array($result_id);
                        $id_max = $rows['id_max'] + 1;

                        $sql = "INSERT INTO binh_luan (id_binh_luan,id_phim, user_name, noi_dung) VALUES (" . $id_max . "," . $id_Phim . ",'" . $user_name . "', '" . $noi_dung . "')";
                        $result = mysqli_query($configDTB, $sql);
                        if ($result) {
                            header("location: ?module=home&action=chitietphim&phim=$maPhim");
                        }
                    }
                } else {
                    header('location: ?module=home&action=dangnhap');
                }
            }

            $sql = "SELECT * FROM binh_luan WHERE id_phim = $maPhim ORDER BY id_binh_luan DESC LIMIT 0,5 ";
            $ket_qua = mysqli_query($configDTB, $sql);

            while ($row = mysqli_fetch_array($ket_qua)) {
                echo "<div class='cmt-box'>";
                echo "<p class='user_name'>" . $row['user_name'] . "</p>";
                echo "<span class='noi_dung_cmt'>" . $row['noi_dung'] . "</span>";
                echo "</div>";
                echo "<div class='userBtnCmt'>
                    <button name='editCmt' class='btnEdit'>Edit</button>
                    <button name='deleteCmt' class='btnDelete'>Xóa</button>
                    <button name='replyCmt' class='btnreplyCmt'>Trả lời</button>
                  </div>";
            }
            mysqli_close($configDTB); //đóng data
            ?>
        </div>
    <?php
    } // KT If $smt
    else {
        echo "<p style='text-align:center;'>Không có dữ liệu!</p>";
    }
    ?>
<?php
} else {
    header('location: ?module=home&action=trangchu');
}
layout('footer');
?>