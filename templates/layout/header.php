<?php
$serverName = 'localhost';
$userName = 'root';
$pass = '';
$databaseName = 'moviebooking';
//Kết nối database
$configDTB = mysqli_connect($serverName, $userName, $pass, $databaseName);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/header.css?ver=<?php echo rand(); ?>">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/content.css?ver=<?php echo rand(); ?>">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/footer.css?ver=<?php echo rand(); ?>">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/datve.css?ver=<?php echo rand(); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js"> -->
    <title><?php echo !empty($data['ten_trang']) ? $data['ten_trang'] : 'Movie Booking'; ?></title>
</head>

<body>
    <!--Header-->
    <div class="header" id="top">
        <?php
        $sql = "SELECT duong_dan FROM banner WHERE loai_banner = 'header'";
        $ket_qua = mysqli_query($configDTB, $sql);
        $link_img = mysqli_fetch_array($ket_qua);
        ?>
        <div class="img_header">
            <!--Banner-->
            <img src="<?php echo _WEB_HOST_TEMPLATES;
                        echo $link_img['duong_dan'] ?>" alt="database error">
        </div>
        <hr class="duong_ngang">
        <!--Thanh menu ngang-->
        <div class="nav">
            <ul>
                <li class="li-nav">
                    <?php
                    // Menu động
                    //Truy vấn menu động
                    $sql = "SELECT * FROM menu";
                    $ket_qua = mysqli_query($configDTB, $sql);
                    while ($rows = mysqli_fetch_array($ket_qua)) {
                    ?>
                        <a href="?module=home&action=<?php echo $rows['ten_duong_dan']; ?>"><?php echo $rows['ten_menu']; ?></a>
                    <?php
                    }
                    ?>
                </li>
            </ul>
        </div>
        <div class="sub_nav">
            <?php
            // Gọi hàm kiểm tra login user
            if (checkLogin('login_user')) {
                $id_user = $_SESSION['id_user'];
                $sql = "SELECT ho_ten FROM login_user WHERE id_user = '$id_user'";
                $ket_qua = mysqli_query($configDTB, $sql);
                $row = mysqli_fetch_array($ket_qua);
                echo '
                <div class="dropdown">
                    <button class="dropbtn">' . $row['ho_ten'] . '</button>
                    <div class="dropdown-content">
                    <a href="?module=home&action=thongtin">Thông tin</a>
                    <a href="?module=home&action=dangxuat">Đăng xuất <i class="fa-solid fa-right-from-bracket"></i></a>
                    </div>
                </div>
              ';
                mysqli_close($configDTB); //đóng database
            } else {
                echo '<a href="?module=home&action=dangnhap" class="login">Đăng nhập</a>';
            }
            ?>
            <span class="search">
                <form action="?module=home&action=search" method="post">
                    <input type="text" class="search__input" name="txtTenPhim" placeholder="Phim đang chiếu...">
                    <button class="search__button">
                        <svg class="search__icon" aria-hidden="true" viewBox="0 0 24 24">
                            <g>
                                <path d="M21.53 20.47l-3.66-3.66C19.195 15.24 20 13.214 20 11c0-4.97-4.03-9-9-9s-9 4.03-9 9 4.03 9 9 9c2.215 0 4.24-.804 5.808-2.13l3.66 3.66c.147.146.34.22.53.22s.385-.073.53-.22c.295-.293.295-.767.002-1.06zM3.5 11c0-4.135 3.365-7.5 7.5-7.5s7.5 3.365 7.5 7.5-3.365 7.5-7.5 7.5-7.5-3.365-7.5-7.5z"></path>
                            </g>
                        </svg>
                    </button>
                </form>
            </span>
        </div>
    </div>
    <!-- <hr class="duong_ngang"> -->
    <!--Bố cục phim-->
    <div class="tien_ich">
        <li class="tien_ich_link">
            <a href="?module=home&action=datve"><i class="fa-solid fa-ticket"></i>&nbsp;Đặt vé nhanh</a>
        </li>
        <li class="tien_ich_link">
            <a href="?module=home&action=dangky"><i class="fa-solid fa-id-card"></i>&nbsp;Tạo tài khoản</a>
        </li>
        <li class="tien_ich_link">
            <a href="?module=home&action=lichchieuphim"><i class="fa-regular fa-calendar-days"></i>&nbsp;Lịch chiếu phim</a>
        </li>
        <li class="tien_ich_link">
            <a href="#top">Top &nbsp;<i class="fa-solid fa-caret-up"></i></a>
        </li>
    </div>
    <script src="<?php echo _WEB_HOST_TEMPLATES ?>/js/tien_ich_home.js"></script>
</body>

</html>