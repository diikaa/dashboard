<?php

// required file
require_once('./model/class.db.php');
$db = new DB();
$db->set_default_connection();

$id = isset($_GET['id']) ? $_GET['id'] : 0;
$sql = "SELECT * FROM laporan WHERE id_laporan = $id";

?>

<div class="row">

	<?php if ($db->get_query_rows($sql) == 0) :?>

		<div class="alert alert-info fade in nomargin">
			<h4>Ups, Laporan Tidak Ada !</h4>
			<p>Laporan yang ingin diproses tidak ditemukan di dalam sistem.<br>Pastikan laporan yang akan di edit tersimpan di sistem.</p>
			<p>
				<a href="?menu=7" class="btn btn-primary">Susun Laporan Baru</a>
				<a href="#" class="btn btn-default">Kembali ke List Laporan</a>
			</p>
		</div>

	<?php 
	else : 
		$laporan = mysql_fetch_assoc($db->select($sql));

		// load data available datasource
		$sql = "SELECT * FROM tab_data ORDER BY nama_data ASC";
		$datasources = $db->select($sql);
		$select_datasources = '<option value="0">PILIH DATASOURCE</option>';
		while ($datasource = mysql_fetch_assoc($datasources)) {
			$select_datasources .= '<option value="' . $datasource['id_data'] . '">' . strtoupper($datasource['nama_data']) . '</option>';
		}
	?>
	
	
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="panel-btns"><a href="" class="minimize">&minus;</a></div>
				<h4 class="panel-title">Laporan : <?php echo ucwords($laporan['nama_laporan']); ?></h4>
				<p>Melakukan pengaturan header, pivot, filter dan grouping dari laporan</p>
			</div>
			<div class="panel-body panel-body-nopadding">

				<div class="basic-wizard" id="report-wizard">
					
					<ul class="nav nav-pills nav-justified">
						<li><a href="#report-header" data-toggle="tab">Header</a></li>
						<li><a href="#report-pivot" data-toggle="tab">Pivot</a></li>
						<li><a href="#report-filter" data-toggle="tab">Filter</a></li>
						<li><a href="#report-grouping" data-toggle="tab">Grouping</a></li>
					</ul>

					<div class="tab-content nopadding">
						<div class="tab-pane" id="report-header">
							<form action="./action/createheader.php" method="POST" class="form-horizontal form-bordered" id="form-header">
								<?php require_once('./view/partial/form_header.php'); ?>
								<input type="hidden" name="e_id" value="<?php echo $laporan['id_laporan']; ?>">
							</form>
						</div>

						<div class="tab-pane" id="report-pivot">
							<form action="./action/createpivot.php" method="POST" class="form-horizontal form-bordered" id="form-pivot">
								<?php require_once('./view/partial/form_pivot.php'); ?>
								<input type="hidden" name="e_id" value="<?php echo $laporan['id_laporan']; ?>">
							</form>
						</div>

						<div class="tab-pane" id="report-filter">
							<form action="./action/createfilter.php" method="POST" class="form-horizontal form-bordered" id="form-filter">
								<?php require_once('./view/partial/form_filter.php'); ?>
								<input type="hidden" name="e_id" value="<?php echo $laporan['id_laporan']; ?>">
							</form>
						</div>

						<div class="tab-pane" id="report-grouping">
							<form action="./action/creategrouping.php" method="POST" class="form-horizontal form-bordered" id="form-grouping">
								<?php require_once('./view/partial/form_grouping.php'); ?>
								<input type="hidden" name="e_id" value="<?php echo $laporan['id_laporan']; ?>">
							</form>
						</div>
					</div>

				</div>

			</div>
		</div>

	<?php endif; ?>

</div>