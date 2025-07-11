<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SILINEN | Log in</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="assets/dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="assets/plugins/iCheck/square/blue.css">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        
        <?php 
            if(isset($_GET['pesan'])){
                if($_GET['pesan'] == "gagal"){
                    echo '<div class="alert alert-warning alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Peringatan!</strong> Username atau password anda salah, silahkan periksa kembali username atau password anda!
                        </div>';
                }else if($_GET['pesan'] == "logout"){
                    echo '<div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Logout Berhasil!</strong> Terimakasih sudah menggunakan aplikasi SILINEN!
                        </div>';
                }else if($_GET['pesan'] == "belum_login"){
                    echo '<div class="alert alert-warning alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Peringatan!</strong> Silahkan melakukan login terlebih dahulu sebelum menggunakan aplikasi!
                        </div>';
                }
            }
        ?>
        <div class="login-logo">
            <a href="index.php"><b>SILINEN</b>Login</a>
        </div>
        <div class="login-box-body">
            <p class="login-box-msg">Silahkan melakukan Login terlebih dahulu</p>
            <form action="proses-login.php" method="post">
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" placeholder="Username" name="username">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="Password" name="password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <button type="submit" name="submit" class="btn btn-primary btn-block btn-flat" onclick="loginBtn(this);">Sign In</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <script src="assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script>
        function loginBtn(d){
            d.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
        }
    </script>
</body>

</html>