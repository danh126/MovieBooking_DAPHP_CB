<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Thay đổi tên trang tương ứng
$data = [
    'ten_trang' => 'Cập nhật phim | Movie Booking'
];
layout('header_admin', $data);

//Nếu không tồn tại loginAdmin thì quay về trang login
if (!checkLogin('login_admin')) {
    header('location: ?module=admin&action=dangnhap');
}
//Xử lý sửa phim
if (!empty($_SERVER["REQUEST_METHOD"] == "POST")) {

    $id_Phim = $_POST['id_phim'];

    $sql = "SELECT * FROM danh_sach_phim WHERE id_phim = $id_Phim";
    $ket_qua = mysqli_query($configDTB, $sql);
    $row = mysqli_fetch_array($ket_qua);

    $sql_ct = "SELECT * FROM chi_tiet_phim WHERE id_phim = $id_Phim";
    $ket_qua_ct = mysqli_query($configDTB, $sql_ct);
    if (mysqli_num_rows($ket_qua_ct) > 0) {
        $view_ct = mysqli_fetch_array($ket_qua_ct);
    }
?>
    <main>
        <div class="edit_phim">
            <h4>Chỉnh sửa phim</h4>
            <form action="?module=admin&action=capnhatphim" method="post" enctype="multipart/form-data">
                <label for="">Id phim <span>(Không thể chỉnh sửa)</span></label>
                <input type="text" name="id_Phim" value="<?php echo $row['id_phim'] ?>" readonly>
                <label for="">Tên phim</label>
                <input type="text" name="ten_phim" value="<?php echo $row['ten_phim'] ?>">
                <label for="">Ngày chiếu</label>
                <input type="date" name="ngay_chieu" value="<?php echo $row['ngay_chieu'] ?>">
                <label for="">Hình ảnh <span style="color: red;">(Giữ nguyên hoặc update hình ảnh mới)</span></label>
                <input type="text" name="hinh_anh" value="<?php echo $row['hinh_anh'] ?>" readonly><br>
                <input type="file" name="update_img">
                <label for="">Thời lượng</label>
                <input type="text" name="thoi_luong" value="<?php echo $row['thoi_luong'] ?>">
                <label for="">Hiện trạng <span style="color: red;">(Đang chiếu (1), sắp chiếu(0).)</span></label>
                <input type="number" name="hien_trang" value="<?php echo $row['hien_trang'] ?>" max=1 min=0>
                <label for="">Xếp hạng</label>
                <input type="text" name="xep_hang" value="<?php echo !empty($view_ct['xep_hang']) ? $view_ct['xep_hang'] : '' ?>">
                <label for="">Thông tin</label>
                <input type="text" name="thong_tin" value="<?php echo !empty($view_ct['thong_tin']) ? $view_ct['thong_tin'] : '' ?>">
                <label for="">Thể loại</label>
                <input type="text" name="the_loai" value="<?php echo !empty($view_ct['the_loai']) ? $view_ct['the_loai'] : '' ?>">
                <label for="">Tóm tắt</label>
                <textarea name="tom_tat" cols="30" rows="10"><?php echo !empty($view_ct['tom_tat']) ? $view_ct['tom_tat'] : '' ?></textarea>
                <label for="">Video trailer <span style="color: red;">(Giữ nguyên hoặc update video trailer mới)</span></label>
                <input type="text" name="video" value="<?php echo !empty($view_ct['video_trailer']) ? $view_ct['video_trailer'] : '' ?>" readonly><br>
                <input type="file" name="update_video">
                <label for="">Hình ảnh trailer 1 <span style="color: red;">(Giữ nguyên hoặc update hình ảnh trailer mới)</span></label>
                <input type="text" name="img_trailer_1" value="<?php echo !empty($view_ct['img_trailer_1']) ? $view_ct['img_trailer_1'] : '' ?>" readonly><br>
                <input type="file" name="update_img_1">
                <label for="">Hình ảnh trailer 2 <span style="color: red;">(Giữ nguyên hoặc update hình ảnh trailer mới)</span></label>
                <input type="text" name="img_trailer_2" value="<?php echo !empty($view_ct['img_trailer_2']) ? $view_ct['img_trailer_2'] : '' ?>" readonly><br>
                <input type="file" name="update_img_2">
                <label for="">Hình ảnh trailer 3 <span style="color: red;">(Giữ nguyên hoặc update hình ảnh trailer mới)</span></label>
                <input type="text" name="img_trailer_3" value="<?php echo !empty($view_ct['img_trailer_3']) ? $view_ct['img_trailer_3'] : '' ?>" readonly><br>
                <input type="file" name="update_img_3">
                <div class="submit">
                    <button type="submit">Cập nhật</button>
                    <a href="?module=admin&action=editphim">Quay lại</a>
                </div>
            </form>
        </div>
    </main>
    <?php
    mysqli_close($configDTB); //đóng database
} else {
    if (!empty($_SESSION['error_img']) || !empty($_SESSION['error_video']) || !empty($_SESSION['error_img_1']) || !empty($_SESSION['error_img_2']) || !empty($_SESSION['error_img_3'])) {
    ?>
        <main>
            <div class="edit_phim">
                <h4>Chỉnh sửa phim</h4>
                <form action="?module=admin&action=capnhatphim" method="post" enctype="multipart/form-data">
                    <label for="">Id phim <span>(Không thể chỉnh sửa)</span></label>
                    <input type="text" name="id_Phim" value="<?php echo $_SESSION['id_phim'] ?>" readonly>
                    <?php unset($_SESSION['id_phim']); ?>
                    <label for="">Tên phim</label>
                    <input type="text" name="ten_phim" value="<?php echo  $_SESSION['ten_phim'] ?>">
                    <?php unset($_SESSION['ten_phim']); ?>
                    <label for="">Ngày chiếu</label>
                    <input type="date" name="ngay_chieu" value="<?php echo  $_SESSION['ngay_chieu'] ?>">
                    <?php unset($_SESSION['ngay_chieu']); ?>
                    <label for="">Hình ảnh <span style="color: red;">(Giữ nguyên hoặc update hình ảnh mới)</span></label>
                    <input type="text" name="hinh_anh" value="<?php echo  $_SESSION['hinh_anh'] ?>" readonly><br>
                    <?php unset($_SESSION['hinh_anh']); ?>
                    <input type="file" name="update_img">
                    <span style="color: red;">
                        <?php
                        echo !empty($_SESSION['error_img']) ? $_SESSION['error_img'] : '';
                        unset($_SESSION['error_img']);
                        ?>
                    </span>
                    <label for="">Thời lượng</label>
                    <input type="text" name="thoi_luong" value="<?php echo  $_SESSION['thoi_luong'] ?>">
                    <?php unset($_SESSION['thoi_luong']); ?>
                    <label for="">Hiện trạng <span style="color: red;">(Đang chiếu (1), sắp chiếu(0).)</span></label>
                    <input type="number" name="hien_trang" value="<?php echo  $_SESSION['hien_trang'] ?>" max=1 min=0>
                    <?php unset($_SESSION['hien_trang']); ?>
                    <label for="">Xếp hạng</label>
                    <input type="text" name="xep_hang" value="<?php echo $_SESSION['xep_hang'] ?>">
                    <?php unset($_SESSION['xep_hang']); ?>
                    <label for="">Thông tin</label>
                    <input type="text" name="thong_tin" value="<?php echo $_SESSION['thong_tin'] ?>">
                    <?php unset($_SESSION['thong_tin']); ?>
                    <label for="">Thể loại</label>
                    <input type="text" name="the_loai" value="<?php echo $_SESSION['the_loai'] ?>">
                    <?php unset($_SESSION['the_loai']); ?>
                    <label for="">Tóm tắt</label>
                    <textarea name="tom_tat" cols="30" rows="10"><?php echo $_SESSION['tom_tat'] ?></textarea>
                    <?php unset($_SESSION['tom_tat']); ?>
                    <label for="">Video trailer <span style="color: red;">(Giữ nguyên hoặc update video trailer mới)</span></label>
                    <input type="text" name="video" value="<?php echo $_SESSION['video_trailer'] ?>" readonly><br>
                    <?php unset($_SESSION['video_trailer']); ?>
                    <input type="file" name="update_video">
                    <span style="color: red;">
                        <?php
                        echo !empty($_SESSION['error_video']) ? $_SESSION['error_video'] : '';
                        unset($_SESSION['error_video']);
                        ?>
                    </span>
                    <label for="">Hình ảnh trailer 1 <span style="color: red;">(Giữ nguyên hoặc update hình ảnh trailer mới)</span></label>
                    <input type="text" name="img_trailer_1" value="<?php echo $_SESSION['img_trailer_1'] ?>" readonly><br>
                    <?php unset($_SESSION['img_trailer_1']); ?>
                    <input type="file" name="update_img_1">
                    <span style="color: red;">
                        <?php
                        echo !empty($_SESSION['error_img_1']) ? $_SESSION['error_img_1'] : '';
                        unset($_SESSION['error_img_1']);
                        ?>
                    </span>
                    <label for="">Hình ảnh trailer 2 <span style="color: red;">(Giữ nguyên hoặc update hình ảnh trailer mới)</span></label>
                    <input type="text" name="img_trailer_2" value="<?php echo $_SESSION['img_trailer_2'] ?>" readonly><br>
                    <?php unset($_SESSION['img_trailer_2']); ?>
                    <input type="file" name="update_img_2">
                    <span style="color: red;">
                        <?php
                        echo !empty($_SESSION['error_img_2']) ? $_SESSION['error_img_2'] : '';
                        unset($_SESSION['error_img_2']);
                        ?>
                    </span>
                    <label for="">Hình ảnh trailer 3 <span style="color: red;">(Giữ nguyên hoặc update hình ảnh trailer mới)</span></label>
                    <input type="text" name="img_trailer_3" value="<?php echo $_SESSION['img_trailer_3'] ?>" readonly><br>
                    <input type="file" name="update_img_3">
                    <span style="color: red;">
                        <?php
                        echo !empty($_SESSION['error_img_3']) ? $_SESSION['error_img_3'] : '';
                        unset($_SESSION['error_img_3']);
                        ?>
                    </span>
                    <?php unset($_SESSION['img_trailer_3']); ?>
                    <div class="submit">
                        <button type="submit">Cập nhật</button>
                        <a href="?module=admin&action=editphim">Quay lại</a>
                    </div>
                </form>
            </div>
        </main>
<?php
    } else {
        header('location: ?module=admin&action=editphim');
    }
}
layout('footer_admin');
?>