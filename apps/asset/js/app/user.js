$(document).ready(function () {

	// set table to datatable
	// initiate all event in datatable
	bindDeleteUser();
	$('#tabel-user').dataTable().on('draw.dt', function () {
		
	});

	// add select 2 class
	jQuery('select').select2({
		minimumResultsForSearch: -1
	});

	// remove existing class
	jQuery('select').removeClass('form-control');


	function bindDeleteUser()
	{
		$('a.delete-user').on('click', function() {
			var name = $(this).attr('href'),
				id = $(this).attr('delete-id'),
				msg = '<p>Apakah Anda yakin akan menghapus user : <span class="text-danger">' + name.toUpperCase() + '</span></p>';

			$('#modal-message').load('view/partial/delete_message.php', function() {
				$('.panel-body', this).html(msg);
				$('a.delete-link', this).attr('href', './action/destroyuser.php?id=' + id);
			});

			$('#modal-message').modal('show');

			return false;
		});
	}

});