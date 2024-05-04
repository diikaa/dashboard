
<?php

// query roles in database
require_once('./model/class.db.php');
$db = new DB();
$db->set_default_connection();

$query = "SELECT * FROM previlage ORDER by id_previlage";
$roles = $db->select($query);

$roles_list = [ 0 => 'PILIH ROLES USER'];
if (mysql_num_rows($roles)) {
	while ($role = mysql_fetch_assoc($roles)) {
		$roles_list[$role['id_previlage']] = strtoupper($role['nama_previlage']); 
	}
}

?>


<div class="row">
	<form action="./action/createuser.php" method="POST" id="form-tambah-user" class="form-horizontal form-bordered">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="panel-btns"><a href="" class="minimize">&minus;</a></div>
				<h4 class="panel-title">Form Tambah User</h4>
			</div>
			<div class="panel-body panel-body-nopadding">

				<?php require_once('./view/partial/form_user.php'); ?>

			</div>
		</div>
	</form>
</div>