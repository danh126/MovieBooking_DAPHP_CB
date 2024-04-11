<?php
$sql = "SELECT tong_tien FROM thong_tin_dat_ve";
$ket_qua = mysqli_query($configDTB, $sql);

// Khởi tạo biến tổng số ghế
$tong_so_ghe = 0;

// Lặp qua các hàng kết quả
while ($row = mysqli_fetch_array($ket_qua)) {

    // Lấy danh sách ghế từ hàng kết quả hiện tại
    $ghe = $row['tong_tien'];

    // // Tách các ghế thành mảng
    // $ghe_array = explode(" ", $ghe);

    // Cộng số ghế trong mảng vào tổng số ghế
    $tong_so_ghe += $ghe;
}

// In ra tổng số ghế sau mỗi lần lặp
echo "Tổng số tiền : " . $tong_so_ghe;

// echo $_POST['txtsoluong'];

// echo "<br>" . $_POST['id'];
