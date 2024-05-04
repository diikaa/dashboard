
<?php

// query roles in database
require_once('./model/class.db.php');
$db = new DB();
$db->set_default_connection();

$sql = "SELECT * FROM previlage ORDER by id_previlage";
$roles = $db->select($sql);

$roles_list = [ 0 => 'PILIH ROLES USER'];
if (mysql_num_rows($roles)) {
	while ($role = mysql_fetch_assoc($roles)) {
		$roles_list[$role['id_previlage']] = strtoupper($role['nama_previlage']); 
	}
}

$id = isset($_GET['id']) ? $_GET['id'] : NULL;
$sql = "SELECT * FROM user WHERE nik = '$id'";
$temp = $db->select($sql);
$dataRows = mysql_num_rows($temp);
$user = mysql_fetch_assoc($temp);

?>


<div class="row">
	
	<?php if ($dataRows == 0) : ?>

		<div class="alert alert-warning fade in nomargin">
			<h4>Ups, Terjadi Kesalahan !</h4>
			<p>User yang ingin di edit tidak ditemukan di dalam sistem.<br>Pastikan user yang akan di edit tersimpan di sistem.</p>
			<p><a href="?menu=3" class="btn btn-warning">Kembali ke List User</a></p>
		</div>

	<?php else : ?>

		<form action="./action/updateuser.php" method="POST" id="form-edit-user" class="form-horizontal form-bordered">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="panel-btns"><a href="" class="minimize">&minus;</a></div>
					<h4 class="panel-title">Form Edit User</h4>
				</div>
				<div class="panel-body panel-body-nopadding">

					<?php require_once('./view/partial/form_user.php'); ?>

				</div>
			</div>
		</form>

	<?php endif; ?>

</div>