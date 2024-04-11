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

    $id_khuyen_mai = $_POST['id_khuyen_mai'];
    //$fileToUploadImgDM = $_POST['fileToUploadImgDM'];
    //$normal_img = $_POST['normal_img'];
    $loai_khuyen_mai = $_POST['loai_khuyen_mai'];
    $begin_date = $_POST['begin-date'];
    $end_date = $_POST['end-date'];
    $tieu_de_ct = $_POST['tieu_de_ct'];
    $id_chi_tiet_km = $_POST['id_chi_tiet_km'];
    //$khuyen_mai_so = $_POST['khuyen_mai_so'];
    //$fileToUploadImgCTKM = $_POST['fileToUploadImgCTKM'];
    //$normal_ct_img = $_POST['normal_ct_img'];
    $noidung1 = $_POST['noidung1'];
    $noidung2 = $_POST['noidung2'];
    $noidung3 = $_POST['noidung3'];

    $_SESSION['id_khuyen_mai'] = $_POST['id_khuyen_mai'];
    $_SESSION['normal_img'] = $_POST['normal_img'];
    $_SESSION['loai_khuyen_mai'] = $_POST['loai_khuyen_mai'];
    $_SESSION['begin-date'] = $_POST['begin-date'];
    $_SESSION['end-date'] = $_POST['end-date'];
    $_SESSION['id_chi_tiet_km'] = $_POST['id_chi_tiet_km'];
    $_SESSION['khuyen_mai_so'] = $_POST['khuyen_mai_so'];
    $_SESSION['tieu_de_ct'] = $_POST['tieu_de_ct'];
    $_SESSION['normal_ct_img'] = $_POST['normal_ct_img'];
    $_SESSION['noidung1'] = $_POST['noidung1'];
    $_SESSION['noidung2'] = $_POST['noidung2'];
    $_SESSION['noidung3'] = $_POST['noidung3'];

    $error1 = 0;
    $error2 = 0;
    $error3 = 0;
    $uploadFile1 = 1;
    $uploadFile2 = 1;

    if ($end_date < $begin_date) {
        $error1 = 1;
        $_SESSION['error_date'] = "<span class='error2'>Ngày kết thúc phải lớn hơn ngày bắt đầu ~</span>";
    }

    #KIỂM TRA HÌNH TRONG DANH MỤC KHUYẾN MÃI
    #xử lý vé khuyến mãi bên phần danh mục vào thư mục
    if ($_FILES['fileToUploadImgDM']['name'] != '') {
        $uploadFile1 = 1;

        $image_dmkm_path = basename($_FILES['fileToUploadImgDM']['name']);
        $target_dir = "templates/images/khuyenmai/ve_khuyen_mai/";
        $target_file = $target_dir . $image_dmkm_path;

        #Hình ảnh upload = 1
        $imgFile_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        #Kiem tra neu tep ton tai
        if (file_exists($target_file)) {
            $error2 = 1;
            #hinh anh upload = 0;
            $_SESSION['img_error'] = '<span class="error2">Hình ảnh khuyến mãi đã tồn tại ~!!!~</span>';
        }
    } else {
        $uploadFile1 = 0;
    }

    if ($_FILES['fileToUploadImgCTKM']['name'] != '') {
        $uploadFile2 = 1;

        $image_ctkm_path = basename($_FILES['fileToUploadImgCTKM']['name']);
        $new_target_dir = "templates/images/khuyenmai/chi_tiet_ve_km/";
        $new_target_file = $new_target_dir . $image_ctkm_path;

        #Hình ảnh upload = 1
        $new_imgFile_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        #Kiem tra neu tep ton tai
        if (file_exists($target_file)) {
            $error3 = 1;
            #hinh anh upload = 0;
            $_SESSION['img_error2'] = '<span class="error2">Hình ảnh chi tiết danh mục đã tồn tại ~!!!~</span>';
        }
    } else {
        $uploadFile2 = 0;
    }

    if ($error2 == 1 || $error1 == 1 || $error3 == 1) {
        header('Location: ?module=admin&action=xulyeditkhuyenmai');
    }

    if ($error1 == 0 && $error2 == 0  && $error3 == 0 && $uploadFile1 == 1 && $uploadFile2 == 1) {
        #Lưu ảnh khuyến mãi
        move_uploaded_file($_FILES['fileToUploadImgDM']['tmp_name'], $target_file);

        #Tạo link cho hình khuyến mãi lưu vào database
        $link_img_dmkm = '/images/khuyenmai/ve_khuyen_mai/';
        $img_dmkm_path = $link_img_dmkm . $image_dmkm_path;

        #Lưu ảnh chi ttieets khuyến mãi
        move_uploaded_file($_FILES['fileToUploadImgCTKM']['tmp_name'], $new_target_file);

        #Tạo link cho hình khuyến mãi lưu vào database
        $link_img_ctkm = '/images/khuyenmai/chi_tiet_ve_km/';
        $img_ctkm_path = $link_img_ctkm . $image_ctkm_path;

        #Lưu nếu có thêm thay đổi hình ảnh
        $sql_thaydoi_km = "UPDATE khuyen_mai SET hinh_anh = '" . $img_dmkm_path . "', ngay_bat_dau = '" . $begin_date . "', ngay_ket_thuc = '" . $end_date . "' WHERE id_km = '" . $id_khuyen_mai . "' ";
        $result_new_dmkm = mysqli_query($configDTB, $sql_thaydoi_km);

        $sql_thaydoi_ctkm = "UPDATE chi_tiet_km SET ten_km = '" . $tieu_de_ct . "', hinh_anh = '" . $img_ctkm_path . "', noi_dung1 = '" . $noidung1 . "', noi_dung2 = '" . $noidung2 . "', noi_dung3 = '" . $noidung3 . "' WHERE km_so = '" . $id_khuyen_mai . "' ";
        $result_new_ctkm = mysqli_query($configDTB, $sql_thaydoi_ctkm);

        if ($result_new_dmkm && $result_new_ctkm) {

            unset($_SESSION['id_khuyen_mai']);
            unset($_SESSION['normal_img']);
            unset($_SESSION['loai_khuyen_mai']);
            unset($_SESSION['begin-date']);
            unset($_SESSION['end-date']);
            unset($_SESSION['id_chi_tiet_km']);
            unset($_SESSION['khuyen_mai_so']);
            unset($_SESSION['tieu_de_ct']);
            unset($_SESSION['normal_ct_img']);
            unset($_SESSION['noidung1']);
            unset($_SESSION['noidung2']);
            unset($_SESSION['noidung3']);

            $_SESSION['thay_doi_khuyen_mai'] = "<span class='error1'>Thay đổi khuyến mãi có id là <span style='color: blue;'> $id_khuyen_mai </span> thành công ~!!!~</span>";
            header('Location:  ?module=admin&action=editkhuyenmai');
        }
    }

    if ($uploadFile1 == 0 && $uploadFile2 == 0) {
        if ($error1 == 0) {
            #Lưu nếu không thêm thay đổi hình ảnh
            $sql_thaydoi_km = "UPDATE khuyen_mai SET ngay_bat_dau = '" . $begin_date . "', ngay_ket_thuc = '" . $end_date . "' WHERE id_km = '" . $id_khuyen_mai . "' ";
            $result_new_dmkm = mysqli_query($configDTB, $sql_thaydoi_km);

            $sql_thaydoi_ctkm = "UPDATE chi_tiet_km SET ten_km = '" . $tieu_de_ct . "', noi_dung1 = '" . $noidung1 . "', noi_dung2 = '" . $noidung2 . "', noi_dung3 = '" . $noidung3 . "' WHERE km_so = '" . $id_khuyen_mai . "' ";
            $result_new_ctkm = mysqli_query($configDTB, $sql_thaydoi_ctkm);

            if ($result_new_dmkm && $result_new_ctkm) {

                unset($_SESSION['id_khuyen_mai']);
                unset($_SESSION['normal_img']);
                unset($_SESSION['loai_khuyen_mai']);
                unset($_SESSION['begin-date']);
                unset($_SESSION['end-date']);
                unset($_SESSION['id_chi_tiet_km']);
                unset($_SESSION['khuyen_mai_so']);
                unset($_SESSION['tieu_de_ct']);
                unset($_SESSION['normal_ct_img']);
                unset($_SESSION['noidung1']);
                unset($_SESSION['noidung2']);
                unset($_SESSION['noidung3']);

                $_SESSION['thay_doi_khuyen_mai'] = "<span class='error1'>Thay đổi khuyến mãi có id là <span style='color: blue;'> $id_khuyen_mai </span> thành công ~!!!~</span>";
                header('Location:  ?module=admin&action=editkhuyenmai');
            }
        } else {
            header('Location: ?module=admin&action=xulyeditkhuyenmai');
        }
    }
}
mysqli_close($configDTB);
