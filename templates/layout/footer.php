<?php
$serverName = 'localhost';
$userName = 'root';
$pass = '';
$databaseName = 'moviebooking';
//Kết nối database
$configDTB = mysqli_connect($serverName, $userName, $pass, $databaseName);
?>
<div class="qc_image" style="text-align: center;">
    <hr width="98%">
    <?php
    $sql = "SELECT duong_dan,link_qc FROM banner WHERE loai_banner = 'footer'";
    $ket_qua = mysqli_query($configDTB, $sql);
    while ($rows = mysqli_fetch_array($ket_qua)) {
    ?>
        <a href="<?php echo $rows['link_qc'] ?>"><img src="<?php echo _WEB_HOST_TEMPLATES;
                                                            echo $rows['duong_dan'] ?>" alt="database error" style="width: 70%;"></a>
    <?php
    }
    mysqli_close($configDTB);
    ?>
</div>
<!--Footer-->
<div class="footer">
    <div class="team">
        <h2>Movie Booking</h2>
        <p>
            <a href=""><i class="fa-brands fa-square-facebook"></i></a>&nbsp;
            <a href=""><i class="fa-brands fa-youtube"></i></a>
            <hr width="500px">
        </p>
        <span>&copy; D-B-A TEAM 2024</span>
    </div>
    <div class="map">
        <!--Chèn google map -->
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.915148450833!2d106.69946807480433!3d10.741022889405597!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.
            1!3m3!1m2!1s0x31752f9f3904fc6d%3A0xf8eedeab7440a13a!2sLotte%20Cinema%20Nam%20S%C3%A0i%20G%C3%B2n!5e0!3m2!1svi!2s!4v1703946170297!5m2!1svi!2s" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="map">
        </iframe>
    </div>
</div>