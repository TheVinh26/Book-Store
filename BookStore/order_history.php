<?php
    session_start();
    ob_start();

    include "dbconnect.php";

    $connection = new Connection();
    $pdo = $connection->CheckConnect();
    $customer = $_SESSION['user'];

    $query = "SELECT * FROM orders WHERE UserName = :customer";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':customer', $customer);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_OBJ);
?>

<div class="container" style="margin-bottom: 50px; margin-top: 50px;">
    <div class="title-page">
        <h4>LỊCH SỬ ĐẶT HÀNG</h4>
    </div>

    <?php
        if(!empty($orders))
        {
            ?>
                <table class = "table text-center align-middle">
                    <thead class ="thead-dark bg-secondary table-dark">
                        <tr>
                            <td>Mã đơn hàng</td>
                            <td>Ngày đặt</td>
                            <td>Thông tin người nhận</td>
                            <td>Tổng tiền</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($orders as $o)
                            {
                                $date_format = strtotime($o->Date);
                                $date = date('d/m/Y', $date_format);
                                ?>
                                    <tr>
                                        <td><?php echo $o->Order_ID; ?></td>
                                        <td><?php echo $date; ?></td>
                                        <td>
                                            Người nhận hàng: <?php echo $o->Recipient; ?> <br>
                                            Địa chỉ giao hàng: <?php echo $o->Address; ?> <br>
                                            Số điện thoại: <?php echo $o->Phone_Number; ?> <br>
                                            Phương thức thanh toán: <?php echo $o->Type_Payment; ?> <br>
                                        </td>
                                        <td><?php echo number_format($o->Total_Price); ?> ₫</td>
                                        <td>
                                            <a class="text-primary link-warning" id="Chi_tiet_don_hang" href="order_detail.php?value=<?php echo $o->Order_ID; ?>">Chi tiết đơn hàng</a>
                                        </td>
                                    </tr>
                                <?php
                            }
                        ?>
                    </tbody>
                </table>
            <?php
        }
        else
        {
            ?>
                <p>Bạn chưa bao giờ đặt hàng</p>
            <?php
        }
    ?>

    <div style="margin-bottom: 20px;">
        <a id="Return" href="cart.php"><i class="fa-solid fa-arrow-left-long" style="margin-right: 5px"></i><b style="font-size: 20px">Quay lại</b></a>
    </div>
</div>    

<?php
    $content = ob_get_clean();
    include ("page_layout.php");
?>