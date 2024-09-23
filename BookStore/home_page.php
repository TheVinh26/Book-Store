<?php
    session_start();
    ob_start();
    include "dbconnect.php";

    $connection = new Connection();
    $pdo = $connection->CheckConnect();

    $sql = "select * from category";
    $sta = $pdo->prepare($sql);
    $sta->execute();
    $category = $sta->fetchAll(PDO::FETCH_OBJ);

    $sql = "select * from products where Discount > 0 and IsActive = 1 order by Discount desc limit 6";
    $sta = $pdo->prepare($sql);
    $sta->execute();
    $good_deal = $sta->fetchAll(PDO::FETCH_OBJ);

    $sql = "select * from products where IsActive = 1 order by Sold desc limit 6";
    $sta = $pdo->prepare($sql);
    $sta->execute();
    $best_seller = $sta->fetchAll(PDO::FETCH_OBJ);

?>

<div class="container-fluid" id="header">
    <div class="row">
        <div class="col-md-3 col-lg-3" id="category">
            <div style="background:#D67B22;color:#fff;font-weight:800;border:none;padding:15px;">Danh mục</div>
            <ul>
                <li>
                    <a href="product_page.php?value=0" style="padding-left: 10px">Tất cả sản phẩm</a>
                </li>
                <?php
                foreach ($category as $c) {
                    ?>
                        <li>
                            <a href="product_page.php?value=<?php echo $c->Category_ID; ?>" style="padding-left: 10px"><?php echo $c->Category_Name; ?></a>
                        </li>
                    <?php
                }
                ?>
            </ul>
        </div>
        <div class="col-md-6 col-lg-6">
            <div id="myCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
                <!-- Indicators -->
                <div class="carousel-indicators">
                    <span data-bs-target="#myCarousel" data-bs-slide-to="0" class="active"></span>
                    <span data-bs-target="#myCarousel" data-bs-slide-to="1"></span>
                    <span data-bs-target="#myCarousel" data-bs-slide-to="2"></span>
                    <span data-bs-target="#myCarousel" data-bs-slide-to="3"></span>
                    <span data-bs-target="#myCarousel" data-bs-slide-to="4"></span>
                    <span data-bs-target="#myCarousel" data-bs-slide-to="5"></span>
                </div>

                <!-- Wrapper for slides -->
                <div class="carousel-inner" role="listbox">
                    <div class="carousel-item active">
                        <img class="d-block" src="img/carousel/1.jpg">
                    </div>

                    <div class="carousel-item">
                        <img class="d-block " src="img/carousel/2.jpg">
                    </div>

                    <div class="carousel-item">
                        <img class="d-block" src="img/carousel/3.jpg">
                    </div>

                    <div class="carousel-item">
                        <img class="d-block" src="img/carousel/4.jpg">
                    </div>

                    <div class="carousel-item">
                        <img class="d-block" src="img/carousel/5.jpg">
                    </div>

                    <div class="carousel-item">
                        <img class="d-block" src="img/carousel/6.jpg">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-lg-3" id="offer">
            <img class="img-responsive center-block" src="img/offers/1.png">
            <img class="img-responsive center-block" src="img/offers/2.png">
            <img class="img-responsive center-block" src="img/offers/3.png">
        </div>
    </div>
</div>

<section class="Section_Product">
    <div class="title_module_main">
        <h2><a href="#"><img src="img/icons/sale.png">Khuyến mãi sốc</a></h2>
    </div>

    <div style="display: grid; grid-template-columns: repeat(6, 1fr); grid-gap: 20px;">
        <?php
            foreach ($good_deal as $all) {
                ?>
                    <div class="product_item" style="text-align: center; position: relative; margin-top: 20px; border: 1px #c6c6c6 solid;">
                        <div class="Product_thumnail">
                            <?php
                                if ($all->Discount != 0) {
                                    ?>
                                        <span class="label_sale">-<?php echo $all->Discount; ?>%</span>
                                    <?php
                                }
                            ?>
                            <a href="description.php?value=<?php echo $all->Product_ID?>"><img src="img/books/<?php echo $all->Image; ?>" alt="<?php echo $all->Title; ?>" style="max-height: 150px;"></a>
                        </div>
                        <div style="background-color: #f1f1f1;">
                            <div style="height: 64px; margin-bottom: 5px;" class="Product_name">
                            <a href="description.php?value=<?php echo $all->Product_ID; ?>"><?php echo $all->Title; ?></a>
                        </div>
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
                                if ($all->Discount != 0) {
                                    ?>
                                        <span class="Product_after_discount"><?php echo number_format($all->price_after_discount); ?> ₫</span>
                                        <span class="Product_full_price"><?php echo number_format($all->Price); ?> ₫</span>
                                    <?php
                                } else {
                                    ?>
                                        <span class="Product_after_discount"><?php echo number_format($all->price_after_discount); ?> ₫</span>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="product_buttons">
                                <?php
                                    if(isset($_SESSION['user']))
                                    {
                                        ?>
                                            <a class="add_to_cart_button" href="cart.php?value=<?php echo $all->Product_ID; ?>"><i class="fa-solid fa-cart-shopping"></i></a>
                                        <?php
                                    }
                                ?>
                                <a class="view_details_button" href="description.php?value=<?php echo $all->Product_ID; ?>"><i class="fa-regular fa-eye"></i></a>
                            </div>
                        </div>
                    </div>
                <?php
            }
        ?>
    </div>
</section>

<section class="Section_Product">
    <div class="title_module_main">
        <h2><a href="#"><img src="img/icons/social-media.png">Có thể bạn sẽ thích</a></h2>
    </div>

    <div style="display: grid; grid-template-columns: repeat(6, 1fr); grid-gap: 20px;">
        <?php
            foreach ($best_seller as $all) {
                ?>
                    <div class="product_item" style="text-align: center; position: relative; margin-top: 20px; border: 1px #c6c6c6 solid;">
                        <div class="Product_thumnail">
                            <?php
                                if ($all->Discount != 0) {
                                    ?>
                                        <span class="label_sale">-<?php echo $all->Discount; ?>%</span>
                                    <?php
                                }
                            ?>
                            <a href="description.php?value=<?php echo $all->Product_ID?>"><img src="img/books/<?php echo $all->Image; ?>" alt="<?php echo $all->Title; ?>" style="max-height: 150px;"></a>
                        </div>
                        <div style="background-color: #f1f1f1;">
                            <div style="height: 64px; margin-bottom: 5px;" class="Product_name">
                                <a href="description.php?value=<?php echo $all->Product_ID; ?>"><?php echo $all->Title; ?></a>
                            </div>
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
                                if ($all->Discount != 0) {
                                    ?>
                                        <span class="Product_after_discount"><?php echo number_format($all->price_after_discount); ?> ₫</span>
                                        <span class="Product_full_price"><?php echo number_format($all->Price); ?> ₫</span>
                                    <?php
                                } else {
                                    ?>
                                        <span class="Product_after_discount"><?php echo number_format($all->price_after_discount); ?> ₫</span>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="product_buttons">
                                <?php
                                    if(isset($_SESSION['user']))
                                    {
                                        ?>
                                            <a class="add_to_cart_button" href="cart.php?value=<?php echo $all->Product_ID; ?>"><i class="fa-solid fa-cart-shopping"></i></a>
                                        <?php
                                    }
                                ?>
                                <a class="view_details_button" href="description.php?value=<?php echo $all->Product_ID; ?>"><i class="fa-regular fa-eye"></i></a>
                            </div>
                        </div>
                    </div>
                <?php
            }
        ?>
    </div>
</section>

<?php
    $content = ob_get_clean();
    include("page_layout.php");
?>