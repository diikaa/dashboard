<?php

// required file
require_once('./model/class.db.php');

// connect to DB
$db = new DB();
$db->set_default_connection();

$sql = "SELECT * FROM laporan ORDER BY nama_laporan ASC";
$reports = $db->select($sql);

?>


<div class="row">
	
	<div class="panel panel-default panel-alt">
		<div class="panel-heading">
			<div class="panel-btns"><a href="" class="minimize">&minus;</a></div>
			<h4 class="panel-title">Panel Laporan</h4>
			<p>Daftar Laporan yang tersimpan di database</p>
		</div>
		<div class="panel-body">
			
			<?php if ($db->get_query_rows($sql) == 0) : ?>

				<p>Tidak ada Laporan di dalam sistem. <a href="#" class="text-success">Tambahkan Laporan baru</a> ke dalam sistem.</p>

			<?php else : ?>
				<p>
					<a href="?menu=7" class="btn btn-primary hidden-xs tambah-laporan-baru">Tambah Laporan Baru</a>
					<a href="./action/updateallreport.php?ajax=1" class="btn btn-primary hidden-xs update-seluruh-laporan">Update Seluruh Laporan</a>
					<a href="?menu=7" class="btn btn-primary btn-block visible-xs mt5 tambah-laporan-baru">Tambah Laporan Baru</a>
					<a href="./action/updateallreport.php?ajax=1" class="btn btn-primary btn-block visible-xs update-seluruh-laporan">Update Seluruh Laporan</a>
				</p><hr>

				<div class="table-responsive">
					<table id="tabel-laporan" class="table table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th>Nama Laporan</th>
								<th>Last Update On</th>
								<th>&nbsp</th>
							</tr>
						</thead>
						<tbody>

							<?php while ($data = mysql_fetch_assoc($reports)) : ?>
								<tr>
									<td><?php echo strtoupper($data['nama_laporan']); ?></td>
									<td class="text-center"><?php echo $data['modif']; ?></td>
									<td class="text-center col-lg-2 col-sm-3">
										<a href="<?php echo $data['id_laporan']; ?>" class="btn btn-primary btn-sm btn-load-laporan mb5 tooltips" data-toggle="tooltip" title="Tampilkan laporan"><i class="fa fa-table"></i></a>
										<a href="?menu=8&id=<?php echo $data['id_laporan']; ?>" class="btn btn-primary btn-sm mb5 tooltips" data-toggle="tooltip" title="Edit laporan"><i class="fa fa-edit"></i></a>
										<a href="<?php echo $data['id_laporan']; ?>" class="btn btn-primary btn-sm btn-update-laporan mb5 tooltips" data-toggle="tooltip" title="Update laporan"><i class="fa fa-rotate-left"></i></a>
										<a href="<?php echo ucwords($data['nama_laporan']) ?>" class="btn btn-danger btn-sm btn-delete-laporan mb5 tooltips" delete-id="<?php echo $data['id_laporan']; ?>" data-toggle="tooltip" title="Hapus laporan"><i class="fa fa-trash-o"></i></a>
									</td>
								</tr>
							<?php endwhile; ?>

						</tbody>
					</table>
				</div>

			<?php endif; ?>

		</div>
	</div>

</div>
