<?php

session_start();

if (isset($_SESSION['status']) && $_SESSION['status'] == 'admin') {
  header('location:admin.php');
  exit();
}

if (isset($_SESSION['login']) && $_SESSION['login'] == 'loginOK') {
  header('location:user.php');
  exit();
}

$error = isset($_GET['error']) ? $_GET['error'] : '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="asset/images/Telkom_Indonesia.png" type="image/png">

  <title>TReg3:Dashboard</title>

  <link href="asset/css/style.default.css" rel="stylesheet">
  <link href="asset/css/style.dodgerblue.css" rel="stylesheet">

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="js/html5shiv.js"></script>
  <script src="js/respond.min.js"></script>
  <![endif]-->
</head>

<body class="signin">


<section>
  
    <div class="signinpanel">
        
        <div class="row">
            
            <div class="col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6">
                
                <div class="signup-info">
                    <div class="logopanel">
                        <img src="./asset/images/Telkom_Indonesia.png" alt="Logo Telkom Indonesia">
                        <h1 class="mt10"><span>[</span> Node-B Dashboard <span>]</span></h1>
                    </div><!-- logopanel -->
                </div>
                
                <form class="form-horizontal form-bordered" method="POST" action="./action/login.php">
                    <p class="mt5 mb20">Please login using your credential.</p>
                
                    <div class="alert alert-danger fade in hidden">
                        <button aria-hidden="true" data-hide="alert" type="button" class="close">&times;</button>
                        <h4>Ups, Login gagal</h4>
                        <p>Kombinasi Username dan/atau Password tidak ditemukan di databse</p>
                    </div>

                    <input type="hidden" name="e_error" value="<?php echo $error; ?>">

                    <div class="input-group mb10">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input type="text" class="form-control" placeholder="Username" name="e_user" />
                    </div>

                    <div class="input-group mb10">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input type="password" class="form-control" placeholder="Password" name="e_pass" />
                    </div>

                    <div class="btn-group btn-group-justified">
                      <div class="btn-group" role="group">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-sign-in mr5"></i>Sign In</button>
                      </div>
                       <a href="./user.php" class="btn btn-default" role="button"><i class="fa fa-th-large mr5"></i>Dashboard</a>
                    </div>
                    
                </form>
            
            </div><!-- row -->
            
            <div class="col-md-offset-4 col-md-4 col-sm-offset-3 col-sm-6 signup-footer text-center">
                    &copy; 2015. All Rights Reserved. Management Dashboard <br>
                    Designed By: <span class="text-primary"><a href="https://nyomankarna.id">Nyoman Karna</href></span>
            </div>
        </div>
        
    </div><!-- signin -->
  
</section>


<script src="asset/js/template/jquery-1.11.1.min.js"></script>
<script src="asset/js/template/jquery-migrate-1.2.1.min.js"></script>
<script src="asset/js/template/bootstrap.min.js"></script>
<script src="asset/js/template/modernizr.min.js"></script>
<script src="asset/js/template/jquery.sparkline.min.js"></script>
<script src="asset/js/template/jquery.cookies.js"></script>
<script src="asset/js/template/toggles.min.js"></script>
<script src="asset/js/template/retina.min.js"></script>
<script src="asset/js/template/custom.js"></script>

<script>
    jQuery(document).ready(function(){
        var error = $('input[name=e_error]').val();

        if (error == 'invalid') {
           $('div.alert').removeClass('hidden');
        }
    });
</script>

</body>
</html>
