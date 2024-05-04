$(document).ready(function () {

	// set table to data table
	// bind action button in data table
	bindShowDatasourceTable();
	bindDeleteDatasource();
	$('#tabel-datasource').dataTable().on('draw.dt', function () {

	});

	// add select 2 class
	jQuery('select').select2({
		minimumResultsForSearch: -1
	});

	// remove existing class
	jQuery('select').removeClass('form-control');


	function bindDeleteDatasource()
	{
		$('a.delete-datasource').on('click', function() {
			var name = $(this).attr('href'),
				id = $(this).attr('delete-id'),
				msg = '<p>Apakah Anda yakin akan menghapus datasource : <span class="text-danger">' + name + '</span></p>';

			$('#modal-message').load('view/partial/delete_message.php', function() {
				$('.panel-body', this).html(msg);
				$('a.delete-link', this).attr('href', './action/destroydatasource.php?id=' + id);
			});

			$('#modal-message').modal('show');

			return false;
		});
	}

	function bindShowDatasourceTable()
	{
		$('a.show-datasource').on('click', function () {
			var xmlFile = $(this).attr('href'),
				action = './action/showdatasource.php';

			$.ajax({
				url: action,
				method: 'POST',
				data: 'datasource=' + xmlFile,
				dataType: 'json',
				beforeSend: function() {
					$('#preloader').show();
				}
			})
			.done(function(result) {
				var res_status = result['status'];

				if (res_status == 'success') {
					showPanelDataSource(result['message'], xmlFile);
				}

				if (res_status == 'error') showErrorMessage(result['message']);

			})
			.always(function() {
				$('#preloader').hide();
			});

			return false;
		});
	}

	function showPanelDataSource(tables, datasource)
	{
		var content = renderJsonToTable(tables);

		$('#modal-datasource .modal-body').html(content);
		$('#modal-datasource').on('show.bs.modal', function () {
			$('.modal-body', this).css('max-height', $( window ).height()*0.8);
			$('.modal-title').html('Datasource : ' + datasource);
		});

		$('#modal-datasource').modal('show');
	}

	function renderJsonToTable(records)
	{
		var content = '';

		for (var i = 0; i < records.length; i++) {
			var colTagOpen = '<td>',
				colTagClose = '</td>';

			content += (i == 0) ? '<thead>' : '';
			content += (i == 1) ? '<tbody>' : '';
				
			content += '<tr>';
			for (var j = 0; j < records[i].length; j++) {
				if (i == 0) {
					colTagOpen = '<th>';
					colTagClose = '</th>';
				}
				content += colTagOpen + records[i][j] + colTagClose;
			}
			content += '</tr>';

			content += (i == 0) ? '</thead>' : '';
		}

		content += '</tbody>';

		return '<table class="table table-primary table-bordered table-striped table-hover">' + content + '</table>';
	}

});