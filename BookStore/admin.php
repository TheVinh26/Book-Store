<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Admin</title>
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/my.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center" style="margin-top: 200px">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h2 class="text-center">Đăng nhập</h2>
                    </div>
                    <div class="card-body">
                        <form action="login.php" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Tên đăng nhập</label>
                                <input class="form-control" type="text" name="login_username" id="username">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu</label>
                                <input class="form-control" type="password" name="login_password" id="password">
                            </div>
                            <button type="submit" class="btn btn-primary" name="submit" value="login_admin">Đăng nhập</button>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>