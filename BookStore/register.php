<?php

  include "dbconnect.php";

  $connection = new Connection();
  $pdo = $connection->CheckConnect();

  if (isset($_POST['submit'])) {
    if ($_POST['submit'] == "register") {
        $username = $_POST['register_username'];
        $password = $_POST['register_password'];
        
        try {
            // Prepare and execute the select statement
            $query = "SELECT * FROM users WHERE UserName = :username";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
              echo '<script type="text/javascript">alert("Tên đăng nhập đã được sử dụng. Hãy sử dụng tên đăng nhập khác!"); window.location.href = "home_page.php";</script>';
            } else {
                // Prepare and execute the insert statement
                $query = "INSERT INTO users (UserName, Password) VALUES (:username, :password)";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':username', $username, PDO::PARAM_STR);
                $stmt->bindParam(':password', $password, PDO::PARAM_STR);
                $stmt->execute();
                
                echo '<script type="text/javascript">alert("Đăng ký thành công!"); window.location.href = "home_page.php";</script>';
            }
        } catch (PDOException $e) {
            die("Database error: " . $e->getMessage());
        }
    }
  }

?>