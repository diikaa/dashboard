<?php

// get report id
$id = isset($_GET['id']) ? $_GET['id'] : 0;
$type = isset($_GET['type']) ? $_GET['type'] : 'table';
$orientation = isset($_GET['orientation']) ? $_GET['orientation'] : 'baris';
$hrow = isset($_GET['hrow']) ? $_GET['hrow'] : 1;
$hcol = isset($_GET['hcol']) ? $_GET['hcol'] : 0;
$auto = isset($_GET['auto']) ? $_GET['auto'] : 0;
$autorate = isset($_GET['time']) ? $_GET['time'] : 1;
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$grouping = isset($_GET['grouping']) ? $_GET['grouping'] : '';

?>

<!DOCTYPE html>
<html lang="in">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="asset/images/logo.png" type="image/png">

    <title>Open Library - Report Page</title>

    <link href="asset/css/style.default.css" rel="stylesheet">
    <link href="asset/css/style.dodgerblue.css" rel="stylesheet">
    <link href="asset/css/jquery.scrollbar.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
</head>

<body style="background-color: #FFF;">
    
    <!-- PRELOADER -->
    <div id="preloader">
        <div id="status"><i class="fa fa-spinner fa-spin"></i></div>
    </div>

    <section>
        
        <input type="hidden" id="id-laporan" value="<?php echo $id; ?>">
        <input type="hidden" id="tipe-chart" value="<?php echo $type; ?>">
        <input type="hidden" id="orientasi-chart" value="<?php echo $orientation; ?>">
        <input type="hidden" id="header-row" value="<?php echo $hrow; ?>">
        <input type="hidden" id="header-col" value="<?php echo $hcol; ?>">
        <input type="hidden" id="auto-refresh" value="<?php echo $auto; ?>">
        <input type="hidden" id="auto-rate" value="<?php echo $autorate; ?>">
        <input type="hidden" id="filter" value="<?php echo $filter; ?>">
        <input type="hidden" id="grouping" value="<?php echo $grouping; ?>">

        <div class="row" style="width: 95%; margin: 10px auto 0;">
            <div class="col-sm-12" id="panel-laporan"></div>
            <div class="col-sm-12" id="panel-chart"></div>
        </div>
        
    </section>

    <!-- JAVASCRIPT -->
    <script src="asset/js/template/jquery-1.11.1.min.js"></script>
    <script src="asset/js/template/jquery-migrate-1.2.1.min.js"></script>
    <script src="asset/js/template/bootstrap.min.js"></script>
    <script src="asset/js/template/modernizr.min.js"></script>
    <script src="asset/js/template/jquery.sparkline.min.js"></script>
    <script src="asset/js/template/toggles.min.js"></script>
    <script src="asset/js/template/retina.min.js"></script>
    <script src="asset/js/template/jquery.cookies.js"></script>
    <script src="asset/js/template/jquery.columnmanager.pack.js"></script>
    <script src="asset/js/template/highcharts.js"></script>
    <script src="asset/js/template/custom.js"></script>
    <script src="asset/js/app/remote.js"></script>

</body>