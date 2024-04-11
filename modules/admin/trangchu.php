<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Thay đổi tên trang tương ứng
$data = [
    'ten_trang' => 'Trang chủ admin | Movie Booking'
];
layout('header_admin', $data);

//Nếu không tồn tại loginAdmin thì quay về trang login
if (!checkLogin('login_admin')) {
    header('location: ?module=admin&action=dangnhap');
}

?>
<main>
    <div class="background">
        <?php
        $sql = "SELECT duong_dan FROM banner WHERE loai_banner = 'dangnhap'";
        $ket_qua = mysqli_query($configDTB, $sql);
        $link_img = mysqli_fetch_array($ket_qua);
        ?>
        <img src="<?php echo _WEB_HOST_TEMPLATES;
                    echo $link_img['duong_dan'] ?>" alt="database_error">
    </div>
    <div class="thong_ke">
        <div class="phim">
            <?php
            $sql = "SELECT id_phim FROM danh_sach_phim";
            $ket_qua = mysqli_query($configDTB, $sql);
            $smt_phim = mysqli_num_rows($ket_qua);
            ?>
            <h4>Tổng số phim</h4>
            <span><?php echo $smt_phim; ?></span>
        </div>
        <div class="ca_chieu">
            <?php
            $sql = "SELECT id_cachieu FROM ca_chieu";
            $ket_qua = mysqli_query($configDTB, $sql);
            $smt_ca_chieu = mysqli_num_rows($ket_qua);
            ?>
            <h4>Tổng số ca chiếu</h4>
            <span><?php echo $smt_ca_chieu; ?></span>
        </div>
        <div class="shop_qua_tang">
            <?php
            $sql = "SELECT id_quatang FROM quatang";
            $ket_qua = mysqli_query($configDTB, $sql);
            $smt_qua_tang = mysqli_num_rows($ket_qua);
            ?>
            <h4>Tổng số shop quà tặng</h4>
            <span><?php echo $smt_qua_tang; ?></span>
        </div>
        <div class="khuyen_mai">
            <?php
            $sql = "SELECT id_km FROM khuyen_mai";
            $ket_qua = mysqli_query($configDTB, $sql);
            $smt_khuyen_mai = mysqli_num_rows($ket_qua);
            ?>
            <h4>Tổng số khuyến mãi</h4>
            <span><?php echo $smt_khuyen_mai; ?></span>
        </div>
    </div>
</main>
<?php
layout('footer_admin');
?>