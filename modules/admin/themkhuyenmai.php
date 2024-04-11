<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Thay đổi tên trang tương ứng
$data = [
    'ten_trang' => 'Thêm khuyến mãi | Movie Booking'
];
layout('header_admin', $data);

//Nếu không tồn tại loginAdmin thì quay về trang login
if (!checkLogin('login_admin')) {
    header('location: ?module=admin&action=dangnhap');
}

//Xử lý thêm phim mới 

if (!empty($_SERVER["REQUEST_METHOD"] == "POST")) {
    #Định danh lại tên
    $ngay_bd_sk = $_POST['begin-date'];
    $ngay_kt_sk = $_POST['end-date'];
    $loai_khuyen_mai = $_POST['chon_dm_km'];
    $tieu_de_ct = $_POST['tieu_de_ct'];
    $noidung1 = $_POST['noidung1'];
    $noidung2 = $_POST['noidung2'];
    $noidung3 = $_POST['noidung3'];

    if ($ngay_kt_sk < $ngay_bd_sk) {
        $error3 = 1;
        $_SESSION['error_date'] = "<sapn class='error2'>Ngày kết thúc phải lớn hơn ngày bắt đầu!</span>";
        #echo 'Khong hop le';
    } else {
        $error3 = 0;
    }

    if ($_FILES['fileToUploadImgDM']['name'] != '' && $_FILES['fileToUploadImgCTKM']['name'] != '') {
        #KIỂM TRA HÌNH TRONG DANH MỤC KHUYẾN MÃI
        #xử lý vé khuyến mãi bên phần danh mục vào thư mục
        $image_dmkm_path = basename($_FILES['fileToUploadImgDM']['name']);
        $target_dir = "templates/images/khuyenmai/ve_khuyen_mai/";
        $target_file = $target_dir . $image_dmkm_path;

        #Hình ảnh upload = 1
        $imgFile_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        #Kiem tra neu tep ton tai
        if (file_exists($target_file)) {
            $error = 1;
            #hinh anh upload = 0;
            $_SESSION['img_error'] = '<span class="error2">Hình ảnh đã tồn tại!</span>';
        } else {
            $error = 0;
        }
        if ($error == 0) {
            #Lưu ảnh
            move_uploaded_file($_FILES['fileToUploadImgDM']['tmp_name'], $target_file);

            #Tạo link cho hình khuyến mãi lưu vào database
            $link_img_dmkm = '/images/khuyenmai/ve_khuyen_mai/';
            $img_dmkm_path = $link_img_dmkm . $image_dmkm_path;
        }
        #KẾT THÚC kiểm tra hình ảnh danh mục khuyến mãi

        #KIỂM TRA HÌNH TRONG CHI TIẾT KHUYẾN MÃI
        #xử lý hình bên chi tiêt khuyến mãi vào thư mục
        $image_ctkm_path = basename($_FILES['fileToUploadImgCTKM']['name']);
        $new_target_dir = "templates/images/khuyenmai/chi_tiet_ve_km/";
        $new_target_file = $new_target_dir . $image_ctkm_path;

        #Hình ảnh upload = 1
        $new_imgFile_type = strtolower(pathinfo($new_target_file, PATHINFO_EXTENSION));

        if (file_exists($new_target_file)) {
            $error2 = 1;
            #hinh anh upload = 0;
            $_SESSION['img_error2'] = '<span class="error2">Hình ảnh đã tồn tại!</span>';
        } else {
            $error2 = 0;
        }
        if ($error2 == 0) {
            #Lưu ảnh
            move_uploaded_file($_FILES['fileToUploadImgCTKM']['tmp_name'], $new_target_file);

            #Tạo link cho hình khuyến mãi lưu vào database
            $link_img_ctkm = '/images/khuyenmai/chi_tiet_ve_km/';
            $img_ctkm_path = $link_img_ctkm . $image_ctkm_path;
        }
        #KẾT THÚC kiểm tra hình ảnh chi tiết khuyến mãi
    }

    if ($error == 0 && $error3 == 0 && $error2 == 0) {
        #Tao id tu dong cho ve danh muc khuyen mai
        $sql_dmkm_id = "SELECT max(id_km) AS id_km_max FROM khuyen_mai";
        $result_km_id = mysqli_query($configDTB, $sql_dmkm_id);
        $rows = mysqli_fetch_array($result_km_id);
        $id_km_max = $rows['id_km_max'] + 1;

        $sql_ctkm_id = "SELECT max(id_ct_km) AS id_ctkm_max FROM chi_tiet_km";
        $result_ctkm_id = mysqli_query($configDTB, $sql_ctkm_id);
        $rowss = mysqli_fetch_array($result_ctkm_id);
        $id_ctkm_max = $rowss['id_ctkm_max'] + 1;

        #Dua du lieu vao database sau khi qua xu ly anh
        $sql_dmkm = "INSERT INTO khuyen_mai(id_km, loai_khuyen_mai, hinh_anh, ngay_bat_dau, ngay_ket_thuc) VALUES ('" . $id_km_max . "', '" . $loai_khuyen_mai . "', '" . $img_dmkm_path . "', '" . $ngay_bd_sk . "', '" . $ngay_kt_sk . "')";
        $result_new_dmkm = mysqli_query($configDTB, $sql_dmkm);

        $sql_ctkm = "INSERT INTO chi_tiet_km(id_ct_km, km_so, ten_km, hinh_anh, noi_dung1, noi_dung2, noi_dung3) VALUES ('" . $id_ctkm_max . "', '" . $id_km_max . "', '" . $tieu_de_ct . "', '" . $img_ctkm_path . "', '" . $noidung1 . "', '" . $noidung2 . "', '" . $noidung3 . "' )";
        $result_new_ctkm = mysqli_query($configDTB, $sql_ctkm);

        if ($result_new_dmkm && $result_new_ctkm) {
            $_SESSION['them_ve_dm_km'] = '<span class="error1">Thêm khuyến mãi thành công!</span>';
        }
    }
}
?>
<main>
    <div class="add_phim">
        <p>Thêm khuyến mãi</p>
        <span class="thong_bao">
            <?php
            echo !empty($_SESSION['them_ve_dm_km']) ?  $_SESSION['them_ve_dm_km'] : '';
            unset($_SESSION['them_ve_dm_km']);
            ?>
        </span>
        <form action="" method="post" enctype="multipart/form-data">

            <!--PHAN KHUYEN MAI-->

            <label id='' for='' class='labelDMKM'>Loại khuyến mãi</label>
            <!--<input id='' name='chon_dm_km' type='text' class='inputDMKM' placeholder='' required></br>-->

            <?php
            $sql = "SELECT * FROM danh_muc_km LIMIT 0,2";
            $result = mysqli_query($configDTB, $sql);
            ?>
            <select name='chon_dm_km' class='inputDMKM' required>
                <?php
                while ($row = mysqli_fetch_array($result)) {
                ?>
                    <option value="<?php echo $row['id_danh_muc'] ?>" placeholder='Chọn danh mục'><?php echo $row['danh_muc'] ?></option>
                <?php
                }
                ?>
            </select>

            <label id='' for='' class='labelDMKM'>Hình ảnh khuyến mãi (200x100)</label>
            <input type="file" name="fileToUploadImgDM" id="fileToUpload" class='inputDMKM' required>
            <span>
                <?php
                if (isset($_SESSION['img_error'])) {
                    echo $_SESSION["img_error"];
                    unset($_SESSION["img_error"]);
                }
                ?>
            </span>

            <label id='' for='' class='labelDMKM'>Ngày bắt đầu khuyến mãi</label>
            <input id='' name='begin-date' type='date' value="<?php echo !empty($_POST['begin-date']) ? $_POST['begin-date'] : '' ?>" class='inputDMKM' placeholder='Thời gian diễn ra sự kiện' required>

            <label id='' for='' class='labelDMKM'>Ngày kết thúc khuyến mãi</label>
            <input id='' name='end-date' type='date' value="<?php echo !empty($_POST['end-date']) ? $_POST['end-date'] : '' ?>" class='inputDMKM' placeholder='Thời gian diễn ra sự kiện' required>
            <span>
                <?php
                if (isset($_SESSION['error_date'])) {
                    echo $_SESSION['error_date'];
                    unset($_SESSION['error_date']);
                }
                ?>
            </span>

            <!-- PHAN CHI TIET KHUYEN MAI -->

            <label id='' for='' class='labelDMKM'>Tiêu đề chính</label>
            <input id='' name='tieu_de_ct' type='text' value="<?php echo !empty($_POST['tieu_de_ct']) ? $_POST['tieu_de_ct'] : '' ?>" class='inputDMKM' placeholder='Nhập tiêu đề' required>

            <label id='' for='' class='labelDMKM'>Hình ảnh chi tiết khuyến mãi (900x400)</label>
            <input type="file" name="fileToUploadImgCTKM" id="fileToUpload" class='inputDMKM' required>
            <span>
                <?php
                if (isset($_SESSION['img_error2'])) {
                    echo $_SESSION["img_error2"];
                    unset($_SESSION["img_error2"]);
                }
                ?>
            </span>

            <label id='' for='' class='labelDMKM'>Nội dung 1 (Tiêu đề cho nội dung)</label>
            <input id='' name='noidung1' type='text' value="<?php echo !empty($_POST['noidung1']) ? $_POST['noidung1'] : '' ?>" class='inputDMKM' placeholder='Tiêu đề cho nội dung' required>

            <label id='' for='' class='labelDMKM'>Nội dung 2 (Nội dung chi tiết)</label>
            <input id='' name='noidung2' type='text' value="<?php echo !empty($_POST['noidung2']) ? $_POST['noidung2'] : '' ?>" class='inputDMKM' placeholder='Nội dung chi tiết' required>

            <label id='' for='' class='labelDMKM'>Nội dung 3 (Kết thúc nội dung)</label>
            <input id='' name='noidung3' type='text' value="<?php echo !empty($_POST['noidung3']) ? $_POST['noidung3'] : '' ?>" class='inputDMKM' placeholder='Kết thúc nội dung' required>
            <div class="submit">
                <button type="submit">Thêm</button>
                <a href="?module=admin&action=quanlykhuyenmai">Quay lại</a>
            </div>
        </form>
    </div>
</main>
<?php
layout('footer_admin');
?>