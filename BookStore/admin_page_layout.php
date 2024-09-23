<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_GET['Message'])) {
        echo '<script type="text/javascript">
                alert("' . $_GET['Message'] . '");
            </script>';
    }

    if (isset($_GET['response'])) {
        echo '<script type="text/javascript">
                alert("' . $_GET['response'] . '");
            </script>';
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/my.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><img src="img/logo.jpg" alt="logo" height="60px"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <p style="margin: 7px 20px 0 0; color: #22d649">Xin Chào <?php echo $_SESSION['user'] ?></p>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn me-2" href="logout.php">Đăng xuất</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div style="margin-left: 5%; margin-right: 5%">
        <?php
            echo $content;
        ?>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script>
        function confirmDelete_product(id) {
            if (confirm("Bạn có chắc chắn muốn xóa sách này không?")) {
                window.location.href = 'admin_product.php?delete_id=' + id;
            }
        }

        function confirmDelete_category(id) {
            if (confirm("Bạn có chắc chắn muốn danh mục này không?")) {
                window.location.href = 'admin_category.php?delete_id=' + id;
            }
        }
    </script>
</body>
</html>