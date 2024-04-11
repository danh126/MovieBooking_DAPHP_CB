<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Thay đổi tên trang tương ứng
$data = [
    'ten_trang' => 'Quản lý lịch chiếu | Movie Booking'
];
layout('header_admin', $data);

//Nếu không tồn tại loginAdmin thì quay về trang login
if (!checkLogin('login_admin')) {
    header('location: ?module=admin&action=dangnhap');
}

//Tìm tổng số mẫu tin
$sql = "SELECT count(id_cachieu) AS SMT FROM ca_chieu";
$ket_qua = mysqli_query($configDTB, $sql);
$row = mysqli_fetch_array($ket_qua);
$tong_SMT = $row['SMT'];
if ($tong_SMT > 0) {

    //Tìm tổng số trang và truyền limit
    $vt_page = isset($_GET['page']) ? $_GET['page'] : 1;
    $limit = 10;

    //Tính tổng trang 

    $total_page = ceil($tong_SMT / $limit);

    //giới hạn vtri trang từ 1 -> tổng trang
    if ($vt_page > $total_page) {
        $vt_page = $total_page;
    } else {
        if ($vt_page < 1) {
            $vt_page = 1;
        }
    }

    //Tìm vị trí bắt đầu

    $start = ($vt_page - 1) * $limit;

    $sql = "SELECT * FROM ca_chieu ORDER BY id_cachieu LIMIT $start,$limit";
    $ket_qua = mysqli_query($configDTB, $sql);
    $smt = mysqli_num_rows($ket_qua);
?>
    <main>
        <div class="list">
            <div class="tieu_de">
                <p>Danh sách lịch chiếu</p>
                <span>Số lượng ca chiếu: <?php echo $smt ?></span>
            </div>
            <hr>
            <table>
                <tr>
                    <th>STT</th>
                    <th>ID CA CHIẾU</th>
                    <th>ID PHIM</th>
                    <th>ID PHÒNG</th>
                    <th>NGÀY CHIẾU</th>
                    <th>GIỜ BẮT ĐẦU</th>
                    <th>GIỜ KẾT THÚC</th>
                </tr>
                <?php
                if ($smt > 0) {
                    $stt = 0;
                    while ($rows = mysqli_fetch_array($ket_qua)) {
                        $stt++;
                ?>
                        <tr>
                            <td style="text-align: center;"><?php echo $stt ?></td>
                            <td style="text-align: center;"><?php echo $rows['id_cachieu']; ?></td>
                            <td style="text-align: center;"><?php echo $rows['id_phim']; ?></td>
                            <td style="text-align: center;"><?php echo $rows['id_phong']; ?></td>
                            <td style="text-align: center;"><?php echo $rows['ngay_chieu']; ?></td>
                            <td style="text-align: center;"><?php echo $rows['gio_bd']; ?></td>
                            <td style="text-align: center;"><?php echo $rows['gio_kt']; ?></td>
                        </tr>
                <?php
                    } //KT while
                } //KT if
                else {
                    echo "<p style='text-align: center;'> Không có dữ liệu </p>";
                }
                mysqli_close($configDTB); //đóng database
                ?>
            </table>
            <div class="tac_vu">
                <a href="?module=admin&action=themlichchieu">Thêm</a>
                <a href="?module=admin&action=editlichchieu">Sửa</a>
                <a href="?module=admin&action=deletelichchieu">Xóa</a>
            </div>
            <br><br>
            <div class="phan_trang">
                <?php
                //Nếu vtri trang > 1 và tổng trang > 1 thì hiện nút quay lại
                if ($vt_page > 1 && $total_page > 1) {
                    echo '<a href="?module=admin&action=quanlylichchieu&page=' . ($vt_page - 1) . '"> < </a>';
                }
                //Lập khoảng giữa 
                for ($i = 1; $i <= $total_page; $i++) {
                    if ($i == $vt_page) {
                        echo '<span>' . $i . '</span>';
                    } else {
                        echo '<a href="?module=admin&action=quanlylichchieu&page=' . $i . '">' . $i . '</a>';
                    }
                }
                //Nếu vtri trang < tổng trang và tổng trang > 1 thì hiện nút trang tiếp
                if ($vt_page < $total_page && $total_page > 1) {
                    echo '<a href="?module=admin&action=quanlylichchieu&page=' . ($vt_page + 1) . '"> > </a>';
                }
                ?>
            </div>
        </div>
    </main>
<?php
} else {
    echo "<p style='text-align:center;'> Không có dữ liệu! <p>";
}
layout('footer_admin');
?>