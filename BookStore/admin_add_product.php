<?php
    ob_start();

    include "dbconnect.php";

    $connection = new Connection();
    $pdo = $connection->CheckConnect();

    if(isset($_POST["submit"]))
    {
        // Lấy dữ liệu từ biểu mẫu
        $Title = $_POST["Title"];
        $Author = $_POST["Author"];
        $Price = $_POST["Price"];
        $Discount = $_POST["Discount"];
        $Publisher = $_POST["Publisher"];
        $Description = $_POST["Description"];
        $Language = $_POST["Language"];
        $Category_ID = $_POST["Category_ID"];

        // Kiểm tra tên sản phẩm đã tồn tại chưa
        $sql_check_add = "SELECT * FROM products WHERE Title = :Title";
        $stmt_check_add = $pdo->prepare($sql_check_add);
        $stmt_check_add->execute([':Title' => $Title]);

        if ($stmt_check_add->rowCount() > 0) {
            echo "<script>alert('Tên sản phẩm đã tồn tại');</script>";
            echo "<script>window.history.back();</script>";
            exit();
        }

        // Xử lý upload hình ảnh
        $target_dir_add = "img/books/";
        $imageFileType_add = strtolower(pathinfo($_FILES["Image"]["name"], PATHINFO_EXTENSION));
        $original_filename_add = pathinfo($_FILES["Image"]["name"], PATHINFO_FILENAME);
        $new_filename_add = $original_filename_add . '.' . $imageFileType_add;
        $target_file_add = $target_dir_add . $new_filename_add;

        // Kiểm tra định dạng file
        $allowed_types_add = array("jpg", "png", "jpeg", "gif");
        if (!in_array($imageFileType_add, $allowed_types_add)) {
            echo "<script>alert('Chỉ chấp nhận các định dạng JPG, JPEG, PNG & GIF.');</script>";
            echo "<script>window.history.back();</script>";
            exit();
        }

        // Kiểm tra xem file có phải là hình ảnh thật hay không
        $check_add = getimagesize($_FILES["Image"]["tmp_name"]);
        if ($check_add === false) {
            echo "<script>alert('File không phải là hình ảnh.');</script>";
            echo "<script>window.history.back();</script>";
            exit();
        }

        // Upload hình ảnh chính
        if (!move_uploaded_file($_FILES["Image"]["tmp_name"], $target_file_add)) {
            echo "<script>alert('Có lỗi xảy ra khi upload ảnh chính.');</script>";
            echo "<script>window.history.back();</script>";
            exit();
        }

        // Lưu thông tin sản phẩm vào cơ sở dữ liệu
        $query = "INSERT INTO products (Title, Author, Image, Price, Discount, Publisher, Description, Language, Category_ID) VALUES (:Title, :Author, :Image, :Price, :Discount, :Publisher, :Description, :Language, :Category_ID)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':Title', $Title);
        $stmt->bindParam(':Author', $Author);
        $stmt->bindParam(':Image', $new_filename_add);
        $stmt->bindParam(':Price', $Price);
        $stmt->bindParam(':Discount', $Discount);
        $stmt->bindParam(':Publisher', $Publisher);
        $stmt->bindParam(':Description', $Description);
        $stmt->bindParam(':Language', $Language);
        $stmt->bindParam(':Category_ID', $Category_ID);
        $stmt->execute();

        $product_id = $pdo->lastInsertId(); // Lấy ID của sản phẩm vừa thêm

        // Xử lý upload hình ảnh phụ
        $additional_images = $_FILES['additional_images'];
        $total_files = count($additional_images['name']);

        for ($i = 0; $i < $total_files; $i++) {
            $imageFileType_additional = strtolower(pathinfo($additional_images['name'][$i], PATHINFO_EXTENSION));
            $original_filename_additional = pathinfo($additional_images['name'][$i], PATHINFO_FILENAME);
            $new_filename_additional = $original_filename_additional . '.' . $imageFileType_additional;
            $target_file_additional = $target_dir_add . $new_filename_additional;

            // Kiểm tra xem file có tồn tại không, nếu có thì đổi tên
            if (file_exists($target_file_additional)) {
                $new_filename_additional = $original_filename_additional . '_' . uniqid() . '.' . $imageFileType_additional;
                $target_file_additional = $target_dir_add . $new_filename_additional;
            }

            if (in_array($imageFileType_additional, $allowed_types_add)) {
                if (getimagesize($additional_images['tmp_name'][$i]) !== false) {
                    if (move_uploaded_file($additional_images['tmp_name'][$i], $target_file_additional)) {
                        // Lưu thông tin ảnh phụ vào cơ sở dữ liệu
                        $query_additional = "INSERT INTO additional_images (Product_ID, Image) VALUES (:Product_ID, :Image)";
                        $stmt_additional = $pdo->prepare($query_additional);
                        $stmt_additional->bindParam(':Product_ID', $product_id);
                        $stmt_additional->bindParam(':Image', $new_filename_additional);
                        $stmt_additional->execute();
                    }
                }
            }
        }


        echo "<script>alert('Thêm sản phẩm thành công');</script>";
        echo "<script>window.location.href='admin_product.php';</script>";
    }

    $query = "SELECT * FROM category";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $category = $stmt->fetchAll(PDO::FETCH_OBJ);
?>

<div class="container" style="margin-bottom: 50px; margin-top: 50px;">
    <div style="margin-bottom: 30px">
        <a href="admin_product.php" class="btn btn-primary">Quay lại</a>
    </div>

    <div class="title-page">
        <h4>THÊM SÁCH</h4>
    </div>

    <form action="admin_add_product.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="Title" class="form-label">Tên sách</label>
            <input type="text" class="form-control" id="Title" name="Title" required>
        </div>
        <div class="mb-3">
            <label for="Author" class="form-label">Tên tác giả</label>
            <input type="text" class="form-control" id="Author" name="Author" required>
        </div>
        <div class="row g-3 mb-3">
            <div class="col">
                <label for="Price" class="form-label">Đơn giá</label>
                <input type="number" class="form-control" id="Price" name="Price" required>
            </div>
            <div class="col">
                <label for="Discount" class="form-label">Giảm giá (%)</label>
                <input type="number" class="form-control" id="Discount" name="Discount" required>
            </div>
            <div class="col">
                <label for="Publisher" class="form-label">Nhà xuất bản</label>
                <input type="text" class="form-control" id="Publisher" name="Publisher" required>
            </div>
        </div>
        <div class="mb-3">
            <label for="Description" class="form-label">Mô tả</label>
            <textarea class="form-control" id="Description" rows="5" name="Description" required></textarea>
        </div>
        <div class="row g-3 mb-3">
            <div class="col">
                <label for="Language" class="form-label">Ngôn ngữ</label>
                <input type="text" class="form-control" id="Language" name="Language" required>
            </div>
            <div class="col">
                <label for="Category_ID" class="form-label">Loại sách</label>
                <select id="Category_ID" class="form-select" name="Category_ID" required>
                    <?php
                        foreach($category as $c)
                        {
                            ?>
                                <option value="<?php echo $c->Category_ID; ?>"><?php echo $c->Category_Name; ?></option>
                            <?php
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="mb-3">
            <label for="Image">Ảnh</label>
            <input type="file" class="form-control" id="Image" name="Image" required>
        </div>
        <div class="mb-3">
            <label for="additional_images">Ảnh phụ</label>
            <input type="file" class="form-control" id="additional_images" name="additional_images[]" multiple required>
        </div>
        <input type="submit" class="btn btn-primary" name="submit" value="Thêm sản phẩm" />
    </form>
</div>

<?php
    $content = ob_get_clean();
    include ("admin_page_layout.php");
?>