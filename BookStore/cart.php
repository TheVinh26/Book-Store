<?php
    session_start();
    ob_start();

    include "dbconnect.php";
    $connection = new Connection();
    $pdo = $connection->CheckConnect();
    $customer = $_SESSION['user'];

    if(isset($_GET["delete"]))
    {
        $product_id = $_GET["delete"];
        
        $query = "DELETE FROM cart WHERE UserName = :customer AND Product_ID = :product_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':customer', $customer);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
    }

    if(isset($_GET["value"]))
    {
        $product_id = $_GET["value"];
        
        $query = "SELECT * FROM cart WHERE UserName = :customer AND Product_ID = :product_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':customer', $customer);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
        $check = $stmt->fetch(PDO::FETCH_OBJ);

        if(isset($_GET["quantity"]))
        {
            if(empty($check))
            {
                $quantity = $_GET["quantity"];
                $query = "INSERT INTO cart VALUES (:customer, :product_id, :quantity)";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':customer', $customer);
                $stmt->bindParam(':product_id', $product_id);
                $stmt->bindParam(':quantity', $quantity);
                $stmt->execute();
            }
            else
            {
                $quantity = $check->Quantity + $_GET["quantity"];
                $query = "UPDATE cart SET Quantity = :quantity WHERE UserName = :customer AND Product_ID = :product_id";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':customer', $customer);
                $stmt->bindParam(':product_id', $product_id);
                $stmt->bindParam(':quantity', $quantity);
                $stmt->execute();
            }
        }
        else
        {
            if(empty($check))
            {
                $query = "INSERT INTO cart VALUES (:customer, :product_id, 1)";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':customer', $customer);
                $stmt->bindParam(':product_id', $product_id);
                $stmt->execute();
            }
            else
            {
                $quantity = $check->Quantity + 1;
                $query = "UPDATE cart SET Quantity = :quantity WHERE UserName = :customer AND Product_ID = :product_id";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':customer', $customer);
                $stmt->bindParam(':product_id', $product_id);
                $stmt->bindParam(':quantity', $quantity);
                $stmt->execute();
            }
        }
    }

    $query = "SELECT * FROM cart WHERE UserName = :customer";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':customer', $customer);
    $stmt->execute();
    $cart = $stmt->fetchAll(PDO::FETCH_OBJ);

    $query = "SELECT SUM(c.Quantity * p.price_after_discount) AS TotalPrice FROM cart c INNER JOIN products p ON c.Product_ID = p.Product_ID WHERE c.UserName = :customer";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':customer', $customer);
    $stmt->execute();
    $total = $stmt->fetch(PDO::FETCH_OBJ);
?>

<div class="container" style="margin-bottom: 50px; margin-top: 50px;">
        <div class="title-page">
            <h4>GIỎ HÀNG</h4>
        </div>

        <div class="d-flex justify-content-end" id="Lich_su_dat_hang" style="margin-bottom: 20px;">
            <a href="order_history.php">Lịch sử đặt hàng</a>
        </div>

        <?php 
            if(!empty($cart))
            {
                ?>
                    <table class = "table text-center align-middle">
                        <thead class ="thead-dark bg-secondary table-dark">
                            <tr>
                                <td>Hình</td>
                                <td>Tên sản phẩm</td>
                                <td>Đơn giá</td>
                                <td>Số lượng</td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach($cart as $c)
                                {
                                    $product_id = $c->Product_ID;
                                    $query = "SELECT * FROM products WHERE Product_ID = :product_id";
                                    $stmt = $pdo->prepare($query);
                                    $stmt->bindParam(':product_id', $product_id);
                                    $stmt->execute();
                                    $product = $stmt->fetch(PDO::FETCH_OBJ);
                                    ?>
                                        <tr>
                                            <td><img src="img/books/<?php echo $product->Image ?>" alt="<?php echo $product->Title ?>" style="max-height: 100px;"></td>
                                            <td><a href="description.php?value=<?php echo $product->Product_ID; ?>"><?php echo $product->Title ?></a></td>
                                            <td><?php echo number_format($product->price_after_discount); ?> ₫</td>
                                            <td><?php echo $c->Quantity ?></td>
                                            <td>
                                                <a id="Delete_item_from_cart" href="cart.php?delete=<?php echo $product->Product_ID; ?>">
                                                    <i class="fa-regular fa-circle-xmark" style="color: red; font-size: 25px"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php
                                }
                            ?>
                        </tbody>
                    </table>

                    <div class="d-flex" style="margin-top: 50px;">
                        <h4 class="ms-auto"><b>Tổng tiền: <?php echo number_format($total->TotalPrice); ?> ₫</b></h4>
                    </div>
                    
                    <div class="d-flex" id="Cart_button" style="margin-top: 50px;">
                        <a class="me-auto btn btn-primary" href="home_page.php">Quay lại trang chính</a>
                        <a class="ms-auto btn btn-primary" href="order.php">Thanh toán</a>
                    </div>
                <?php
            }
            else
            {
                ?>
                    <div class="d-flex justify-content-center" style="margin-top: 50px;">Giỏ hàng của bạn trống</div>
                <?php
            }
        ?>
</div>

<?php
    $content = ob_get_clean();
    include ("page_layout.php");
?>