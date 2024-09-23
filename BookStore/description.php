<?php
  session_start();
  ob_start();

  include "dbconnect.php";
  $connection = new Connection();
  $pdo = $connection->CheckConnect();

  $Product = $_GET['value'];
  $customer = isset($_SESSION['user']) ?  $_SESSION['user'] : null;

  $query = "SELECT * FROM products WHERE Product_ID=:product";
  $stmt = $pdo->prepare($query);
  $stmt->bindParam(':product', $Product);
  $stmt->execute();
  $product = $stmt->fetch(PDO::FETCH_OBJ);

  $query = "SELECT * FROM additional_images WHERE Product_ID=:product";
  $stmt = $pdo->prepare($query);
  $stmt->bindParam(':product', $Product);
  $stmt->execute();
  $imgs = $stmt->fetchAll(PDO::FETCH_OBJ);

  include("page_navigation.php");
  $limit = 5;
  $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

  $query = "SELECT COUNT(*) AS total_rows FROM reviews WHERE Product_ID=:product AND LENGTH(Comment) > 0";
  $stmt = $pdo->prepare($query);
  $stmt->bindParam(':product', $Product);
  $stmt->execute();

  $total_rows = $stmt->fetch(PDO::FETCH_ASSOC)['total_rows'];

  $p = new Pager();
  $pages = $p->findPages($total_rows, $limit);
  $vt = $p->findStart($limit);

  $query = "SELECT * FROM reviews WHERE Product_ID=:product AND LENGTH(Comment) > 0 ORDER BY Date DESC LIMIT $vt, $limit";
  $stmt = $pdo->prepare($query);
  $stmt->bindParam(':product', $Product);
  $stmt->execute();
  $reviews = $stmt->fetchAll(PDO::FETCH_OBJ);

  if(isset($_GET["submit"]))
  {
    $star = isset($_GET["rating"]) ? $_GET["rating"] : 0;
    $comment = $_GET["comment"];
    $date = date("Y/m/d");

    $query = "INSERT INTO reviews VALUES (NUll, :customer, :product, :star, :comment, :date)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':customer', $customer);
    $stmt->bindParam(':product', $Product);
    $stmt->bindParam(':star', $star);
    $stmt->bindParam(':comment', $comment);
    $stmt->bindParam(':date', $date);
    $stmt->execute();
  }
?>

<div class="container-fluid" style="margin: 20px 0 20px 0;">
  <div style="margin-bottom: 20px;">
    <a id="Return" href="javascript:history.go(-1)"><i class="fa-solid fa-arrow-left-long" style="margin-right: 5px"></i><b style="font-size: 20px">Quay lại</b></a>
  </div>
  <div class="row">
    <div class="col-4">
      <div class="main-image" style="margin-left: 20px">
        <img src="img/books/<?php echo $product->Image; ?>" alt="Ảnh sản phẩm" id="mainImg" height="400xp">
      </div>
      <div class="sub-images" style="margin-top: 10px">
        <?php
          if($imgs)
          {
            ?>
              <img src="img/books/<?php echo $product->Image; ?>" alt="Ảnh sản phẩm" class="sub-image" onclick="changeImage(this)" height="150px">
            <?php
          }
        ?>
        <?php
          foreach($imgs as $i)
          {
            ?>
              <img src="img/books/<?php echo $i->Image; ?>" alt="Ảnh sản phẩm" class="sub-image" onclick="changeImage(this)" height="150px">
            <?php
          }
        ?>
      </div>
    </div>

    <div class="col-8" style="background-color: #f1f1f1;">
      <div style="margin-left: 10px;">
        <h3 style="margin-top: 5px;"><b><?php echo $product->Title; ?></b>
          <?php
            if($product->Discount != 0)
            {
              ?>
                <span class="Discount_badge" style="position: relative; display: inline-block;">-<?php echo $product->Discount; ?>%</span>
              <?php
            }
          ?>
        </h3>
        <p style="font-weight: bold; color: #707070;">Mã sản phẩm: <?php echo $product->Product_ID; ?></p>
        <div>
        <?php
            $star_rating = $product->Stars; // Điểm số đánh giá
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
          <span style="margin-left: 10px; color: #767676">(Đã bán: <?php echo $product->Sold; ?>)</span>
          <div style="margin-top: 30px; margin-bottom: 30px;">
            <?php
              if($product->Discount != 0)
              {
                ?>
                  <span class="Detail_price"><?php echo number_format($product->price_after_discount); ?> ₫</span>
                  <span class="Product_full_price" style="margin-left: 20px;"><?php echo number_format($product->Price); ?> ₫</span>
                <?php
              }
              else
              {
                ?>
                  <span class="Detail_price"><?php echo number_format($product->price_after_discount); ?> ₫</span>
                <?php
              }
            ?>
          </div>
          <div>
            <p><b>Mô tả:</b> <?php echo $product->Description; ?></p>
            <div class="d-flex justify-content-between">
              <p><b>Ngôn ngữ:</b> <?php echo $product->Language; ?></p>
              <p><b>Tác giả:</b> <?php echo $product->Author; ?></p>
              <p><b>Nhà xuất bản:</b> <?php echo $product->Publisher; ?></p>
            </div>
          </div>
          <?php
            if(isset($_SESSION['user']))
            {
              ?>
                <div style="width: 350px; margin-top: 50px;">
                  <form method="GET" action="cart.php" class="d-flex">
                    <input type="hidden" name="value" value="<?php echo $product->Product_ID; ?>">
                    <div class="input-group mb-3" style="width: 150px;">
                      <button class="btn btn-outline-secondary" type="button" id="subtractQty">-</button>
                      <input type="number" id="quantity" name="quantity" class="form-control text-center" value="1" min="1">
                      <button class="btn btn-outline-secondary" type="button" id="addQty">+</button>
                    </div>
                    <button type="submit" class="btn btn-success ms-auto" style="height: 40px;">
                      <i class="fa fa-shopping-cart" style="padding-right: 10px;"></i>Thêm vào giỏ hàng
                    </button>
                  </form>
                </div>
              <?php
            }
          ?>
        </div>
      </div>
    </div>
  </div>

  <div style="margin-top: 100px;">
    <div class="col-7">
      <div class="product-rating-comment">
        <h4>Đánh giá sản phẩm</h4>
        <?php
          if(isset($_SESSION['user']))
          {
            ?>
              <form method="get" action="description.php" id="ratingForm">
                <input type="hidden" name="value" value="<?php echo $Product; ?>">
                <div class="mb-3 d-flex">
                  <label for="rating" style="margin-top: 12px; margin-right: 5px;">Đánh giá:</label>
                  <div class="rating">
                    <input type="radio" id="r1" name="rating" value="5">
                    <label for="r1"></label>
                    <input type="radio" id="r2" name="rating" value="4">
                    <label for="r2"></label>
                    <input type="radio" id="r3" name="rating" value="3">
                    <label for="r3"></label>
                    <input type="radio" id="r4" name="rating" value="2">
                    <label for="r4"></label>
                    <input type="radio" id="r5" name="rating" value="1">
                    <label for="r5"></label>
                  </div>
                </div>
                <div class="mb-3 d-flex" style="margin-top: 40px;">
                  <div style="width: 150px; word-wrap: break-word; text-align: center; margin-right: 10px;">
                    <img src="img/icons/user.png" alt="Ảnh đại diện" height="40px">
                    <p><?php echo $customer ?></p>
                  </div>
                  <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
                </div>
                <div class="d-flex">
                  <button type="submit" class="btn btn-primary ms-auto" name="submit" value="submit">Gửi</button>
                </div>
              </form>
              <div id="commentList">
                <?php
                  if (!empty($reviews))
                  {
                    foreach($reviews as $r)
                    {
                      ?>
                        <div class="mb-3 d-flex" style="margin-top: 50px;">
                          <div style="width: 150px; word-wrap: break-word; text-align: center; margin-right: 10px;">
                            <img src="img/icons/user.png" alt="Ảnh đại diện" height="40px">
                            <p><?php echo $r->Username; ?></p>
                          </div>
                          <textarea class="form-control" id="comment" name="comment" rows="3" readonly><?php echo $r->Comment; ?></textarea>
                        </div>
                      <?php
                    }
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
            <?php
          }
          else
          {
            ?>
              <div class="text-center m-5">
                <h5>Vui lòng đăng nhập để đánh giá sản phẩm</h5>
              </div>
              <div id="commentList">
                <?php
                  if (!empty($reviews))
                  {
                    foreach($reviews as $r)
                    {
                      ?>
                        <div class="mb-3 d-flex" style="margin-top: 50px;">
                          <div style="width: 150px; word-wrap: break-word; text-align: center; margin-right: 10px;">
                            <img src="img/icons/user.png" alt="Ảnh đại diện" height="40px">
                            <p><?php echo $r->Username; ?></p>
                          </div>
                          <textarea class="form-control" id="comment" name="comment" rows="3" readonly><?php echo $r->Comment; ?></textarea>
                        </div>
                      <?php
                    }
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
            <?php
          }
        ?>
      </div>
    </div>
  </div>
</div>

<?php
    $content = ob_get_clean();
    include ("page_layout.php");
?>