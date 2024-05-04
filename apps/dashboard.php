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

<body class="leftpanel-collapsed">

    <!-- SECTION PAGE -->
    <section>
    
        <!-- MAINPANEL-->
        <div class="mainpanel" style="margin-left: 0;">

            <!-- HEADER -->
            <!-- HEADERBAR -->
            <div class="headerbar" style="margin-left: 0;">
                <div class="header-left">
                    <div class="logopanel-center push-left" style="width: 240px;">
                        <img src="./asset/images/logo.png" alt="" class="mr10" style="float: left;">
                        <h1 style="float: left;"><span>[</span> OpenLibrary <span>]</span></h1>
                    </div>
                </div>
                <div class="header-right">
                    <ul class="headermenu">
                        <li>
                            <a href="index.php" class="btn btn-default tp-icon">
                                <i class="fa fa-sign-in"></i>
                            </a>
                        </li>
                    </ul>
                </div>         
            </div>

            <!-- CONTENT -->
            <div class="contentpanel">
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-12" style="height: 600px; margin-bottom:10px;">
                                <iframe src="http://192.168.1.14/remote.php?id=1&auto=1&time=3&type=column&orientation=baris&hrow=1&hcol=0&grouping=1" frameborder="0" style="width: 100%; height: 100%;"></iframe>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12" style="height: 300px; margin-bottom: 10px;">
                                <iframe src="http://192.168.1.14/remote.php?id=2&auto=1&time=3&type=pie&orientation=baris&hrow=1&hcol=0&grouping=2" frameborder="0" style="width: 100%; height: 100%;"></iframe>
                            </div>
                            <div class="col-md-12" style="height: 290px">
                                <iframe src="http://192.168.1.14/remote.php?id=3&auto=1&time=3&type=column&orientation=baris&hrow=1&hcol=0&filter=3-33_3-32_3-31_3-30_3-29_3-23_3-24_3-20_3-28_3-18_3-0_3-3_3-4_3-5_3-6_3-8_3-9_3-17_3-19_3-21_3-22_3-25_3-27_3-14" frameborder="0" style="width: 100%; height: 100%;"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END OF CONTENT -->

        </div>
        <!-- END OF MAINPANEL -->

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
    <script src="asset/js/template/custom.js"></script>
    <!-- END OF JAVASCRIPT -->

</body>

</html>
