<?php
    session_start();

    ob_start();

    include "dbconnect.php";
    include("page_navigation.php");
    $connection = new Connection();
    $pdo = $connection->CheckConnect();

    $limit = 12;
    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

    $sql = "SELECT * FROM category";
    $sta = $pdo->prepare($sql);
    $sta->execute();
    $all_category = $sta->fetchAll(PDO::FETCH_OBJ);

    if (isset($_GET["keyword"])) {
        $_SESSION["keyword"] = $_GET["keyword"];
    }
        $keyword = $_SESSION["keyword"];

        $query = "SELECT COUNT(*) AS total_rows FROM products WHERE Product_ID LIKE ? OR Title LIKE ? OR Author LIKE ? OR Publisher LIKE ?";
        $stmt = $pdo->prepare($query);
        $like_keyword = "%{$keyword}%";
        $stmt->execute([$like_keyword, $like_keyword, $like_keyword, $like_keyword]);

        $total_rows = $stmt->fetch(PDO::FETCH_ASSOC)['total_rows'];

        $p = new Pager();
        $pages = $p->findPages($total_rows, $limit);
        $vt = $p->findStart($limit);

        if (isset($_GET['sort'])) {
            switch ($_GET['sort']) {
                case "price":
                    $query = "SELECT * FROM products WHERE Product_ID LIKE ? OR Title LIKE ? OR Author LIKE ? OR Publisher LIKE ? ORDER BY price_after_discount LIMIT $vt, $limit";
                    break;
                case "priceh":
                    $query = "SELECT * FROM products WHERE Product_ID LIKE ? OR Title LIKE ? OR Author LIKE ? OR Publisher LIKE ? ORDER BY price_after_discount DESC LIMIT $vt, $limit";
                    break;
                case "discount":
                    $query = "SELECT * FROM products WHERE Product_ID LIKE ? OR Title LIKE ? OR Author LIKE ? OR Publisher LIKE ? ORDER BY Discount DESC LIMIT $vt, $limit";
                    break;
                case "discountl":
                    $query = "SELECT * FROM products WHERE Product_ID LIKE ? OR Title LIKE ? OR Author LIKE ? OR Publisher LIKE ? ORDER BY Discount LIMIT $vt, $limit";
                    break;
                default:
                    $query = "SELECT * FROM products WHERE Product_ID LIKE ? OR Title LIKE ? OR Author LIKE ? OR Publisher LIKE ? LIMIT $vt, $limit";
            }
        } else {
            $query = "SELECT * FROM products WHERE Product_ID LIKE ? OR Title LIKE ? OR Author LIKE ? OR Publisher LIKE ? LIMIT $vt, $limit";
        }

        $stmt = $pdo->prepare($query);
        $stmt->execute([$like_keyword, $like_keyword, $like_keyword, $like_keyword]);
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);

    $sql = "SELECT * FROM category";
    $sta = $pdo->prepare($sql);
    $sta->execute();
    $all_category = $sta->fetchAll(PDO::FETCH_OBJ);
?>

<div style="margin-top: 20px">
    <div class="row">
        <div class="col-md-3 col-lg-3" id="category">
            <div style="background:#D67B22;color:#fff;font-weight:800;border:none;padding:15px;">Danh mục</div>
            <ul>
                <li>
                    <a href="product_page.php?value=0" style="padding-left: 10px">Tất cả sản phẩm</a>
                </li>
                <?php
                foreach ($all_category as $ca) {
                    ?>
                        <li>
                            <a href="product_page.php?value=<?php echo $ca->Category_ID; ?>" style="padding-left: 10px"><?php echo $ca->Category_Name; ?></a>
                        </li>
                    <?php
                }
                ?>
            </ul>
        </div>

        <div class="col-md-9 col-lg-9">
            <h2 style="color:rgb(228, 55, 25);text-transform:uppercase;margin-bottom:0px;">
                Tìm được <?php echo $total_rows; ?> sách với từ khoá "<?php echo $keyword; ?>"
            </h2>

            <div class="text-end" style="margin: 20px 0 50px 0;">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" class="pull-right">
                    <input type="hidden" name="keyword" value="<?php echo $keyword; ?>">
                    <label for="sort">Sort by &nbsp: &nbsp</label>
                    <select name="sort" id="select" onchange="form.submit()">
                        <option value="default" name="default" selected="selected">Select</option>
                        <option value="price" name="price">Low To High Price </option>
                        <option value="priceh" name="priceh">Highest To Lowest Price </option>
                        <option value="discountl" name="discountl">Low To High Discount </option>
                        <option value="discount" name="discount">Highest To Lowest Discount</option>
                    </select>
                </form>
            </div>

            <div style="display: grid; grid-template-columns: repeat(4, 1fr); grid-gap: 20px;">
            <?php
                foreach($result as $all)
                {
                    ?>
                        <div class="product_item" style="text-align: center; position: relative; margin-top: 20px; border: 1px #c6c6c6 solid;">
                            <div class="Product_thumnail">
                                <?php
                                    if($all->Discount != 0)
                                    {
                                        ?>
                                            <span class="label_sale">-<?php echo $all->Discount; ?>%</span>
                                        <?php
                                    }
                                ?>
                                <a href="#"><img src="img/books/<?php echo $all->Image; ?>" alt="<?php echo $all->Title; ?>" style="max-height: 300px;"></a>
                            </div>
                            <div style="background-color: #f1f1f1;">
                                <div style="height: 64px; margin-bottom: 5px;" class="Product_name"><a href="#"><?php echo $all->Title; ?></a></div>
                                <div>
                                    <?php
                                        $star_rating = $all->Stars; // Điểm số đánh giá
                                        $full_stars = floor($star_rating); // Số sao full
                                        $half_star = ceil($star_rating) != $full_stars; // Có nửa sao hay không
                                        $empty_stars = 5 - $full_stars - ($half_star ? 1 : 0); // Số sao trống

                                        // Hiển thị các sao
                                        for ($i = 0; $i < $full_stars; $i++) {
                                            echo '<i class="fa-solid fa-star"></i>';
                                        }
                                        if ($half_star) {
                                            echo '<i class="fa-solid fa-star-half-alt"></i>';
                                        }
                                        for ($i = 0; $i < $empty_stars; $i++) {
                                            echo '<i class="fa-regular fa-star"></i>';
                                        }
                                    ?>
                                </div>
                                <span style="font-weight: bold; color: #707070;">Mã sản sách: <?php echo $all->Product_ID; ?></span>
                                <div>
                                    <?php
                                        if($all->Discount != 0)
                                        {
                                            ?>
                                                <span class="Product_after_discount"><?php echo number_format($all->price_after_discount); ?> ₫</span>
                                                <span class="Product_full_price"><?php echo number_format($all->Price); ?> ₫</span>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                                <span class="Product_after_discount"><?php echo number_format($all->price_after_discount); ?> ₫</span>
                                            <?php
                                        }
                                    ?>
                                </div>
                                <div class="product_buttons">
                                    <a class="add_to_cart_button" href="cart.php?value=<?php echo $all->Product_ID; ?>"><i class="fa-solid fa-cart-shopping"></i></a>
                                    <a class="view_details_button" href="description.php?value=<?php echo $all->Product_ID; ?>"><i class="fa-regular fa-eye"></i></a>
                                </div>
                            </div>
                        </div>
                    <?php
                }
            ?>
        </div>
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
</div>

<?php
    $content = ob_get_clean();
    include ("page_layout.php");
?>