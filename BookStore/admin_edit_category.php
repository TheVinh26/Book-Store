<?php
    session_start();
    ob_start();

    include "dbconnect.php";

    $connection = new Connection();
    $pdo = $connection->CheckConnect();

    if(isset($_GET["value"]))
    {
        $_SESSION["category_id"] = $_GET["value"];
    }

    $category_id = $_SESSION["category_id"];

    $query = "SELECT * FROM category WHERE Category_ID = :category_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':category_id', $category_id);
    $stmt->execute();
    $category = $stmt->fetch(PDO::FETCH_OBJ);

    if(isset($_POST["submit"]))
    {
        // Lấy dữ liệu từ biểu mẫu
        $category_name = $_POST["Category_Name"];

        // Lưu thông tin sản phẩm vào cơ sở dữ liệu
        $query = "UPDATE category SET Category_Name = :category_name WHERE Category_ID = :category_id";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':category_name', $category_name);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->execute();

        echo "<script>alert('Sửa danh mục thành công');</script>";
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

    <form action="admin_edit_category.php" method="POST">
        <div class="mb-3">
            <label for="Category_Name" class="form-label">Tên danh mục</label>
            <input type="text" class="form-control" id="Category_Name" name="Category_Name" required value="<?php echo $category->Category_Name; ?>">
        </div>
        <input type="submit" class="btn btn-primary" name="submit" value="Lưu thay đổi" />
    </form>
</div>

<?php
    $content = ob_get_clean();
    include ("admin_page_layout.php");
?>