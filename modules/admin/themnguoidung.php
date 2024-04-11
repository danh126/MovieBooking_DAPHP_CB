<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Thay đổi tên trang tương ứng
$data = [
    'ten_trang' => 'Thêm người dùng | Movie Booking'
];
layout('header_admin', $data);

//Nếu không tồn tại loginAdmin thì quay về trang login
if (!checkLogin('login_admin')) {
    header('location: ?module=admin&action=dangnhap');
}

//Xử lý thêm phim mới 

if (!empty($_SERVER["REQUEST_METHOD"] == "POST")) {
    $ten_tai_khoan = $_POST['ten_tai_khoan'];
    $email = $_POST['email'];
    $gioi_tinh = $_POST['gioi_tinh'];
    $mat_khau = $_POST['mat_khau'];

    $sql = "SELECT id_user FROM login_user WHERE email = '$email'";
    $ket_qua = mysqli_query($configDTB, $sql);

    $error = 0;
    if (mysqli_num_rows($ket_qua) > 0) {
        $error = 1;
        $_SESSION['trung_email'] = "Email đã tồn tại!";
    }

    if (strlen($mat_khau) < 8) {
        $error = 1;
        $_SESSION['error_pass'] = "Độ dài mật khẩu phải lớn hơn hoặc bằng 8 ký tự!";
    }

    if ($error == 0) {

        //Tạo id tự động
        $sql_id = "SELECT MAX(id_user) AS id_max FROM login_user";
        $result_id = mysqli_query($configDTB, $sql_id);
        $rows = mysqli_fetch_array($result_id);
        $id_max = $rows['id_max'] + 1;

        $mat_khau_mh = password_hash($mat_khau, PASSWORD_DEFAULT); //mã hóa mật khẩu

        $sql = "INSERT INTO login_user (id_user, ho_ten, email, gioi_tinh, mat_khau) VALUES (" . $id_max . ",'" . $ten_tai_khoan . "', '" . $email . "','" . $gioi_tinh . "','" . $mat_khau_mh . "') ";
        $ket_qua = mysqli_query($configDTB, $sql);
        if ($ket_qua) {
            $_SESSION['them_user_thanh_cong'] = "Thêm ngưởi dùng thành công!";
        }
    }
}
?>
<main>
    <div class="add_phim">
        <p>Thêm người dùng</p>
        <span class="thong_bao">
            <?php
            echo !empty($_SESSION['them_user_thanh_cong']) ?  $_SESSION['them_user_thanh_cong'] : '';
            unset($_SESSION['them_user_thanh_cong']);
            ?>
        </span>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="">Tên tài khoản</label>
            <input type="text" name="ten_tai_khoan" placeholder="Nhập tên người dùng" required value="<?php echo !empty($_POST['ten_tai_khoan']) ? $_POST['ten_tai_khoan'] : '' ?>">
            <label for="">Email</label>
            <input type="text" name="email" placeholder="Nhập email người dùng" required value="<?php echo !empty($_POST['email']) ? $_POST['email'] : '' ?>">
            <span>
                <?php
                echo !empty($_SESSION['trung_email']) ? $_SESSION['trung_email'] : '';
                unset($_SESSION['trung_email']);
                ?>
            </span>
            <label for="">Giới tính</label>
            <span style="color: black;"><input type="radio" name="gioi_tinh" value="Nam" required>Nam</span>
            <span style="color: black;"><input type="radio" name="gioi_tinh" value="Nữ">Nữ</span>
            <span style="color: black;"><input type="radio" name="gioi_tinh" value="Khác">Khác</span>
            <label for="">Mật khẩu</label>
            <input type="password" name="mat_khau" placeholder="Nhập mật khẩu người dùng" required>
            <span>
                <?php
                echo !empty($_SESSION['error_pass']) ? $_SESSION['error_pass'] : '';
                unset($_SESSION['error_pass']);
                ?>
            </span>
            <div class="submit">
                <button type="submit">Thêm</button>
                <a href="?module=admin&action=quanlynguoidung">Quay lại</a>
            </div>
        </form>
    </div>
</main>
<?php
layout('footer_admin');
?>