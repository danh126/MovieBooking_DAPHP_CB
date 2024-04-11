<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Thay đổi tên trang tương ứng
$data = [
    'ten_trang' => 'Cập nhật thông tin | Movie Booking'
];
layout('header', $data);

//Gọi hàm kiểm tra đăng nhập
if (checkLogin('login_user')) {

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $id_user = $_SESSION['id_user'];
        $ten_user = $_POST['ten_user'];
        $email = $_POST['email_user'];
        $phone_user = $_POST['phone_user'];
        $dia_chi_user = $_POST['dia_chi_user'];
        $pass_user = $_POST['pass_user'];
        $xac_nhan_pass = $_POST['xac_nhan_pass_user'];

        $error = []; // mảng chứa lỗi

        //Kiểm tra độ dài tên tài khoản

        if (!empty($_POST["ten_user"])) {
            if (strlen($_POST['ten_user']) < 6) {
                $error['error_ten_user'] = "<span style='color:red;'>Độ dài tên tài khoản phải trên 6 ký tự!</span>";
            }
        }

        // Kiểm tra trùng email

        if (!empty($_POST['email_user'])) {
            $sql = "SELECT email FROM login_user WHERE id_user = '$id_user'";
            $ket_qua = mysqli_query($configDTB, $sql);
            $row = mysqli_fetch_array($ket_qua);

            $email_user = $row['email'];
            if ($email_user != ($_POST['email_user'])) {
                $sql = "SELECT email FROM login_user WHERE email = '" . $_POST['email_user'] . "'";
                $ket_qua = mysqli_query($configDTB, $sql);
                if (mysqli_num_rows($ket_qua) > 0) {
                    $error['trung_email'] = "Email đã tồn tại!";
                }
            }
        }

        // Kiểm tra định dạng phone

        if (!empty($_POST['phone_user'])) {
            if (strlen($_POST['phone_user']) < 10 || strlen($_POST['phone_user']) > 10) {
                $error['error_phone'] = "Vui lòng nhập đúng định dạng số điện thoại!";
            }
        }

        // Khi người dùng cập nhật mật khẩu mới

        if ($_POST['pass_user'] != '' && $_POST['xac_nhan_pass_user']) {

            //Kiểm tra độ dài mật khẩu

            if (!empty($_POST["pass_user"])) {
                if (strlen($_POST['pass_user']) < 8) {
                    $error['error_pass'] = "<span style='color:red;'>Độ dài mật khẩu phải trên 8 ký tự!</span>";
                }
            }

            // Kiểm tra xác nhận mật khẩu

            if (!empty($_POST["xac_nhan_pass_user"])) {
                if (($_POST['pass_user']) != ($_POST['xac_nhan_pass_user'])) {
                    $error['xac_nhan_pass_user'] = "<span style='color:red;'>Mật khẩu nhập lại không đúng!</span>";
                }
            }

            // Nếu không có lỗi thì update

            if (count($error) == 0) {
                //Mã hóa password
                $mh_pass = password_hash($_POST["xac_nhan_pass_user"], PASSWORD_DEFAULT);
                $sql = "UPDATE login_user SET ho_ten = '" . $ten_user . "', email = '" . $email . "', so_dien_thoai_user = '" . $phone_user . "' ,
                mat_khau = '" . $mh_pass . "',dia_chi_user = '" . $dia_chi_user . "' WHERE id_user = " . $id_user . "";
                $ket_qua = mysqli_query($configDTB, $sql);
                if ($ket_qua) {
                    $_SESSION['cap_nhat_user'] = "Cập nhật thông tin thành công!";
                    header('Location: ?moduel=home&action=thongtin');
                }
            }
        }

        // Khi người dùng không cập nhật mật khẩu mới 

        // Nếu không có lỗi thì update

        if (count($error) == 0) {
            $sql = "UPDATE login_user SET ho_ten = '" . $ten_user . "', email = '" . $email . "', so_dien_thoai_user = '" . $phone_user . "' ,
            dia_chi_user = '" . $dia_chi_user . "' WHERE id_user = " . $id_user . "";
            $ket_qua = mysqli_query($configDTB, $sql);
            if ($ket_qua) {
                $_SESSION['cap_nhat_user'] = "Cập nhật thông tin thành công!";
                header('Location: ?moduel=home&action=thongtin');
            }
        }
    }
?>
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/thongtin.css?ver=<?php echo rand(); ?>">
    <div class="cap_nhat_tt">
        <h2>Cập nhật thông tin</h2>
        <?php
        if (!empty($_SESSION['id_user'])) {
            $id_user = $_SESSION['id_user'];
            $sql = "SELECT * FROM login_user WHERE id_user = '$id_user'";
            $ket_qua = mysqli_query($configDTB, $sql);
            $row = mysqli_fetch_array($ket_qua);
        }
        ?>
        <form action="" method="post">
            <div class="form">
                <div class="rol-6">
                    <label for="">Tên tài khoản</label>
                    <input type="text" name="ten_user" value="<?php echo !empty($_POST['ten_user']) ? $_POST['ten_user']  : $row['ho_ten'] ?>">
                    <span style="color:red; font-size: 16px;" id="error3" class="error">
                        <?php
                        if (isset($error['error_ten_user'])) {
                            echo $error['error_ten_user'];
                        }
                        ?>
                    </span>
                    <label for="">Email</label>
                    <input type="email" name="email_user" value="<?php echo !empty($_POST['email_user']) ? $_POST['email_user'] : $row['email'] ?>">
                    <span style="color:red; font-size: 16px;" id="error3" class="error">
                        <?php
                        if (isset($error['trung_email'])) {
                            echo $error['trung_email'];
                        }
                        ?>
                    </span>
                    <label for="">Số điện thoại</label>
                    <input type="text" name="phone_user" value="<?php echo !empty($_POST['phone_user']) ? $_POST['phone_user'] : $row['so_dien_thoai_user'] ?>" placeholder="Nhập số điện thoại">
                    <span style="color:red; font-size: 16px;" id="error3" class="error">
                        <?php
                        if (isset($error['error_phone'])) {
                            echo $error['error_phone'];
                        }
                        ?>
                    </span>
                </div>
                <div class="rol-6">
                    <label for="">Địa chỉ</label>
                    <input type="text" name="dia_chi_user" value="<?php echo !empty($_POST['dia_chi_user']) ? $_POST['dia_chi_user']  : $row['dia_chi_user'] ?>" placeholder="Nhập địa chỉ">
                    <span style="color:red; font-size: 16px;" id="error3" class="error">
                        <?php
                        // if (isset($error['xac_nhan_pass_user'])) {
                        //     echo $error['xac_nhan_pass_user'];
                        // }
                        ?>
                    </span>
                    <label for="">Mật khẩu</label>
                    <input type="password" name="pass_user" value="" placeholder="Thay đổi mật khẩu mới hoặc giữ nguyên">
                    <span style="color:red; font-size: 16px;" id="error3" class="error">
                        <?php
                        if (isset($error['error_pass'])) {
                            echo $error['error_pass'];
                        }
                        ?>
                    </span>
                    <label for="">Xác nhận mật khẩu</label>
                    <input type="phone" name="xac_nhan_pass_user" value="" placeholder="Xác nhận mật khẩu mới">
                    <span style="color:red; font-size: 16px;" id="error3" class="error">
                        <?php
                        if (isset($error['xac_nhan_pass_user'])) {
                            echo $error['xac_nhan_pass_user'];
                        }
                        ?>
                    </span>
                </div>
            </div>
            <div class="tac_vu">
                <button>Cập nhật</button>
                <a href="?moduel=home&action=thongtin">Quay lại</a>
            </div>
        </form>
    </div>

<?php
    mysqli_close($configDTB); //đóng database
} // KT login
else {
    header('location: ?module=home&action=dangnhap');
}
layout('footer');
?>