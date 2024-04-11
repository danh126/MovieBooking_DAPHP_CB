<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Thay đổi tên trang tương ứng
$data = [
    'ten_trang' => 'Xóa phim | Movie Booking'
];
layout('header_admin', $data);

//Nếu không tồn tại loginAdmin thì quay về trang login
if (!checkLogin('login_admin')) {
    header('location: ?module=admin&action=dangnhap');
}

//Tìm tổng số mẫu tin
$sql = "SELECT count(id_phim) AS SMT FROM danh_sach_phim";
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

    $sql = "SELECT * FROM danh_sach_phim ORDER BY id_phim LIMIT $start,$limit";
    $ket_qua = mysqli_query($configDTB, $sql);
    $smt = mysqli_num_rows($ket_qua);
?>
    <main>
        <form action="" method="post">
            <div class="list">
                <div class="tieu_de">
                    <p>Danh sách phim</p>
                </div>
                <hr>
                <table>
                    <tr>
                        <th>STT</th>
                        <th>ID PHIM</th>
                        <th>TÊN PHIM</th>
                        <th>NGÀY CHIẾU</th>
                        <th>HÌNH ẢNH</th>
                        <th>THỜI LƯỢNG</th>
                        <th>HIỆN TRẠNG</th>
                        <th>XÓA</th>
                    </tr>
                    <?php
                    if ($smt > 0) {
                        $stt = 0;
                        while ($rows = mysqli_fetch_array($ket_qua)) {
                            $stt++;
                    ?>
                            <tr>
                                <td style="text-align: center;"><?php echo $stt ?></td>
                                <td style="text-align: center;"><?php echo $rows['id_phim']; ?></td>
                                <td><?php echo $rows['ten_phim']; ?></td>
                                <td style="text-align: center;"><?php echo $rows['ngay_chieu']; ?></td>
                                <td><?php echo $rows['hinh_anh']; ?></td>
                                <td style="text-align: center;"><?php echo $rows['thoi_luong']; ?></td>
                                <td style="text-align: center;"><?php echo $rows['hien_trang']; ?></td>
                                <td>
                                    <input type="hidden" name="ten_phim" value="<?php echo $rows['ten_phim']; ?>">
                                    <input type="checkbox" name="delete_phim[]" value="<?php echo $rows['id_phim']; ?>">
                                </td>
                            </tr>
                    <?php
                        } //KT while
                    } //KT if
                    else {
                        echo "<p style='text-align: center;'> Không có dữ liệu </p>";
                    }
                    ?>
                </table>
                <p class="note">Hiện trạng: Đang chiếu (1), sắp chiếu(0).</p>
                <div class="quay_lai">
                    <a href="?module=admin&action=quanlyphim">Quản lý phim</a>
                    <button onClick="return confirm('Bạn có chắc chắn muốn xóa không ?')">Xóa phim</button>
                </div>
        </form>
        <?php
        //Xử lý xóa phim
        if (!empty($_SERVER['REQUEST_METHOD'] == "POST")) {
            if (isset($_POST['delete_phim']) != '') {
                $smt_check = array();
                $smt_check = count($_POST['delete_phim']);
                for ($i = 0; $i < $smt_check; $i++) {
                    $del_id = $_POST['delete_phim'][$i];
                    $ten_phim = $_POST['ten_phim'];

                    // //Kiểm tra xem id_phim có tồn tại trong bảng ca_chieu hay không COUNT(*)
                    $sql = "SELECT COUNT(*) as count FROM ca_chieu WHERE id_phim = $del_id";
                    $ket_qua_1 = mysqli_query($configDTB, $sql);
                    $row1 = mysqli_fetch_array($ket_qua_1);
                    $count1 = $row1['count'];

                    // //Kiểm tra xem id_phim có tồn tại trong bảng chi_tiet_phim hay không COUNT(*)
                    // $sql = "SELECT COUNT(*) as count FROM chi_tiet_phim WHERE id_phim = $del_id";
                    // $ket_qua_2 = mysqli_query($configDTB, $sql);
                    // $row2 = mysqli_fetch_array($ket_qua_2);
                    // $count2 = $row2['count'];

                    //Nếu không tồn tại trong 2 bảng trên thì tiến hành xóa phim
                    if ($count1 == 0) {
                        $sql = "DELETE FROM danh_sach_phim WHERE id_phim ='" . $del_id . "'";
                        $ket_qua = mysqli_query($configDTB, $sql);
                        if ($ket_qua) {
                            echo "<span style='color:red;'>Đã xóa phim <span style='color:blue;'> $ten_phim </span> <span> thành công!" . '<br>';
                        }
                    } else {
                        echo "<span style='color:red;'>Không thể xóa phim <span style='color:blue;'> $ten_phim </span> do phim đang liên kết đến bảng khác!<span>" . '<br>';
                    }
                    // $sql = "DELETE FROM danh_sach_phim WHERE id_phim ='" . $del_id . "'";
                    // $ket_qua = mysqli_query($configDTB, $sql);
                    // if ($ket_qua) {
                    //     echo "<span style='color:red;'>Đã xóa phim có id là: $del_id.<span>" . '<br>';
                    // }
                }
            } else {
                echo "<span style='color:red;'>Vui lòng chọn các mục cần xóa! </span>";
            }
        }
        mysqli_close($configDTB); //đóng database
        ?>
        <div class="phan_trang">
            <?php
            //Nếu vtri trang > 1 và tổng trang > 1 thì hiện nút quay lại
            if ($vt_page > 1 && $total_page > 1) {
                echo '<a href="?module=admin&action=deletephim&page=' . ($vt_page - 1) . '"> < </a>';
            }
            //Lập khoảng giữa 
            for ($i = 1; $i <= $total_page; $i++) {
                if ($i == $vt_page) {
                    echo '<span>' . $i . '</span>';
                } else {
                    echo '<a href="?module=admin&action=deletephim&page=' . $i . '">' . $i . '</a>';
                }
            }
            //Nếu vtri trang < tổng trang và tổng trang > 1 thì hiện nút trang tiếp
            if ($vt_page < $total_page && $total_page > 1) {
                echo '<a href="?module=admin&action=deletephim&page=' . ($vt_page + 1) . '"> > </a>';
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