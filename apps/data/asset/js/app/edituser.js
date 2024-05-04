$(document).ready(function () {

	$('input[name=e_reset_password]').bind('click', function() {
		if ('checked' != $(this).attr('checked')) {
			$('input[name=e_pass]').attr('readonly', true);
		} else {
			$('input[name=e_pass]').removeAttr('readonly');
		}
	});

	$('#form-edit-user').on('submit', function () {
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

});