<?php

// retrieve all user property 
$id = isset($user) ? $user['nik'] : '';
$nama = isset($user) ? $user['nama_user'] : '';
$role = isset($user) ? $user['prev_user'] : 0;
$readonly = isset($user) ? 'readonly' : '';
$primary = isset($user) ? '' : 'required primary';

?>


<div class="form-group">
	<label class="control-label col-sm-3 hidden-xs">NIK User</label>
	<div class="col-sm-4">
		<input type="text" class="form-control <?php echo $primary; ?>" name="e_nik" placeholder="Username untuk masuk ke aplikasi" value="<?php echo $id; ?>" <?php echo $readonly; ?> >

		<?php if (isset($user)) : ?>
			<input type="hidden" name="e_id" value="<?php echo $id; ?>">
		<?php endif; ?>

	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-3 hidden-xs">Nama User</label>
	<div class="col-sm-6">
		<input type="text" class="form-control required primary" name="e_user" placeholder="Nama user" value="<?php echo strtoupper($nama); ?>">
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-3 hidden-xs">Password</label>
	<div class="col-sm-4">

		<?php if (isset($user)) : ?>

		<div class="input-group">
			<span class="input-group-addon"><input type="checkbox" name="e_reset_password" value="1"></span>
			<input type="password" class="form-control required primary" name="e_pass" placeholder="Password untuk login ke aplikasi" readonly="readonly">
		</div>

		<?php else : ?>

			<input type="password" class="form-control required primary" name="e_pass" placeholder="Password untuk login ke aplikasi">
		
		<?php endif; ?>
		
		<span class="help-block">Pilih checkbox apabila ingin mengganti password user</span>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-3 hidden-xs">Tipe Database</label>
	<div class="col-sm-4">
		<select name="e_role" class="form-control required primary">

			<?php 
			foreach ($roles_list as $key => $val) : 
				$selected = ($role == $key) ? 'selected' : '';
			?>
				<option value="<?php echo $key; ?>" <?php echo $selected; ?> ><?php echo $val; ?></option>
			<?php endforeach; ?>

		</select>
	</div>	
</div>

<div class="form-group background-white">
	<div class="col-sm-9 col-sm-offset-3 hidden-xs">
		<button class="btn btn-primary" type="submit">Simpan User</button>
		<a href="?menu=3" class="btn btn-default">Kembali ke Tabel User</a>
	</div>
	<div class="col-xs-12 visible-xs">
		<button class="btn btn-primary btn-block" type="submit">Simpan User</button>
		<a href="?menu=3" class="btn btn-default btn-block">Kembali ke Tabel User</a>
	</div>
</div>