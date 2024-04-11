<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Thay đổi tên trang tương ứng
$data = [
    'ten_trang' => 'Thêm lịch chiếu | Movie Booking'
];
layout('header_admin', $data);

//Nếu không tồn tại loginAdmin thì quay về trang login
if (!checkLogin('login_admin')) {
    header('location: ?module=admin&action=dangnhap');
}

//Xử lý thêm phim mới 

if (!empty($_SERVER["REQUEST_METHOD"] == "POST")) {
    $id_phim = $_POST['id_phim'];
    $phong_chieu = $_POST['phong_chieu'];
    $ngay_Chieu = $_POST['ngay_chieu'];
    $gio_bd = $_POST['gio_bd'];
    $gio_kt = $_POST['gio_kt'];

    //Kiểm tra trùng lịch chiếu khi cùng ngày chiếu, mã phòng và cùng giờ bắt đầu 
    $sql = "SELECT * FROM ca_chieu WHERE id_phim = $id_phim AND ngay_chieu = '$ngay_Chieu' AND gio_bd = '$gio_bd' AND gio_kt = '$gio_kt' AND id_phong = '$phong_chieu' ";
    $kt_trung = mysqli_query($configDTB, $sql);
    $smt = mysqli_num_rows($kt_trung);
    if ($smt > 0) {
        $_SESSION['error_lichchieu'] = "Lịch chiếu đã tồn tại!";
    } else {

        //Tạo id tự động
        $sql_id = "SELECT MAX(id_cachieu) AS id_max FROM ca_chieu";
        $result_id = mysqli_query($configDTB, $sql_id);
        $rows = mysqli_fetch_array($result_id);
        $id_max = $rows['id_max'] + 1;

        // // Tạo ID mới
        // $new_id = "MV" . str_pad((int)$rows['id_max'] + 1, 2, '0', STR_PAD_LEFT);

        $sql = "INSERT INTO ca_chieu (id_cachieu, id_phim, id_phong, ngay_chieu, gio_bd, gio_kt) 
                VALUES (" . $id_max . ", " . $id_phim . ", '" . $phong_chieu . "', '" . $ngay_Chieu . "', '" . $gio_bd . "', '" . $gio_kt . "')";

        $ket_qua = mysqli_query($configDTB, $sql);
        if ($ket_qua) {
            $_SESSION['add_lichchieu'] = "Thêm lịch chiếu mới thành công!";
        }
    }
}

?>
<main>
    <div class="add_phim">
        <p>Thêm lịch chiếu</p>
        <span class="thong_bao">
            <?php
            echo !empty($_SESSION['add_lichchieu']) ?  $_SESSION['add_lichchieu'] : '';
            unset($_SESSION['add_lichchieu']);
            echo !empty($_SESSION['error_lichchieu']) ?  $_SESSION['error_lichchieu'] : '';
            unset($_SESSION['error_lichchieu']);
            ?>
        </span>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="">Tên phim</label>
            <?php
            $sql = "SELECT id_phim, ten_phim FROM danh_sach_phim WHERE hien_trang = 1";
            $ket_qua_1 = mysqli_query($configDTB, $sql);
            ?>
            <select name="id_phim" required>
                <option></option>
                <?php
                while ($rows = mysqli_fetch_array($ket_qua_1)) :
                ?>
                    <option value="<?php echo $rows['id_phim'] ?>"><?php echo $rows['ten_phim'] ?></option>
                <?php
                endwhile;
                ?>
            </select>
            <label for="">Phòng chiếu</label>
            <?php
            $sql = "SELECT id_phong, ten_phong FROM phong_chieu";
            $ket_qua_2 = mysqli_query($configDTB, $sql);
            ?>
            <select name="phong_chieu" required>
                <option></option>
                <?php
                while ($rows = mysqli_fetch_array($ket_qua_2)) :
                ?>
                    <option value="<?php echo $rows['id_phong'] ?>"><?php echo $rows['ten_phong'] ?></option>
                <?php
                endwhile;
                ?>
            </select>
            <label for="">Ngày chiếu</label>
            <input type="date" name="ngay_chieu" required value="<?php echo !empty($_POST['ngay_chieu']) ? $_POST['ngay_chieu'] : '' ?>">
            <label for="">Giờ bắt đầu</label>
            <input type="time" name="gio_bd" required value="<?php echo !empty($_POST['gio_bd']) ? $_POST['gio_bd'] : '' ?>">
            <label for="">Giờ kết thúc</label>
            <input type="time" name="gio_kt" required value="<?php echo !empty($_POST['gio_kt']) ? $_POST['gio_kt'] : '' ?>">
            <div class="submit">
                <button type="submit">Thêm</button>
                <a href="?module=admin&action=quanlylichchieu">Quay lại</a>
            </div>
        </form>
    </div>
</main>
<?php
layout('footer_admin');
?>