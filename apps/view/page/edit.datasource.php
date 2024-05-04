<?php

// query datasource in database
require_once('./model/class.db.php');
$db = new DB();
$db->set_default_connection();

$query = "SELECT * FROM tab_data WHERE nama_data = '$id'";
$temp = $db->select($query);
$dataRows = mysql_num_rows($temp);

?>


<div class="row">
	
	<?php if ($dataRows == 0) : ?>

		<div class="alert alert-warning fade in nomargin">
			<h4>Ups, Terjadi Kesalahan !</h4>
			<p>Datasource yang ingin di edit tidak ditemukan di dalam sistem.<br>Pastikan datasource yang akan di edit tersimpan di sistem.</p>
			<p><a href="?menu=1" class="btn btn-warning">Kembali ke List Datasource</a></p>
		</div>

	<?php 
	else : 
		$datasource = mysql_fetch_assoc($temp);
	?>
		<div class="alert alert-info fade in">
			<button class="close" data-dismiss="alert" type="button" aria-hidden="true">&times;</button>
			<h4>Informasi Update !</h4>
			<p>Untuk menghindari perubahan pada template laporan yang telah dibuat, maka proses update datasource tidak dapat mengubah Query dan Nama datasource.<br>Perubahan Query dan Nama datasource dapat menyebabkan laporan yang telah dibuat tidak dapat ditampilkan.</p>
		</div>

		<form action="./action/updatedatasource.php" method="POST" class="form-horizontal form-bordered" id="form-update-datasource">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="panel-btns"><a href="" class="minimize">&minus;</a></div>
					<h4 class="panel-title">Form Edit Datasource</h4>
				</div>
				<div class="panel-body panel-body-nopadding">

					<?php require_once('./view/partial/form_datasource.php'); ?>

				</div>
			</div>
		</form>

	<?php endif; ?>

</div>