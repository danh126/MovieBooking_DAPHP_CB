<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Thay đổi tên trang tương ứng
$data = [
    'ten_trang' => 'Khuyến mãi | Movie Booking'
];
layout('header', $data);
// $sql = "SELECT * FROM khuyen_mai WHERE id_danh_muc = '1'";
// $result = mysqli_query($configDTB, $sql);
?>
<link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/khuyenmai.css?ver=<?php echo rand(); ?>">
<div class="main-content">
    <div class="khuyenmai-header">

        <?php
        $sql = "SELECT * FROM danh_muc_km WHERE id_danh_muc = '2'"; //goi ten danh muc 1
        $result = mysqli_query($configDTB, $sql);
        while ($rows = mysqli_fetch_array($result)) {
        ?>
            <p class="khuyenmai_le"><?php echo $rows['danh_muc']; ?></p>
        <?php
        }
        ?>

        <div class="row">
            <?PHP
            $sql = "SELECT * FROM khuyen_mai WHERE loai_khuyen_mai = '2'"; //goi len ve khuyen mai
            $result = mysqli_query($configDTB, $sql);
            //Dua tung mau tin vao rows
            while ($rows = mysqli_fetch_array($result)) {
                $ngay_bd = $rows['ngay_bat_dau'];
                $ngay_kt = $rows['ngay_ket_thuc'];
                // Chuyển đổi thời gian sang timestamp
                $timestamp = strtotime($ngay_bd);
                // Định dạng ngày
                $ngaybd_FM = date('d-m-Y', $timestamp);

                // Chuyển đổi thời gian sang timestamp
                $timestamp_1 = strtotime($ngay_kt);
                // Định dạng ngày
                $ngaykt_FM = date('d-m-Y', $timestamp_1);
            ?>

                <div class="cols">
                    <div class="table">
                        <a href="?module=home&action=ctkhuyenmai&makm=<?php echo $rows['id_km']; ?>"><img src="<?php echo _WEB_HOST_TEMPLATES;
                                                                                                                echo $rows['hinh_anh']; ?>" alt="database error" class="image"></a>
                        <p class="ngay"><?php echo $ngaybd_FM; ?> ~ <?php echo $ngaykt_FM; ?> </p>
                    </div>
                </div>

            <?PHP
            } //KT While
            ?>
        </div>
    </div>

    <div class="khuyenmai_p">

        <?php
        $sql = "SELECT * FROM danh_muc_km WHERE id_danh_muc = '1'"; //goi ten danh muc 2
        $result = mysqli_query($configDTB, $sql);
        while ($rows = mysqli_fetch_array($result)) {
        ?>
            <p class="khuyenmai_phim"><?php echo $rows['danh_muc']; ?></p>
        <?php
        }
        ?>

        <div class="rows">

            <?php
            $sql = "SELECT * FROM khuyen_mai WHERE loai_khuyen_mai = '1'"; //goi len rap phim khuyen mai
            $result = mysqli_query($configDTB, $sql);

            while ($rows = mysqli_fetch_array($result)) {
                $ngay_bd = $rows['ngay_bat_dau'];
                $ngay_kt = $rows['ngay_ket_thuc'];
                // Chuyển đổi thời gian sang timestamp
                $timestamp = strtotime($ngay_bd);
                // Định dạng ngày
                $ngaybd_FM = date('d-m-Y', $timestamp);

                // Chuyển đổi thời gian sang timestamp
                $timestamp_1 = strtotime($ngay_kt);
                // Định dạng ngày
                $ngaykt_FM = date('d-m-Y', $timestamp_1);
            ?>

                <div class="col">
                    <div class="table1">
                        <a href="?module=home&action=ctkhuyenmai&makm=<?php echo $rows['id_km']; ?>"><img src="<?php echo _WEB_HOST_TEMPLATES;
                                                                                                                echo $rows['hinh_anh']; ?>" alt="database error" class="image_phim"></a>
                        <p class="ngay"><?php echo $ngaybd_FM; ?> ~ <?php echo $ngaykt_FM; ?> </p>
                    </div>
                </div>

            <?php
            }
            mysqli_close($configDTB);
            ?>
        </div>

    </div>

</div>
<?php
layout('footer');
?>