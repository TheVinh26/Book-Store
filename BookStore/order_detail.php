<?php
    session_start();
    ob_start();

    include "dbconnect.php";

    $connection = new Connection();
    $pdo = $connection->CheckConnect();
    $customer = $_SESSION['user'];

    if(isset($_GET["value"]))
    {
        $order_id = $_GET["value"];
    }

    $query = "SELECT * FROM order_detail WHERE Order_ID = :order_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':order_id', $order_id);
    $stmt->execute();
    $order_details = $stmt->fetchAll(PDO::FETCH_OBJ);
?>

<div class="container" style="margin-bottom: 50px; margin-top: 50px;">
    <div class="title-page">
        <h4>CHI TIẾT ĐƠN HÀNG</h4>
    </div>

    <table class = "table text-center align-middle">
        <thead class="thead-dark bg-secondary table-dark">
            <tr>
                <td>Hình</td>
                <td>Tên sản phẩm</td>
                <td>Đơn giá</td>
                <td>Số lượng</td>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach($order_details as $od)
                {
                    $product_id = $od->Product_ID;
                    $query = "SELECT * FROM products WHERE Product_ID = :product_id";
                    $stmt = $pdo->prepare($query);
                    $stmt->bindParam(':product_id', $product_id);
                    $stmt->execute();
                    $product = $stmt->fetch(PDO::FETCH_OBJ);

                    ?>
                        <tr>
                            <td><img src="img/books/<?php echo $product->Image?>" alt="<?php echo $product->Title?>" style="max-height: 200px;"></td>
                            <td><?php echo $product->Title?></td>
                            <td><?php echo number_format($product->price_after_discount); ?> ₫</td>
                            <td><?php echo $od->Quantity; ?></td>
                        </tr>
                    <?php
                }
            ?>
        </tbody>
    </table>

    <div style="margin-bottom: 20px;">
        <a id="Return" href="order_history.php"><i class="fa-solid fa-arrow-left-long" style="margin-right: 5px"></i><b style="font-size: 20px">Quay lại</b></a>
    </div>
</div>    

<?php
    $content = ob_get_clean();
    include ("page_layout.php");
?>