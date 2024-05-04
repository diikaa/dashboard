$(document).ready(function () {

	bindTriggerLoadLaporan();
	bindTriggerUpdateLaporan();
	bindTriggerDeleteReport();
	$('#tabel-laporan').dataTable().on('draw.dt', function () {
		
	});

	// add select 2 class
	jQuery('select').select2({
		minimumResultsForSearch: -1
	});

	// remove existing class
	jQuery('select').removeClass('form-control');

	// update all report
	$('.update-seluruh-laporan').on('click', function () {
		$.ajax({
			url: $(this).attr('href'),
			method: 'GET',
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


	
	function bindTriggerLoadLaporan()
	{
		$('.btn-load-laporan').on('click', function () {
			var id = $(this).attr('href'),
				action = './action/reportpage.loadreport.php';

			$.ajax({
				url: action,
				method: 'POST',
				data: 'e_id=' + id,
				dataType: 'json',
				beforeSend: function() {
					$('#preloader').show();
				}
			})
			.done(function(result) {
				var res_status = result['status'];

				if (res_status == 'success') {
					$('#modal-datasource .modal-body').html(result['message']);
					$('#modal-datasource').on('show.bs.modal', function () {
						$('.modal-body', this).css('max-height', $( window ).height()*0.8);
						$('.modal-title').html('Laporan : ' + result['report']);
					});

					$('#modal-datasource').modal('show');
				}

				if (res_status == 'error') showErrorMessage(result['message']);

			})
			.always(function() {
				$('#preloader').hide();
			});

			return false;
		});
	}

	function bindTriggerUpdateLaporan()
	{
		$('.btn-update-laporan').on('click', function () {
			var id = $(this).attr('href'),
				action = './action/updatereportdatasource.php';

			$.ajax({
				url: action,
				method: 'POST',
				data: 'id=' + id,
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
	}

	function bindTriggerDeleteReport()
	{
		$('a.btn-delete-laporan').on('click', function() {
			var name = $(this).attr('href'),
				id = $(this).attr('delete-id'),
				msg = '<p>Apakah Anda yakin akan menghapus laporan : <span class="text-danger">' + name + '</span></p>';

			$('#modal-message').load('view/partial/delete_message.php', function() {
				$('.panel-body', this).html(msg);
				$('a.delete-link', this).attr('href', './action/destroyreport.php?id=' + id);
			});

			$('#modal-message').modal('show');

			return false;
		});
	}

});