
<?php

// required file
require_once('./model/class.db.php');

$id = isset($_GET['id']) ? $_GET['id'] : 0;

// connect to DB
$db = new DB();
$db->set_default_connection();

$sqlReport = "SELECT * FROM laporan ORDER BY nama_laporan ASC";
$reports = $db->select($sqlReport);

$sqlRole = "SELECT * FROM previlage WHERE id_previlage = $id";
$role = mysql_fetch_assoc($db->select($sqlRole));

$sqlPermission = "SELECT * FROM otoritas WHERE id_previlage = $id";
$permissions = [];
if ($db->get_query_rows($sqlPermission) > 0) {
	$temp = $db->select($sqlPermission);
	while ($data = mysql_fetch_assoc($temp)) {
		$permissions[] = $data['id_laporan'];
	}
}

?>


<div class="row">
	
<?php if ($db->get_query_rows($sqlReport) == 0) : ?>

	<div class="alert alert-info fade in nomargin">
		<h4>Ups, Laporan Kosong !</h4>
		<p>Tidak ada laporan di dalam sistem.<br>Untuk mengatur hak akses laproan, minimal harus ada 1 laporan tersimpan di dalam sistem.</p>
		<p>
			<a href="?menu=7" class="btn btn-primary">Susun Laporan Baru</a>
			<a href="?menu=6" class="btn btn-default">Kembali ke List Hak Akses</a>
		</p>
	</div>

<?php elseif ($db->get_query_rows($sqlRole) == 0) : ?>

	<div class="alert alert-info fade in nomargin">
		<h4>Ups, Hak Akses Tidak Ada !</h4>
		<p>Hak akses yang akan diatur tidak ditemukan di dalam sistem.<br>Pastikan hak akses tersimpan di dalam sistem.</p>
		<p><a href="?menu=6" class="btn btn-primary">Kembali ke List Hak Akses</a></p>
	</div>

<?php else : ?>

	<form action="./action/createpermission.php" method="POST" id="form-permission">

		<div class="panel panel-default panel-alt">
			<div class="panel-heading">
				<div class="panel-btns"><a href="" class="minimize">&minus;</a></div>
				<h4 class="panel-title">Panel Hak Akses Laporan</h4>
				<p>Daftar Laporan yang dapat diakses</p>
			</div>
			<div class="panel-body">
				<p>Pilih laporan yang bisa dibuka oleh : <span class="text-danger"><?php echo ucwords($role['nama_previlage']); ?></span></p>
				<input type="hidden" name="e_role_id" value="<?php echo $role['id_previlage']; ?>">

				<table class="table table-primary table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th class="col-sm-1 text-center">&nbsp</th>
							<th>Nama Laporan</th>
							<th>Last Update On</th>
						</tr>
					</thead>
					<tbody>

					<?php 
					while ($report = mysql_fetch_assoc($reports)) : 
						$checked = in_array($report['id_laporan'], $permissions) ? 'checked' : '';
					?>

						<tr>
							<td class="col-sm-1 text-center"><input type="checkbox" class="form-control" name="e_laporan[]" value="<?php echo $report['id_laporan']; ?>" <?php echo $checked; ?>></td>
							<td><?php echo ucwords($report['nama_laporan']); ?></td>
							<td class="text-center"><?php echo $report['modif']; ?></td>
						</tr>

					<?php endwhile; ?>

					</tbody>
				</table>

			</div>
			<div class="panel-footer">
				<div class="hidden-xs">
					<button class="btn btn-primary" type="submit">Simpan Hak Akses</button>
					<a href="?menu=6" class="btn btn-default">Kembali ke List Hak Akses</a>
				</div>
				<div class="visible-xs">
					<button class="btn btn-primary btn-block" type="submit">Simpan Hak Akses</button>
					<a href="?menu=6" class="btn btn-default btn-block">Kembali ke List Hak Akses</a>
				</div>
			</div>
		</div>

	</form>

<?php endif; ?>

</div>
