<?php
session_start();
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

// Xử lý đăng nhập
if (isset($_POST['submit']) && $_POST['submit'] == "đăng nhập") {
    $username = $_POST['login_username'];
    $password = $_POST['login_password'];

    $query = "SELECT * FROM users WHERE UserName = :username AND Password = :password";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['username' => $username, 'password' => $password]);

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['user'] = $row['UserName'];
        echo '<script type="text/javascript">alert("Đăng nhập thành công!");</script>';
    } else {
        echo '<script type="text/javascript">alert("Sai tên đăng nhập hoặc mật khẩu!");</script>';
    }
}
// =======================================================================================================
// Load Ảnh Bổ Sung
$sql_additional_images = "SELECT a.*, p.Title FROM additional_images a inner join products p on a.Product_ID = p.Product_ID order by additional_images_ID DESC";
$sta = $pdo->prepare($sql_additional_images);
$sta->execute();
if ($sta->rowCount() > 0) {
    $additional_images = $sta->fetchAll(PDO::FETCH_OBJ);
}

//Load Product
$sql_pro = "SELECT * FROM products";
$sta1 = $pdo->prepare($sql_pro);
$sta1->execute();
if ($sta1->rowCount() > 0) {
    $product = $sta1->fetchAll(PDO::FETCH_OBJ);
}

// Xử lý thêm ảnh
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit_add_admin_additional_images'])) {

        $Product_ID = $_POST['Product_add'];

        // Xử lý upload hình ảnh
        $target_dir_add = "books/"; //foder chứa ảnh khi thêm
        $imageFileType_add = strtolower(pathinfo($_FILES["Image_add"]["name"], PATHINFO_EXTENSION));
        $original_filename_add = pathinfo($_FILES["Image_add"]["name"], PATHINFO_FILENAME);
        $new_filename_add = $original_filename_add . '.' . $imageFileType_add;
        $target_file_add = $target_dir_add . $new_filename_add;

        // Kiểm tra định dạng file
        $allowed_types_add = array("jpg", "png", "jpeg", "gif");
        if (!in_array($imageFileType_add, $allowed_types_add)) {
            echo "<script>alert('Chỉ chấp nhận các định dạng JPG, JPEG, PNG & GIF.');</script>";
            echo "<script>window.history.back();</script>";
            exit();
        }

        // Kiểm tra xem file có phải là hình ảnh thật hay không
        $check_add = getimagesize($_FILES["Image_add"]["tmp_name"]);
        if ($check_add === false) {
            echo "<script>alert('File không phải là hình ảnh.');</script>";
            echo "<script>window.history.back();</script>";
            exit();
        }

        // Lưu thông tin ảnh
        $sql_add = "INSERT INTO additional_images (Product_ID, Image) VALUES (:Product_ID, :Image)";
        $stmt_add = $pdo->prepare($sql_add);
        $params_add = [
            ':Product_ID' => $Product_ID,
            ':Image' => $new_filename_add
        ];

        if ($stmt_add->execute($params_add)) {
            // Di chuyển file đến thư mục books sau khi thêm vào cơ sở dữ liệu thành công
            if (move_uploaded_file($_FILES["Image_add"]["tmp_name"], $target_file_add)) {
                echo "<script>alert('Thêm ảnh thành công');</script>";
                echo "<script>window.location.href = 'admin_additional_images.php';</script>";
            } else {
                // Xóa bản ghi vừa thêm nếu không thể di chuyển file ảnh
                $last_id_add = $pdo->lastInsertId();
                $sql_delete_add = "DELETE FROM additional_images WHERE additional_images_ID = :id";
                $stmt_delete_add = $pdo->prepare($sql_delete_add);
                $stmt_delete_add->execute([':id' => $last_id_add]);

                echo "<script>alert('Có lỗi xảy ra khi tải file. Ảnh đã bị xóa.');</script>";
                echo "<script>window.history.back();</script>";
            }
        } else {
            echo "<script>alert('Lỗi: Không thể thêm ảnh');</script>";
            echo "<script>window.history.back();</script>";
        }
    }
}


// Xử lý xóa ảnh
if (isset($_GET['delete_image_id'])) {
    $delete_id = $_GET['delete_image_id'];
    $sql_delete_image = "DELETE FROM additional_images WHERE additional_images_ID = :id";
    $stmt_delete_image = $pdo->prepare($sql_delete_image);
    if ($stmt_delete_image->execute([':id' => $delete_id])) {
        echo "<script>alert('Xóa ảnh thành công');</script>";
        echo "<script>window.history.back();</script>";
    } else {
        echo "<script>alert('Xóa ảnh không thành công');</script>";
    }
}

//Xử lý sửa ảnh

//Check additional_images_ID
if (isset($_GET['update_image_id'])) {
    $id_ud = $_GET['update_image_id'];
    $sql_check_id = "SELECT * FROM additional_images WHERE additional_images_ID = :id_ud";
    $stmt_check_id = $pdo->prepare($sql_check_id);
    $stmt_check_id->execute([':id_ud' => $id_ud]);
    $additional_images_ud = $stmt_check_id->fetch(PDO::FETCH_OBJ);

    // Trả về dữ liệu dưới dạng JSON
    header('Content-Type: application/json');
    echo json_encode($additional_images_ud);
    exit;
}
// Xử lý cập nhật ảnh
if (isset($_POST['submit_update_admin_additional_images'])) {

    $additional_images_ID = $_POST['additional_ID'];
    $Product_ID = $_POST['Product_up'];

    $stmt_update = null;
    $params_update = [];

    // Xử lý upload hình ảnh nếu có
    if (!empty($_FILES["Image_ud"]["name"])) 
    {
        $target_dir_update = "books/";
        $imageFileType_update = strtolower(pathinfo($_FILES["Image_ud"]["name"], PATHINFO_EXTENSION));
        $original_filename_update = pathinfo($_FILES["Image_ud"]["name"], PATHINFO_FILENAME);
        $new_filename_update = $original_filename_update . '.' . $imageFileType_update;
        $target_file_update = $target_dir_update . $new_filename_update;

        // Kiểm tra định dạng file
        $allowed_types_update = array("jpg", "png", "jpeg", "gif");
        if (!in_array($imageFileType_update, $allowed_types_update)) {
            echo "<script>alert('Chỉ chấp nhận các định dạng JPG, JPEG, PNG & GIF.');</script>";
            echo "<script>window.history.back();</script>";
            exit();
        }

        // Kiểm tra xem file có phải là hình ảnh thật hay không
        $check_update = getimagesize($_FILES["Image_ud"]["tmp_name"]);
        if ($check_update === false) {
            echo "<script>alert('File không phải là hình ảnh.');</script>";
            echo "<script>window.history.back();</script>";
            exit();
        }

        // Di chuyển file đến thư mục books
        if (!move_uploaded_file($_FILES["Image_ud"]["tmp_name"], $target_file_update)) {
            echo "<script>alert('Có lỗi xảy ra khi tải file.');</script>";
            echo "<script>window.history.back();</script>";
            exit();
        }

        // Cập nhật thông tin ảnh với hình ảnh mới
        $sql_update = "UPDATE additional_images SET 
            Product_ID = :Product_ID, 
            Image = :Image
            WHERE additional_images_ID  = :additional_images_ID";

        $stmt_update = $pdo->prepare($sql_update);
        $params_update = [
            ':Product_ID' => $Product_ID,
            ':Image' => $new_filename_update,
            ':additional_images_ID' => $additional_images_ID
        ];
    }

    if ($stmt_update && $stmt_update->execute($params_update)) {
        echo "<script>alert('Cập nhật ảnh thành công');</script>";
        echo "<script>window.location.href = 'admin_additional_images.php';</script>"; // Điều hướng đến trang admin
    } else {
        echo "<script>alert('Lỗi: Không thể cập nhật ảnh');</script>";
        echo "<script>window.history.back();</script>";
    }
}


?>
<script>
    function confirmImageDelete(id) {
        if (confirm("Bạn có chắc chắn muốn xóa ảnh này không?")) {
            window.location.href = 'admin_additional_images.php?delete_image_id=' + id;
        }
    }

    function confirmImageUpdate() {
        return confirm("Bạn có chắc chắn muốn sửa ảnh này?");
    }

    function openUpdateImageModal(additional_images_ID) {
        // Gửi yêu cầu AJAX để lấy dữ liệu image
        fetch('admin_additional_images.php?update_image_id=' + additional_images_ID)
            .then(response => response.json())
            .then(data => {
                // Điền dữ liệu vào form trong modal
                document.getElementById('additional_ID').value = data.additional_images_ID;

                // Hiển thị modal
                $('#updateImageModal').modal('show');
            })
            .catch(error => console.error('Error:', error));
    }
</script>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Books">
    <meta name="author" content="Shivangi Gupta">
    <title>Cửa hàng Sách Online</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/my.css" rel="stylesheet">
    <style>
        .modal-header {
            background: #D67B22;
            color: #fff;
            font-weight: 800;
        }

        .modal-body {
            font-weight: 800;
        }

        .modal-body ul {
            list-style: none;
        }

        .modal .btn {
            background: #D67B22;
            color: #fff;
        }

        .modal a {
            color: #D67B22;
        }

        .modal-backdrop {
            position: inherit !important;
        }

        #login_button,
        #register_button {
            background: none;
            color: #D67B22 !important;
        }

        #query_button {
            position: fixed;
            right: 0px;
            bottom: 0px;
            padding: 10px 80px;
            background-color: #D67B22;
            color: #fff;
            border-color: #f05f40;
            border-radius: 2px;
        }

        .tbody {
            color: #000000;
        }

        @media(max-width:767px) {
            #query_button {
                padding: 5px 20px;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-default navbar-fixed-top navbar-inverse">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#" style="padding: 1px;"><img class="img-responsive" alt="Brand" src="img/logo.jpg" style="width: 147px;margin: 0px;"></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <?php
                    if (!isset($_SESSION['user'])) {
                        echo '
            <li>
                <button type="button" id="login_button" class="btn btn-lg" data-toggle="modal" data-target="#login">Đăng nhập ADMIN</button>
                  <div id="login" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title text-center">Đăng nhập ADMIN</h4>
                            </div>
                            <div class="modal-body">
                                          <form class="form" role="form" method="post" action="admin_additional_images.php" accept-charset="UTF-8">
                                              <div class="form-group">
                                                  <label class="sr-only" for="username">Tên đăng nhập</label>
                                                  <input type="text" name="login_username" class="form-control" placeholder="Tên đăng nhập" required>
                                              </div>
                                              <div class="form-group">
                                                  <label class="sr-only" for="password">Mật khẩu</label>
                                                  <input type="password" name="login_password" class="form-control"  placeholder="Mật khẩu" required>
                                              </div>
                                              <div class="form-group">
                                                  <button type="submit" name="submit" value="đăng nhập" class="btn btn-block">
                                                      Đăng nhập
                                                  </button>
                                              </div>
                                          </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                            </div>
                        </div>
                    </div>
                  </div>
            </li>';
                    } else {
                        echo ' <li> <a href="#" class="btn btn-lg"> Xin chào ' . $_SESSION['user'] . '</a></li>
                    <li> <a href="destroy_admin.php" class="btn btn-lg"> Đăng xuất </a> </li>';
                    }
                    ?>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
    <div>
        <h2 class="modal-title text-center">ADMIN</h2>
        <!-- Add Image dialog -->
        <div class="modal fade" id="addImageModal" tabindex="-1" role="dialog" aria-labelledby="addBookModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addBookModalLabel">Thêm ảnh mới</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Form Add Image -->
                        <form id="" action="admin_additional_images.php" method="post" enctype="multipart/form-data">
                            <ul>
                                <li style="margin-top: 10px;">
                                    <label for="Product_add">Sản phẩm:</label>
                                    <select class="form-select" id="Product_add" name="Product_add">
                                        <?php foreach ($product as $p) { ?>
                                            <option value="<?php echo $p->Product_ID ?>">
                                                <?php echo $p->Title ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </li>
                                <li>
                                    <label for="Image_add">Ảnh:</label>
                                    <input type="file" class="form-control" id="Image_add" name="Image_add" required>
                                </li>
                                <li style="margin-top: 10px;">
                                    <button type="submit" name="submit_add_admin_additional_images" class="btn btn-primary">Thêm ảnh</button>
                                </li>
                            </ul>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Image dialog -->
        <div class="modal fade" id="updateImageModal" tabindex="-1" role="dialog" aria-labelledby="updateBookModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateBookModalLabel">Cập nhật ảnh</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Form Update Image -->
                        <form id="" action="admin_additional_images.php" method="post" enctype="multipart/form-data" onsubmit="return confirmImageUpdate()">
                            <input type="hidden" name="additional_ID" id="additional_ID">
                            <ul>
                                <li style="margin-top: 10px;">
                                    <label for="Product_up">Sản phẩm:</label>
                                    <select class="form-select" id="Product_up" name="Product_up">
                                        <?php foreach ($product as $p) { ?>
                                            <option value="<?php echo $p->Product_ID ?>">
                                                <?php echo $p->Title ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </li>
                                <li>
                                    <label for="Image_ud">Ảnh:</label>
                                    <input type="file" class="form-control" id="Image_ud" name="Image_ud" required>
                                </li>
                                <li style="margin-top: 10px;">
                                    <button type="submit" name="submit_update_admin_additional_images" class="btn btn-primary">Sửa ảnh</button>
                                </li>
                            </ul>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    // Kiểm tra xem người dùng đã đăng nhập hay chưa
    if (isset($_SESSION['user'])) {
        // Nếu đã đăng nhập, hiển thị bảng sách
    ?>
        <div class="container">
            <td>
                <a href="admin.php" class="btn btn-info">Quay lại</a>
                <?php if (isset($_SESSION['user'])) : ?>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addImageModal">
                        Thêm hình ảnh
                    </button>
                <?php endif; ?>
            </td>
            <h2 class="text-center" style="color: #eb9316;">Danh Sách Ảnh Bổ Sung</h2>
            <div class="table-responsive">
                <table class="table table-striped" style="background-color: #eb9316;color: white">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Sách</th>
                            <th>Ảnh</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="tbody">
                        <!-- PHP code to fetch and display books from the database -->
                        <?php
                        if (isset($additional_images) && is_array($additional_images)) {
                            $stt = 1;
                            foreach ($additional_images as $a) { ?>
                                <tr>
                                    <td><?php echo $stt ?></td>
                                    <td><?php echo $a->Title ?></td>
                                    <td><img src="books/<?php echo $a->Image ?>" alt="Ảnh sách" style="width:50px;height:auto;"></td>
                                    <td class="row">

                                        <?php if (isset($_SESSION['user'])) : ?>
                                            <button type="button" class="btn btn-danger" style="width: 80px;" onclick="confirmImageDelete(<?php echo $a->additional_images_ID; ?>)">
                                                Xóa
                                            </button>
                                        <?php endif; ?>

                                        <?php if (isset($_SESSION['user'])) : ?>
                                            <button type="button" class="btn btn-primary" style="width: 80px;" onclick="openUpdateImageModal(<?php echo $a->additional_images_ID; ?>)" data-toggle="modal" data-target="#updateImageModal">
                                                Sửa ảnh
                                            </button>
                                        <?php endif; ?>

                                    </td>
                                </tr>
                        <?php
                                $stt++;
                            }
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php
    } // Kết thúc điều kiện kiểm tra đăng nhập
    ?>

    <div class="container">
        <!-- Trigger the modal with a button -->
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>
    </div>
</body>

</html>