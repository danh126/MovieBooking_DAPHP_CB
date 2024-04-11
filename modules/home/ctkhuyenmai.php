<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Thay đổi tên trang tương ứng
$data = [
    'ten_trang' => 'Chi tiết khuyến mãi | Movie Booking'
];
layout('header', $data);

$ma = $_GET['makm'];
$sql = "SELECT * FROM chi_tiet_km WHERE km_so = '" . $ma . "' ";
$result = mysqli_query($configDTB, $sql);
$smt = mysqli_num_rows($result);
if ($smt > 0) {
?>
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/chitietkm.css?ver=<?php echo rand(); ?>">
    <div class="container">
        <?php
        #$ctkm = $_GET['idkm']; // gọi mã khi bấm vào vé khuyên mãi
        while ($rows = mysqli_fetch_array($result)) // vong lap while 
        {
        ?>
            <div class="chitietkm">
                <div class="header">
                    <p class="title"><?php echo $rows['ten_km']; ?></p>
                </div>
                <div class="image">
                    <img src="<?php echo _WEB_HOST_TEMPLATES;
                                echo $rows['hinh_anh']; ?>" alt="" class="big-img">
                </div>
                <div class="km-infos">
                    <p class="infos"><?php echo $rows['noi_dung1']; ?></p>
                    <p class="infos"><?php echo $rows['noi_dung2']; ?></p>
                    <p class="infos"><?php echo $rows['noi_dung3']; ?></p>
                    <p class="infos"></p>
                    <!-- <span class="footer">
                        <p class="infos-footer"></p>
                    </span> -->
                </div>
            </div>
        <?php
        };
        ?>
        <div class="goback">
            <a href="?module=home&action=khuyenmai" class="back">Danh sách khuyến mãi</a>
        </div>

    <?php
    mysqli_close($configDTB);
} else {
    echo '<div style="border: 2px solid blue;color: blue; font-size 24px; font-weight: 500; text-align: center; padding: 5px 20px;">';
    echo 'Không co du lieu';
    echo '</div>';
}
    ?>
    </div>
    <?php
    layout('footer');
    ?>