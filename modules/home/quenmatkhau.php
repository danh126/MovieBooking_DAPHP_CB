<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Thay đổi tên trang tương ứng
$data = [
    'ten_trang' => 'Quân mật khẩu | Movie Booking'
];
layout('header', $data);

//Gọi hàm kiểm tra đăng nhập
if (checkLogin('login_user')) {
    header('location: ?module=home&action=trangchu');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['email'] != '') {
        $email = $_POST["email"];

        // Truy vấn SQL để lấy mật khẩu từ CSDL dựa trên email
        $sql = "SELECT email FROM login_user WHERE email = '$email'";
        $result = mysqli_query($configDTB, $sql);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $_SESSION['quen_mat_khau'] = "Vui lòng liên hệ quản trị viên để lấy lại mật khẩu!";
            } else {
                $_SESSION['no_email'] = "Email không tồn tại!";
            }
        }
    } else {
        $_SESSION['error_null'] = "Vui lòng nhập thông tin!";
    }
}
?>
<link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/auth.css?ver=<?php echo rand(); ?>">
<div class="auth_form">
    <div class="auth">
        <div class="quenmk">
            <div class="quenmatkhau">
                <form action="" method="post">
                    <h1>Quên Mật Khẩu</h1>
                    <?php
                    if (isset($_SESSION['quen_mat_khau'])) {
                        echo "<p class='thong_bao'>" . $_SESSION['quen_mat_khau'] . "</p>";
                        unset($_SESSION['quen_mat_khau']);
                    }
                    ?>
                    <div class="quenmatkhau1">
                        <input type="email" name="email" id="input1" placeholder="Nhập email" value="<?php echo isset($email) ? $email : '' ?>" onBlur="checkInput(this)" />
                        <p style="color:red; font-size: 16px;" id="error1" class="error">
                            <?php
                            if (isset($_SESSION['no_email'])) {
                                echo $_SESSION['no_email'];
                                unset($_SESSION['no_email']);
                            }
                            if (isset($_SESSION['error_null'])) {
                                echo $_SESSION['error_null'];
                                unset($_SESSION['error_null']);
                            }
                            ?>
                        </p>
                    </div>
                    <button type="submit" class="btn">Lấy lại mật khẩu</button>
                    <div class="dangnhaplai">
                        <p><a href="?module=home&action=dangnhap">Đăng Nhập</a></p>
                    </div>
                </form>
                <script src="<?php echo _WEB_HOST_TEMPLATES ?>/js/validation_form.js"></script>
            </div>
        </div>
        <div class="background_3">
            <?php
            $sql = "SELECT duong_dan FROM banner WHERE loai_banner = 'dangnhap'";
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