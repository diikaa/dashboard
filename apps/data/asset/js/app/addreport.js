
$(document).ready(function () {

	$('#form-tambah-laporan').on('submit', function () {
		
		$.ajax({
			url: $(this).attr('action'),
			method: 'POST',
			data: $(this).serialize(),
			dataType: 'json',
			beforeSend: function() {
				$('#preloader').show();
			}
		})
		.done(function(result) {
			var res_status = result['status'];

			if (res_status == 'success') {
				$('#modal-message').load('view/partial/success_message.php', function() {
					var link = '<a href="admin.php?menu=8&id=' + result['laporan'] + '" class="btn btn-default">Proses Laporan</a>';

					$('.panel-body', this).html(result['message']);
					$('.panel-footer').html(link);
				});

				$('#modal-message').modal('show');
			}

			if (res_status == 'error') showErrorMessage(result['message']);

		})
		.always(function() {
			$('#preloader').hide();
		});

		return false;
	});

});