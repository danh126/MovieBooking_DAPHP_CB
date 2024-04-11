<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Thay đổi tên trang tương ứng
$data = [
    'ten_trang' => 'Thêm phim mới | Movie Booking'
];
layout('header_admin', $data);

//Nếu không tồn tại loginAdmin thì quay về trang login
if (!checkLogin('login_admin')) {
    header('location: ?module=admin&action=dangnhap');
}

//Xử lý thêm phim mới 

if (!empty($_SERVER["REQUEST_METHOD"] == "POST")) {
    $ten_phim = $_POST['ten_phim'];
    $ngay_Chieu = $_POST['ngay_chieu'];
    $thoi_luong = $_POST['thoi_luong'];
    $hien_trang = $_POST['hien_trang'];
    $xep_hang = $_POST['xep_hang'];
    $thong_tin = $_POST['thong_tin'];
    $the_loai = $_POST['the_loai'];
    $tom_tat = $_POST['tom_tat'];

    // Xử lý lưu ảnh vào thư mục
    $hinhanhpath = basename($_FILES["hinh_anh"]["name"]);
    $target_dir = "templates/images/phim/";
    $target_file = $target_dir . $hinhanhpath;

    $upload_file_error = 0; //Kiểm tra lỗi trùng file

    //Kiểm tra trùng tên phim
    $sql = "SELECT ten_phim FROM danh_sach_phim WHERE ten_phim = '$ten_phim'";
    $ket_qua = mysqli_query($configDTB, $sql);
    if (mysqli_num_rows($ket_qua) > 0) {
        $_SESSION['error_phim'] = "Phim đã tồn tại!";
    } else {

        // Kiểm tra nếu tệp đã tồn tại
        if (file_exists($target_file)) {
            $upload_file_error = 1;
            $_SESSION['error_img'] = "Hình ảnh đã tồn tại!";
        }
    }

    if ($_FILES['video_trailer']['name'] != '') {
        // Xử lý lưu video vào thư mục
        $video_path = basename($_FILES["video_trailer"]["name"]);
        $target_dir = "templates/video/";
        $target_file_2 = $target_dir . $video_path;

        // $uploadOk = 1; 
        $imageFileType = strtolower(pathinfo($target_file_2, PATHINFO_EXTENSION));

        //Kiểm tra trùng video 
        if (file_exists($target_file_2)) {
            $upload_file_error = 1;
            $_SESSION['trung_video'] = "Video trailer đã tồn tại!";
        }
        if ($imageFileType != 'mp4') {
            $upload_file_error = 1;
            $_SESSION['error_video'] = "Vidoe trailer phải có đuôi .mp4!";
        }
    }

    if ($_FILES['hinh_anh_1']['name'] != '') {
        // Xử lý lưu img trailer vào thư mục

        $img_path_1 = basename($_FILES["hinh_anh_1"]["name"]);
        $target_dir = "templates/images/trailer/";
        $target_file_3 = $target_dir . $img_path_1;

        $imageFileType = strtolower(pathinfo($target_file_3, PATHINFO_EXTENSION));

        //Kiểm tra trùng img_trailer_1 
        if (file_exists($target_file_3)) {
            $upload_file_error = 1;
            $_SESSION['trung_img_1'] = "Hình ảnh trailer 1 đã tồn tại!";
        }
    }

    if ($_FILES['hinh_anh_2']['name'] != '') {
        $img_path_2 = basename($_FILES["hinh_anh_2"]["name"]);
        $target_dir = "templates/images/trailer/";
        $target_file_4 = $target_dir . $img_path_2;

        $imageFileType = strtolower(pathinfo($target_file_4, PATHINFO_EXTENSION));

        //Kiểm tra trùng img_trailer_2
        if (file_exists($target_file_4)) {
            $upload_file_error = 1;
            $_SESSION['trung_img_2'] = "Hình ảnh trailer 2 đã tồn tại!";
        }
    }

    if ($_FILES['hinh_anh_3']['name'] != '') {
        $img_path_3 = basename($_FILES["hinh_anh_3"]["name"]);
        $target_dir = "templates/images/trailer/";
        $target_file_5 = $target_dir . $img_path_3;

        $imageFileType = strtolower(pathinfo($target_file_5, PATHINFO_EXTENSION));

        //Kiểm tra trùng img_trailer_3
        if (file_exists($target_file_5)) {
            $upload_file_error = 1;
            $_SESSION['trung_img_3'] = "Hình ảnh trailer 3 đã tồn tại!";
        }
    }

    // Nếu không xảy ra lỗi thì lưu tất cả các file vào thư mục
    if ($upload_file_error == 0 && $_FILES['video_trailer']['name'] != '' && $_FILES['hinh_anh_1']['name'] != '' && $_FILES['hinh_anh_2']['name'] != '' && $_FILES['hinh_anh_3']['name'] != '') {

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        move_uploaded_file($_FILES["hinh_anh"]["tmp_name"], $target_file);
        move_uploaded_file($_FILES["video_trailer"]["tmp_name"], $target_file_2);
        move_uploaded_file($_FILES["hinh_anh_1"]["tmp_name"], $target_file_3);
        move_uploaded_file($_FILES["hinh_anh_2"]["tmp_name"], $target_file_4);
        move_uploaded_file($_FILES["hinh_anh_3"]["tmp_name"], $target_file_5);

        //Tạo id tự động
        $sql_id = "SELECT MAX(id_phim) AS id_max FROM danh_sach_phim";
        $result_id = mysqli_query($configDTB, $sql_id);
        $rows = mysqli_fetch_array($result_id);
        $id_max = $rows['id_max'] + 1;

        //Tạo link để lưu vào database
        $link_img = "/images/phim/";
        $hinh_anh_path = $link_img . $hinhanhpath;

        $sql_phim = "INSERT INTO danh_sach_phim (id_phim, ten_phim, ngay_chieu, hinh_anh, thoi_luong, hien_trang) 
         VALUES ('" . $id_max . "','" . $ten_phim . "','" . $ngay_Chieu . "','" . $hinh_anh_path . "','" . $thoi_luong . "'," . $hien_trang . ")";

        $ket_qua = mysqli_query($configDTB, $sql_phim);

        //Tạo id tự động
        $sql_id = "SELECT MAX(id_ct_phim) AS id_max FROM chi_tiet_phim";
        $result_id = mysqli_query($configDTB, $sql_id);
        $rows = mysqli_fetch_array($result_id);
        $id_max_ct = $rows['id_max'] + 1;

        $sql_ct = "INSERT INTO chi_tiet_phim (id_ct_phim, id_phim, ten_phim, xep_hang, ngay_chieu,
        thong_tin, the_loai, tom_tat, hinh_anh, video_trailer, img_trailer_1, img_trailer_2, img_trailer_3)
        VALUES (" . $id_max_ct . "," . $id_max . ", '" . $ten_phim . "','" . $xep_hang . "','" . $ngay_Chieu . "', '" . $thong_tin . "',
        '" . $the_loai . "','" . $tom_tat . "','" . $hinh_anh_path . "','" . $video_path . "','" . $img_path_1 . "','" . $img_path_2 . "','" . $img_path_3 . "')";
        $ket_qua_chi_tiet = mysqli_query($configDTB, $sql_ct);

        if ($ket_qua && $ket_qua_chi_tiet) {
            $_SESSION['add_phim'] = "Thêm phim <span style='color:blue;'> $ten_phim </span> thành công!";
            // header('location: ?module=admin&action=themphimmoi');
        }
    } else {
        if ($upload_file_error == 0) {
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            move_uploaded_file($_FILES["hinh_anh"]["tmp_name"], $target_file);

            //Tạo id tự động
            $sql_id = "SELECT MAX(id_phim) AS id_max FROM danh_sach_phim";
            $result_id = mysqli_query($configDTB, $sql_id);
            $rows = mysqli_fetch_array($result_id);
            $id_max = $rows['id_max'] + 1;

            //Tạo link để lưu vào database
            $link_img = "/images/phim/";
            $hinh_anh_path = $link_img . $hinhanhpath;

            $sql_phim = "INSERT INTO danh_sach_phim (id_phim, ten_phim, ngay_chieu, hinh_anh, thoi_luong, hien_trang) 
         VALUES ('" . $id_max . "','" . $ten_phim . "','" . $ngay_Chieu . "','" . $hinh_anh_path . "','" . $thoi_luong . "'," . $hien_trang . ")";

            $ket_qua = mysqli_query($configDTB, $sql_phim);

            //Tạo id tự động
            $sql_id = "SELECT MAX(id_ct_phim) AS id_max FROM chi_tiet_phim";
            $result_id = mysqli_query($configDTB, $sql_id);
            $rows = mysqli_fetch_array($result_id);
            $id_max_ct = $rows['id_max'] + 1;

            // $sql_chi_tiet = "INSERT INTO chi_tiet_phim (id_ct_phim, id_phim, ten_phim, xep_hang, ngay_chieu, 
            // thong_tin, the_loai, tom_tat, hinh_anh, video_trailer, img_trailer_1, img_trailer_2, img_trailer_3) 
            // VALUES(" . $id_max_ct . ", " . $id_max . ",'" . $ten_phim . "','" . $xep_hang . "', '" . $ngay_Chieu . "','" . $thong_tin . "',
            // '" . $the_loai . "', '" . $tom_tat . "','" . $hinh_anh_path . "','" . $video_path . "','" . $img_path_1 . "','" . $img_path_2 . "','" . $img_path_3 . "')";
            // $ket_qua_ct = mysqli_query($configDTB, $sql_chi_tiet);

            $sql_ct = "INSERT INTO chi_tiet_phim (id_ct_phim, id_phim, ten_phim, xep_hang, ngay_chieu,
        thong_tin, the_loai, tom_tat, hinh_anh, video_trailer, img_trailer_1, img_trailer_2, img_trailer_3)
        VALUES (" . $id_max_ct . "," . $id_max . ", '" . $ten_phim . "','" . $xep_hang . "','" . $ngay_Chieu . "', '" . $thong_tin . "',
        '" . $the_loai . "','" . $tom_tat . "','" . $hinh_anh_path . "','','','','')";
            $ket_qua_chi_tiet = mysqli_query($configDTB, $sql_ct);

            if ($ket_qua && $ket_qua_chi_tiet) {
                $_SESSION['add_phim'] = "Thêm phim <span style='color:blue;'> $ten_phim </span> thành công!";
                // header('location: ?module=admin&action=themphimmoi');
            }
        }
    }
}
?>
<main>
    <div class="add_phim">
        <p>Thêm phim mới</p>
        <span class="thong_bao">
            <?php
            echo !empty($_SESSION['add_phim']) ?  $_SESSION['add_phim'] : '';
            unset($_SESSION['add_phim']);
            ?>
        </span>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="">Tên phim</label>
            <input type="text" name="ten_phim" placeholder="Nhập tên phim mới" required value="<?php echo !empty($_POST['ten_phim']) ? $_POST['ten_phim'] : '' ?>">
            <span>
                <?php
                echo !empty($_SESSION['error_phim']) ? $_SESSION['error_phim'] : '';
                unset($_SESSION['error_phim']);
                ?>
            </span>
            <label for="">Ngày chiếu</label>
            <input type="date" name="ngay_chieu" required value="<?php echo !empty($_POST['ngay_chieu']) ? $_POST['ngay_chieu'] : '' ?>">
            <label for="">Hình ảnh</label>
            <input type="file" name="hinh_anh" required>
            <span>
                <?php
                echo !empty($_SESSION['error_img']) ? $_SESSION['error_img'] : '';
                unset($_SESSION['error_img']);
                ?>
            </span>
            <label for="">Thời lượng</label>
            <input type="text" name="thoi_luong" placeholder="Nhập vào thời lượng chiếu" required value="<?php echo !empty($_POST['thoi_luong']) ? $_POST['thoi_luong'] : '' ?>">
            <label for="">Hiện trạng phim <span>(Đang chiếu (1), sắp chiếu(0).)</span></label>
            <input type="number" max=1 min=0 name="hien_trang" placeholder="Nhập vào hiện trạng phim" required value="<?php echo !empty($_POST['hien_trang']) ? $_POST['hien_trang'] : '' ?>">
            <label for="">Video trailer</label>
            <input type="file" name="video_trailer">
            <span>
                <?php
                echo !empty($_SESSION['trung_video']) ? $_SESSION['trung_video'] : '';
                unset($_SESSION['trung_video']);
                echo !empty($_SESSION['error_video']) ? $_SESSION['error_video'] : '';
                unset($_SESSION['error_video']);
                ?>
            </span>
            <label for="">Hình ảnh trailer 1</label>
            <input type="file" name="hinh_anh_1">
            <span>
                <?php
                echo !empty($_SESSION['trung_img_1']) ? $_SESSION['trung_img_1'] : '';
                unset($_SESSION['trung_img_1']);
                ?>
            </span>
            <label for="">Hình ảnh trailer 2</label>
            <input type="file" name="hinh_anh_2">
            <span>
                <?php
                echo !empty($_SESSION['trung_img_2']) ? $_SESSION['trung_img_2'] : '';
                unset($_SESSION['trung_img_2']);
                ?>
            </span>
            <label for="">Hình ảnh trailer 3</label>
            <input type="file" name="hinh_anh_3">
            <span>
                <?php
                echo !empty($_SESSION['trung_img_3']) ? $_SESSION['trung_img_3'] : '';
                unset($_SESSION['trung_img_3']);
                ?>
            </span>
            <label for="">Xếp hạng</label>
            <input type="text" name="xep_hang" required value="<?php echo !empty($_POST['xep_hang']) ? $_POST['xep_hang'] : '' ?>" placeholder="Nhập vào xếp hạng phim">
            <label for="">Thông tin</label>
            <input type="text" name="thong_tin" required value="<?php echo !empty($_POST['thong_tin']) ? $_POST['thong_tin'] : '' ?>" placeholder=" Nhập vào thông tin phim">
            <label for="">Thể loại</label>
            <input type="text" name="the_loai" required value="<?php echo !empty($_POST['the_loai']) ? $_POST['the_loai'] : '' ?>" placeholder="Nhập vào thể loại phim">
            <label for="">Tóm tắt</label>
            <textarea name="tom_tat" cols="30" rows="10"><?php echo !empty($_POST['tom_tat']) ? $_POST['tom_tat'] : '' ?></textarea>
            <div class="submit">
                <button type="submit">Thêm</button>
                <a href="?module=admin&action=quanlyphim">Quay lại</a>
            </div>
        </form>
    </div>
</main>
<?php
layout('footer_admin');
?>