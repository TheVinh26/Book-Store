<?php
    session_start();
    unset($_SESSION['user']);
    session_destroy();
    echo '<script type="text/javascript">alert("Đăng xuất thành công!"); window.location.href = "home_page.php";</script>';
?>
