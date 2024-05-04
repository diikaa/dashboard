
<div class="form-group">
	<label class="control-label col-sm-3 hidden-xs">Nama Datasource</label>
	<div class="col-sm-6">
		<input type="hidden" name="e_id" value="<?php echo $datasource['id_data']; ?>">
		<input type="text" class="form-control" name="e_nama" placeholder="Nama Datasource" value="<?php echo $datasource['nama_data']; ?>" readonly>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-3 hidden-xs">Alamat Server Database</label>
	<div class="col-sm-6">
		<input type="text" class="form-control required primary" name="e_host" placeholder="Alamat IP / Nama Server Database" value="<?php echo $datasource['server']; ?>">
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-3 hidden-xs">Nama Database</label>
	<div class="col-sm-6">
		<input type="text" class="form-control required primary" name="e_dt" placeholder="Nama Database" value="<?php echo $datasource['data_base']; ?>">
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-3 hidden-xs">Username</label>
	<div class="col-sm-4">
		<input type="text" class="form-control required primary" name="e_user" placeholder="Username Login Database" value="<?php echo $datasource['user']; ?>">
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-3 hidden-xs">Password</label>
	<div class="col-sm-4">
		<input type="password" class="form-control required primary" name="e_pass" placeholder="Password Login Database">
		<span class="help-block">
			Untuk alasan keamanan maka <strong>password harus diisi ulang saat proses update</strong>.
		</span>
	</div>
</div>

<?php

$db_type = [
	'MySQL' => 'MySQL',
	'Oci' => 'Oracle'
];

?>

<div class="form-group">
	<label class="control-label col-sm-3 hidden-xs">Tipe Database</label>
	<div class="col-sm-4">
		<select name="e_jenis" class="form-control required primary">

			<?php 
			foreach ($db_type as $key => $val) : 
				$selected = ($key == $datasource['jenis']) ? 'selected' : '';
			?>
				<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $val; ?></option>
			<?php endforeach; ?>

		</select>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-3 hidden-xs">Query</label>
	<div class="col-sm-9">
		<textarea name="e_sql" id="autoResizeTA" class="form-control" rows="5" readonly><?php echo $datasource['query']; ?></textarea>
	</div>
</div>

<div class="form-group background-white">
	<div class="col-sm-9 col-sm-offset-3 hidden-xs">
		<button class="btn btn-primary" type="submit">Simpan Datasource</button>
		<a href="?menu=1" class="btn btn-default">Kembali ke Tabel Datasource</a>
	</div>
	<div class="col-xs-12 visible-xs">
		<button class="btn btn-primary btn-block" type="submit">Simpan Datasource</button>
		<a href="?menu=1" class="btn btn-default btn-block">Kembali ke Tabel Datasource</a>
	</div>
</div>