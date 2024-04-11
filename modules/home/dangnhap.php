<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Thay đổi tên trang tương ứng
$data = [
    'ten_trang' => 'Đăng nhập tài khoản | Movie Booking'
];
layout('header', $data);

//Gọi hàm kiểm tra đăng nhập
if (checkLogin('login_user')) {
    header('location: ?module=home&action=trangchu');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //Kiểm tra giá trị rỗng
    if ($_POST['email'] != '' && $_POST['password'] != '') {
        $email = $_POST["email"];
        $pass = $_POST["password"];

        // Truy vấn SQL để lấy mật khẩu từ CSDL dựa trên email
        $sql = "SELECT id_user, mat_khau, ho_ten FROM login_user WHERE email = '$email'";
        $result = mysqli_query($configDTB, $sql);

        if ($result) {
            // Kiểm tra xem có bản ghi nào được trả về hay không
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_array($result);
                $Password = $row["mat_khau"];

                // So sánh mật khẩu nhập vào với mật khẩu đã lưu trong CSDL
                // password_verify kiểm tra mật khẩu mã hóa trong DTB
                if (password_verify($pass, $Password)) {
                    $tenUser = $row['ho_ten'];
                    $id_user = $row['id_user'];
                    $_SESSION["login_user"] = true; //key session tự đặt
                    $_SESSION['username'] = $tenUser;
                    $_SESSION['id_user'] = $id_user;
                    header('location: ?module=home&action=trangchu');
                } else {
                    $_SESSION["error_pass"] = "Mật khẩu không chính xác!";
                }
            } else {
                $_SESSION["error_email"] = "Email không tồn tại!";
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
        <div class="dangnhap">
            <form action="" method="post">
                <h1>ĐĂNG NHẬP</h1>
                <?php
                if (isset($_SESSION['error_null'])) {
                    echo '  <p style="color:red; font-size: 16px;">' . $_SESSION['error_null'] . '</p>';
                    unset($_SESSION['error_null']);
                }
                ?>
                <div class="DANGNHAP1">
                    <input type="email" id="input1" placeholder="Email" name="email" value="<?php echo isset($email) ? $email : '' ?>" onBlur="checkInput(this)" />
                    <p style="color:red; font-size: 16px;" id="error1" class="error">
                        <?php
                        if (isset($_SESSION['error_email'])) {
                            echo $_SESSION['error_email'];
                            unset($_SESSION['error_email']);
                        }
                        ?>
                    </p>
                    <input type="password" id="input2" placeholder="Mật khẩu" name="password" onBlur="checkInput(this)" />
                    <p style="color:red; font-size: 16px;" id="error2" class="error">
                        <?php
                        if (isset($_SESSION['error_pass'])) {
                            echo $_SESSION['error_pass'];
                            unset($_SESSION['error_pass']);
                        }
                        ?>
                    </p>
                </div>
                <div class="quenmatkhau_link">
                    <a href="?module=home&action=quenmatkhau">Quên mật khẩu ?</a>
                </div>

                <button type="submit" class="btn">Đăng nhập</button>
                <div class="dangkyclick">
                    <p>Bạn không có tài khoản ?<a href="?module=home&action=dangky">Đăng ký ngay!</a></p>
                </div>
            </form>
            <script src="<?php echo _WEB_HOST_TEMPLATES ?>/js/validation_form.js"></script>
        </div>
        <div class="background">
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