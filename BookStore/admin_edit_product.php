<?php
    session_start();
    ob_start();

    include "dbconnect.php";

    $connection = new Connection();
    $pdo = $connection->CheckConnect();

    $query = "SELECT * FROM category";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $category = $stmt->fetchAll(PDO::FETCH_OBJ);

    if(isset($_GET["value"]))
    {
        $_SESSION["product_id"] = $_GET["value"];
    }

    $product_id = $_SESSION["product_id"];

    $query = "SELECT * FROM products WHERE Product_ID = :product_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_OBJ);

    $query = "SELECT * FROM additional_images WHERE Product_ID = :product_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->execute();
    $additional_images = $stmt->fetchAll(PDO::FETCH_OBJ);

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
        $IsActive = $_POST["IsActive"];

        $target_dir_add = "img/books/";
        $allowed_types_add = array("jpg", "png", "jpeg", "gif");

        if($_FILES["Image"]["size"] > 0 && $_FILES["Image"]["error"] == 0) {
            // Xử lý upload hình ảnh
            $imageFileType_add = strtolower(pathinfo($_FILES["Image"]["name"], PATHINFO_EXTENSION));
            $original_filename_add = pathinfo($_FILES["Image"]["name"], PATHINFO_FILENAME);
            $new_filename_add = $original_filename_add . '.' . $imageFileType_add;
            $target_file_add = $target_dir_add . $new_filename_add;

            // Kiểm tra định dạng file
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

            $query = "UPDATE products SET Image = :Image WHERE Product_ID = :product_id";

            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':Image', $new_filename_add);
            $stmt->bindParam(':product_id', $product_id);
            $stmt->execute();
        
            // Xoá ảnh cũ nếu tồn tại
            $old_image_path = "img/books/" . $product->Image;
            if(file_exists($old_image_path)) {
                unlink($old_image_path); // Xoá ảnh cũ
            }
        }

        // Xử lý các ảnh đã chọn để xoá
        if(isset($_POST["delete_images"]) && is_array($_POST["delete_images"])) {
            foreach($_POST["delete_images"] as $image_id) {
                // Xoá ảnh khỏi thư mục
                $query_delete_image = "SELECT Image FROM additional_images WHERE additional_images_ID = :image_id";
                $stmt_delete_image = $pdo->prepare($query_delete_image);
                $stmt_delete_image->bindParam(':image_id', $image_id);
                $stmt_delete_image->execute();
                $image = $stmt_delete_image->fetch(PDO::FETCH_OBJ);

                $image_path = "img/books/" . $image->Image;
                if(file_exists($image_path)) {
                    unlink($image_path); // Xoá ảnh khỏi thư mục
                }

                // Xoá ảnh khỏi cơ sở dữ liệu
                $query_delete_image_db = "DELETE FROM additional_images WHERE additional_images_ID = :image_id";
                $stmt_delete_image_db = $pdo->prepare($query_delete_image_db);
                $stmt_delete_image_db->bindParam(':image_id', $image_id);
                $stmt_delete_image_db->execute();
            }
        }

        if(isset($_FILES["additional_images"]) && !empty($_FILES["additional_images"]["name"][0])) {
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
        }

        // Lưu thông tin sản phẩm vào cơ sở dữ liệu
        $query = "UPDATE products SET Title = :Title, Author = :Author, Price = :Price, Discount = :Discount, Publisher = :Publisher,
        Description = :Description, Language = :Language, Category_ID = :Category_ID, IsActive = :IsActive WHERE Product_ID = :product_id";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':Title', $Title);
        $stmt->bindParam(':Author', $Author);
        $stmt->bindParam(':Price', $Price);
        $stmt->bindParam(':Discount', $Discount);
        $stmt->bindParam(':Publisher', $Publisher);
        $stmt->bindParam(':Description', $Description);
        $stmt->bindParam(':Language', $Language);
        $stmt->bindParam(':Category_ID', $Category_ID);
        $stmt->bindParam(':IsActive', $IsActive);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();

        echo "<script>alert('Sửa sản phẩm thành công');</script>";
        echo "<script>window.location.href='admin_edit_product.php';</script>";
    }
?>

<div class="container" style="margin-bottom: 50px; margin-top: 50px;">
    <div style="margin-bottom: 30px">
        <a href="admin_product.php" class="btn btn-primary">Quay lại</a>
    </div>

    <div class="title-page">
        <h4>SỬA SÁCH</h4>
    </div>

    <form action="admin_edit_product.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="Title" class="form-label">Tên sách</label>
            <input type="text" class="form-control" id="Title" name="Title" required value="<?php echo $product->Title; ?>">
        </div>
        <div class="mb-3">
            <label for="Author" class="form-label">Tên tác giả</label>
            <input type="text" class="form-control" id="Author" name="Author" required value="<?php echo $product->Author; ?>">
        </div>
        <div class="row g-3 mb-3">
            <div class="col">
                <label for="Price" class="form-label">Đơn giá</label>
                <input type="number" class="form-control" id="Price" name="Price" required value="<?php echo $product->Price; ?>">
            </div>
            <div class="col">
                <label for="Discount" class="form-label">Giảm giá (%)</label>
                <input type="number" class="form-control" id="Discount" name="Discount" required value="<?php echo $product->Discount; ?>">
            </div>
            <div class="col">
                <label for="Publisher" class="form-label">Nhà xuất bản</label>
                <input type="text" class="form-control" id="Publisher" name="Publisher" required value="<?php echo $product->Publisher; ?>">
            </div>
        </div>
        <div class="mb-3">
            <label for="Description" class="form-label">Mô tả</label>
            <textarea class="form-control" id="Description" rows="5" name="Description" required><?php echo $product->Description; ?></textarea>
        </div>
        <div class="row g-3 mb-3">
            <div class="col">
                <label for="Language" class="form-label">Ngôn ngữ</label>
                <input type="text" class="form-control" id="Language" name="Language" required value="<?php echo $product->Language; ?>">
            </div>
            <div class="col">
                <label for="Category_ID" class="form-label">Loại sách</label>
                <select id="Category_ID" class="form-select" name="Category_ID" required>
                    <?php
                        foreach($category as $c)
                        {
                            if($c->Category_ID == $product->Category_ID)
                            {
                                ?>
                                    <option value="<?php echo $c->Category_ID; ?>" selected><?php echo $c->Category_Name; ?></option>
                                <?php
                            }
                            else
                            {
                                ?>
                                    <option value="<?php echo $c->Category_ID; ?>"><?php echo $c->Category_Name; ?></option>
                                <?php
                            }
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="mb-3">
            <label for="Image">Ảnh</label>
            <div class="image-item" style="position: relative; margin: 10px;">
                <img src="img/books/<?php echo $product->Image?>" height="200px">
            </div>
            <input type="file" class="form-control" id="Image" name="Image">
        </div>
        <div class="mb-3">
            <label for="additional_images">Ảnh phụ</label>
            <div style="margin-bottom: 30px;">
                <div class="d-flex" style="flex-wrap: wrap;">
                    <?php
                        foreach($additional_images as $ai)
                        {
                            ?>
                                <div class="image-item" style="position: relative; margin: 10px;">
                                    <img src="img/books/<?php echo $ai->Image?>" height="200px">
                                    <input type="checkbox" name="delete_images[]" value="<?php echo $ai->additional_images_ID; ?>" style="position: absolute; top: 10px; right: 10px;">
                                </div>
                            <?php
                        }
                    ?>
                </div>
                <input type="file" class="form-control" id="additional_images" name="additional_images[]" multiple>
            </div>
        </div>
        <div class="mb-3">
            <div style="margin-bottom: 20px">Trạng thái</div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="IsActive" id="IsActive" value="1" <?php if ($product->IsActive == 1) echo 'checked'; ?>>
                <label class="form-check-label" for="IsActive">Hoạt động</label>
            </div>
            <div class="form-check form-check-inline" style="margin-left: 50px">
                <input class="form-check-input" type="radio" name="IsActive" id="IsActive" value="0" <?php if ($product->IsActive == 0) echo 'checked'; ?>>
                <label class="form-check-label" for="IsActive">Không hoạt động</label>
            </div>
        </div>
        <input type="submit" class="btn btn-primary" name="submit" value="Lưu thay đổi" />
    </form>
</div>

<?php
    $content = ob_get_clean();
    include ("admin_page_layout.php");
?>