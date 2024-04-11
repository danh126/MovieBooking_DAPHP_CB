<?php
//Kiểm tra truy cập 

if (!defined('_CODE')) {
    die('Truy cập không hợp lệ !');
}

//Thay đổi tên trang tương ứng
$data = [
    'ten_trang' => 'Trang chủ admin | Movie Booking'
];
layout('header_admin', $data);

//Nếu không tồn tại loginAdmin thì quay về trang login
if (!checkLogin('login_admin')) {
    header('location: ?module=admin&action=dangnhap');
}

if (!empty($_SERVER["REQUEST_METHOD"] == "POST")) {

    $id_dat_ve = $_POST['ma_dat_ve'];

    $sql_thongtinve = "SELECT * FROM thong_tin_dat_ve WHERE id_dat_ve = '" . $id_dat_ve . "' ";
    $result_thongtinve = mysqli_query($configDTB, $sql_thongtinve);
    $rows_thongtinvedat = mysqli_fetch_array($result_thongtinve);
?>
    <style>
        .body {
            font-family: Arial, Helvetica, sans-serif;
            width: 100%;
            height: auto;
            border: none;
            margin-top: 40px;
        }

        .KSTtitle {
            text-align: center;
            vertical-align: middle;
            text-transform: uppercase;
            font-size: 25px;
            color: black;
        }

        .containor {
            margin: 0 10% 0 10%;
            padding: 0;
            width: 80%;
            height: auto;
            border: none;
        }

        .thongtin {
            text-align: center;
            font-size: 28px;
            font-weight: bolder;
            text-transform: uppercase;
        }

        .table {
            display: flex;
        }

        .infos {
            border: 2px solid black;
            border-radius: 20px;
            position: relative;
            height: auto;
            margin: 10px 10%;
            width: 80%;
            display: block;
        }

        .infomation {
            position: relative;
            width: auto;
            height: auto;
            padding: 0 20px;
            margin: 10px 10%;
            font-size: 20px;
            font-weight: 600;
            word-break: break-word;
        }

        .data {
            font-weight: 100;
        }

        .quaylai a {
            margin-top: 10px;
            width: 100px;
            padding: 10px 10px;
            background-color: #19373c;
            color: #f9f9f9;
            text-align: center;
            margin-left: 5px;
            text-decoration: none;
        }

        .quaylai {
            display: flex;
            justify-content: right;
        }
    </style>
    <main>
        <div class="body">
            <div class="KSTtitle">
                <span>thông tin chi tiết vé xem phim</span>
            </div>
            <div class="containor">
                <div class="table">
                    <div class="infos">
                        <p class="infomation">ID đặt vé: <span class="data"><?php echo $rows_thongtinvedat['id_dat_ve'] ?></span></p>
                        <p class="infomation">Tên khách hàng: <span class="data"><?php echo $rows_thongtinvedat['ten_KH'] ?></span></p>
                        <p class="infomation">Email khách hàng: <span class="data"><?php echo $rows_thongtinvedat['email_KH'] ?></span> - Số điện thoại: <span class="data"><?php echo $rows_thongtinvedat['dien_thoai_KH'] ?></span></p>
                        <p class="infomation">Ghi chú: <span class="data"><?php echo $rows_thongtinvedat['ghi_chu'] ?></span></p>
                        <p class="infomation">Tên phim: <span class="data"><?php echo $rows_thongtinvedat['ten_phim'] ?></span></p>
                        <p class="infomation">Ngày chiếu: <span class="data"><?php echo $rows_thongtinvedat['ngay_chieu'] ?></span> - Số phòng: <span class="data"><?php echo $rows_thongtinvedat['ten_phong'] ?></span></p>
                        <p class="infomation">Thời gian bắt đầu và kết thúc: <span class="data"><?php echo $rows_thongtinvedat['thoi_gian_bd'] ?></span> - <span class="data"><?php echo $rows_thongtinvedat['thoi_gian_kt'] ?></span></p>
                        <p class="infomation">Số ghế: <span class="data"><?php echo $rows_thongtinvedat['ghe_da_chon'] ?></span></p>
                        <p class="infomation">Ngày đặt: <span class="data"><?php echo $rows_thongtinvedat['ngay_dat'] ?></span></p>
                        <p class="infomation">Tổng tiền: <span class="data"><?php echo $rows_thongtinvedat['tong_tien'] ?></span></p>
                    </div>
                </div>
            </div>
            <div class="quaylai">
                <a href="?module=admin&action=kiemsoatvephim">Quay lại</a>
            </div>
        </div>
    </main>
<?php
} else {
    header('location: ?module=admin&action=kiemsoatvephim');
}
layout('footer_admin');
