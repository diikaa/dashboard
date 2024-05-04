
<div class="col-md-offset-3 col-md-6 mt20">
	<form action="./action/createrole.php" method="POST" id="form-tambah-hak-akses">
		<div class="panel panel-alt panel-primary">
		    <div class="panel-heading">
		        <h3 class="panel-title">Form Tambah Role</h3>
		    </div>
		    <div class="panel-body">
		    	<div class="alert alert-danger fade in nomargin mb10" style="display: none">
		    		<button class="close" type="button" data-hide="alert" aria-hidden="true">&times;</button>
		    		<h4>Ups, Terjadi Kesalahan </h4>
		    		<p></p>
		    	</div>

		    	<div class="row">
		    		<div class="form-group col-sm-12">
		    			<input type="text" class="form-control" name="e_nama" placeholder="Nama Hak Akses Baru">
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


<script>

	$(document).ready(function () {

		$('#form-tambah-hak-akses .alert').hide();

		$('[data-hide]').on('click', function () {
			$(this).closest("." + $(this).attr("data-hide")).hide();
		});

		$('#form-tambah-hak-akses').on('submit', function () {
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

				if (res_status == 'success')  showAddRoleSuccessMessage(result['message']);

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


		function showAddRoleSuccessMessage(msg)
		{
			$('#modal-message').load('view/partial/success_message_add_role.php', function() {
				$('.panel-body', this).html(msg);
			});

			$('#modal-message').modal('show');
		}

	});

</script>