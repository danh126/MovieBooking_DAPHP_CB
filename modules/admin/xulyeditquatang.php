<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Thay đổi tên trang tương ứng
$data = [
    'ten_trang' => 'Cập nhật quà tặng | Movie Booking'
];
layout('header_admin', $data);

//Nếu không tồn tại loginAdmin thì quay về trang login
if (!checkLogin('login_admin')) {
    header('location: ?module=admin&action=dangnhap');
}
//Xử lý sửa phim
if (!empty($_SERVER["REQUEST_METHOD"] == "POST")) {

    //Lấy nema từ form button trong danh sách sửa theo phương thức post.
    $id_quatang = $_POST['id_quatang'];

    $sql1 = "SELECT * FROM quatang WHERE id_quatang = " . $id_quatang . "";
    $ket_qua1 = mysqli_query($configDTB, $sql1);
    $rows1 = mysqli_fetch_array($ket_qua1);

    $sql2 = "SELECT * FROM chitietquatang WHERE id_quatang = " . $id_quatang . "";
    $ket_qua2 = mysqli_query($configDTB, $sql2);
    $rows2 = mysqli_fetch_array($ket_qua2);
?>
    <main>
        <div class="edit_phim">
            <h4>Chỉnh sửa quà tặng</h4>
            <form action="?module=admin&action=capnhatquatang" method="post" enctype="multipart/form-data">
                <label>Id quà tặng <span>(Không thể chỉnh sửa)</span></label>
                <input type="text" name="id_quatang" value="<?php echo $rows1['id_quatang'] ?>" readonly>

                <label for="">Hình ảnh </label>
                <input type="file" name="hinhanh">

                <label for="">Tên sản phẩm </label>
                <input type="text" name="name" value="<?php echo $rows1['tensp'] ?>" required>

                <label for="">Hạn sử dụng </label>
                <input type="text" name="hsd" value="<?php echo $rows1['hsd'] ?>" required>


                <label for="">Giá bán </label>
                <input type="float" name="giaban" value="<?php echo $rows1['giaban'] ?>" required>

                <label for="">Loại quà tặng <span>(Không thể chỉnh sửa)</span></label>
                <input type="text" name="loaiquatang" value="<?php echo $rows1['loaiquatang'] ?>" readonly>

                <label for="">Nội dung hình ảnh</label>
                <input type="text" name="nd_hinhanh" value="<?php echo $rows2['noidunghinhanh'] ?>" required>

                <label for="">Số lượng mua tối thiểu</label>
                <input type="text" name="SL_muatoithieu" value="<?php echo $rows2['SLmuatoithieu'] ?>" required>

                <label for="">Hướng dẫn sử dụng</label>
                <input type="text" name="hdsd" value="<?php echo $rows2['hdsd'] ?>" required>

                <label for="">Chi tiết sản phẩm</label>
                <textarea name="ct_sp" id="" cols="30" rows="10"><?php echo $rows2['chitietsp'] ?></textarea>
                <div class="submit">
                    <button type="submit">Cập nhật</button>
                    <a href="?module=admin&action=editquatang">Quay lại</a>
                </div>
            </form>
        </div>
    </main>
    <?php
    mysqli_close($configDTB); //đóng database
} else {
    if (!empty($_SESSION['ERROR_img'])) {
    ?>
        <main>
            <div class="edit_phim">
                <h4>Chỉnh sửa quà tặng</h4>
                <form action="?module=admin&action=capnhatquatang" method="post" enctype="multipart/form-data">

                    <label>Id quà tặng <span>(Không thể chỉnh sửa)</span></label>
                    <input type="text" name="id_quatang" value="<?php echo $_SESSION['id_quatang'] ?>" readonly>
                    <?php unset($_SESSION['id_quatang']); ?>
                    <label for="">Hình ảnh </label>
                    <input type="file" name="hinhanh">
                    <span style="color: red;">
                        <?php
                        if (isset($_SESSION['ERROR_img'])) {
                            echo ($_SESSION['ERROR_img']);
                            unset($_SESSION['ERROR_img']);
                        }
                        ?>
                    </span>

                    <label for="">Tên sản phẩm </label>
                    <input type="text" name="name" value="<?php echo $_SESSION['tensp'] ?>" required>
                    <?php unset($_SESSION['tensp']); ?>
                    <span style="color: red;">
                        <?php
                        if (isset($_SESSION['error_tenquatang'])) {
                            echo ($_SESSION['error_tenquatang']);
                            unset($_SESSION['error_tenquatang']);
                        }
                        ?>
                    </span>

                    <label for="">Hạn sử dụng </label>
                    <input type="text" name="hsd" value="<?php echo $_SESSION['hsd'] ?>" required>
                    <?php unset($_SESSION['hsd']); ?>

                    <label for="">Giá bán </label>
                    <input type="float" name="giaban" value="<?php echo $_SESSION['giaban'] ?>" required>
                    <?php unset($_SESSION['giaban']); ?>
                    <label for="">Loại quà tặng <span>(Không thể chỉnh sửa)</span></label>
                    <input type="text" name="loaiquatang" value="<?php echo $_SESSION['loaiquatang'] ?>" required>
                    <?php unset($_SESSION['loaiquatang']); ?>
                    <label for="">Nội dung hình ảnh</label>
                    <input type="text" name="nd_hinhanh" value="<?php echo $_SESSION['nd_hinhanh'] ?>" required>
                    <?php unset($_SESSION['nd_hinhanh']); ?>
                    <label for="">Số lượng mua tối thiểu</label>
                    <input type="text" name="SL_muatoithieu" value="<?php echo $_SESSION['SL_muatoithieu'] ?>" required>
                    <?php unset($_SESSION['SL_muatoithieu']); ?>
                    <label for="">Hướng dẫn sử dụng</label>
                    <input type="text" name="hdsd" value="<?php echo $_SESSION['hdsd'] ?>" required>
                    <?php unset($_SESSION['hdsd']); ?>
                    <label for="">Chi tiết sản phẩm</label>
                    <textarea name="ct_sp" id="" cols="30" rows="10"><?php echo $_SESSION['ct_sp'] ?></textarea>
                    <?php unset($_SESSION['ct_sp']); ?>
                    <div class="submit">
                        <button type="submit">Cập nhật</button>
                        <a href="?module=admin&action=editquatang">Quay lại</a>
                    </div>
                </form>
            </div>
        </main>
<?php
    } else {
        header('location: ?module=admin&action=editquatang');
    }
}
layout('footer_admin');
?>