$(document).ready(function() {

	// call jQuery plugin
	$('#autoResizeTA').autogrow();

	// scrollbar
    $('.scrollbar-inner').scrollbar();

    // apply equal height
    $('.kontrol-sql').each(function() {
    	$(this).find('.same-column-height').matchHeight({
    		property: 'max-height',
    		target: $(this).find('.target-column-height')
    	});
    });


	$('#form-koneksi').on('submit', function() {
		resetListTablePanel();
		resetStructureTablePanel();
		resetQueryPanel();
		resetQueryResultPanel();
		resetDataSourceName();

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

			if (res_status == 'success') {
				$('#list-tabel-database').html( result['message'] );
				bindTableListTrigger();
			}

			if (res_status == 'error') showErrorMessage(result['message']);

		})
		.always(function() {
			$('#preloader').hide();
		});

		return false;
	});


	$('#form-query').on('submit', function() {
		resetQueryResultPanel();

		$.ajax({
			url: $(this).attr('action'),
			method: $(this).attr('method'),
			data: $(this).serialize() + '&' + $('#form-koneksi').serialize(),
			dataType: 'json',
			beforeSend: function() {
				$('#preloader').show();
			}
		})
		.done(function(result) {
			var res_status = result['status'];

			if (res_status == 'success') {
				$('#list-hasil-query').html( result['message'] );
			}

			if (res_status == 'error') showErrorMessage(result['message']);

		})
		.always(function() {
			$('#preloader').hide();
		});

		return false;
	});


	$('.save-data-source').on('click', function() {

		var query = $('#form-koneksi').serialize() + '&' +$('#form-query').serialize(),
			action = './action/createdatasource.php';

		$.ajax({
			url: action,
			method: 'POST',
			data: query,
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

	});


	function bindTableListTrigger()
	{
		$('a.db-table-item').on('click', function() {
			var tabel = $(this).attr('href'),
				query = $('#form-koneksi').serialize() + '&e_tabel=' + tabel;

			$('#list-tabel-database .selected-list').removeClass('selected-list');
			$('li', this).addClass('selected-list');

			$.ajax({
				url: './action/viewstructable.php',
				method: 'POST',
				data: query,
				dataType: 'json',
				beforeSend: function() {
					$('#preloader').show();
				}
			})
			.done(function(result) {
				var res_status = result['status'];

				if (res_status == 'success') {
					$('#list-struktur-tabel').html( result['message'] );
				}

				if (res_status == 'error') showErrorMessage(result['message']);

			})
			.always(function() {
				$('#preloader').hide();
			});

			return false;
		});
	}


	// list of all additional function

	function resetListTablePanel()
	{
		var default_result = '<p class="padding15">Tidak ada tabel yang ditampilkan.<br>Hubungkan aplikasi dengan database terlebih dahulu.</p>';

		$('#list-tabel-database').html(default_result);
	}

	function resetDataSourceName()
	{
		$('input[name=e_nama]').val('');
	}

	function resetStructureTablePanel()
	{
		var default_result = '<p class="padding15">Tidak ada struktur tabel yang ditampilkan.<br>Hubungkan aplikasi dengan database dan pilih tabel yang akan ditampilkan strukturnya.</p>';

		$('#list-struktur-tabel').html(default_result);
	}

	function resetQueryResultPanel()
	{
		var default_result = 'Tidak ada hasil query yang ditampilkan.<br>Silahkan atur koneksi dan masukkan SQL code yang akan di-query.<br>Hasil query akan ditampilkan disini.';

		$('#list-hasil-query').html(default_result);
	}

	function resetQueryPanel()
	{
		$('textarea[name=e_sql]').val('');
	}

});


