<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Nếu không tồn tại loginAdmin thì quay về trang login
if (!checkLogin('login_admin')) {
    header('location: ?module=admin&action=dangnhap');
}

if (!empty($_SERVER["REQUEST_METHOD"] == "POST")) {

    // Nhận giá trị từ $_POST
    $id_phim = $_POST['id_Phim'];
    $ten_phim = $_POST['ten_phim'];
    $ngay_chieu = $_POST['ngay_chieu'];
    $thoi_luong = $_POST['thoi_luong'];
    $hien_trang = $_POST['hien_trang'];
    $xep_hang = $_POST['xep_hang'];
    $thong_tin = $_POST['thong_tin'];
    $the_loai = $_POST['the_loai'];
    $tom_tat = $_POST['tom_tat'];

    //Khởi tạo session lưu giá trị
    $_SESSION['id_phim'] = $_POST['id_Phim'];
    $_SESSION['ten_phim'] = $_POST['ten_phim'];
    $_SESSION['ngay_chieu'] = $_POST['ngay_chieu'];
    $_SESSION['hinh_anh'] = $_POST['hinh_anh'];
    $_SESSION['thoi_luong'] = $_POST['thoi_luong'];
    $_SESSION['hien_trang'] = $_POST['hien_trang'];
    $_SESSION['xep_hang'] = $_POST['xep_hang'];
    $_SESSION['thong_tin'] = $_POST['thong_tin'];
    $_SESSION['the_loai'] = $_POST['the_loai'];
    $_SESSION['tom_tat'] = $_POST['tom_tat'];
    $_SESSION['video_trailer'] = $_POST['video'];
    $_SESSION['img_trailer_1'] = $_POST['img_trailer_1'];
    $_SESSION['img_trailer_2'] = $_POST['img_trailer_2'];
    $_SESSION['img_trailer_3'] = $_POST['img_trailer_3'];

    $upload_error_img = 1;
    $upload_error_img1 = 1;
    $upload_error_img2 = 1;
    $upload_error_img3 = 1;
    $upload_error_video = 1;

    $upload_fie = 1;
    //Xử lý lưu ảnh nếu $_FILES['update_img']['name'] != null
    if ($_FILES['update_img']['name'] != '') {

        // Xử lý lưu ảnh vào thư mục
        $hinhanhpath = basename($_FILES["update_img"]["name"]);
        $target_dir = "templates/images/phim/";
        $target_file = $target_dir . $hinhanhpath;

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (file_exists($target_file)) {
            $upload_error_img = 0;
            $_SESSION['error_img'] = "Hình ảnh đã tồn tại!";
        }
    } else {
        $upload_fie = 0;
    }

    if ($_FILES['update_video']['name'] != '') {

        // Xử lý lưu video vào thư mục
        $video_path = basename($_FILES["update_video"]["name"]);
        $target_dir = "templates/video/";
        $target_file_2 = $target_dir . $video_path;

        $imageFileType = strtolower(pathinfo($target_file_2, PATHINFO_EXTENSION));

        //Kiểm tra trùng video 
        if (file_exists($target_file_2)) {
            $upload_error_video = 0;
            $_SESSION['error_video'] = "Video trailer đã tồn tại!";
        }
    } else {
        $upload_fie = 0;
    }

    if ($_FILES['update_img_1']['name'] != '') {

        $img_path_1 = basename($_FILES["update_img_1"]["name"]);
        $target_dir = "templates/images/trailer/";
        $target_file_3 = $target_dir . $img_path_1;

        $imageFileType = strtolower(pathinfo($target_file_3, PATHINFO_EXTENSION));

        //Kiểm tra trùng img_trailer_1 
        if (file_exists($target_file_3)) {
            $upload_error_img1 = 0;
            $_SESSION['error_img_1'] = "Hình ảnh trailer 1 đã tồn tại!";
        }
    } else {
        $upload_fie = 0;
    }

    if ($_FILES['update_img_2']['name'] != '') {

        $img_path_2 = basename($_FILES["update_img_2"]["name"]);
        $target_dir = "templates/images/trailer/";
        $target_file_4 = $target_dir . $img_path_2;

        $imageFileType = strtolower(pathinfo($target_file_4, PATHINFO_EXTENSION));

        //Kiểm tra trùng img_trailer_1 
        if (file_exists($target_file_4)) {
            $upload_error_img2 = 0;
            $_SESSION['error_img_2'] = "Hình ảnh trailer 2 đã tồn tại!";
        }
    } else {
        $upload_fie = 0;
    }

    if ($_FILES['update_img_3']['name'] != '') {

        $img_path_3 = basename($_FILES["update_img_3"]["name"]);
        $target_dir = "templates/images/trailer/";
        $target_file_5 = $target_dir . $img_path_3;

        $imageFileType = strtolower(pathinfo($target_file_5, PATHINFO_EXTENSION));

        //Kiểm tra trùng img_trailer_1 
        if (file_exists($target_file_5)) {
            $upload_error_img3 = 0;
            $_SESSION['error_img_3'] = "Hình ảnh trailer 3 đã tồn tại!";
        }
    } else {
        $upload_fie = 0;
    }

    if ($upload_error_img == 1 && $upload_error_video == 1 && $upload_error_img1 == 1 && $upload_error_img2 == 1 && $upload_error_img3 == 1) {
        if ($_FILES['update_img']['name'] != '') {
            move_uploaded_file($_FILES["update_img"]["tmp_name"], $target_file);
            // Tạo link để lưu vào database
            $link_img = "/images/phim/";
            $hinh_anh_path = $link_img . $hinhanhpath;

            // Cập nhật phim
            $sql_file = "UPDATE danh_sach_phim SET hinh_anh = '$hinh_anh_path' WHERE id_phim = $id_phim ";
            $ket_qua_file = mysqli_query($configDTB, $sql_file);

            $sql_file = "UPDATE chi_tiet_phim SET hinh_anh = '$hinh_anh_path' WHERE id_phim = $id_phim ";
            $ket_qua_file = mysqli_query($configDTB, $sql_file);
        }

        if ($_FILES['update_video']['name'] != '') {
            move_uploaded_file($_FILES["update_video"]["tmp_name"], $target_file_2);
            // Cập nhật phim
            $sql_file = "UPDATE chi_tiet_phim SET video_trailer = '$video_path' WHERE id_phim = $id_phim ";
            $ket_qua_file = mysqli_query($configDTB, $sql_file);
        }

        if ($_FILES['update_img_1']['name'] != '') {
            move_uploaded_file($_FILES["update_img_1"]["tmp_name"], $target_file_3);
            // Cập nhật phim
            $sql_file = "UPDATE chi_tiet_phim SET img_trailer_1 = '$img_path_1' WHERE id_phim = $id_phim ";
            $ket_qua_file = mysqli_query($configDTB, $sql_file);
        }

        if ($_FILES['update_img_2']['name'] != '') {
            move_uploaded_file($_FILES["update_img_2"]["tmp_name"], $target_file_4);
            // Cập nhật phim
            $sql_file = "UPDATE chi_tiet_phim SET img_trailer_2 = '$img_path_2' WHERE id_phim = $id_phim ";
            $ket_qua_file = mysqli_query($configDTB, $sql_file);
        }

        if ($_FILES['update_img_3']['name'] != '') {
            move_uploaded_file($_FILES["update_img_3"]["tmp_name"], $target_file_5);
            // Cập nhật phim
            $sql_file = "UPDATE chi_tiet_phim SET img_trailer_3 = '$img_path_3' WHERE id_phim = $id_phim ";
            $ket_qua_file = mysqli_query($configDTB, $sql_file);
        }
        //Cập nhật phim
        $sql_phim = "UPDATE danh_sach_phim SET ten_phim = '$ten_phim', ngay_chieu = '$ngay_chieu', thoi_luong = '$thoi_luong', hien_trang = '$hien_trang' WHERE id_phim = '$id_phim' ";
        $ket_qua_phim = mysqli_query($configDTB, $sql_phim);

        // //Cập nhật chi tiết
        // $sql_chi_tiet = "UPDATE chi_tiet_phim SET ten_phim = '$ten_phim',xep_hang = '$xep_hang', ngay_chieu = '$ngay_chieu', thong_tin = '$thong_tin', the_loai = '$the_loai', tom_tat = '$tom_tat' WHERE id_phim = '$id_phim' ";
        // $ket_qua_chi_tiet = mysqli_query($configDTB, $sql_chi_tiet);

        $sql_chi_tiet = "UPDATE chi_tiet_phim SET ten_phim = '$ten_phim', xep_hang = '$xep_hang', ngay_chieu = '$ngay_chieu', thong_tin = '$thong_tin', the_loai = '$the_loai', tom_tat = '$tom_tat' WHERE id_phim = '$id_phim'";
        $ket_qua_chi_tiet = mysqli_query($configDTB, $sql_chi_tiet);
        // var_dump($ket_qua_chi_tiet);

        if ($ket_qua_phim && $ket_qua_chi_tiet) {

            //Xóa session
            unset($_SESSION['id_phim']);
            unset($_SESSION['ten_phim']);
            unset($_SESSION['ngay_chieu']);
            unset($_SESSION['hinh_anh']);
            unset($_SESSION['thoi_luong']);
            unset($_SESSION['hien_trang']);
            unset($_SESSION['xep_hang']);
            unset($_SESSION['thong_tin']);
            unset($_SESSION['the_loai']);
            unset($_SESSION['tom_tat']);
            unset($_SESSION['video_trailer']);
            unset($_SESSION['img_trailer_1']);
            unset($_SESSION['img_trailer_2']);
            unset($_SESSION['img_trailer_3']);

            $_SESSION['update_thanh_cong_phim'] = "Cập nhật phim <span style='color:blue;'> $ten_phim </span> thành công!";
            header('location: ?module=admin&action=editphim');
        }
    }

    //Nếu lỗi thì quay về trang cập nhật
    if ($upload_error_img == 0 || $upload_error_video == 0 || $upload_error_img1 == 0 || $upload_error_img2 == 0 || $upload_error_img3 == 0) {
        header('location: ?module=admin&action=xulyeditphim');
    } else {
        if ($upload_fie = 0) {
            //Cập nhật phim
            $sql_phim = "UPDATE danh_sach_phim SET ten_phim = '$ten_phim', ngay_chieu = '$ngay_chieu', thoi_luong = '$thoi_luong', hien_trang = '$hien_trang' WHERE id_phim = $id_phim ";
            $ket_qua_phim = mysqli_query($configDTB, $sql_phim);

            //Cập nhật chi tiết

            $sql_chi_tiet = "UPDATE chi_tiet_phim SET ten_phim = '$ten_phim', xep_hang = '$xep_hang', ngay_chieu = '$ngay_chieu', thong_tin = '$thong_tin', the_loai = '$the_loai', tom_tat = '$tom_tat' WHERE id_phim = '$id_phim'";
            $ket_qua_chi_tiet = mysqli_query($configDTB, $sql_chi_tiet);

            if ($ket_qua_phim && $ket_qua_chi_tiet) {

                //Xóa session
                unset($_SESSION['id_phim']);
                unset($_SESSION['ten_phim']);
                unset($_SESSION['ngay_chieu']);
                unset($_SESSION['hinh_anh']);
                unset($_SESSION['thoi_luong']);
                unset($_SESSION['hien_trang']);
                unset($_SESSION['xep_hang']);
                unset($_SESSION['thong_tin']);
                unset($_SESSION['the_loai']);
                unset($_SESSION['tom_tat']);
                unset($_SESSION['video_trailer']);
                unset($_SESSION['img_trailer_1']);
                unset($_SESSION['img_trailer_2']);
                unset($_SESSION['img_trailer_3']);

                $_SESSION['update_thanh_cong_phim'] = "Cập nhật phim <span style='color:blue;'> $ten_phim </span> thành công!";
                header('location: ?module=admin&action=editphim');
            }
        }
    }
} else {
    header('location: ?module=admin&action=editphim');
}
mysqli_close($configDTB);
