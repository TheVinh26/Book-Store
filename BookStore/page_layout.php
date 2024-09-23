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
    <meta name="viewport" Content-Type: text/html; charset=utf-8>
    <title>Cửa Hàng Sách Online</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/my.css" rel="stylesheet">
    <link rel="stylesheet" href="fontawesome/css/all.min.css" type="text/css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="home_page.php"><img src="img/logo.jpg" alt="logo" height="60px"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <?php
                    if (!isset($_SESSION['user'])) {
                        ?>
                            <li class="nav-item">
                                <a class="nav-link me-2" id="login_button" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Đăng nhập</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="register_button" href="#" data-bs-toggle="modal" data-bs-target="#registerModal">Đăng ký</a>
                            </li>
                        <?php
                    } else {
                        ?>
                            <li class="nav-item">
                                <p style="margin: 7px 20px 0 0; color: #22d649">Xin Chào <?php echo $_SESSION['user'] ?></p>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn me-2" href="cart.php"><i class="fa-solid fa-cart-shopping" style="margin-right: 5px"></i>Giỏ hàng</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link btn me-2" href="logout.php">Đăng xuất</a>
                            </li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Đăng nhập</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="login.php" method="post" accept-charset="UTF-8" role="form">
                        <div class="mb-3">
                            <label for="loginUsername" class="form-label">Tên đăng nhập</label>
                            <input type="text" class="form-control" id="loginUsername" name="login_username">
                        </div>
                        <div class="mb-3">
                            <label for="loginPassword" class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control" id="loginPassword" name="login_password">
                        </div>
                        <button type="submit" class="btn btn-primary" name="submit" value="login">Đăng nhập</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">Đăng ký</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="register.php" method="post" accept-charset="UTF-8" role="form">
                        <div class="mb-3">
                            <label for="registerUsername" class="form-label">Tên đăng nhập</label>
                            <input type="text" class="form-control" id="registerUsername" name="register_username">
                        </div>
                        <div class="mb-3">
                            <label for="registerPassword" class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control" id="registerPassword" name="register_password">
                        </div>
                        <button type="submit" class="btn btn-primary" name="submit" value="register">Đăng ký</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="top">
        <div id="searchbox" class="container-fluid">
            <div>
                <form role="search" method="get" action="Result.php">
                    <input type="text" class="form-control" name="keyword" style="width:80%;margin:20px 10% 20px 10%;" placeholder="Nhập tên sách, Tác giả hoặc Thể loại...">
                </form>
            </div>
        </div>
    </div>

    <div style="margin-left: 5%; margin-right: 5%">
        <?php
            echo $content;
        ?>
    </div>

    <footer style="margin-left:-6%;margin-right:-6%;">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-1 col-md-1 col-lg-1">
                </div>
                <div class="col-sm-7 col-md-5 col-lg-5">
                    <div class="row text-center">
                        <h2>Hãy giữ liên lạc!</h2>
                        <hr class="primary">
                        <p>Mọi thắc mắc xin liên hệ đường dây nóng hoặc địa chỉ mail bên dưới</p>
                    </div>
                    <div class="row">
                        <div class="col-md-6 text-center">
                            <i class="fa-solid fa-phone"></i>
                            0964-513-228
                        </div>
                        <div class="col-md-6 text-center">
                            <i class="fa-solid fa-envelope"></i>
                            <!-- Email -->
                        </div>
                    </div>
                </div>
                <div class="hidden-sm-down col-md-2 col-lg-2">
                </div>
                <div class="col-sm-4 col-md-3 col-lg-3 text-center">
                    <h2 style="color: #D67B22;">Theo dõi chúng tôi tại</h2>
                    <div style="margin-top: 20px">
                        <a href=" ">
                            <img title="Twitter" alt="Twitter" src="img/icons/twitter.png" width="35" height="35" />
                        </a>
                        <a href=" ">
                            <img title="LinkedIn" alt="LinkedIn" src="img/icons/linkedin.png" width="35" height="35" />
                        </a>
                        <a href=" ">
                            <img title="Facebook" alt="Facebook" src="img/icons/facebook.png" width="35" height="35" />
                        </a>
                        <a href=" ">
                            <img title="google+" alt="google+" src="img/icons/google.jpg" width="35" height="35" />
                        </a>
                        <a href=" ">
                            <img title="Pinterest" alt="Pinterest" src="img/icons/pinterest.jpg" width="35"
                                height="35" />
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <div class="container">
        <!-- Trigger the modal with a button -->
        <button type="button" id="query_button" class="btn btn-lg btn-primary" data-bs-toggle="modal" data-bs-target="#query">Đặt câu hỏi tại đây</button>
        <!-- Modal -->
        <div class="modal fade" id="query" tabindex="-1" aria-labelledby="queryModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header text-center">
                        <h5 class="modal-title" id="queryModalLabel">Hãy đặt câu hỏi cho chúng tôi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="query.php" class="form" role="form">
                            <div class="form-group">
                                <label class="form-label" for="name">Tên</label>
                                <input type="text" class="form-control" placeholder="Tên của bạn" id="name" name="sender" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="email">Email</label>
                                <input type="email" class="form-control" placeholder="abc@gmail.com" id="email" name="senderEmail" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="query">Câu hỏi</label>
                                <textarea class="form-control" rows="5" cols="30" id="query" name="message" placeholder="Câu hỏi của bạn" required></textarea>
                            </div>
                            <div class="form-group" style="margin-top: 20px">
                                <button type="submit" name="submit" value="query" class="btn btn-block btn-primary">
                                    Gửi câu hỏi
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script>
        //Chuyển ảnh trong chi tiết sản phẩm
        function changeImage(img) {
            var mainImg = document.getElementById("mainImg");
            mainImg.src = img.src;
        }

        //nút tăng giảm sản phẩm trong trong chi tiết sản phẩm
        document.getElementById('subtractQty').addEventListener('click', function() {
            var quantityInput = document.getElementById('quantity');
            var currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        });

        document.getElementById('addQty').addEventListener('click', function() {
            var quantityInput = document.getElementById('quantity');
            var currentValue = parseInt(quantityInput.value);
            quantityInput.value = currentValue + 1;
        });

        
    </script>
</body>

</html>