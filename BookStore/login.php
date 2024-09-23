<?php
    session_start();
    include "dbconnect.php";

    $connection = new Connection();
    $pdo = $connection->CheckConnect();

    if (isset($_POST['submit'])) {
        $username = $_POST['login_username'];
        $password = $_POST['login_password'];

        if ($_POST['submit'] == "login") {
            $query = "SELECT * FROM users WHERE UserName = :username AND IsStaff = 0";
        } elseif ($_POST['submit'] == "login_admin") {
            $query = "SELECT * FROM users WHERE UserName = :username AND IsStaff = 1";
        }

        try {
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if (strcmp($row['UserName'], $username) === 0 && strcmp($row['Password'], $password) === 0) {
                    $_SESSION['user'] = $row['UserName'];
                    if ($_POST['submit'] == "login") {
                        echo '<script type="text/javascript">alert("Đăng nhập thành công!"); window.location.href = "home_page.php";</script>';
                    } elseif ($_POST['submit'] == "login_admin") {
                        echo '<script type="text/javascript">alert("Đăng nhập thành công!"); window.location.href = "admin_product.php";</script>';
                    }
                    exit();
                } else {
                    echo '<script type="text/javascript">alert("Sai tên đăng nhập hoặc mật khẩu!"); window.location.href = document.referrer;</script>';
                }
            } else {
                echo '<script type="text/javascript">alert("Sai tên đăng nhập hoặc mật khẩu!"); window.location.href = document.referrer;</script>';
            }
        } catch (PDOException $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href = document.referrer;</script>";
        }
    }
?>