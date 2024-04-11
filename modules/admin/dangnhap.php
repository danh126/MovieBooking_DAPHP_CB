<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Thay đổi tên trang tương ứng
$data = [
    'ten_trang' => 'Đăng nhập admin | Movie Booking'
];
layout('header_admin', $data);

//Gọi hàm kiểm tra đăng nhập
if (checkLogin('login_admin')) {
    header('location: ?module=admin&action=trangchu');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['username'] != '' && $_POST['password'] != '') {
        $username = $_POST['username'];
        $pass = $_POST['password'];
        // Truy vấn SQL để lấy mật khẩu từ CSDL dựa trên user name
        $sql = "SELECT pass,user_name FROM login_admin WHERE user_name = '$username'";
        $result = mysqli_query($configDTB, $sql);

        if ($result) {
            // Kiểm tra xem có bản ghi nào được trả về hay không
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_array($result);
                $Password = $row["pass"];

                // So sánh mật khẩu nhập vào với mật khẩu đã lưu trong CSDL
                if ($pass == $Password) {
                    $tenUser = $row['user_name'];
                    $_SESSION['login_admin'] = true;
                    $_SESSION['admin_name'] = $tenUser;
                    header('location: ?module=admin&action=trangchu');
                } else {
                    $_SESSION["error_pass"] = "Mật khẩu không chính xác!";
                }
            } else {
                $_SESSION["error_username"] = "Tài khoản không tồn tại!";
            }
        }
    } else {
        $_SESSION['error_null'] = "Vui lòng nhập đầy đủ thông tin!";
    }
}
?>
<div class="login">
    <div class="login-container">
        <h2>ĐĂNG NHẬP ADMIN</h2>
        <p style="color:red; font-size: 16px; text-align:center;">
            <?php
            echo !empty($_SESSION['error_null']) ?  $_SESSION['error_null'] : '';
            unset($_SESSION['error_null']);
            ?>
        </p>
        <form class="login-form" action="" method="post">
            <div class="form-group">
                <label for="username">Tên tài khoản:</label>
                <input type="text" id="input1" name="username" value="<?php echo isset($username) ? $username : '' ?>" onBlur="checkInput(this)">
                <p style="color:red; font-size: 16px; text-align:center;" id="error1" class="error">
                    <?php
                    echo !empty($_SESSION['error_username']) ?  $_SESSION['error_username'] : '';
                    unset($_SESSION['error_username']);
                    ?>
                </p>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input type="password" id="input2" name="password" onBlur="checkInput(this)">
                <p style="color:red; font-size: 16px; text-align:center;" id="error2" class="error">
                    <?php
                    echo !empty($_SESSION['error_pass']) ?  $_SESSION['error_pass'] : '';
                    unset($_SESSION['error_pass']);
                    ?>
                </p>
            </div>
            <button type="submit" class="login-button">Đăng nhập</button>
        </form>
        <script src="<?php echo _WEB_HOST_TEMPLATES ?>/js/validation_form.js"></script>
    </div>
</div>
<?php
layout('footer_admin');
?>