<?php

// include file
require_once('../../model/class.db.php');

// catch the param
$id = $_GET['id'];

// get role data
$db = new DB();
$db->set_default_connection();
$sql = "SELECT * FROM previlage WHERE id_previlage = $id";
$role = mysql_fetch_assoc($db->select($sql));

?>

<?php if ($db->get_query_rows($sql) == 0) : ?>

<div class="col-md-offset-3 col-md-6 mt20">
	<div class="panel panel-alt panel-danger">
	    <div class="panel-heading">
	        <h3 class="panel-title">Error</h3>
	    </div>
	    <div class="panel-body">
	    	<p>Hak akses tidak ditemukan di database.<br>Pastikan hak akses yang ingin di update tersimpan di aplikasi.</p>
	    </div>
	    <div class="panel-footer"><button data-dismiss="modal" class="btn btn-default">Kembali ke Aplikasi</button></div>
	</div>
</div>

<?php else : ?>

<div class="col-md-offset-3 col-md-6 mt20">
	<form action="./action/updaterole.php" method="POST" id="form-edit-hak-akses">
		<div class="panel panel-alt panel-primary">
		    <div class="panel-heading">
		        <h3 class="panel-title">Form Edit Role</h3>
		    </div>
		    <div class="panel-body">
		    	<div class="alert alert-danger fade in nomargin mb10" style="display: none">
		    		<button class="close" type="button" data-hide="alert" aria-hidden="true">&times;</button>
		    		<h4>Ups, Terjadi Kesalahan </h4>
		    		<p></p>
		    	</div>

		    	<div class="row">
		    		<div class="form-group col-sm-12">
		    			<input type="text" class="form-control" name="e_nama" placeholder="Nama Hak Akses Baru" value="<?php echo strtoupper($role['nama_previlage']); ?>">
		    			<input type="hidden" name="e_id" value="<?php echo $role['id_previlage']; ?>">
		    		</div>
		    	</div>
		    </div>
		    <div class="panel-footer">
		    	<div class="hidden-xs">
		    		<button class="btn btn-primary" type="submit">Simpan Hak Akses</button>
		    		<button data-dismiss="modal" class="btn btn-default">Kembali ke Aplikasi</button>
		    	</div>
				<div class="visible-xs">
					<button class="btn btn-primary btn-block" type="submit">Simpan Hak Akses</button>
		    		<button data-dismiss="modal" class="btn btn-default btn-block">Kembali ke Aplikasi</button>
				</div>
		    </div>
		</div>
	</form>
</div>

<?php endif; ?>


<script>

	$(document).ready(function () {

		$('#form-edit-hak-akses .alert').hide();

		$('[data-hide]').on('click', function () {
			$(this).closest("." + $(this).attr("data-hide")).hide();
		});

		$('#form-edit-hak-akses').on('submit', function () {
			$.ajax({
				url: $(this).attr('action'),
				method: $(this).attr('method'),
				data: $(this).serialize(),
				dataType: 'json',
				beforeSend: function() {
					$('#preloader').show();
				}
			})
			.done(function(result) {
				var res_status = result['status'];

				if (res_status == 'success')  showEditRoleSuccessMessage(result['message']);

				if (res_status == 'error') {
					$('#form-tambah-hak-akses .alert').show();
					$('#form-tambah-hak-akses .alert p').html(result['message']);
				}

			})
			.always(function() {
				$('#preloader').hide();
			});

			return false;
		});


		function showEditRoleSuccessMessage(msg)
		{
			$('#modal-message').load('view/partial/success_message_add_role.php', function() {
				$('.panel-body', this).html(msg);
			});

			$('#modal-message').modal('show');
		}

	});

</script>