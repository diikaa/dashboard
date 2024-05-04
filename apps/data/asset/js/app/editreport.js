
$(document).ready(function () {

	bindShowXmlField();
	loadReportPivot();

	$('#report-wizard').bootstrapWizard();

	$('.toggle-active-datasource').bind('click', function() {
		var inputGroup = $(this).closest('.input-group');

		if ('checked' != $(this).attr('checked')) {
			$(inputGroup).find('select').attr('disabled', true).val(0);
			$(inputGroup).find('.datasource-field').html('');

			// additional function for pivot
			if ($(this).attr('name') == 'e_pivot_2') {
				$('#list-pivot-1-item .show-xml-field').removeAttr('ds-prefix');
				$('#list-pivot-2-item .show-xml-field').removeAttr('ds-prefix');
			}

			// additional function for pivot
			if ($(this).attr('name') == 'e_pivot_3') {
				$('#list-pivot-1-item .show-xml-field').removeAttr('ds-prefix');
				if ($('input[name=e_pivot_2]').is(':checked')) {
					$('#list-pivot-2-item .show-xml-field').attr('ds-prefix', 'data2');
					$('#list-pivot-2-item .show-xml-field').attr('ds-name', 'e_pivot_datasource_2');
				}
				else {
					$('#list-pivot-2-item .show-xml-field').removeAttr('ds-prefix');
				}
			}
		} else {
			$(inputGroup).find('select').removeAttr('disabled');

			// additional function for pivot
			if ($(this).attr('name') == 'e_pivot_2') {
				$('#list-pivot-1-item .show-xml-field').attr('ds-prefix', 'data1');
				$('#list-pivot-2-item .show-xml-field').attr('ds-prefix', 'data2');
			}

			// additional function for pivot
			if ($(this).attr('name') == 'e_pivot_3') {
				$('#list-pivot-1-item .show-xml-field').attr('ds-prefix', 'data1');
				$('#list-pivot-2-item .show-xml-field').attr('ds-prefix', 'data3');
				$('#list-pivot-2-item .show-xml-field').attr('ds-name', 'e_pivot_datasource_3');
			}
		}
	});

	$('button[dynamic-type=add]').on('click', function() {
		var cloneElement = $('#' + $(this).attr('dynamic-source')),
			targetElement = $('#' + $(this).attr('dynamic-target')),
			fn = $(this).attr('dynamic-callback');

		cloneElement = '<div>' + cloneElement.html() + '</div>';
		targetElement.append(cloneElement);
		bindShowXmlField($(this).attr('dynamic-target'), true);

		if ( typeof fn !== 'undefined') {
			window[fn]();
		}
	});

	$('button[dynamic-type=del]').on('click', function() {
		var targetElement = $('#' + $(this).attr('dynamic-target')),
			fn = $(this).attr('dynamic-callback'),
			childNUmber = targetElement.children('div').size();

		if (childNUmber > 1) {
			targetElement.children('div:last-child').remove();
		}

		if ( typeof fn !== 'undefined') {
			window[fn]();
		}
	});

	$('.datasource-selected').on('change', function () {
		loadDataSorceField(this)
	});

	$('.btn-load-laporan').on('click', function () {
		var id = $(this).closest('form').find('input[name=e_id]').val(),
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
				showReportModal(result['message'], result['report']);
			}

			if (res_status == 'error') showErrorMessage(result['message']);

		})
		.always(function() {
			$('#preloader').hide();
		});
	});


	/*
	| ---------------------------------------------------------------------
	| All Header action function
	| ---------------------------------------------------------------------
	 */
	
	$('.btn-tampilkan-header').on('click', function () {
		var id = $(this).closest('form').find('input[name=e_id]').val(),
			action = './action/showheader.php';

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

			if (res_status == 'success') {
				showReportModal(result['message'], result['report']);
			}

			if (res_status == 'error') showErrorMessage(result['message']);

		})
		.always(function() {
			$('#preloader').hide();
		});
	});

	$('.btn-delete-header').on('click', function () {
		var id = $(this).closest('form').find('input[name=e_id]').val(),
			msg = '<p>Apakah Anda yakin akan menghapus header untuk laporan ini ?</p>';

		$('#modal-message').load('view/partial/delete_message.php', function() {
			$('.panel-body', this).html(msg);
			$('.panel-title', this).html('Konfirmasi Hapus Header');
			$('a.delete-link', this).attr('href', './action/destroyheader.php?id=' + id);

			$('a.delete-link', this).on('click', function () {
				$.get( $(this).attr('href'), function(result) {
					if (result['status'] == 'error') showErrorMessage(result['message']);
					if (result['status'] == 'success') showSuccessMessage(result['message']);
				}, 'json');

				return false;
			});
		});

		$('#modal-message').modal('show');
	});

	$('#form-header').on('submit', function () {
		ajaxProcess(this);

		return false;
	});

	// End of Header Function



	/*
	| ---------------------------------------------------------------------
	| All Pivot action function
	| ---------------------------------------------------------------------
	|
	 */

	$('#form-pivot').on('submit', function () {
		ajaxProcess(this)

		return false;
	});

	// End of Pivot Function
	


	/*
	| ---------------------------------------------------------------------
	| All Filter action function
	| ---------------------------------------------------------------------
	|
	 */
	
	$('select[name=e_filter_datasource]').on('change', function() {
		var selectbox = $(this);
			action = './action/getdatasourcefield.php',
			query = 'id=' + selectbox.val();

		$.post(action, query, function (result) {

			$('select[name=e_filter_field]').children('option:gt(0)').remove();
			if (result['status'] == 'success') {
				
				var content = '';
				$.each(result['fields'], function (index, field) {
					content += '<option value="' + field + '">' + field.toUpperCase() + '</option>';
				});

				$('select[name=e_filter_field]').append(content);
			}

		}, 'json');
	});

	$('.btn-generate-filter').on('click', function () {
		var id = $(this).closest('form').find('select[name=e_filter_datasource]').val(),
			field = $(this).closest('form').find('select[name=e_filter_field]').val(),
			base = $(this).closest('form').find('input[name=e_filter_base_auto]').val(),
			inc = $(this).closest('form').find('input[name=e_filter_inc_auto]').val(),
			action = '../action/getdatasourcefieldvalue.php',
			query = 'id=' + id + '&field=' + field + '&base=' + base + '&inc=' + inc;

		$.post(action, query, function (result) {

			if (result['status'] == 'success') {
				generateFilter(result)
			} else {
				showErrorMessage(result['message']);
			}

		}, 'json');

		return false;
	});

	$('.btn-load-filter').on('click', function () {
		var action = '../action/showfilter.php',
			id = $(this).closest('form').find('input[name=e_id]').val();

		loadFilterOrGrouping(action, id);
	});

	$('#form-filter').on('submit', function () {
		ajaxProcess(this);
		
		return false;
	});

	function generateFilter(result)
	{
		var cloneElement = $('#filter-item'),
			targetElement = $('#list-filter-item'),
			interval = $('#form-filter').find('select[name=e_filter_interval]').val(),
			start_idx = result['base'],
			inc = result['inc'];

		// set initial div value
		targetElement.children('div:gt(0)').remove();
		targetElement.find('input[name ^= e_filter_text]').val('');
		targetElement.find('input[name ^= e_filter_base]').val('');
		targetElement.find('input[name ^= e_filter_inc]').val('');

		// render all datasource value
		$.each(result['filters'], function(index, filter) {
			var lastChild = targetElement.children('div:last-child');

			inc = (interval == 'range') ? (start_idx + result['inc'] - 1) : inc;

			$('input[name ^= e_filter_text]', lastChild).val(filter);
			$('input[name ^= e_filter_base]', lastChild).val(start_idx);
			$('input[name ^= e_filter_inc]', lastChild).val(inc);

			if (($(result['filters']).size() - 1) > index) {
				content = '<div>' + $(cloneElement).html() + '</div>';
				targetElement.append(content);
				start_idx = (interval == 'range') ? (inc + 1) : (start_idx + 1);
			}
		
		});

		calculateFilterItemIndex();
	}

	// End of Grouping Function
	


	/*
	| ---------------------------------------------------------------------
	| All Grouping action function
	| ---------------------------------------------------------------------
	|
	 */
	
	$('select[name=e_group_datasource]').on('change', function() {
		var selectbox = $(this);
			action = './action/getdatasourcefield.php',
			query = 'id=' + selectbox.val();

		$.post(action, query, function (result) {

			$('select[name=e_group_field]').children('option:gt(0)').remove();
			if (result['status'] == 'success') {
				
				var content = '';
				$.each(result['fields'], function (index, field) {
					content += '<option value="' + field + '">' + field.toUpperCase() + '</option>';
				});

				$('select[name=e_group_field]').append(content);
			}

		}, 'json');
	});

	$('.btn-generate-grouping').on('click', function () {
		var id = $(this).closest('form').find('select[name=e_group_datasource]').val(),
			field = $(this).closest('form').find('select[name=e_group_field]').val(),
			base = $(this).closest('form').find('input[name=e_group_base_auto]').val(),
			inc = $(this).closest('form').find('input[name=e_group_inc_auto]').val(),
			action = '../action/getdatasourcefieldvalue.php',
			query = 'id=' + id + '&field=' + field + '&base=' + base + '&inc=' + inc;

		$.post(action, query, function (result) {

			if (result['status'] == 'success') {
				generateGrouping(result);
			} else {
				showErrorMessage(result['message']);
			}

		}, 'json');

		return false;
	});

	$('.btn-load-grouping').on('click', function () {
		var action = '../action/showgrouping.php',
			id = $(this).closest('form').find('input[name=e_id]').val();

		loadFilterOrGrouping(action, id);
	});

	$('#form-grouping').on('submit', function () {
		ajaxProcess(this);

		return false;
	});

	function generateGrouping(result)
	{
		var cloneElement = $('#group-item'),
			targetElement = $('#list-group-item'),
			interval = $('#form-grouping').find('select[name=e_group_interval]').val(),
			start_idx = result['base'],
			inc = result['inc'];

		// set initial div value
		targetElement.children('div:gt(0)').remove();
		targetElement.find('input[name ^= e_group_text]').val('');
		targetElement.find('input[name ^= e_group_base]').val('');
		targetElement.find('input[name ^= e_group_inc]').val('');

		// render all datasource value
		$.each(result['filters'], function(index, filter) {
			var lastChild = targetElement.children('div:last-child');

			inc = (interval == 'range') ? (start_idx + result['inc'] - 1) : inc;

			$('input[name ^= e_group_text]', lastChild).val(filter);
			$('input[name ^= e_group_base]', lastChild).val(start_idx);
			$('input[name ^= e_group_inc]', lastChild).val(inc);

			if (($(result['filters']).size() - 1) > index) {
				content = '<div>' + $(cloneElement).html() + '</div>';
				targetElement.append(content);
				start_idx = (interval == 'range') ? (inc + 1) : (start_idx + 1);
			}
		
		});

		calculateGroupingItemIndex();
	}



	/**
	 | --------------------------------------------------------------------
	 | List of All Callback FUnction
	 | --------------------------------------------------------------------
	 |
	 */

	calculateHeaderItemIndex = function() {
		$('#list-header-item').children('div').each( function (index, elmnt) {
			$('input[name ^= e_header_text]', elmnt).attr('name', 'e_header_text[' + index + ']');
			$('input[name ^= e_header_row]', elmnt).attr('name', 'e_header_row[' + index + ']');
			$('input[name ^= e_header_col]', elmnt).attr('name', 'e_header_col[' + index + ']');
		});
	}

	calculatePivotItemIndex_1 = function() {
		$('#list-pivot-1-item').children('div').each( function (index, elmnt) {
			$('input[name ^= e_field_one]', elmnt).attr('name', 'e_field_one[' + index + ']');
		});	
	}

	calculatePivotItemIndex_2 = function() {
		$('#list-pivot-2-item').children('div').each( function (index, elmnt) {
			$('input[name ^= e_field_two]', elmnt).attr('name', 'e_field_two[' + index + ']');
		});	
	}

	calculateFilterItemIndex = function() {
		$('#list-filter-item').children('div').each( function (index, elmnt) {
			$('input[name ^= e_filter_text]', elmnt).attr('name', 'e_filter_text[' + index + ']');
			$('input[name ^= e_filter_base]', elmnt).attr('name', 'e_filter_base[' + index + ']');
			$('input[name ^= e_filter_inc]', elmnt).attr('name', 'e_filter_inc[' + index + ']');
		});
	}

	calculateGroupingItemIndex = function() {
		$('#list-group-item').children('div').each( function (index, elmnt) {
			$('input[name ^= e_group_text]', elmnt).attr('name', 'e_group_text[' + index + ']');
			$('input[name ^= e_group_base]', elmnt).attr('name', 'e_group_base[' + index + ']');
			$('input[name ^= e_group_inc]', elmnt).attr('name', 'e_group_inc[' + index + ']');
		});
	}



	/**
	 | --------------------------------------------------------------------
	 | List of All Function
	 | --------------------------------------------------------------------
	 |
	 */

	function loadFilterOrGrouping(action, id)
	{
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

			if (res_status == 'success') showReportFilterGrouping(result['message'], result['title']);

			if (res_status == 'error') showErrorMessage(result['message']);

		})
		.always(function() {
			$('#preloader').hide();
		});
	}

	function loadDataSorceField(selectbox)
	{
		var selectbox = $(selectbox);
			action = './action/getdatasourcefield.php',
			query = 'id=' + selectbox.val();

		$.post(action, query, function (result) {

			if (result['status'] == 'success') {
				
				var content = '';
				$.each(result['fields'], function (index, field) {
					content += '<div>' + field + '</div>';
				});

				selectbox.siblings('.datasource-field').html(content);
			} else {
				selectbox.siblings('.datasource-field').html('');
			}

		}, 'json');
	}

	function bindShowXmlField(elmtContainer, onlyLast)
	{
		onlyLast = typeof onlyLast !== 'undefined' ? onlyLast : false;
		elmtContainer = onlyLast ? $('#' + elmtContainer).find('.show-xml-field:last') : $('.show-xml-field'),

		elmtContainer.on('click', function () {
			var dsName = $('select[name=' + $(this).attr('ds-name') + ']');
			
			// show massage box field
			if (parseInt(dsName.val()) != 0) {
				var datasourceFields = dsName.siblings('.datasource-field'),
					datasourceName = dsName.find('option[value=' + dsName.val() + ']').text(),
					content = '';

				if ($(datasourceFields).size() > 0 ) {
					content = 'Daftar Field yang ada di datasource <span class="text-danger">' + datasourceName + '</span> : ';

					content += '<ul class="padding15">';
					$(datasourceFields).children('div').each(function (index, field) {
						content += '<li class="field-name" style="cursor: pointer;">' + $(field).text() + '</li>';
					});
					content += '</ul>';

					showDatasourceField(content, this);
					return;
				}

				content = 'Datasource kosong.<br>Tidak ditemukan record di dalam datasource.';
				showDatasourceField(content);
				return;
			}

			// if datasource not selected
			content = 'Belum ada datasource yang dipilih.<br>Silahkan pilih datasource terlebih dahulu.';
			showDatasourceField(content);
		});
	}

	function showDatasourceField(content, showFieldTrigger)
	{
		showFieldTrigger = typeof showFieldTrigger !== 'undefined' ? showFieldTrigger : 0;

		$('#modal-field .modal-body').html(content);
		$('#modal-field').on('show.bs.modal', function () {
			$('.modal-body', this).css('max-height', $(window).height() * 0.8);
			$('.modal-title').html('Daftar Field');
		});

		$('#modal-field').modal('show');
		$('#modal-field .field-name').on('click', function () {
			addFieldToInput($(this).text(), showFieldTrigger);
		});

		return false;		
	}

	function addFieldToInput(field, showFieldTrigger)
	{
		var prefix = $(showFieldTrigger).attr('ds-prefix');

		prefix = typeof prefix !== 'undefined' ? prefix : '';
		$(showFieldTrigger).siblings('input').val(prefix + '::' + field);
		$('#modal-field').modal('hide');
	}

	function showReportModal(content, report)
	{
		$('#modal-datasource .modal-body').html(content);
		$('#modal-datasource').on('show.bs.modal', function () {
			$('.modal-body', this).css('max-height', $( window ).height()*0.8);
			$('.modal-title').html('Laporan : ' + report);
		});

		$('#modal-datasource').modal('show');
	}

	function showReportFilterGrouping(content, title)
	{
		$('#modal-datasource .modal-body').html(content);
		$('#modal-datasource').on('show.bs.modal', function () {
			$('.modal-title').html(title);
		});

		$('#modal-datasource .btn-delete-filter-grouping').on('click', function() {
			$('#modal-datasource').modal('hide');
			bindTriggerDeleteFilterOrGrouping(this);

			return false;
		});

		$('#modal-datasource').modal('show');
	}

	function bindTriggerDeleteFilterOrGrouping(elmnt)
	{
		var action = $(elmnt).attr('href');

		$.ajax({
			url: action,
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
	}

	function loadReportPivot()
	{
		var id = $('#form-pivot input[name=e_id]').val(),
			action = './action/showpivot.php';

		$.post(action, {id: id}, function (result) {
			if (result['status'] == 'success') {
				generatePivot(result['pivot']);
			}
		}, 'json');
	}

	function generatePivot(pivot)
	{
		$('select[name=e_pivot_datasource_1]').val(pivot['query'][1]);
		loadDataSorceField('select[name=e_pivot_datasource_1]');

		if (pivot['type'] == 2 || pivot['type'] == 3) {
			$('select[name=e_pivot_datasource_2]').val(pivot['query'][2]).removeAttr('disabled');
			$('input[name=e_pivot_2]').attr('checked', 'checked');
			loadDataSorceField('select[name=e_pivot_datasource_2]');

			$('input[name=e_pivot_syarat_1]').val(pivot['rule'][0]);
			$('#list-pivot-1-item .show-xml-field').attr('ds-prefix', 'data1');
		}

		if (pivot['type'] == 3) {
			$('select[name=e_pivot_datasource_3]').val(pivot['query'][3]).removeAttr('disabled');
			$('input[name=e_pivot_3]').attr('checked', 'checked');
			loadDataSorceField('select[name=e_pivot_datasource_3]');

			$('input[name=e_pivot_syarat_2]').val(pivot['rule'][1]);
		}

		if (pivot['type'] == 2) {
			$('#list-pivot-2-item .show-xml-field').attr('ds-prefix', 'data2');
		}

		if (pivot['type'] == 3) {
			$('#list-pivot-2-item .show-xml-field').attr('ds-prefix', 'data3');
			$('#list-pivot-2-item .show-xml-field').attr('ds-name', 'e_pivot_datasource_3');
		}

		var targetElement = $('#list-pivot-1-item'),
			cloneElement = $('#pivot-1-item');
		$.each(pivot['data'][0], function (index, data) {
			if (index >= 1) {
				tmp = '<div>' + cloneElement.html() + '</div>';
				targetElement.append(tmp);
				bindShowXmlField('list-pivot-1-item', true);
			}
			targetElement.children('div:eq(' + index + ')').find('input[name^=e_field_one]').val(data);
		});
		calculatePivotItemIndex_1();

		if (pivot['type'] >= 2) {
			targetElement = $('#list-pivot-2-item'),
			cloneElement = $('#pivot-2-item');
			$.each(pivot['data'][1], function (index, data) {
				if (index >= 1) {
					tmp = '<div>' + cloneElement.html() + '</div>';
					targetElement.append(tmp);
					bindShowXmlField('list-pivot-2-item', true);
				}
				targetElement.children('div:eq(' + index + ')').find('input[name^=e_field_two]').val(data);
			});
			calculatePivotItemIndex_2();
		}
		
	}

	function ajaxProcess(form) {
		$.ajax({
			url: $(form).attr('action'),
			method: $(form).attr('method'),
			data: $(form).serialize(),
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
	}

});