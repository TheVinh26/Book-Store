<?php
    ob_start();

    include "dbconnect.php";

    $connection = new Connection();
    $pdo = $connection->CheckConnect();

    if(isset($_POST["submit"]))
    {
        // Lấy dữ liệu từ biểu mẫu
        $category_name = $_POST["Category_Name"];

        // Kiểm tra tên danh mục đã tồn tại chưa
        $sql_check_add = "SELECT * FROM category WHERE Category_Name = :category_name";
        $stmt_check_add = $pdo->prepare($sql_check_add);
        $stmt_check_add->execute([':category_name' => $category_name]);

        if ($stmt_check_add->rowCount() > 0) {
            echo "<script>alert('Tên danh mục đã tồn tại');</script>";
            echo "<script>window.history.back();</script>";
            exit();
        }

        // Lưu thông tin sản phẩm vào cơ sở dữ liệu
        $query = "INSERT INTO category (Category_Name) VALUES (:category_name)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':category_name', $category_name);
        $stmt->execute();

        echo "<script>alert('Thêm danh mục thành công');</script>";
        echo "<script>window.location.href='admin_category.php';</script>";
    }
?>

<div class="container" style="margin-bottom: 50px; margin-top: 50px;">
    <div style="margin-bottom: 30px">
        <a href="admin_category.php" class="btn btn-primary">Quay lại</a>
    </div>

    <div class="title-page">
        <h4>THÊM DANH MỤC</h4>
    </div>

    <form action="admin_add_category.php" method="POST">
        <div class="mb-3">
            <label for="Category_Name" class="form-label">Tên danh mục</label>
            <input type="text" class="form-control" id="Category_Name" name="Category_Name" required>
        </div>
        <input type="submit" class="btn btn-primary" name="submit" value="Thêm danh mục" />
    </form>
</div>

<?php
    $content = ob_get_clean();
    include ("admin_page_layout.php");
?>