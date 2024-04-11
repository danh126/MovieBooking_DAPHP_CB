<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/admin.css?ver=<?php echo rand(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title><?php echo !empty($data['ten_trang']) ? $data['ten_trang'] : 'Trang quản trị'; ?></title>
</head>

<body>
    <div class="header">
        <h1>Movie Booking</h1>
    </div>
    <div class="user">
        <?php
        if (isset($_SESSION['login_admin'])) {
            echo '
                <div class="dropdown">
                    <button class="dropbtn">Tên tài khoản: ' . $_SESSION['admin_name'] . '</button>
                    <div class="dropdown-content">
                    <a href="?module=admin&action=dangxuat">Đăng xuất <i class="fa-solid fa-right-from-bracket"></i></a>
                    </div>
                </div>
              ';
        } else {
            echo "<a href='?module=admin&action=dangnhap'>Đăng nhập</a>";
        }
        ?>
    </div>
    <div class="nav">
        <ul>
            <li class="home"><a href="?module=admin&action=trangchu">Trang chủ</a></li>
            <li>
                <a href="?module=admin&action=quanlyphim">Quản lý phim</a>
                <ul>
                    <li><a href="?module=admin&action=themphimmoi">Thêm phim mới</a></li>
                    <li><a href="?module=admin&action=editphim">Cập nhật phim</a></li>
                    <li><a href="?module=admin&action=deletephim">Xóa phim</a></li>
                </ul>
            </li>
            <li>
                <a href="?module=admin&action=quanlynguoidung">Quản lý người dùng</a>
                <!-- <ul>
                    <li><a href="?module=admin&action=themnguoidung">Thêm người dùng</a></li>
                    <li><a href="?module=admin&action=editnguoidung">Cập nhật người dùng</a></li>
                    <li><a href="?module=admin&action=deletenguoidung">Xóa người dùng</a></li>
                </ul> -->
            </li>
            <li>
                <a href="?module=admin&action=kiemsoatvephim">Kiểm soát vé xem phim</a>
                <ul>
                    <a href="?module=admin&action=kiemsoatvequatang">Kiểm soát vé quà tặng</a>
                </ul>
            </li>
            <li>
                <a href="?module=admin&action=quanlylichchieu">Quản lý lịch chiếu</a>
                <ul>
                    <li><a href="?module=admin&action=themlichchieu">Thêm lịch chiếu</a></li>
                    <li><a href="?module=admin&action=editlichchieu">Cập nhật lịch chiếu</a></li>
                    <li><a href="?module=admin&action=deletelichchieu">Xóa lịch chiếu</a></li>
                </ul>
            </li>
            <li>
                <a href="?module=admin&action=quanlyquatang">Quản lý quà tặng</a>
                <ul>
                    <li><a href="?module=admin&action=themquatang">Thêm quà tặng</a></li>
                    <li><a href="?module=admin&action=editquatang">Cập nhật quà tặng</a></li>
                    <li><a href="?module=admin&action=deletequatang">Xóa quà tặng</a></li>
                </ul>
            </li>
            <li>
                <a href="?module=admin&action=quanlykhuyenmai">Quản lý khuyến mãi</a>
                <ul>
                    <li><a href="?module=admin&action=themkhuyenmai">Thêm khuyến mãi</a></li>
                    <li><a href="?module=admin&action=editkhuyenmai">Cập nhật khuyến mãi</a></li>
                    <li><a href="?module=admin&action=deletekhuyenmai">Xóa khuyến mãi</a></li>
                </ul>
            </li>
            <li><a href="?module=home&action=trangchu">Đi đến Website</a></li>
        </ul>
    </div>
</body>