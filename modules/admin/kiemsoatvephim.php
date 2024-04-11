<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Thay đổi tên trang tương ứng
$data = [
    'ten_trang' => 'Kiểm soát vé xem phim | Movie Booking'
];
layout('header_admin', $data);

//Nếu không tồn tại loginAdmin thì quay về trang login
if (!checkLogin('login_admin')) {
    header('location: ?module=admin&action=dangnhap');
}
?>
<main>
    <div class="mainKST">
        <div class="KSTtitle">
            <span>Kiểm soát vé xem phim</span>
        </div>

        <div class="navs">
            <form action="" method="POST">
                <label for="">Tìm kiếm vé phim:</label>
                <input class="SearchId" type="text" name="inputTenphim" id="myInput" onkeyup="myFunction()" placeholder="Nhập tên phim...">
                <a class="link" href="?module=admin&action=kiemsoatvequatang">Kiểm soát vé quà tặng</a>
            </form>

        </div>

        <div class="KST">
            <div class="smallboxVE">
                <div class="tableVExemphim">
                    <table id="myKSVtable" colspan="7" class="table-biggie">
                        <tr class="headRows">
                            <th class="head-row">Tên khách hàng</th>
                            <th class="head-row">ID đặt vé</th>
                            <th class="head-row">Tên phim</th>
                            <th class="head-row">Ngày chiếu</th>
                            <th class="head-row">Tên phòng</th>
                            <th class="head-row">Ghế chọn</th>
                            <th class="head-row">Tổng tiền</th>
                            <th class="head-row">Xem chi tiết</th>
                        </tr>

                        <?php
                        if (isset($_POST['inputTenphim']) != '') {
                            $timkiem = $_POST['inputTenphim'];

                            $sql_timkiem = "SELECT * FROM thong_tin_dat_ve WHERE ten_phim LIKE '%$timkiem%' ";
                            $result_timkiem = mysqli_query($configDTB, $sql_timkiem);
                            while ($row_timkiem = mysqli_fetch_array($result_timkiem)) {
                        ?>
                                <tr id="infos" class="infos-rows">
                                    <td class="infos"><?php echo $row_timkiem['ten_KH'] ?></td>
                                    <td class="infos"><?php echo $row_timkiem['id_dat_ve'] ?></td>
                                    <td class="infos"><?php echo $row_timkiem['ten_phim'] ?></td>
                                    <td class="infos"><?php echo $row_timkiem['ngay_chieu'] ?></td>
                                    <td class="infos"><?php echo $row_timkiem['ten_phong'] ?></td>
                                    <td class="infos"><?php echo $row_timkiem['ghe_da_chon'] ?></td>
                                    <td class="infos"><?php echo $row_timkiem['tong_tien'] ?></td>
                                    <td class="infos">
                                        <form action="?module=admin&action=viewctvephim" method='post'>
                                            <input type="hidden" name='ma_dat_ve' value='<?php echo $row_timkiem['id_dat_ve']; ?>'>
                                            <button type='submit'><i class="fa-solid fa-pen-to-square"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php
                            }
                            echo "</table>";
                            echo "</div>";
                        } else {
                            ?>
                            <?php
                            $sql_vexemphim = "SELECT * FROM thong_tin_dat_ve ";
                            $result_vexemphim = mysqli_query($configDTB, $sql_vexemphim);

                            while ($rows = mysqli_fetch_array($result_vexemphim)) {
                            ?>
                                <tr id="infos" class="infos-rows">
                                    <td class="infos"><?php echo $rows['ten_KH'] ?></td>
                                    <td class="infos"><?php echo $rows['id_dat_ve'] ?></td>
                                    <td class="infos"><?php echo $rows['ten_phim'] ?></td>
                                    <td class="infos"><?php echo $rows['ngay_chieu'] ?></td>
                                    <td class="infos"><?php echo $rows['ten_phong'] ?></td>
                                    <td class="infos"><?php echo $rows['ghe_da_chon'] ?></td>
                                    <td class="infos"><?php echo $rows['tong_tien'] ?></td>
                                    <td class="infos">
                                        <form action="?module=admin&action=viewctvephim" method='post'>
                                            <input type="hidden" name='ma_dat_ve' value='<?php echo $rows['id_dat_ve']; ?>'>
                                            <button type='submit'><i class="fa-solid fa-pen-to-square"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                    </table>
                </div>
            </div>
        </div>
        <div class="statisicBox">

            <table class="table-smallies">
                <tr>
                    <td class="numsVephim">
                        <span>Số lượng vé phim đã bán</span>
                    </td>
                    <td class="numsTongtien">
                        <span>Tổng doanh thu vé phim</span>
                    </td>
                </tr>

                <tr>
                    <td class="numsVephim">
                        <?php
                            $sql_tongvedat = "SELECT ghe_da_chon FROM thong_tin_dat_ve ";
                            $result_tongvedat = mysqli_query($configDTB, $sql_tongvedat);
                            $tongvedat = mysqli_num_rows($result_tongvedat);

                            $tong_so_ghe = 0;
                            while ($row_tongvedat = mysqli_fetch_array($result_tongvedat)) {

                                //Gọi số ghê từ hàng ghe_da_chon trong database
                                $ghe = $row_tongvedat['ghe_da_chon'];

                                //Tách các ghế thành mảng
                                $ghe_array = explode(" ", $ghe);

                                //Cộng tổng các số ghế vào tong_so_ghe 
                                $tong_so_ghe += count($ghe_array);
                            }
                        ?>
                        <span><?= $tong_so_ghe; ?></span>
                    </td>
                    <td class="numsTongtien">
                        <?php
                            $sql_tongtien = "SELECT tong_tien FROM thong_tin_dat_ve ";
                            $result_tongtien = mysqli_query($configDTB, $sql_tongtien);

                            $tong_tien = 0;
                            while ($row_tongtien = mysqli_fetch_array($result_tongtien)) {
                                $tong_tien_1 = $row_tongtien['tong_tien'];
                                $tong_tien += $tong_tien_1;
                            }
                        ?>
                        <span><?= $tong_tien; ?></span>
                    </td>
                </tr>
            </table>
        <?php
                        } //kết thúc if
                        mysqli_close($configDTB);
        ?>
        </div>
    </div>
</main>
<?php
layout('footer_admin');
