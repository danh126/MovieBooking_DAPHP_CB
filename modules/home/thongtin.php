<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Thay đổi tên trang tương ứng
$data = [
    'ten_trang' => 'Thông tin cá nhân | Movie Booking'
];
layout('header', $data);

//Kiểm tra lgoin
if (checkLogin('login_user')) {
?>
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/thongtin.css?ver=<?php echo rand(); ?>">
    <div class="container">
        <?php
        if (!empty($_SESSION['id_user'])) {
            $id_user = $_SESSION['id_user'];
            $sql = "SELECT * FROM login_user WHERE id_user = '$id_user'";
            $ket_qua = mysqli_query($configDTB, $sql);
            $row = mysqli_fetch_array($ket_qua);
            $email = $row['email'];
        }
        ?>
        <div class="profile-picture">
            <img src="<?php echo _WEB_HOST_TEMPLATES ?>/images/avt/macdinh.jpg" alt="Avatar">
        </div>
        <div class="profile-info">
            <span style="color:rgb(154, 26, 26);">
                <?php
                echo !empty($_SESSION['cap_nhat_user']) ? $_SESSION['cap_nhat_user'] : '';
                unset($_SESSION['cap_nhat_user']);
                ?>
            </span>
            <h1><?php echo $row['ho_ten'] ?></h1>
            <p>Giới tính: <?php echo $row['gioi_tinh'] ?> </p>
            <p>Email: <?php echo $row['email'] ?> </p>
            <p>Địa chỉ: <?php echo $row['dia_chi_user'] ?> </p>
            <p>Số điện thoại: <?php echo $row['so_dien_thoai_user'] ?></p>
            <a href="?module=home&action=capnhatthongtin">Cập nhật thông tin</a>
        </div>
        <div class="thong_tin_dat_ve">
            <p class="ttdv_tieu_de">Danh sách phim của bạn </p>
            <?php
            $sql = "SELECT * FROM thong_tin_dat_ve WHERE email_KH = '$email'";
            $ket_qua_2 = mysqli_query($configDTB, $sql);
            $smt = mysqli_num_rows($ket_qua_2);
            if ($smt > 0) {
                $phim = array();
                while ($rows = mysqli_fetch_array($ket_qua_2)) {
                    $ten_phim = $rows['ten_phim'];
                    $phong = $rows['ten_phong'];
                    $thoi_gian_bd = $rows['thoi_gian_bd'];
                    $thoi_gian_kt = $rows['thoi_gian_kt'];
                    $ngaychieu = $rows['ngay_chieu'];
                    $soluong = $rows['ghe_da_chon'];

                    // Chuyển đổi thời gian sang timestamp
                    $timestamp = strtotime($ngaychieu);
                    // Định dạng ngày
                    $ngaychieu_FM = date('d-m-Y', $timestamp);

                    // Chuyển đổi thời gian sang timestamp
                    $timestamp_1 = strtotime($thoi_gian_bd);
                    // Định dạng giờ và phút
                    $formattedTime_gio_bd = date('H:i', $timestamp_1);

                    // Chuyển đổi thời gian sang timestamp
                    $timestamp_2 = strtotime($thoi_gian_kt);
                    // Định dạng giờ và phút
                    $formattedTime_gio_kt = date('H:i', $timestamp_2);

                    if (!isset($phim[$ten_phim])) {
                        $phim[$ten_phim] = array();
                    }
                    if (!isset($phim[$ten_phim][$phong])) {
                        $phim[$ten_phim][$phong] = array();
                    }
                    if (!isset($phim[$ten_phim][$phong][$ngaychieu_FM])) {
                        $phim[$ten_phim][$phong][$ngaychieu_FM] = array();
                    }
                    if (!isset($phim[$ten_phim][$phong][$ngaychieu_FM][$formattedTime_gio_bd])) {
                        $phim[$ten_phim][$phong][$ngaychieu_FM][$formattedTime_gio_bd] = array();
                    }
                    if (!isset($phim[$ten_phim][$phong][$ngaychieu_FM][$formattedTime_gio_bd][$formattedTime_gio_kt])) {
                        $phim[$ten_phim][$phong][$ngaychieu_FM][$formattedTime_gio_bd][$formattedTime_gio_kt] = array();
                    }
                    $phim[$ten_phim][$phong][$ngaychieu_FM][$formattedTime_gio_bd][$formattedTime_gio_kt][] = $soluong;
                }
                foreach ($phim as $ten_phim => $phongs) {
            ?>
                    <p><span>Tên phim:</span> <?php echo $ten_phim ?></p>
                    <?php
                    foreach ($phongs as $phong => $ngaychieu_s) {
                    ?>
                        <p><span>Tên phòng:</span> <?php echo $phong ?></p>
                        <?php
                        foreach ($ngaychieu_s as $ngaychieu_FM  => $formattedTime_gio_bd_s) {
                        ?>
                            <p><span>Ngày chiếu:</span> <?php echo $ngaychieu_FM  ?></p>
                            <?php
                            foreach ($formattedTime_gio_bd_s as $formattedTime_gio_bd => $formattedTime_gio_kt_s) {
                            ?>
                                <?php
                                foreach ($formattedTime_gio_kt_s as $formattedTime_gio_kt => $soluong_s) {
                                ?>
                                    <p><span>Thời gian:</span> <?php echo $formattedTime_gio_bd ?> - <?php echo $formattedTime_gio_kt ?></p>
                                    <?php
                                    foreach ($soluong_s as $soluong) {
                                    ?>
                                        <p><span>Số lượng vé:</span> <?php echo $soluong ?></p>
                                        <hr>
            <?php
                                    }
                                }
                            }
                        }
                    }
                }
            } //KT $smt
            else {
                echo "<p>Danh sách phim của bạn đang trống!</p>";
            }
            ?>
        </div>
        <div class="vequatang">
            <?php
            $sql = "SELECT * FROM ve_qua_tang WHERE email = '$email'";
            $ket_qua_2 = mysqli_query($configDTB, $sql);
            $smt = mysqli_num_rows($ket_qua_2);
            if ($smt > 0) {
            ?>
                <p>Danh sách vé quà tặng của bạn</p>
                <table>
                    <thead>
                        <tr>
                            <th>Tên vé</th>
                            <th>Số lượng hiện có</th>
                            <th>Thời gian sử dụng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_array($ket_qua_2)) :
                            $tenve = $row['ten_ve'];
                            $sql = "SELECT hsd FROM quatang WHERE tensp = '$tenve'";
                            $ket_qua_3 = mysqli_query($configDTB, $sql);
                            while ($row2 = mysqli_fetch_array($ket_qua_3)) :
                        ?>
                                <tr>
                                    <td><?= $row['ten_ve'] ?></td>
                                    <td style="text-align: center;"><?= $row['soluong'] ?></td>
                                    <td style="text-align: center;"><?= $row2['hsd'] ?></td>
                                </tr>
                        <?php
                            endwhile;
                        endwhile;
                        ?>
                    </tbody>
                </table>
            <?php
            } else {
                echo "";
            }
            ?>
        </div>
    </div>
<?php
} else {
    header('location: ?module=home&action=dangnhap');
}
layout('footer');
?>