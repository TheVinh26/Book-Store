<?php
    session_start();
    ob_start();

    include "dbconnect.php";

    $connection = new Connection();
    $pdo = $connection->CheckConnect();
    $customer = $_SESSION['user'];

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
        <h4>THANH TOÁN</h4>
    </div>

    <div class="row">
        <div class="col-6">
            <h4 style="margin-bottom: 20px;">Thông tin người nhận</h4>
            <form action="order_success.php" method="POST">
                <div class="mb-3">
                    <label for="Recipient" class="form-label">Tên người nhận</label>
                    <input type="text" class="form-control" id="Recipient" name="Recipient">
                </div>
                <div class="mb-3">
                    <label for="Address" class="form-label">Địa chỉ</label>
                    <input type="text" class="form-control" id="Address" name="Address">
                </div>
                <div class="mb-3">
                    <label for="Phone_Number" class="form-label">Số điện thoại</label>
                    <input type="number" class="form-control" id="Phone_Number" name="Phone_Number">
                </div>
                <div class="mb-3">
                    <label for="Type_Payment" class="form-label">Thanh toán</label>
                    <select id="Type_Payment" class="form-select" name="Type_Payment">
                        <option value="Tiền mặt">Tiền mặt</option>
                        <option value="Visa">Visa</option>
                        <option value="Master card">Master card</option>
                        <option value="Momo">Momo</option>
                    </select>
                </div>
                <input type = "submit" class="btn btn-primary" name="submit" value="Xác nhận thanh toán" style="margin-top: 20px"/>
            </form> 
        </div>

        <div class="col-4" style="margin-left: 150px;">
            <h4 style="margin-bottom: 20px;">Đơn hàng</h4>
            <table class = "table" id="table_don_hang">
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
                                    <td><?php echo $product->Title; ?></td>
                                    <td><?php echo number_format($product->price_after_discount); ?> ₫</td>
                                    <td><?php echo $c->Quantity; ?></td>
                                </tr>
                            <?php
                        }
                    ?>
                </tbody>
            </table>

            <div class="d-flex" style="margin-top: 50px;">
                <h4 class="ms-auto"><b>Tổng tiền: <?php echo number_format($total->TotalPrice); ?> ₫</b></h4>
            </div>
        </div>
    </div>
</div>  

<?php
    $content = ob_get_clean();
    include ("page_layout.php");
?>