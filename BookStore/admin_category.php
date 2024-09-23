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
    
    $query = "SELECT COUNT(*) AS total_rows FROM category";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $total_rows = $stmt->fetch(PDO::FETCH_ASSOC)['total_rows'];

    $p = new Pager();
    $pages = $p->findPages($total_rows, $limit);
    $vt = $p->findStart($limit);
    
    // Load danh mục
    $sql_category = "SELECT category.*, COUNT(products.Category_ID) AS Quantity FROM category LEFT JOIN products ON category.Category_ID = products.Category_ID 
    GROUP BY category.Category_ID ORDER BY category.Category_ID ASC LIMIT $vt, $limit";

    $sta = $pdo->prepare($sql_category);
    $sta->execute();
    $category = $sta->fetchAll(PDO::FETCH_OBJ);

    // Xử lý danh mục
    if (isset($_GET['delete_id'])) {
        try{
            $pdo->beginTransaction();
            $delete_id = $_GET['delete_id'];

            //Xóa chính phụ khỏi database
            $sql_delete_category = "DELETE FROM category WHERE Category_ID = :id";
            $stmt_delete_category = $pdo->prepare($sql_delete_category);

            if ($stmt_delete_category->execute([':id' => $delete_id])) {
                echo "<script>alert('Xóa danh mục thành công');</script>";
                echo "<script>window.history.back();</script>";
            } else {
                echo "<script>alert('Xóa danh mục không thành công');</script>";
            }
            $pdo->commit();
        } catch (PDOException $e) {
            $pdo->rollBack();
            echo "<script>alert('Không thể xoá danh mục này !!!');</script>";
        }
    }
?>

<div>
    <div class="d-flex" style="margin: 30px 0 30px 0;">
        <a href="admin_add_category.php" class="btn btn-primary">Thêm Danh mục</a>
        <a href="admin_product.php" class="btn btn-primary ms-2">Danh Sách Sách</a>
    </div>

    <h2 class="text-center" style="color: #eb9316; margin-bottom: 30px">Danh Mục Sách</h2>
    <table class="table text-center align-middle">
        <thead class="table-warning">
            <tr>
                <th>ID Danh mục</th>
                <th>Tên Danh Mục</th>
                <th>Số lượng sách</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach($category as $c)
                {
                    ?>
                        <tr>
                            <td><?php echo $c->Category_ID; ?></td>
                            <td><?php echo $c->Category_Name; ?></td>
                            <td><?php echo $c->Quantity; ?></td>
                            <td>
                                <button type="button" class="btn btn-danger" onclick="confirmDelete_category(<?php echo $c->Category_ID; ?>)">
                                    Xóa
                                </button>
                                <a href="admin_edit_category.php?value=<?php echo $c->Category_ID; ?>" class="btn btn-primary">Sửa Danh Mục</a>
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