<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Thay đổi tên trang tương ứng
$data = [
    'ten_trang' => 'Thêm quà tặng | Movie Booking'
];
layout('header_admin', $data);

//Nếu không tồn tại loginAdmin thì quay về trang login
if (!checkLogin('login_admin')) {
    header('location: ?module=admin&action=dangnhap');
}

//Xử lý thêm phim mới 

if (!empty($_SERVER["REQUEST_METHOD"] == "POST")) {
    $tensp = $_POST['name'];
    $hsd = $_POST['hsd'];
    $giaban = $_POST['giaban'];
    $loaiquatang = $_POST['loaiquatang'];
    $nd_hinhanh = $_POST['nd_hinhanh'];
    $SL_muatoithieu = $_POST['SL_muatoithieu'];
    $hdsd = $_POST['hdsd'];
    $ct_sp = $_POST['ct_sp'];



    //kiểm tra trùng tên quà tặng
    $sql = "SELECT tensp FROM quatang WHERE tensp = '$tensp'";
    $ket_qua = mysqli_query($configDTB, $sql);
    if (mysqli_num_rows($ket_qua) > 0) {
        $_SESSION['error_tenquatang'] = "Tên quà tặng đã tồn tại !";
    } else {
        //xử lý lưu ảnh vào thư mục
        $hinhanhthumuc = basename($_FILES["hinhanh"]["name"]); //lấy đường dẫn tải lên
        $target_dir = "templates/images/quatang/"; //lấy đường dẫn trong thư mục suorcode của mình vd trong shop qua tang co img
        $target_file = $target_dir . $hinhanhthumuc;
        // up load = 1;
        $imagefile = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        //kiểm tra nếu tệp đã tồn tại
        if (file_exists($target_file)) { //gán biến để xem hình ảnh có tồn tại chưa
            $_SESSION['ERROR_img'] = "Hình ảnh đã tồn tại !";
        } else {
            if (move_uploaded_file($_FILES["hinhanh"]["tmp_name"], $target_file)) {
                //echo "luu thanh công" để xem chạy đc ko 
                //tạo link để lưu vào database
                $link_img = "/";
                $hinhanh_path = $link_img . $hinhanhthumuc; //echo "luu anh thanh cong" chạy ,xem thư mục img có ảnh chưa
                //tạo id tự động
                $sql_id = "SELECT MAX(id_quatang) AS id_max FROM quatang";
                $result_id = mysqli_query($configDTB, $sql_id);
                $rows = mysqli_fetch_array($result_id);
                $id_max = $rows['id_max'] + 1;   // echo $id_max; xem kêt quả                  
                //tạo id mới
                $sql_id_moi = "INSERT INTO quatang (id_quatang, hinhanh, tensp, hsd, giaban, loaiquatang)
                VALUES (" . $id_max . ",'" . $hinhanh_path . "','" . $tensp . "','" . $hsd . "','" . $giaban . "'," . $loaiquatang . ")";

                $ket_qua_id = mysqli_query($configDTB, $sql_id_moi);

                //thêm dữ liệu chi tiết 

                //tạo id tự động của trang chi tiết
                $sql_id = "SELECT MAX(id_spquatang) AS id_max FROM chitietquatang";
                $result_id = mysqli_query($configDTB, $sql_id);
                $rows = mysqli_fetch_array($result_id);
                $id_ct_qt = $rows['id_max'] + 1;
                //tạo id ct moi                  
                $sql_id_ct = "INSERT INTO chitietquatang (id_spquatang, id_quatang, hinhanh, tensp, giaban, noidunghinhanh, SLmuatoithieu, hsd, hdsd, chitietsp)
                VALUES (" . $id_ct_qt . "," . $id_max . ",'" . $hinhanh_path . "','" . $tensp . "','" . $giaban . "','" . $nd_hinhanh . "','" . $SL_muatoithieu . "','" . $hsd . "','" . $hdsd . "','" . $ct_sp . "')";
                $ket_qua_id_ct = mysqli_query($configDTB, $sql_id_ct);
                if ($ket_qua_id && $ket_qua_id_ct) {
                    $_SESSION['add_quatang'] = "Thêm quà tặng mới thành công";
                } else {
                    echo "lỗi nặng";
                }
            } else {
                echo "Lưu ảnh không thành công ! ";
            }
        }
    }
}
?>
<main>
    <div class="add_phim">
        <p>Thêm quà tặng</p>
        <span class="thong_bao">
            <?php
            echo !empty($_SESSION['add_quatang']) ?  $_SESSION['add_quatang'] : '';
            unset($_SESSION['add_quatang']);
            ?>
        </span>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="">Hình ảnh </label>
            <input type="file" name="hinhanh" required>
            <span style="color: red;">
                <?php
                if (isset($_SESSION['ERROR_img'])) {
                    echo ($_SESSION['ERROR_img']);
                    unset($_SESSION['ERROR_img']);
                }
                ?>
            </span>

            <label for="">Tên sản phẩm </label>
            <input type="text" name="name" value="<?php echo !empty($_POST['name']) ? $_POST['name'] : '' ?>" required placeholder="Nhập tên sản phẩm">
            <span style="color: red;">
                <?php
                if (isset($_SESSION['error_tenquatang'])) {
                    echo ($_SESSION['error_tenquatang']);
                    unset($_SESSION['error_tenquatang']);
                }
                ?>
            </span>

            <label for="">Hạn sử dụng </label>
            <input type="text" name="hsd" value="<?php echo !empty($_POST['hsd']) ? $_POST['hsd'] : '' ?>" required placeholder="Nhập hạn sử dụng">

            <label for="">Giá bán </label>
            <input type="float" name="giaban" value="<?php echo !empty($_POST['giaban']) ? $_POST['giaban'] : '' ?>" required placeholder="Nhập giá bán">

            <label for="">Loại quà tặng</label>
            <?php
            $sql1 = "SELECT * FROM danh_muc_km LIMIT 2,3 ";
            $ket_qua = mysqli_query($configDTB, $sql1);
            ?>
            <select name="loaiquatang" required>
                <?php
                while ($rows = mysqli_fetch_array($ket_qua)) {
                ?>
                    <option value="<?php echo $rows['id_danh_muc'] ?>"><?php echo $rows['danh_muc'] ?></option>
                <?php
                }
                ?>
            </select>

            <label for="">Nội dung sản phẩm</label>
            <input type="text" name="nd_hinhanh" value="<?php echo !empty($_POST['nd_hinhanh']) ? $_POST['nd_hinhanh'] : '' ?>" required placeholder="Nhập nội dung sản phẩm">

            <label for="">Số lượng mua tối thiểu</label>
            <input type="text" name="SL_muatoithieu" value="<?php echo !empty($_POST['SL_muatoithieu']) ? $_POST['SL_muatoithieu'] : '' ?>" required placeholder="Nhập số lượng mua tối thiểu">

            <label for="">Hướng dẫn sử dụng</label>
            <input type="text" name="hdsd" value="<?php echo !empty($_POST['hdsd']) ? $_POST['hdsd'] : '' ?>" required placeholder="Nhập hướng dẫn sử dụng">

            <label for="">Chi tiết sản phẩm</label>
            <input type="text" name="ct_sp" value="<?php echo !empty($_POST['ct_sp']) ? $_POST['ct_sp'] : '' ?>" required placeholder="Nhập chi tiết sản phẩm">
            <div class="submit">
                <button type="submit">Thêm</button>
                <a href="?module=admin&action=quanlyquatang">Quay lại</a>
            </div>
        </form>
    </div>
</main>
<?php
layout('footer_admin');
?>