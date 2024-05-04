$(document).ready(function () {

	// set table to datatable
	// initiate all event in datatable
	bindDeleteRole();
	bindEditRole();
	$('#tabel-hak-akses').dataTable().on('draw.dt', function () {
		
	});

	// add select 2 class
	jQuery('select').select2({
		minimumResultsForSearch: -1
	});

	// remove existing class
	jQuery('select').removeClass('form-control');

	$('.tambah-role-baru').on('click', function () {
		$('#modal-message').load('view/partial/panel_add_role.php');

		$('#modal-message').modal('show');

		return false;
	});


	function bindDeleteRole()
	{
		$('a.delete-hak-akses').on('click', function() {
			var name = $(this).attr('href'),
				id = $(this).attr('delete-id'),
				msg = '<p>Apakah Anda yakin akan menghapus hak akses : <span class="text-danger">' + name.toUpperCase() + '</span></p>';

			$('#modal-message').load('view/partial/delete_message.php', function() {
				$('.panel-body', this).html(msg);
				$('a.delete-link', this).attr('href', './action/destroyrole.php?id=' + id);
			});

			$('#modal-message').modal('show');

			return false;
		});
	}

	function bindEditRole()
	{
		$('a.edit-hak-akses').on('click', function() {
			var id = $(this).attr('href');

			$('#modal-message').load('view/partial/panel_edit_role.php?id=' + id);

			$('#modal-message').modal('show');

			return false;
		});
	}

});