<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Thay đổi tên trang tương ứng
$data = [
    'ten_trang' => 'Xóa lịch chiếu | Movie Booking'
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

    $sql = "SELECT * FROM ca_chieu ORDER BY id_phim LIMIT $start,$limit";
    $ket_qua = mysqli_query($configDTB, $sql);
    $smt = mysqli_num_rows($ket_qua);
?>
    <main>
        <form action="" method="post">
            <div class="list">
                <div class="tieu_de">
                    <p>Danh sách lịch chiếu</p>
                </div>
                <hr>
                <table>
                    <tr>
                        <th>STT</th>
                        <th>ID CA CHIẾU</th>
                        <th>ID PHIM</th>
                        <th>TÊN PHIM</th>
                        <th>ID PHÒNG</th>
                        <th>NGÀY CHIẾU</th>
                        <th>GIỜ BẮT ĐẦU</th>
                        <th>GIỜ KẾT THÚC</th>
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
                                <td style="text-align: center;"><?php echo $rows['id_cachieu']; ?></td>
                                <td style="text-align: center;"><?php echo $rows['id_phim']; ?></td>
                                <?php
                                $id_phim =  $rows['id_phim'];
                                $sql = "SELECT ten_phim FROM danh_sach_phim WHERE id_phim = '$id_phim'";
                                $ket_qua_1 = mysqli_query($configDTB, $sql);

                                while ($row = mysqli_fetch_array($ket_qua_1)) {
                                ?>
                                    <td><?php echo $row['ten_phim']; ?></td>
                                <?php
                                }
                                ?>
                                <td style="text-align: center;"><?php echo $rows['id_phong']; ?></td>
                                <td style="text-align: center;"><?php echo $rows['ngay_chieu']; ?></td>
                                <td style="text-align: center;"><?php echo $rows['gio_bd']; ?></td>
                                <td style="text-align: center;"><?php echo $rows['gio_kt']; ?></td>
                                <td>
                                    <input type="checkbox" name="delete_cachieu[]" value="<?php echo $rows['id_cachieu']; ?>">
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
                <div class="quay_lai">
                    <a href="?module=admin&action=quanlylichchieu" style="width: 150px;">Quản lý lịch chiếu</a>
                    <button onClick="return confirm('Bạn có chắc chắn muốn xóa không ?')">Xóa lịch chiếu</button>
                </div>
        </form>
        <?php
        //Xử lý xóa phim
        if (!empty($_SERVER['REQUEST_METHOD'] == "POST")) {
            if (isset($_POST['delete_cachieu']) != '') {
                $smt_check = array();
                $smt_check = count($_POST['delete_cachieu']);
                for ($i = 0; $i < $smt_check; $i++) {
                    $del_id = $_POST['delete_cachieu'][$i];
                    $sql = "DELETE FROM ca_chieu WHERE id_cachieu ='$del_id'";
                    $ket_qua = mysqli_query($configDTB, $sql);
                    if ($ket_qua) {
                        echo "<span style='color:red;'>Đã xóa ca chiếu có id là: <span style='color:blue;'>$del_id</span> thành công!</span>" . '<br>';
                    }
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
                echo '<a href="?module=admin&action=deletelichchieu&page=' . ($vt_page - 1) . '"> < </a>';
            }
            //Lập khoảng giữa 
            for ($i = 1; $i <= $total_page; $i++) {
                if ($i == $vt_page) {
                    echo '<span>' . $i . '</span>';
                } else {
                    echo '<a href="?module=admin&action=deletelichchieu&page=' . $i . '">' . $i . '</a>';
                }
            }
            //Nếu vtri trang < tổng trang và tổng trang > 1 thì hiện nút trang tiếp
            if ($vt_page < $total_page && $total_page > 1) {
                echo '<a href="?module=admin&action=deletelichchieu&page=' . ($vt_page + 1) . '"> > </a>';
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