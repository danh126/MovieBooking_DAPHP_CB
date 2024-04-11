<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Thay đổi tên trang tương ứng
$data = [
    'ten_trang' => 'Cập nhật lịch chiếu | Movie Booking'
];
layout('header_admin', $data);

//Nếu không tồn tại loginAdmin thì quay về trang login
if (!checkLogin('login_admin')) {
    header('location: ?module=admin&action=dangnhap');
}
//Xử lý sửa phim
if (!empty($_SERVER["REQUEST_METHOD"] == "POST")) {

    $id_ca_chieu = $_POST['id_cachieu'];

    $sql = "SELECT * FROM ca_chieu WHERE id_cachieu = $id_ca_chieu";
    $ket_qua = mysqli_query($configDTB, $sql);
    $row = mysqli_fetch_array($ket_qua);
?>
    <main>
        <div class="edit_phim">
            <h4>Chỉnh sửa lịch chiếu</h4>
            <form action="?module=admin&action=capnhatlichchieu" method="post" enctype="multipart/form-data">
                <label for="">Id ca chiếu <span>(Không thể chỉnh sửa)</span></label>
                <input type="text" name="id_cachieu" value="<?php echo $row['id_cachieu'] ?>" readonly>
                <label for="">Id phim <span>(Không thể chỉnh sửa)</span></label>
                <input type="text" name="id_phim" value="<?php echo $row['id_phim'] ?>" readonly>
                <?php
                $id_phim = $row['id_phim'];
                $sql = "SELECT ten_phim FROM danh_sach_phim WHERE id_phim = $id_phim ";
                $ten_phim = mysqli_query($configDTB, $sql);
                $ten_phim_view = mysqli_fetch_array($ten_phim);
                ?>
                <label for="">Tên phim <span>(Không thể chỉnh sửa)</span></label>
                <input type="text" name="ten_phim" value="<?php echo $ten_phim_view['ten_phim'] ?>" readonly>
                <label for="">Id phòng chiếu <span>(Không thể chỉnh sửa)</span></label>
                <input type="text" name="id_phong" value="<?php echo $row['id_phong'] ?>" readonly>
                <label for="">Ngày chiếu</label>
                <input type="date" name="ngay_chieu" value="<?php echo $row['ngay_chieu'] ?>">
                <label for="">Giờ bắt đầu</label>
                <input type="time" name="gio_bd" value="<?php echo $row['gio_bd'] ?>">
                <label for="">Giờ kết thúc</label>
                <input type="time" name="gio_kt" value="<?php echo $row['gio_kt'] ?>">
                <div class="submit">
                    <button type="submit">Cập nhật</button>
                    <a href="?module=admin&action=editlichchieu">Quay lại</a>
                </div>
            </form>
        </div>
    </main>
    <?php
    mysqli_close($configDTB); //đóng database
} else {
    if (!empty($_SESSION['error_lichchieu_edit'])) {
    ?>
        <main>
            <div class="edit_phim">
                <h4>Chỉnh sửa lịch chiếu</h4>
                <span style="color: red;">
                    <?php
                    echo !empty($_SESSION['error_lichchieu_edit']) ?  $_SESSION['error_lichchieu_edit'] : '';
                    unset($_SESSION['error_lichchieu_edit']);
                    ?>
                </span>
                <form action="?module=admin&action=capnhatlichchieu" method="post" enctype="multipart/form-data">
                    <label for="">Id ca chiếu <span>(Không thể chỉnh sửa)</span></label>
                    <input type="text" name="id_cachieu" value="<?php echo  $_SESSION['id_cachieu'] ?>" readonly>
                    <?php unset($_SESSION['id_cachieu']); ?>
                    <label for="">Id phim <span>(Không thể chỉnh sửa)</span></label>
                    <input type="text" name="id_phim" value="<?php echo  $_SESSION['id_phim'] ?>" readonly>
                    <?php
                    $id_phim = $_SESSION['id_phim'];
                    $sql = "SELECT ten_phim FROM danh_sach_phim WHERE id_phim = $id_phim ";
                    $ten_phim = mysqli_query($configDTB, $sql);
                    $ten_phim_view = mysqli_fetch_array($ten_phim);
                    ?>
                    <label for="">Tên phim <span>(Không thể chỉnh sửa)</span></label>
                    <input type="text" name="ten_phim" value="<?php echo $ten_phim_view['ten_phim'] ?>" readonly>
                    <?php
                    unset($_SESSION['id_phim']);
                    mysqli_close($configDTB); //đóng database
                    ?>
                    <label for="">Id phòng chiếu <span>(Không thể chỉnh sửa)</span></label>
                    <input type="text" name="id_phong" value="<?php echo  $_SESSION['id_phong'] ?>" readonly>
                    <?php unset($_SESSION['id_phong']); ?>
                    <label for="">Ngày chiếu</label>
                    <input type="date" name="ngay_chieu" value="<?php echo $_SESSION['ngay_chieu'] ?>">
                    <?php unset($_SESSION['ngay_chieu']); ?>
                    <label for="">Giờ bắt đầu</label>
                    <input type="time" name="gio_bd" value="<?php echo $_SESSION['gio_bd'] ?>">
                    <?php unset($_SESSION['gio_bd']); ?>
                    <label for="">Giờ kết thúc</label>
                    <input type="time" name="gio_kt" value="<?php echo $_SESSION['gio_kt'] ?>">
                    <?php unset($_SESSION['gio_kt']); ?>
                    <div class="submit">
                        <button type="submit">Cập nhật</button>
                        <a href="?module=admin&action=editlichchieu">Quay lại</a>
                    </div>
                </form>
            </div>
        </main>
<?php
    } else {
        header('location: ?module=admin&action=editlichchieu');
    }
}
layout('footer_admin');
?>