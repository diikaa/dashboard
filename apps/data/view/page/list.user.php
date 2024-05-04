<?php

// query user in database
require_once('./model/class.db.php');

// connect to DB
$db = new DB();
$db->set_default_connection();

$query = "SELECT * 
	FROM user u
	JOIN previlage p ON  u.prev_user = p.id_previlage
	ORDER BY nama_user ASC";

$users = $db->select($query);

?>


<div class="row">
	
	<div class="panel panel-default panel-alt">
		<div class="panel-heading">
			<div class="panel-btns"><a href="" class="minimize">&minus;</a></div>
			<h4 class="panel-title">Panel User</h4>
			<p>Daftar User yang tersimpan di database</p>
		</div>
		<div class="panel-body">
			
			<?php if ($db->get_query_rows($query) == 0) : ?>

				<p>Tidak ada User di dalam sistem. <a href="#" class="text-success">Tambahkan User baru</a> ke dalam sistem.</p>

			<?php else : ?>
				<p>
					<a href="?menu=4" class="btn btn-primary hidden-xs">Tambah User Baru</a>
					<a href="?menu=4" class="btn btn-primary btn-block visible-xs">Tambah User Baru</a>
				</p><hr>

				<div class="table-responsive">
					<table id="tabel-user" class="table table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th>NIK</th>
								<th>Nama</th>
								<th>Role</th>								
								<th>&nbsp</th>
							</tr>
						</thead>
						<tbody>
							<?php while ($data = mysql_fetch_assoc($users)) : ?>
								<tr>
									<td class="text-center"><?php echo $data['nik']; ?></td>
									<td><?php echo strtoupper($data['nama_user']); ?></td>
									<td class="text-center"><?php echo strtoupper($data['nama_previlage']); ?></td>
									<td class="text-center col-lg-2 col-sm-2">
										<a href="?menu=5&id=<?php echo $data['nik']; ?>" class="btn btn-sm btn-primary mb5 tooltips" data-toggle="tooltip" title="Edit user"><i class="fa fa-edit"></i></a>
										<a href="<?php echo $data['nama_user']; ?>" delete-id="<?php echo $data['nik']; ?>" class="delete-user btn btn-sm btn-danger mb5 tooltips" data-toggle="tooltip" title="Hapus user"><i class="fa fa-trash-o"></i></a>
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