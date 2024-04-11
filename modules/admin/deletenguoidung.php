<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Thay đổi tên trang tương ứng
$data = [
    'ten_trang' => 'Xóa người dùng | Movie Booking'
];
layout('header_admin', $data);

//Nếu không tồn tại loginAdmin thì quay về trang login
if (!checkLogin('login_admin')) {
    header('location: ?module=admin&action=dangnhap');
}

//Tìm tổng số mẫu tin
$sql = "SELECT count(id_user) AS SMT FROM login_user";
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

    $sql = "SELECT * FROM login_user ORDER BY id_user LIMIT $start,$limit";
    $ket_qua = mysqli_query($configDTB, $sql);
    $smt = mysqli_num_rows($ket_qua);
?>
    <main>
        <form action="" method="post">
            <div class="list">
                <div class="tieu_de">
                    <p>Danh sách người dùng</p>
                </div>
                <hr>
                <table>
                    <tr>
                        <th>STT</th>
                        <th>ID USER</th>
                        <th>TÊN TÀI KHOẢN</th>
                        <th>EMAIL</th>
                        <th>GIỚI TÍNH</th>
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
                                <td style="text-align: center;"><?php echo $rows['id_user']; ?></td>
                                <td><?php echo $rows['ho_ten']; ?></td>
                                <td><?php echo $rows['email']; ?></td>
                                <td style="text-align: center;"><?php echo $rows['gioi_tinh']; ?></td>
                                <td>
                                    <input type="checkbox" name="delete_user[]" value="<?php echo $rows['id_user']; ?>">
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
                    <a href="?module=admin&action=quanlynguoidung" style="width: 150px;">Quản lý người dùng</a>
                    <button type="submit">Xóa người dùng</button>
                </div>
        </form>
        <?php
        //Xử lý xóa phim
        if (!empty($_SERVER['REQUEST_METHOD'] == "POST")) {
            if (isset($_POST['delete_user']) != '') {
                $smt_check = array();
                $smt_check = count($_POST['delete_user']);
                for ($i = 0; $i < $smt_check; $i++) {
                    $del_id = $_POST['delete_user'][$i];
                    $sql = "DELETE FROM login_user WHERE id_user ='$del_id'";
                    $ket_qua = mysqli_query($configDTB, $sql);
                    if ($ket_qua) {
                        echo "<span style='color:red;'>Đã xóa người dùng có id là: $del_id.<span>" . '<br>';
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