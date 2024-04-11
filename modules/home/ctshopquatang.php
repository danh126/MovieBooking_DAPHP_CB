<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Thay đổi tên trang tương ứng
$data = [
    'ten_trang' => 'Chi tiết quà tặng | Movie Booking'
];
layout('header', $data);
if (isset($_GET['maqt'])) {
    $maqt = $_GET['maqt'];
    $sql = "SELECT * FROM chitietquatang WHERE id_quatang = '" . $maqt . "' ";
    $ket_qua = mysqli_query($configDTB, $sql);
?>
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/ctshopquatang.css?ver=<?php echo rand(); ?>">


    <div class="chitietquatang">
        <?php
        while ($rows = mysqli_fetch_array($ket_qua)) // vong lap while 
        {
        ?>
            <div class="img_noidung_ctiet">
                <div class="hienthisp">
                    <img src="<?php echo _WEB_HOST_TEMPLATES ?>/images/quatang<?php echo $rows['hinhanh'] ?>" alt="">
                    <p class="noidung"><?php echo $rows['noidunghinhanh']; ?></p>
                    <p class="sluong"><?php echo $rows['SLmuatoithieu']; ?></p>
                    <p class="hsd"><?php echo $rows['hsd']; ?></p>
                </div>
                <div class="huongdansd">
                    <span><?php echo $rows['hdsd']; ?></span>
                </div>
                <div class="huongdansdchitiet">
                    <span><?php echo $rows['chitietsp']; ?></span>
                </div>
            </div>
            <div class="ctspquatang">
                <div class="sp_giaban">
                    <p class="tensp"><b><?php echo $rows['tensp'] ?></b></p>
                    <hr style="margin-left: 10px;">
                    <p class="giaban">Giá bán online:&nbsp;<?php echo $rows['giaban']; ?> &nbsp;VNĐ</p>
                </div>
                <div class="soluong_muangay ">
                    <form action="?module=home&action=datvequatang" method="post">
                        <div class="soluong">
                            <p><b> Số lượng: &nbsp;</b><input type="number" name="so_luong" value="1" max="50" min="1"></p>
                            <input type="hidden" name="id_quatang" value="<?php echo $rows['id_quatang'] ?>">
                        </div>
                        <div class="Muangay">
                            <input type="submit" value="Mua ngay ">
                            <a href="?module=home&action=shopquatang">Danh sách quà tặng</a>
                        </div>
                    </form>
                </div>
                <!-- <div class="danhsachquatang">
                <a href="?module=home&action=shopquatang">Danh sách quà tặng</a>
            </div> -->
            </div>
        <?php
        };
        mysqli_close($configDTB);
        ?>
    </div>
<?php
} else {
    header('location: ?module=home&action=shopquatang');
}
layout('footer');
?>