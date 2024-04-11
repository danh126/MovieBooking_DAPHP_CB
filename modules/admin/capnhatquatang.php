<?php
if (!empty($_SERVER["REQUEST_METHOD"] == "POST")) {

    $id_quatang = $_POST['id_quatang'];
    $tensp = $_POST['name'];
    $hsd = $_POST['hsd'];
    $giaban = $_POST['giaban'];
    $loaiquatang = $_POST['loaiquatang'];
    $nd_hinhanh = $_POST['nd_hinhanh'];
    $SL_muatoithieu = $_POST['SL_muatoithieu'];
    $hdsd = $_POST['hdsd'];
    $ct_sp = $_POST['ct_sp'];


    $_SESSION['id_quatang'] = $id_quatang;
    $_SESSION['tensp'] = $tensp;
    $_SESSION['hsd'] = $hsd;
    $_SESSION['giaban'] = $giaban;
    $_SESSION['loaiquatang'] = $loaiquatang;
    $_SESSION['nd_hinhanh'] = $nd_hinhanh;
    $_SESSION['SL_muatoithieu'] = $SL_muatoithieu;
    $_SESSION['hdsd'] = $hdsd;
    $_SESSION['ct_sp'] = $ct_sp;

    $error = 0;
    $uploadfile = 1;

    if ($_FILES['hinhanh']['name'] != '') {

        $uploadfile = 1;
        //xử lý lưu ảnh vào thư mục
        $hinhanhthumuc = basename($_FILES["hinhanh"]["name"]); //lấy đường dẫn tải lên
        $target_dir = "templates/images/quatang/"; //lấy đường dẫn trong thư mục suorcode của mình vd trong shop qua tang co img
        $target_file = $target_dir . $hinhanhthumuc;
        // up load = 1;
        $imagefile = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        //kiểm tra nếu tệp đã tồn tại
        if (file_exists($target_file)) { //gán biến để xem hình ảnh có tồn tại chưa
            $error = 1;
            $_SESSION['ERROR_img'] = "Hình ảnh đã tồn tại !";
        }
    } else {
        $uploadfile = 0;
    }

    if ($error == 1) {
        header('Location: ?module=admin&action=xulyeditquatang');
    } else {
        if ($error == 0 && $uploadfile == 1) {
            move_uploaded_file($_FILES["hinhanh"]["tmp_name"], $target_file);

            $link_img = "/";
            $hinhanh_path = $link_img . $hinhanhthumuc;

            $sql = "UPDATE quatang SET hinhanh = '" . $hinhanh_path . "', tensp = '" . $tensp . "', hsd = '" . $hsd . "',
            giaban = '" . $giaban . "' WHERE id_quatang = " . $id_quatang . "";

            $ket_qua_1 = mysqli_query($configDTB, $sql);

            $sql_2 = "UPDATE chitietquatang SET hinhanh = '" . $hinhanh_path . "', tensp = '" . $tensp . "', giaban = '" . $giaban . "',
            noidunghinhanh = '" . $nd_hinhanh . "', SLmuatoithieu = '" . $SL_muatoithieu . "', hsd = '" . $hsd . "',
            hdsd = '" . $hdsd . "', chitietsp = '" . $ct_sp . "' WHERE id_quatang = " . $id_quatang . "";

            $ket_qua_2 = mysqli_query($configDTB, $sql_2);

            if ($ket_qua_1 && $ket_qua_2) {

                unset($_SESSION['id_quatang']);
                unset($_SESSION['tensp']);
                unset($_SESSION['hsd']);
                unset($_SESSION['giaban']);
                unset($_SESSION['loaiquatang']);
                unset($_SESSION['nd_hinhanh']);
                unset($_SESSION['SL_muatoithieu']);
                unset($_SESSION['hdsd']);
                unset($_SESSION['ct_sp']);


                $_SESSION['thay_doi_qua_tang'] = "<span class='error1'>Thay đổi quà tặng có id là <span style='color: blue;'> $id_quatang </span> thành công!</span>";
                header('Location:  ?module=admin&action=editquatang');
            }
        }
    }

    if ($uploadfile == 0) {
        $sql = "UPDATE quatang SET tensp = '" . $tensp . "', hsd = '" . $hsd . "',
        giaban = '" . $giaban . "' WHERE id_quatang = " . $id_quatang . "";

        $ket_qua_1 = mysqli_query($configDTB, $sql);

        $sql_2 = "UPDATE chitietquatang SET tensp = '" . $tensp . "', giaban = '" . $giaban . "',
        noidunghinhanh = '" . $nd_hinhanh . "', SLmuatoithieu = '" . $SL_muatoithieu . "', hsd = '" . $hsd . "',
        hdsd = '" . $hdsd . "', chitietsp = '" . $ct_sp . "' WHERE id_quatang = " . $id_quatang . "";

        $ket_qua_2 = mysqli_query($configDTB, $sql_2);

        if ($ket_qua_1 && $ket_qua_2) {

            unset($_SESSION['id_quatang']);
            unset($_SESSION['tensp']);
            unset($_SESSION['hsd']);
            unset($_SESSION['giaban']);
            unset($_SESSION['loaiquatang']);
            unset($_SESSION['nd_hinhanh']);
            unset($_SESSION['SL_muatoithieu']);
            unset($_SESSION['hdsd']);
            unset($_SESSION['ct_sp']);

            $_SESSION['thay_doi_qua_tang'] = "<span class='error1'>Thay đổi quà tặng có id là <span style='color: blue;'> $id_quatang </span> thành công!</span>";
            header('Location:  ?module=admin&action=editquatang');
        }
    }
}
