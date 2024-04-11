<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Thay đổi tên trang tương ứng
$data = [
    'ten_trang' => 'Shop quà tặng | Movie Booking'
];
layout('header', $data);
$sql = "SELECT * FROM quatang WHERE loaiquatang = '3'";
$ket_qua = mysqli_query($configDTB, $sql);

$sql = "SELECT * FROM danh_muc_km WHERE id_danh_muc = '3'"; //goi ten danh muc 4
$result = mysqli_query($configDTB, $sql);
$row = mysqli_fetch_array($result);
?>
<link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/shopquatang.css?ver=<?php echo rand(); ?>">
<div class="tieude">
    <a href="#banchay" class="tieudebanchay" style=" font-family: Arial, Helvetica, sans-serif;">Bán chạy</a>
    <a href="#combohot" style=" font-family: Arial, Helvetica, sans-serif;">Combo hot</a>
</div>
<p class="name" id="banchay" style=" font-family: Arial, Helvetica, sans-serif;"><?php echo $row['danh_muc'] ?></p>
<div class="banchay">
    <?php
    while ($rows = mysqli_fetch_array($ket_qua)) {
    ?>
        <div class="list">
            <a href="?module=home&action=ctshopquatang&maqt=<?php echo $rows['id_quatang']; ?>"><img src="<?php echo _WEB_HOST_TEMPLATES ?>/images/quatang<?php echo $rows['hinhanh'] ?>" alt="" width="225px"></a>
            <span class="tensp"><b><?php echo $rows['tensp'] ?></b></span>
            <span class="hsd"><?php echo $rows['hsd'] ?></span>
            <a href="?module=home&action=ctshopquatang&maqt=<?php echo $rows['id_quatang']; ?>" class="muangay">Giá bán online : <?php echo $rows['giaban'] ?>đ</a>
        </div>
    <?php
    }
    ?>
</div>

<?php
$sql = "SELECT * FROM quatang WHERE loaiquatang = '4'";
$ket_qua = mysqli_query($configDTB, $sql);

$sql = "SELECT * FROM danh_muc_km WHERE id_danh_muc = '4'"; //goi ten danh muc 4
$result = mysqli_query($configDTB, $sql);
$row = mysqli_fetch_array($result);
?>
<p class="name" id="combohot" style=" font-family: Arial, Helvetica, sans-serif;"><?php echo $row['danh_muc'] ?></p>
<div class="banchay">
    <?php
    while ($rows = mysqli_fetch_array($ket_qua)) {
    ?>
        <div class="list">
            <a href="?module=home&action=ctshopquatang&maqt=<?php echo $rows['id_quatang']; ?>"><img src="<?php echo _WEB_HOST_TEMPLATES ?>/images/quatang<?php echo $rows['hinhanh'] ?>" alt="" width="220px"></a>
            <span class="tensp"><b><?php echo $rows['tensp'] ?></b></span>
            <span class="hsd"><?php echo $rows['hsd'] ?></span>
            <a href="?module=home&action=ctshopquatang&maqt=<?php echo $rows['id_quatang']; ?>" class="muangay">Giá bán online : <?php echo $rows['giaban'] ?>đ</a>
        </div>
    <?php
    }
    ?>
</div>
<?php
layout('footer');
?>