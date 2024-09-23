<?php
    session_start();
    ob_start();

    include "dbconnect.php";

    $connection = new Connection();
    $pdo = $connection->CheckConnect();
    $customer = $_SESSION['user'];

    $query = "SELECT SUM(c.Quantity * p.price_after_discount) AS TotalPrice FROM cart c INNER JOIN products p ON c.Product_ID = p.Product_ID WHERE c.UserName = :customer";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':customer', $customer);
    $stmt->execute();
    $total = $stmt->fetch(PDO::FETCH_OBJ);

    $query = "SELECT * FROM cart WHERE UserName = :customer";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':customer', $customer);
    $stmt->execute();
    $cart = $stmt->fetchAll(PDO::FETCH_OBJ);

    if(isset($_POST["submit"]))
    {
        $recipient = $_POST["Recipient"];
        $address = $_POST["Address"];
        $phone_number = $_POST["Phone_Number"];
        $type_payment = $_POST["Type_Payment"];
        $date = date("Y/m/d");

        $query = "INSERT INTO orders VALUES (NUll, :customer, :recipient, :address, :phone_number, :total, :type_payment, :date)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':customer', $customer);
        $stmt->bindParam(':recipient', $recipient);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->bindParam(':total', $total->TotalPrice);
        $stmt->bindParam(':type_payment', $type_payment);
        $stmt->bindParam(':date', $date);
        $stmt->execute();

        $query = "SELECT * FROM orders WHERE UserName = :customer";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':customer', $customer);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_OBJ);

        $lastOrder = end($orders);
        $lastOder_id = $lastOrder->Order_ID;

        foreach($cart as $c)
        {
            $query = "SELECT * FROM products WHERE Product_ID = :product_id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':product_id', $c->Product_ID);
            $stmt->execute();
            $find_product = $stmt->fetch(PDO::FETCH_OBJ);

            $query = "INSERT INTO order_detail VALUES (:lastOder_id, :product_id, :quantity, :price)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':lastOder_id', $lastOder_id);
            $stmt->bindParam(':product_id', $c->Product_ID);
            $stmt->bindParam(':quantity', $c->Quantity);
            $stmt->bindParam(':price', $find_product->price_after_discount);
            $stmt->execute();

            $query = "SELECT * FROM products WHERE Product_ID = :product_id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':product_id', $c->Product_ID);
            $stmt->execute();
            $p = $stmt->fetch(PDO::FETCH_OBJ);

            $sold = $p->Sold + $c->Quantity;

            $query = "UPDATE products SET Sold = :sold WHERE Product_ID = :product_id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':sold', $sold);
            $stmt->bindParam(':product_id', $c->Product_ID);
            $stmt->execute();
        }

        $query = "DELETE FROM cart WHERE UserName = :customer";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':customer', $customer);
        $stmt->execute();
    }
?>

<div class="container" style="margin-bottom: 50px; margin-top: 50px;">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h2 class="text-center">Thông tin đơn hàng<img src="img/icons/accept.png" class="checkmark animate-checkmark"></h2>
                </div>
                <div class="card-body">
                    <?php
                        $date_format = strtotime($lastOrder->Date);
                        $date = date('d/m/Y', $date_format);
                    ?>
                    <p><strong>Mã đơn hàng:</strong> <?php echo $lastOrder->Order_ID; ?></p>
                    <p><strong>Người nhận hàng:</strong> <?php echo $lastOrder->Recipient; ?></p>
                    <p><strong>Địa chỉ giao hàng:</strong> <?php echo $lastOrder->Address; ?></p>
                    <p><strong>Số điện thoại:</strong> <?php echo $lastOrder->Phone_Number; ?></p>
                    <p><strong>Số tiền thanh toán:</strong> <?php echo number_format($lastOrder->Total_Price); ?> ₫</p>
                    <p><strong>Phương thức thanh toán:</strong> <?php echo $lastOrder->Type_Payment; ?></p>
                    <p><strong>Ngày thanh toán:</strong> <?php echo $date; ?></p>
                </div>
            </div>
        </div>
    </div>

        <h2 class="text-center text-danger" style="margin-top: 50px;"><b>Cảm ơn bạn đã đặt hàng!</b></h2>
</div>

<?php
    $content = ob_get_clean();
    include ("page_layout.php");
?>