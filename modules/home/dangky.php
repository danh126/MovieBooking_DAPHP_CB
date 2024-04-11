<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Thay đổi tên trang tương ứng
$data = [
    'ten_trang' => 'Đăng ký tài khoản | Movie Booking'
];
layout('header', $data);

//Gọi hàm kiểm tra đăng nhập
if (checkLogin('login_user')) {
    header('location: ?module=home&action=trangchu');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //Kiểm tra giá trị rỗng
    if ($_POST['fullname'] != '' && $_POST['email'] != '' && $_POST['gender'] != '' && $_POST['pass'] != '' && $_POST["pass_comfirm"] != '') {
        $error = []; // mảng chứa lỗi

        // Validate name: kiểm tra tên: không để trống, đư ít nhất 6 ký tự
        if (!empty($_POST["fullname"])) {
            if (strlen($_POST['fullname']) < 6) {
                $error['error_fullname'] = "<span style='color:red;'>Độ dài họ và tên phải trên 6 ký tự!</span>";
            }
        }

        // Validate pass (password): kiểm tra mật khẩu: không để trống, đủ ít nhất 8 ký tự
        if (!empty($_POST["pass"])) {
            if (strlen($_POST['pass']) < 8) {
                $error['error_pass'] = "<span style='color:red;'>Độ dài mật khẩu phải trên 8 ký tự!</span>";
            }
        }

        // Validate rewrite-pass (password): kiểm tra lại mật khẩu: không để trống, kiểm tra lại có trùng với mật khẩu bên trên đã nhập (nhập lại mật khẩu trên)
        if (!empty($_POST["pass_comfirm"])) {
            if (($_POST['pass']) != ($_POST['pass_comfirm'])) {
                $error['error_pass_comfirm'] = "<span style='color:red;'>Mật khẩu nhập lại không đúng!</span>";
            }
        }

        // Validate email: kiểm tra email: không để trống, tránh trùng lặp email trong database
        if (!empty($_POST["email"])) {
            //Chọn email trong database để kiểm tra email.
            $sql = "SELECT email FROM login_user WHERE email = '" . $_POST['email'] . "' ";

            $ketqua = mysqli_query($configDTB, $sql);
            $result = mysqli_num_rows($ketqua);

            if ($result > 0) {
                $error['error_email'] = "<span style='color:red;'>Email đã tồn tại. Vui lòng nhập lại email khác!</span>";
            }
        }

        //Mã hóa password
        $mh_pass = password_hash($_POST["pass_comfirm"], PASSWORD_DEFAULT);

        if (count($error) == 0) {

            //Tao id tu dong 
            $sql = "SELECT max(id_user) AS id_max FROM login_user";
            $ket_qua = mysqli_query($configDTB, $sql);
            $rows = mysqli_fetch_array($ket_qua);
            $id_max = $rows['id_max'] + 1;

            $sql = "INSERT INTO login_user (id_user, ho_ten, email, gioi_tinh, mat_khau) VALUES (" . $id_max . ",'" . $_POST['fullname'] . "','" . $_POST['email'] . "','" . $_POST['gender'] . "','" . $mh_pass . "') ";
            $result2 = mysqli_query($configDTB, $sql);
            if ($result2) {
                $_SESSION['dang_ky_thanh_cong'] = "Đăng ký thành công!";
            }
        }
    } else {
        $_SESSION['error_null'] = "Vui lòng nhập đầy đủ thông tin!";
    }
}
?>
<link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/auth.css?ver=<?php echo rand(); ?>">
<div class="auth_form">
    <div class="auth">
        <div class="dangky">
            <form action="" method="post">
                <h1>Đăng ký tài khoản </h1>
                <?php
                if (isset($_SESSION['dang_ky_thanh_cong'])) {
                    echo "<p class='thong_bao'>" . $_SESSION['dang_ky_thanh_cong'] . "</p>";
                    unset($_SESSION['dang_ky_thanh_cong']);
                }
                if (isset($_SESSION['error_null'])) {
                    echo '  <p style="color:red; font-size: 16px;">' . $_SESSION['error_null'] . '</p>';
                    unset($_SESSION['error_null']);
                }
                ?>
                <div class="dangkytaikhoan">
                    <input name="fullname" type="text" id="input1" placeholder="Tên tài khoản" value="<?php echo isset($_POST['fullname']) ? $_POST['fullname'] : '' ?>" onBlur="checkInput(this)" />
                    <p style="color:red; font-size: 16px;" id="error1" class="error">
                        <?php
                        if (isset($error['error_fullname'])) {
                            echo $error['error_fullname'];
                        }
                        ?>
                    </p>
                </div>

                <div class="dangkytaikhoan">
                    <input name="email" type="email" id="input2" placeholder="Email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : '' ?>" onBlur="checkInput(this)" />
                    <p style="color:red; font-size: 16px;" id="error2" class="error">
                        <?php
                        if (isset($error['error_email'])) {
                            echo $error['error_email'];
                        }
                        ?>
                    </p>
                </div>

                <div class="gioitinh">
                    <label>Giới Tính</label>
                    <input type="radio" name="gender" value="Nam" required> Nam
                    <input type="radio" name="gender" value="Nữ"> Nữ
                    <input type="radio" name="gender" value="Khác">Khác
                </div>

                <div class="dangkytaikhoan">
                    <input name="pass" type="password" id="input3" placeholder="Mật khẩu" onBlur="checkInput(this)" />
                    <p style="color:red; font-size: 16px;" id="error3" class="error">
                        <?php
                        if (isset($error['error_pass'])) {
                            echo $error['error_pass'];
                        }
                        ?>
                    </p>
                </div>

                <div class="dangkytaikhoan">
                    <input name="pass_comfirm" type="password" id="input4" placeholder="Xác nhận mật khẩu" onBlur="checkInput(this)" />
                    <p style="color:red; font-size: 16px;" id="error4" class="error">
                        <?php
                        if (isset($error['error_pass_comfirm'])) {
                            echo $error['error_pass_comfirm'];
                        }
                        ?>
                    </p>
                </div>
                <button type="submit" class="btn">Đăng ký</button>
                <div class="quaylai">
                    <p><a href="?module=home&action=dangnhap">Đăng nhập</a></p>
                </div>
            </form>
            <script src="<?php echo _WEB_HOST_TEMPLATES ?>/js/validation_form.js"></script>
        </div>
        <div class="background_2">
            <?php
            $sql = "SELECT duong_dan FROM banner WHERE loai_banner = 'dangky'";
            $ket_qua = mysqli_query($configDTB, $sql);
            $link_img = mysqli_fetch_array($ket_qua);
            ?>
            <img src="<?php echo _WEB_HOST_TEMPLATES;
                        echo $link_img['duong_dan'] ?>" alt="database_error">
        </div>
    </div>
</div>

<?php
mysqli_close($configDTB); //đóng database
layout('footer');
?>