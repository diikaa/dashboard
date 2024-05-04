<?php

// required file
require_once('./model/class.db.php');
require_once('./helper/protected_role.php');

$protected_role = getProtectedRole();

// connect to DB
$db = new DB();
$db->set_default_connection();

$sql = "SELECT p.*,
		count(nama_user) as jum_user
	FROM previlage p
	LEFT JOIN user u on p.id_previlage = u.prev_user
	GROUP BY p.nama_previlage
	ORDER BY id_previlage ASC";
$roles = $db->select($sql);

?>


<div class="row">
	
	<div class="panel panel-default panel-alt">
		<div class="panel-heading">
			<div class="panel-btns"><a href="" class="minimize">&minus;</a></div>
			<h4 class="panel-title">Panel Hak Akses</h4>
			<p>Daftar Hak Akses yang tersimpan di database</p>
		</div>
		<div class="panel-body">
			
			<?php if ($db->get_query_rows($sql) == 0) : ?>

				<p>Tidak ada Hak Akses di dalam sistem. <a href="#" class="text-success">Tambahkan Hak Akses baru</a> ke dalam sistem.</p>

			<?php else : ?>
				<p>
					<button class="btn btn-primary hidden-xs tambah-role-baru">Tambah Hak Akses Baru</button>
					<button class="btn btn-primary btn-block visible-xs mt5 tambah-role-baru">Tambah Hak Akses Baru</button>
				</p><hr>

				<div class="table-responsive">
					<table id="tabel-hak-akses" class="table table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th>Nama Hak Akses</th>
								<th>Jumlah User</th>
								<th>&nbsp</th>
							</tr>
						</thead>
						<tbody>
							<?php while ($data = mysql_fetch_assoc($roles)) : ?>
								<tr>
									<td class="text-center"><?php echo strtoupper($data['nama_previlage']); ?></td>
									<td class="text-center"><?php echo strtoupper($data['jum_user']); ?></td>
									<td class="text-center col-lg-2 col-sm-2">
										<a href="<?php echo $data['id_previlage']; ?>" class="edit-hak-akses btn btn-sm btn-primary mb5 tooltips" data-toggle="tooltip" title="Edit hak akses"><i class="fa fa-edit"></i></a>

										<a href="?menu=9&id=<?php echo $data['id_previlage']?>" class="btn btn-sm btn-primary mb5 tooltips" data-toggle="tooltip" title="Edit daftar laporan"><i class="fa fa-list"></i></a>
										
										<?php if ($data['jum_user'] == 0  && ! in_array($data['id_previlage'], $protected_role)) : ?>
											<a href="<?php echo $data['nama_previlage']; ?>" delete-id="<?php echo $data['id_previlage']; ?>" class="delete-hak-akses btn btn-sm btn-danger mb5 tooltips" data-toggle="tooltip" title="Hapus hak akses"><i class="fa fa-trash-o"></i></a>
										<?php endif; ?>

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