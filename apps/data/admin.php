<?php

//  check if user already login or not
//  if user not authorizes then back to login page

session_start();

if (! isset($_SESSION['login']) || $_SESSION['login'] != 'loginOK') {
    header('location:index.php');
    exit();
}

if (! isset($_SESSION['status']) || $_SESSION['status'] != 'admin') {
    header('location:user.php');
    exit();
}


/*
| -------------------------------------------------------------------------
| MENU AND CONTENT SETTING
| -------------------------------------------------------------------------
|
| Initiate Menu
| Set content that need to show base on menu
|
 */

$menu = isset($_GET['menu']) ? $_GET['menu'] : '0';

$content = array();
$page_dir = './view/page/';
$js_dir = './asset/js/app/';
$jquery_dir = './asset/js/template/';

switch ($menu) {
    case '1':
        // list data source
        $content['title'] = 'Pengaturan Datasource';
        $content['subtitle'] = 'Melakukan pengelolaan datasource';
        $content['icon'] = 'fa fa-table';
        $content['page'] = $page_dir . 'list.datasource.php';
        $content['breadcrumb'] = '<li><a href="user.php">OpenLibrary</a></li><li class="active">Datasource</li>';
        $content['jquery'] = [
            $jquery_dir . 'jquery.datatables.min.js',
            $jquery_dir . 'select2.min.js'
        ];
        $content['js'] = $js_dir . 'datasource.js';
        break;

    case '2' :
        // edit data source
        $id = isset($_GET['id']) ? $_GET['id'] : NULL;
        $content['title'] = 'Edit Datasource';
        $content['subtitle'] = 'Masukan data server dan query datasource';
        $content['icon'] = 'fa fa-pencil';
        $content['page'] = $page_dir . 'edit.datasource.php';
        $content['breadcrumb'] = '<li><a href="user.php">OpenLibrary</a></li><li><a href="admin.php?menu=1">Datasource</a></li><li class="active">Edit Datasource</li>';
         $content['jquery'] = [
            $jquery_dir . 'jquery.autogrow-textarea.js'
        ];
        $content['js'] = $js_dir . 'editdatasource.js';
        break;

    case '3':
        // list user
        $content['title'] = 'Pengaturan User';
        $content['subtitle'] = 'Melakukan pengelolaan user';
        $content['icon'] = 'fa fa-user';
        $content['page'] = $page_dir . 'list.user.php';
        $content['breadcrumb'] = '<li><a href="user.php">OpenLibrary</a></li><li class="active">User</li>';
        $content['jquery'] = [
            $jquery_dir . 'jquery.datatables.min.js',
            $jquery_dir . 'select2.min.js'
        ];
        $content['js'] = $js_dir . 'user.js';
        break;

    case '4':
        // new user
        $content['title'] = 'Tambah User';
        $content['subtitle'] = 'Melakukan penambahan user';
        $content['icon'] = 'fa fa-user';
        $content['page'] = $page_dir . 'add.user.php';
        $content['breadcrumb'] = '<li><a href="user.php">OpenLibrary</a></li><li><a href="admin.php?menu=3">User</a></li><li class="active">Tambah User</li>';
        $content['jquery'] = [];
        $content['js'] = $js_dir . 'adduser.js';
        break;

    case '5':
        // edit user
        $content['title'] = 'Edit User';
        $content['subtitle'] = 'Merubah data user';
        $content['icon'] = 'fa fa-user';
        $content['page'] = $page_dir . 'edit.user.php';
        $content['breadcrumb'] = '<li><a href="user.php">OpenLibrary</a></li><li><a href="admin.php?menu=3">User</a></li><li class="active">Edit User</li>';
        $content['jquery'] = [];
        $content['js'] = $js_dir . 'edituser.js';
        break;

    case '6':
        // list role
        $content['title'] = 'Pengaturan Hak Akses';
        $content['subtitle'] = 'Melakukan pengelolaan hak akses';
        $content['icon'] = 'fa fa-lock';
        $content['page'] = $page_dir . 'list.role.php';
        $content['breadcrumb'] = '<li><a href="user.php">OpenLibrary</a></li><li class="active">Hak Akses</li>';
        $content['jquery'] = [
            $jquery_dir . 'jquery.datatables.min.js',
            $jquery_dir . 'select2.min.js'
        ];
        $content['js'] = $js_dir . 'role.js';
        break;

     case '7':
        // new report
        $content['title'] = 'Tambah Laporan Baru';
        $content['subtitle'] = 'Menambahkan laporan ke dalam sistem';
        $content['icon'] = 'fa fa-file';
        $content['page'] = $page_dir . 'add.report.php';
        $content['breadcrumb'] = '<li><a href="user.php">OpenLibrary</a></li><li><a href="admin.php?menu=10">Laporan</a></li><li class="active">Tambah Laporan</li>';
        $content['jquery'] = [];
        $content['js'] = $js_dir . 'addreport.js';
        break;

    case '8':
        // edit report
        $content['title'] = 'Pengaturan Laporan';
        $content['subtitle'] = 'Mengatur konten laporan';
        $content['icon'] = 'fa fa-file';
        $content['page'] = $page_dir . 'edit.report.php';
        $content['breadcrumb'] = '<li><a href="user.php">OpenLibrary</a></li><li><a href="admin.php?menu=10">Laporan</a></li><li class="active">Edit Laporan</li>';
        $content['jquery'] = [
             $jquery_dir . 'bootstrap-wizard.min.js'
        ];
        $content['js'] = $js_dir . 'editreport.js';
        break;

    case '9':
        // list permission
        $content['title'] = 'Pengaturan Hak Akses';
        $content['subtitle'] = 'Melakukan pengelolaan hak akses';
        $content['icon'] = 'fa fa-lock';
        $content['page'] = $page_dir . 'list.role.report.php';
        $content['breadcrumb'] = '<li><a href="user.php">OpenLibrary</a></li><li><a href="admin.php?menu=6">Hak Akses</a></li><li class="active">Daftar Laporan</li>';
        $content['jquery'] = [];
        $content['js'] = $js_dir . 'rolereport.js';
        break;

    case '10':
        // list report
        $content['title'] = 'Pengaturan Laporan';
        $content['subtitle'] = 'Melakukan pengelolaan laporan';
        $content['icon'] = 'fa fa-file';
        $content['page'] = $page_dir . 'list.report.php';
        $content['breadcrumb'] = '<li><a href="user.php">OpenLibrary</a></li><li class="active">Laporan</li>';
        $content['jquery'] = [
            $jquery_dir . 'jquery.datatables.min.js',
            $jquery_dir . 'select2.min.js'
        ];
        $content['js'] = $js_dir . 'report.js';
        break;

    case '11':
        // list report
        $content['title'] = 'Edit Data';
        $content['subtitle'] = 'Melakukan peremajaan data';
        $content['icon'] = 'fa fa-pencil';
        $content['page'] = $page_dir . 'edit.data.php';
        $content['breadcrumb'] = '<li><a href="user.php">OpenLibrary</a></li><li class="active">Edit</li>';
        $content['jquery'] = [
            $jquery_dir . 'jquery.datatables.min.js',
            $jquery_dir . 'select2.min.js'
        ];
        $content['js'] = $js_dir . 'report.js';
        break;
    
    default:
        // show jendela query by default
        $content['title'] = 'Query Window';
        $content['subtitle'] = 'Please Define Your Query to Database';
        $content['icon'] = 'fa fa-database';
        $content['page'] = $page_dir . 'sql.php';
        $content['breadcrumb'] = '<li><a href="user.php">Dashboard</a></li><li class="active">Query Window</li>';
        $content['jquery'] = [
            $jquery_dir . 'jquery.autogrow-textarea.js',
            $jquery_dir . 'jquery.matchHeight-min.js'
        ];
        $content['js'] = $js_dir . 'sql.js';
        break;
}

?>


<!DOCTYPE html>
<html lang="in">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="asset/images/Telkom_Indonesia.png" type="image/png">

    <title>TReg3 - Administrator Page</title>

    <link href="asset/css/style.default.css" rel="stylesheet">
    <link href="asset/css/style.dodgerblue.css" rel="stylesheet">
    <link href="asset/css/jquery.datatables.css" rel="stylesheet">
    <link href="asset/css/jquery.scrollbar.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
</head>

<body>

    <!-- MENU INDICATOR -->
    <input type="hidden" name="menu" id="menu" value="<?php echo $menu; ?>">

    <!-- PRELOADER -->
    <div id="preloader">
        <div id="status"><i class="fa fa-spinner fa-spin"></i></div>
    </div>

    <!-- MODAL -->
    <div class="modal fade" id="modal-message" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            </div>
        </div>
    </div>

    <!-- MODAL SHOW DATA SOURCE -->
    <div class="modal fade" id="modal-datasource" tabindex="-1" role="dialog" aria-labelledby="modalDataSource" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button aria-hidden="true" data-dismiss="modal" type="button" class="close">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body" style="overflow: auto;"></div>
            </div>
        </div>
    </div>

    <!-- MODAL FIELD DATA SOURCE -->
    <div class="modal fade" id="modal-field" tabindex="-1" role="dialog" aria-labelledby="modalDataSource" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button aria-hidden="true" data-dismiss="modal" type="button" class="close">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body" style="overflow: auto;"></div>
            </div>
        </div>
    </div>


    <!-- SECTION PAGE -->
    <section>

        <!-- LEFTPANEL -->
        <div class="leftpanel">

            <div class="logopanel">
                <img src="./asset/images/Telkom_Indonesia_small.png" alt="" class="mr10" style="float: left;">
                <h1><span>[</span> TReg3 <span>]</span></h1>
            </div><!-- logopanel -->

            <div class="leftpanelinner">
                <h5 class="sidebartitle">Navigation</h5>
                <ul class="nav nav-pills nav-stacked nav-bracket" id="app-menu">
                    <li menu-id="11"><a href="?menu=11"><i class="fa fa-pencil"></i> <span>Edit Data</span></a></li>
                    <li menu-id="0"><a href="admin.php"><i class="fa fa-database"></i> <span>Query Window</span></a></li>
                    <li menu-id="7"><a href="?menu=7"><i class="fa fa-file"></i> <span>Create New Report</span></a></li>
                    <li menu-id="99"><a href="user.php"><i class="fa fa-folder-open"></i> <span>View Report</span></a></li>
                    <li menu-id="parent" class="nav-parent"><a href="#"><i class="fa fa-gear"></i> <span>Administrator</span></a>
                        <ul class="children">
                            <li menu-id="1"><a href="?menu=1"><i class="fa fa-caret-right"></i>Data Source</a></li>
                            <li menu-id="10"><a href="?menu=10"><i class="fa fa-caret-right"></i>Report</a></li>
                            <li menu-id="3"><a href="?menu=3"><i class="fa fa-caret-right"></i>User</a></li>
                            <li menu-id="6"><a href="?menu=6"><i class="fa fa-caret-right"></i>Access Privilege</a></li>
                            <li><a href="#"><i class="fa fa-caret-right"></i>Dashboard</a></li>
                        </ul>
                    </li>
                    <li><a href="action/logout.php"><i class="glyphicon glyphicon-log-out"></i> <span>Logout</span></a></li>
                </ul>
            </div>

        </div>
        <!-- END OF LEFTPANEL -->

    
        <!-- MAINPANEL-->
        <div class="mainpanel">

            <!-- HEADER -->
            <div class="headerbar">
                <a class="menutoggle"><i class="fa fa-bars"></i></a>
            </div><!-- headerbar -->

            <div class="pageheader">
                <h2><i class="<?php echo $content['icon'] ?>"></i> <?php echo $content['title']; ?> <span><?php echo $content['subtitle']; ?></span></h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb"><?php echo $content['breadcrumb']; ?></ol>
                </div>
            </div>
            <!-- END OF HEADER-->


            <!-- CONTENT -->
            <div class="contentpanel">
               <?php require_once($content['page']); ?>
            </div>
            <!-- END OF CONTENT -->

        </div>
        <!-- END OF MAINPANEL -->

    </section>


    <script src="asset/js/template/jquery-1.11.1.min.js"></script>
    <script src="asset/js/template/jquery-migrate-1.2.1.min.js"></script>
    <script src="asset/js/template/bootstrap.min.js"></script>
    <script src="asset/js/template/modernizr.min.js"></script>
    <script src="asset/js/template/jquery.sparkline.min.js"></script>
    <script src="asset/js/template/toggles.min.js"></script>
    <script src="asset/js/template/retina.min.js"></script>
    <script src="asset/js/template/jquery.cookies.js"></script>
    <script src="asset/js/template/jquery.autogrow-textarea.js"></script>
    <script src="asset/js/template/jquery.scrollbar.min.js"></script>

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

</body>

</html>
