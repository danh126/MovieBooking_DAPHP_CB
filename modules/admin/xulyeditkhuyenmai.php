<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Thay đổi tên trang tương ứng
$data = [
    'ten_trang' => 'Cập nhật khuyến mãi | Movie Booking'
];
layout('header_admin', $data);

//Nếu không tồn tại loginAdmin thì quay về trang login
if (!checkLogin('login_admin')) {
    header('location: ?module=admin&action=dangnhap');
}
//Xử lý sửa phim
if (!empty($_SERVER["REQUEST_METHOD"] == "POST")) {

    //Lấy nema từ form button trong danh sách sửa theo phương thức post.
    $id_km = $_POST['id_km'];

    #Gọi dữ liệu theo id_km.
    $sql = "SELECT * FROM khuyen_mai WHERE id_km = '" . $id_km . "' ";
    $result = mysqli_query($configDTB, $sql);
    $rows = mysqli_fetch_array($result);
?>
    <main>
        <div class="edit_phim">
            <h4>Chỉnh sửa khuyến mãi</h4>
            <form action="?module=admin&action=capnhatkhuyenmai" method="post" enctype="multipart/form-data">
                <label id='' for='' class='labelDMKM'>ID khuyến mãi <span>(Không thể chỉnh sửa)</span></label>
                <input type="text" name="id_khuyen_mai" id="id_km" class='inputDMKM' value='<?php echo $rows['id_km'] ?>' readonly>

                <label id='' for='' class='labelDMKM'>Danh mục khuyến mãi <span>(Không thể chỉnh sửa)</span></label>
                <input id='' name='loai_khuyen_mai' type='text' class='inputDMKM' value='<?php echo $rows['loai_khuyen_mai'] ?>' placeholder='Nhập danh mục khuyên mãi' readonly>

                <label id='' for='' class='labelDMKM'>Link hinh ảnh vé danh mục</label>

                <input type="text" name="normal_img" id="" class='inputDMKM' value='<?php echo $rows['hinh_anh'] ?>' readonly>
                <br>
                <input type="file" name="fileToUploadImgDM" id="fileToUpload" class='inputDMKM' value=''>

                <label id='' for='' class='labelDMKM'>Ngày bắt dầu khuyến mãi</label>
                <input id='' name='begin-date' type='date' pattern='' class='inputDMKM' value='<?php echo $rows['ngay_bat_dau'] ?>' placeholder='Thời gian diễn ra sự kiện' autofocus>

                <label id='' for='' class='labelDMKM'>Ngày kết thúc khuyến mãi</label>
                <input id='' name='end-date' type='date' pattern='' class='inputDMKM' value='<?php echo $rows['ngay_ket_thuc'] ?>' placeholder='Thời gian diễn ra sự kiện' autofocus>
                <?php
                $sql = "SELECT * FROM chi_tiet_km WHERE km_so = '" . $id_km . "' ";
                $result = mysqli_query($configDTB, $sql);
                $rows = mysqli_fetch_array($result);
                ?>
                <label id='' for='' class='labelDMKM'>ID chi tiết khuyến mãi <span>(Không thể chỉnh sửa)</span></label>
                <input id='' name='id_chi_tiet_km' type='text' class='inputDMKM' placeholder='ID chi tiết khuyến mãi' value='<?php echo $rows['id_ct_km']; ?>' readonly>

                <label id='' for='' class='labelDMKM'>Khuyến mãi số <span>(Không thể chỉnh sửa)</span></label>
                <input id='' name='khuyen_mai_so' type='text' class='inputDMKM' placeholder='khuyến mãi số' value='<?php echo $rows['km_so']; ?>' readonly>

                <label id='' for='' class='labelDMKM'>Nhập tiêu đề</label>
                <input id='' name='tieu_de_ct' type='text' class='inputDMKM' placeholder='Nhập tiêu đề' value='<?php echo $rows['ten_km']; ?>' required>

                <label id='' for='' class='labelDMKM'>Hình ảnh chi tiết khuyến mãi (900x400)</label>
                <input type="file" name="fileToUploadImgCTKM" id="fileToUpload" class='inputDMKM'>
                <br>
                <input type="text" name="normal_ct_img" id="" class='inputDMKM' value='<?php echo $rows['hinh_anh'] ?>' readonly>

                <label id='' for='' class='labelDMKM'>Nội dung 1 (Tiêu đề cho nội dung)</label>
                <textarea name="noidung1" id="" cols="30" rows="10"><?php echo $rows['noi_dung1'] ?></textarea>

                <label id='' for='' class='labelDMKM'>Nội dung 2 (Nội dung chi tiết)</label>
                <textarea name="noidung2" id="" cols="30" rows="10"><?php echo $rows['noi_dung2'] ?></textarea>

                <label id='' for='' class='labelDMKM'>Nội dung 3 (Kết thúc nội dung)</label>
                <textarea name="noidung3" id="" cols="30" rows="10"><?php echo $rows['noi_dung3'] ?></textarea>
                <div class="submit">
                    <button type="submit">Cập nhật</button>
                    <a href="?module=admin&action=editkhuyenmai">Quay lại</a>
                </div>
            </form>
        </div>
    </main>
    <?php
    mysqli_close($configDTB); //đóng database
} else {
    if (!empty($_SESSION['img_error']) || !empty($_SESSION['error_date']) || !empty($_SESSION['error_date'])) {
    ?>
        <main>
            <div class="edit_phim">
                <h4>Chỉnh sửa khuyến mãi</h4>
                <form action="?module=admin&action=capnhatkhuyenmai" method="post" enctype="multipart/form-data">

                    <label id='' for='' class='labelDMKM'>ID khuyến mãi <span>(Không thể chỉnh sửa)</span></label>
                    <input type="text" name="id_khuyen_mai" id="id_km" class='inputDMKM' value='<?php echo $_SESSION['id_khuyen_mai']; ?>' readonly>
                    <?php unset($_SESSION['id_khuyen_mai']); ?>
                    <label id='' for='' class='labelDMKM'>Danh mục khuyến mãi <span>(Không thể chỉnh sửa)</span></label>
                    <input id='' name='danhmuc' type='text' class='inputDMKM' value='<?php echo $_SESSION['loai_khuyen_mai']; ?>' placeholder='Nhập danh mục khuyên mãi' readonly>
                    <?php unset($_SESSION['loai_khuyen_mai']); ?>
                    <label id='' for='' class='labelDMKM'>Hình ảnh vé danh mục</label></br>
                    <input type="file" name="fileToUploadImgDM" id="fileToUpload" class='inputDMKM'></br>
                    <span>
                        <?php
                        if (isset($_SESSION['img_error'])) {
                            echo $_SESSION["img_error"];
                            unset($_SESSION["img_error"]);
                        }
                        ?>
                    </span>
                    <input type="text" name="normal_img" id="" class='inputDMKM' value='<?php echo $_SESSION['normal_img']; ?>' readonly>
                    <?php unset($_SESSION['normal_img']); ?>
                    <label id='' for='' class='labelDMKM'>Ngày bắt dầu khuyến mãi</label>
                    <input id='' name='begin-date' type='date' pattern='' class='inputDMKM' value='<?php echo $_SESSION['begin-date']; ?>' placeholder='Thời gian diễn ra sự kiện' autofocus>
                    <?php unset($_SESSION['begin-date']); ?>
                    <label id='' for='' class='labelDMKM'>Ngày kết thúc khuyến mãi</label>
                    <input id='' name='end-date' type='date' pattern='' class='inputDMKM' value='<?php echo $_SESSION['end-date']; ?>' placeholder='Thời gian diễn ra sự kiện' autofocus>
                    <?php unset($_SESSION['end-date']); ?>
                    <span>
                        <?php
                        if (isset($_SESSION['error_date'])) {
                            echo $_SESSION["error_date"];
                            unset($_SESSION["error_date"]);
                        }
                        ?>
                    </span>
                    <label id='' for='' class='labelDMKM'>ID chi tiết khuyến mãi <span>(Không thể chỉnh sửa)</span></label>
                    <input id='' name='id_chi_tiet_km' type='text' class='inputDMKM' placeholder='ID chi tiết khuyến mãi' value='<?php echo $_SESSION['id_chi_tiet_km']; ?>' readonly>
                    <?php unset($_SESSION['id_chi_tiet_km']); ?>
                    <label id='' for='' class='labelDMKM'>Khuyến mãi số <span>(Không thể chỉnh sửa)</span></label>
                    <input id='' name='khuyen_mai_so' type='text' class='inputDMKM' placeholder='khuyến mãi số' value='<?php echo $_SESSION['khuyen_mai_so']; ?>' readonly>
                    <?php unset($_SESSION['khuyen_mai_so']); ?>
                    <label id='' for='' class='labelDMKM'>Nhập tiêu đề</label>
                    <input id='' name='tieu_de_ct' type='text' class='inputDMKM' placeholder='Nhập tiêu đề' value='<?php echo $_SESSION['tieu_de_ct']; ?>' required>
                    <?php unset($_SESSION['tieu_de_ct']); ?>
                    <label id='' for='' class='labelDMKM'>Hình ảnh chi tiết khuyến mãi (900x400)</label>
                    <input type="file" name="fileToUploadImgCTKM" id="fileToUpload" class='inputDMKM'>
                    <span>
                        <?php
                        if (isset($_SESSION['img_error2'])) {
                            echo $_SESSION["img_error2"];
                            unset($_SESSION["img_error2"]);
                        }
                        ?>
                    </span>
                    <input type="text" name="normal_ct_img" id="" class='inputDMKM' value='<?php echo $_SESSION['normal_ct_img']; ?>' readonly>
                    <?php unset($_SESSION['normal_ct_img']); ?>
                    <label id='' for='' class='labelDMKM'>Nội dung 1 (Tiêu đề cho nội dung)</label>
                    <textarea name="noidung1" id="" cols="30" rows="10"><?php echo $_SESSION['noidung1'] ?></textarea>
                    <?php unset($_SESSION['noidung1']); ?>
                    <label id='' for='' class='labelDMKM'>Nội dung 2 (Nội dung chi tiết)</label>
                    <textarea name="noidung2" id="" cols="30" rows="10"><?php echo $_SESSION['noidung2'] ?></textarea>
                    <?php unset($_SESSION['noidung2']); ?>
                    <label id='' for='' class='labelDMKM'>Nội dung 3 (Kết thúc nội dung)</label>
                    <textarea name="noidung3" id="" cols="30" rows="10"><?php echo $_SESSION['noidung3'] ?></textarea>
                    <?php unset($_SESSION['noidung3']); ?>
                    <div class="submit">
                        <button type="submit">Cập nhật</button>
                        <a href="?module=admin&action=editkhuyenmai">Quay lại</a>
                    </div>
                </form>
            </div>
        </main>
<?php
    } else {
        header('location: ?module=admin&action=editkhuyenmai');
    }
}
layout('footer_admin');
?>