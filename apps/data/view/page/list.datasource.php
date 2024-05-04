<?php

// query datasource in database
require_once('./model/class.db.php');
$db = new DB();
$db->set_default_connection();

$query = "SELECT * FROM tab_data ORDER BY nama_data ASC";
$datasources = $db->select($query);

?>


<div class="row">
	
	<div class="panel panel-default panel-alt">
		<div class="panel-heading">
			<div class="panel-btns"><a href="" class="minimize">&minus;</a></div>
			<h4 class="panel-title">Panel Datasource</h4>
			<p>Daftar Datasource yang tersimpan di database</p>
		</div>
		<div class="panel-body">
			
			<?php if ($db->get_query_rows($query) == 0) : ?>

				<p>Tidak ada Datasource di dalam sistem. <a href="admin.php" class="text-success">Tambahkan Datasource baru</a> ke dalam sistem.</p>

			<?php else : ?>
				<p>
					<a href="admin.php" class="btn btn-primary hidden-xs">Tambah Datasource Baru</a>
					<a href="admin.php" class="btn btn-primary btn-block visible-xs">Tambah Datasource Baru</a>
				</p><hr>

				<div class="table-responsive">
					<table id="tabel-datasource" class="table table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th>Nama Datasource</th>
								<th>Database</th>
								<th>Server</th>								
								<th>Jenis DB</th>
								<th>User</th>
								<th>&nbsp</th>
							</tr>
						</thead>
						<tbody>
							<?php while ($data = mysql_fetch_assoc($datasources)) : ?>
								<tr>
									<td><?php echo strtoupper($data['nama_data']); ?></td>
									<td class="text-center"><?php echo $data['jenis']; ?></td>
									<td class="text-center"><?php echo $data['server']; ?></td>
									<td class="text-center"><?php echo $data['data_base']; ?></td>
									<td class="text-center col-lg-1 col-sm-2"><?php echo $data['user']; ?></td>
									<td class="text-center col-lg-2 col-sm-2">
										<a href="<?php echo $data['nama_data']; ?>" class="show-datasource btn btn-sm btn-primary mb5 tooltips" data-toggle="tooltip" title="Tampilkan datasource"><i class="fa fa-table"></i></a>
										<a href="?menu=2&id=<?php echo $data['nama_data']; ?>" class="btn btn-sm btn-primary mb5 tooltips" data-toggle="tooltip" title="Edit datasource"><i class="fa fa-edit"></i></a>
										<a href="<?php echo $data['nama_data']; ?>" delete-id="<?php echo $data['id_data']; ?>" class="delete-datasource btn btn-sm btn-danger mb5 tooltips" data-toggle="tooltip" title="Hapus datasource"><i class="fa fa-trash-o"></i></a>
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