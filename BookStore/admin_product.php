<?php
    session_start();
    ob_start();
    include "dbconnect.php";

    $connection = new Connection();
    $pdo = $connection->CheckConnect();

    // Hiển thị thông báo nếu có
    if (isset($_GET['Message'])) {
        echo '<script type="text/javascript">alert("' . $_GET['Message'] . '");</script>';
    }

    if (isset($_GET['response'])) {
        echo '<script type="text/javascript">alert("' . $_GET['response'] . '");</script>';
    }
    // ==========================================================================================================================================
    include("page_navigation.php");
    $limit = 15;
    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    
    $query = "SELECT COUNT(*) AS total_rows FROM products";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $total_rows = $stmt->fetch(PDO::FETCH_ASSOC)['total_rows'];

    $p = new Pager();
    $pages = $p->findPages($total_rows, $limit);
    $vt = $p->findStart($limit);

    // Load sản phẩm
    $sql_book = "SELECT * FROM products ORDER BY Product_ID ASC LIMIT $vt, $limit";
    $sta = $pdo->prepare($sql_book);
    $sta->execute();
    $books = $sta->fetchAll(PDO::FETCH_OBJ);

    // Xử lý xóa sách
    if (isset($_GET['delete_id'])) {
        try{
            $pdo->beginTransaction();
            $delete_id = $_GET['delete_id'];

            // Xóa ảnh phụ khỏi folder ảnh
            $sql_select_additional_images = "SELECT Image FROM additional_images WHERE Product_ID = :id";
            $stmt_select_additional_images = $pdo->prepare($sql_select_additional_images);
            $stmt_select_additional_images->execute([':id' => $delete_id]);
            $additional_images = $stmt_select_additional_images->fetchAll(PDO::FETCH_ASSOC);

            foreach ($additional_images as $image_data) {
                $image_path = "img/books/" . $image_data['Image'];
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }

            // Xóa ảnh chính khỏi folder ảnh
            $sql_select_image = "SELECT Image FROM products WHERE Product_ID = :id";
            $stmt_select_image = $pdo->prepare($sql_select_image);
            $stmt_select_image->execute([':id' => $delete_id]);
            $image_data = $stmt_select_image->fetch(PDO::FETCH_ASSOC);

            $image_path = "img/books/" . $image_data['Image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }

            //Xóa ảnh phụ khỏi database
            $sql_delete_additional_images = "DELETE FROM additional_images WHERE Product_ID = :id";
            $stmt_delete_additional_images = $pdo->prepare($sql_delete_additional_images);
            $stmt_delete_additional_images->execute([':id' => $delete_id]);

            //Xóa sản phẩm khỏi database
            $sql_delete_book = "DELETE FROM products WHERE Product_ID = :id";
            $stmt_delete_book = $pdo->prepare($sql_delete_book);

            if ($stmt_delete_book->execute([':id' => $delete_id])) {
                echo "<script>alert('Xóa sách thành công');</script>";
                echo "<script>window.history.back();</script>";
            } else {
                echo "<script>alert('Xóa sách không thành công');</script>";
            }
            $pdo->commit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo "<script>alert('Không thể xoá sách này thay vào đó hãy cho nó dừng hoạt động !!!');</script>";
        }
    }
?>

<div>
    <div class="d-flex" style="margin: 30px 0 30px 0;">
        <a href="admin_add_product.php" class="btn btn-primary">Thêm sách</a>
        <a href="admin_category.php" class="btn btn-primary ms-2">Danh mục sách</a>
    </div>

    <h2 class="text-center" style="color: #eb9316; margin-bottom: 30px">Danh Sách Sách</h2>
    <table class="table text-center align-middle">
        <thead class="table-warning">
            <tr>
                <th>ID Sách</th>
                <th>Ảnh</th>
                <th>Tên Sách</th>
                <th>Giá</th>
                <th>Số lượng bán</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach($books as $b)
                {
                    ?>
                        <tr>
                            <td><?php echo $b->Product_ID; ?></td>
                            <td><img src="img/books/<?php echo $b->Image ?>" alt="<?php echo $b->Title ?>" style="max-height: 80px;"></td>
                            <td><?php echo $b->Title; ?></td>
                            <td><?php echo number_format($b->price_after_discount); ?> ₫</td>
                            <td><?php echo $b->Sold; ?></td>
                            <td>
                                <?php
                                    if($b->IsActive == 1)
                                    {
                                        ?>
                                            Hoạt động
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                            Không hoạt động
                                        <?php
                                    }
                                ?>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger" onclick="confirmDelete_product(<?php echo $b->Product_ID; ?>)">
                                    Xóa
                                </button>
                                <a href="admin_edit_product.php?value=<?php echo $b->Product_ID; ?>" class="btn btn-primary">Sửa sách</a>
                            </td>
                        </tr>
                    <?php
                }
            ?>
        </tbody>
    </table>

    <!-- Hiển thị phân trang -->
    <?php
        if($pages > 1)
        {
            ?>
                <div class="d-flex justify-content-end" style="margin-top: 20px; font-size:20px;">
                    <?php echo $p->pageList($current_page, $pages); ?>
                </div>
            <?php
        }
    ?>
</div>

<?php
    $content = ob_get_clean();
    include ("admin_page_layout.php");
?>