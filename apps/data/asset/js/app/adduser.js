$(document).ready(function () {

	$('#form-tambah-user').on('submit', function () {
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

			if (res_status == 'success') showSuccessMessage(result['message']);

			if (res_status == 'error') showErrorMessage(result['message']);

		})
		.always(function() {
			$('#preloader').hide();
		});

		return false;
	});

})