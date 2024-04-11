<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Thay đổi tên trang tương ứng
$data = [
    'ten_trang' => 'Kiểm soát vé quà tặng | Movie Booking'
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
            <span>Kiểm soát vé quà tặng</span>
        </div>

        <div class="navs">
            <form action="" method="POST">
                <label for="">Tìm kiếm vé phim:</label>
                <input class="SearchId" type="text" name="inputTenQtang" id="myInput" onkeyup="myFunction()" placeholder="Nhập tên quà tặng...">
                <a class="link" href="?module=admin&action=kiemsoatvephim">Kiểm soát vé xem phim</a>
            </form>

        </div>

        <div class="KST">
            <div class="smallboxVE">
                <div class="tableVExemphim">
                    <table id="myKSVtable" colspan="7" class="table-biggie">

                        <tr class="headRows">
                            <th class="head-row">Tên khách hàng</th>
                            <th class="head-row">ID đặt vé</th>
                            <th class="head-row">Tên quà tặng</th>
                            <th class="head-row">Giá bán</th>
                            <th class="head-row">Ngày đặt</th>
                            <th class="head-row">Số lượng</th>
                            <th class="head-row">Tổng tiền</th>
                            <th class="head-row">Xem chi tiết</th>
                        </tr>

                        <?php
                        if (isset($_POST['inputTenQtang']) != '') {
                            $timkiem = $_POST['inputTenQtang'];

                            $sql_timkiem = "SELECT * FROM ve_qua_tang WHERE ten_ve LIKE '%$timkiem%' ";
                            $result_timkiem = mysqli_query($configDTB, $sql_timkiem);
                            while ($row_timkiem = mysqli_fetch_array($result_timkiem)) {
                        ?>
                                <tr id="infos" class="infos-rows">
                                    <td class="infos"><?php echo $row_timkiem['Ten_kh'] ?></td>
                                    <td class="infos"><?php echo $row_timkiem['id_vequatang'] ?></td>
                                    <td class="infos"><?php echo $row_timkiem['ten_ve'] ?></td>
                                    <td class="infos"><?php echo $row_timkiem['giaban'] ?></td>
                                    <td class="infos"><?php //echo $row_timkiem['thoigian'] 
                                                        ?></td>
                                    <td class="infos"><?php echo $row_timkiem['soluong'] ?></td>
                                    <td class="infos"><?php echo $row_timkiem['tongtien'] ?></td>
                                    <td class="infos">
                                        <form action="?module=admin&action=viewctvequatang" method='post'>
                                            <input type="hidden" name='ma_dat_vequatang' value='<?php echo $row_timkiem['id_vequatang']; ?>'>
                                            <button type='submit'><i class="fa-solid fa-pen-to-square"></i></button>
                                        </form>
                                    </td>

                                </tr>
                            <?php
                            }
                            echo "</table>";
                            echo "</div>";
                        } else {
                            $sql_vequatang = "SELECT * FROM ve_qua_tang ";
                            $result_vequatang = mysqli_query($configDTB, $sql_vequatang);

                            while ($rows = mysqli_fetch_array($result_vequatang)) {
                            ?>
                                <tr id="infos" class="infos-rows">
                                    <td class="infos"><?php echo $rows['Ten_kh'] ?></td>
                                    <td class="infos"><?php echo $rows['id_vequatang'] ?></td>
                                    <td class="infos"><?php echo $rows['ten_ve'] ?></td>
                                    <td class="infos"><?php echo $rows['giaban'] ?></td>
                                    <td class="infos"><?php echo $rows['thoigian']
                                                        ?></td>
                                    <td class="infos"><?php echo $rows['soluong'] ?></td>
                                    <td class="infos"><?php echo $rows['tongtien'] ?></td>
                                    <td class="infos">
                                        <form action="?module=admin&action=viewctvequatang" method='post'>
                                            <input type="hidden" name='ma_dat_vequatang' value='<?php echo $rows['id_vequatang']; ?>'>
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
                        <span>Số lượng vé quà tặng đã bán</span>
                    </td>
                    <td class="numsTongtien">
                        <span>Tổng doanh thu vé quà tặng</span>
                    </td>
                </tr>

                <tr>
                    <td class="numsVephim">
                        <?php
                            $sql_sove = "SELECT soluong FROM ve_qua_tang ";
                            $result_sove = mysqli_query($configDTB, $sql_sove);

                            $tong_sove = 0;
                            while ($row_sove = mysqli_fetch_array($result_sove)) {
                                $tong_sove_1 = $row_sove['soluong'];
                                $tong_sove += $tong_sove_1;
                            }
                        ?>
                        <span><?= $tong_sove; ?></span>
                    </td>
                    <td class="numsTongtien">
                        <?php
                            $sql_tongtien = "SELECT tongtien FROM ve_qua_tang ";
                            $result_tongtien = mysqli_query($configDTB, $sql_tongtien);

                            $tong_tien = 0;
                            while ($row_tongtien = mysqli_fetch_array($result_tongtien)) {
                                $tong_tien_1 = $row_tongtien['tongtien'];
                                $tong_tien += $tong_tien_1;
                            }
                        ?>
                        <span><?= $tong_tien; ?></span>
                    </td>
                </tr>
            </table>
        <?php
                        } //kết thúc while
                        mysqli_close($configDTB);
        ?>
        </div>
    </div>
</main>
<?php
layout('footer_admin');
?>