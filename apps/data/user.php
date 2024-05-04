<?php

//  check if user already login or not
//  if user not authorizes then back to login page

session_start();

// if (! isset($_SESSION['login']) || $_SESSION['login'] != 'loginOK') {
//     header('location:index.php');
//     exit();
// }


// rquired file
require_once('./model/class.db.php');

$db = new DB();
$db->set_default_connection();

// write you base url here
$base_url = 'http://localhost/reposys/';


// function to ger all report
function getReportList()
{
    global $db;
    $role = isset($_SESSION['prev']) ? $_SESSION['prev'] : 0;

    $sql = "SELECT * 
            FROM laporan l
            JOIN otoritas o on l.id_laporan = o.id_laporan
            WHERE id_previlage IN (2, $role)
            ORDER BY nama_laporan ASC";
    $reports = $db->select($sql);

    $report_list = [ 0 => 'PILIH LAPORAN'];
    if ($db->get_query_rows($sql) > 0) {
        while ($report = mysql_fetch_assoc($reports)) {
            $report_list[$report['id_laporan']] = strtoupper($report['nama_laporan']);
        }
    }

    return $report_list;
}


/*
| -------------------------------------------------------------------------
| CONTENT SETTING
| -------------------------------------------------------------------------
|
| Initiate Menu
| Set content that need to show base on menu
|
 */

$content = array();
$page_dir = './view/page/';
$js_dir = './asset/js/app/';
$jquery_dir = './asset/js/template/';

$content['title'] = 'Pengaturan Datasource';
$content['subtitle'] = 'Melakukan pengelolaan datasource';
$content['icon'] = 'fa fa-table';
$content['jquery'] = [
    $jquery_dir . 'select2.min.js',
    $jquery_dir . 'jquery.matchHeight-min.js',
    $jquery_dir . 'jquery.columnmanager.pack.js',
    $jquery_dir . 'jquery.scrollbar.min.js',
    $jquery_dir . 'highcharts.js',
    $jquery_dir . 'exporting.js',
];
$content['js'] = $js_dir . 'reportpage.js';

$report_list = getReportList();

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

<body class="leftpanel-collapsed">

    <!-- PRELOADER -->
    <div id="preloader">
        <div id="status"><i class="fa fa-spinner fa-spin"></i></div>
    </div>

    <div id="base_url" class="hidden"><?php echo $base_url; ?></div>

    <!-- MODAL -->
    <div class="modal fade" id="modal-message" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            </div>
        </div>
    </div>

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

                        <?php if (isset($_SESSION['status']) && $_SESSION['status'] == 'admin') : ?>
                            <li>
                                <a href="admin.php" class="btn btn-default tp-icon">
                                    <i class="fa fa-gear"></i>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['login']) && $_SESSION['login'] == 'loginOK') : ?>
                            <li>
                                <a href="./action/logout.php" class="btn btn-default tp-icon">
                                    <i class="glyphicon glyphicon-log-out"></i>
                                </a>
                            </li>
                        <?php else : ?>
                            <li>
                                <a href="index.php" class="btn btn-default tp-icon">
                                    <i class="glyphicon glyphicon-log-in"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                        
                    </ul>
                </div>
               
            </div>

            <!-- PAGEHEADER -->
            <div class="pageheader">
                <form action="">
                    <div class="row">
                        <div class="col-sm-12">
                            <select name="" id="report-list">

                                <?php foreach ($report_list as $key => $value) : ?>
                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                <?php endforeach; ?>
                                
                            </select>
                        </div>
                    </div>
                </form> 
            </div>
            <!-- END OF HEADER-->

            <!-- CONTENT -->
            <div class="contentpanel" style="padding: 20px 5px;">
               <div class="col-md-3 kontrol-laporan">
                    
                    <div class="row">

                        <!-- PANeL GRAFIK -->
                        <?php require_once('./view/partial/panel_report_graph.php'); ?>
                        <!-- END OF PANEL GRAFIK -->

                        <!-- PANeL FILTER -->
                         <?php require_once('./view/partial/panel_report_filter.php'); ?>
                        <!-- END OF PANEL FILTER -->

                        <!-- PANEL GROUPING -->
                         <?php require_once('./view/partial/panel_report_grouping.php'); ?>
                        <!-- END OF PANEL GROUPING -->

                    </div>

               </div>
               <div class="col-md-9">
                   
                    <!-- PANEL CHART -->
                    <div class="panel panel-default panel-alt widget-messaging" id="panel-chart-laporan">
                        <div class="panel-heading">
                            <div class="panel-btns">
                                <a href="" class="minimize">&minus;</a>
                            </div>
                            <h4 class="panel-title">Report on Graph</h4>
                        </div>

                        <div id="container-chart" class="panel-body" style="overflow: auto; padding:15px;"></div>
                    </div>
                    <!-- END OF PANEL CHART -->

                    <!-- PANEL LAPORAN -->
                    <div class="panel panel-default panel-alt widget-messaging" id="panel-tabel-laporan">
                        <div class="panel-heading">
                            <div class="panel-btns">
                                <a href="" class="minimize">&minus;</a>
                            </div>
                            <h4 class="panel-title">Laporan</h4>
                            <p></p>
                        </div>

                        <div class="panel-body" style="overflow: auto; padding:15px;"></div>
                    </div>
                    <!-- END OF PANEL LAPORAN -->

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

    <?php 
    if ( isset($content['jquery']) && is_array($content['jquery']) ) : 
        foreach ($content['jquery'] as $jq_script) :
    ?>  
            <script src="<?php echo $jq_script; ?>"></script>
    <?php
        endforeach;
    endif; 
    ?>

    <script src="asset/js/template/custom.js"></script>
    <script src="asset/js/app/admin.js"></script>
    <script src="<?php echo $content['js']; ?>"></script>
    <!-- END OF JAVASCRIPT -->

</body>

</html>
