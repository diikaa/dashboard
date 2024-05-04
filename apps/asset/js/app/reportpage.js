$(document).ready(function () {

	// global parameter for table
	var fColumn, fRow,
		gColumnSetting, gRowSetting, graphSetting,
		fSelected,
        reportId;

	// inital state
	initialFilterState();
    initialGroupingState();

    $('.contentpanel').hide();

	// apply select2 style
    $('#report-list').select2({
        width: '100%'
    });

    // scrollbar
    $('.scrollbar-inner').scrollbar();

    // apply equal height
    $('.kontrol-laporan').each(function() {
    	$(this).find('.same-column-height').matchHeight({
    		property: 'max-height',
    		target: $(this).find('.target-column-height')
    	});
    });

    // action when select report name
    var $eventSelect = $('#report-list');
    $eventSelect.on('select2-selecting', function (e) {
    	if (e.val != 0) {
    		loadReport(e);
    	}   	
    });

    // action show filter list item
    $('#list-filter-laporan select').on('change', function () {

    	$.ajax({
			url: './action/reportpage.loadfilter.php',
			method: 'POST',
			data: 'e_filter=' + $(this).val(),
			dataType: 'json',
			cache: false,
			beforeSend: function() {
				$('#preloader').show();
			}
		})
		.done(function(result) {
			var res_status = result['status'];

			if (res_status == 'success') {
				setFilterList(result['message']);
			}

			if (res_status == 'error') showErrorMessage(result['message']);

		})
		.always(function() {
			$('#preloader').hide();
		});
    });

    // show graph
    $('.btn-graph-show').on('click', function () {
        var headSize = $('#table-report').is(':visible') ? $('#table-report thead tr').size() : $('#table-grouping thead tr').size(),
            form = $('#form-graph');

        if (headSize == 1) {
            setGraphSetting(form);
            buildChart();
        } else {
            var msg = "Grafik tidak dapat ditampilkan karena tabel laporan memiliki header dari 1 baris.<br>Silahkan grouping laporan untuk menampilkan grafik.";
            showErrorMessage(msg);
        }

        return false;
    });

    // show remote link
    $('.btn-remote-link').on('click', function () {
        var gRow = gRowSetting == '' ? '' : gRowSetting.id,
            gCol = gColumnSetting == '' ? '' : gColumnSetting.id,
            gRemote = gRow != '' && gCol != '' ? gRow + '_' + gCol : gRow + gCol,
            gRemote = gRemote == '' ? '' : '&grouping=' + gRemote;
            fRemote = fSelected.join('_');
            fRemote = fRemote == '' ? '' : '&filter=' + fRemote,
            type = typeof graphSetting['type'] == 'undefined' ? '&type=table' : '&type=' + graphSetting['type'],
            xxx = typeof graphSetting['orientation'] == 'undefined' ? '' : '&orientation=' + graphSetting['orientation'],
            hrow = typeof graphSetting['headerRow'] == 'undefined' ? '' : '&hrow=' + graphSetting['headerRow'],
            hcol = typeof graphSetting['headerColumn'] == 'undefined' ? '' : '&hcol=' + graphSetting['headerColumn'],
            id = 'id=' + reportId,
            baseUrl = $('#base_url').text(),
            link = baseUrl + 'remote.php?' + id + '&auto=1&time=3' + type + xxx + hrow + hcol + fRemote + gRemote,
            msg = 'Remote link untuk menampilkan laporan : <br><a target="_blank" href="' + link + '">' + link + '</a>';

        console.log(xxx);
        console.log(graphSetting['orientation']);
        showSuccessMessage(msg);

        return false;
    });

    // reset grouping
    $('#grouping-reset').on('click', function() {
        $('#table-grouping').remove();
        $('#table-report').show();
        gColumnSetting = '';
        gRowSetting = '';
        $(this).closest('.panel-body').find('li').removeClass('selected-list');
    });


    function loadReport(selected_report)
    {
        $('.contentpanel').hide();
        reportId = selected_report.val;

    	$.ajax({
			url: './action/reportpage.loadreport.php',
			method: 'POST',
			data: 'e_id=' + selected_report.val,
			dataType: 'json',
			cache: false,
			beforeSend: function() {
				$('#preloader').show();
			}
		})
		.done(function(result) {
			var res_status = result['status'];

            $('#panel-chart-laporan').hide();
			if (res_status == 'success') {
                $('#panel-tabel-laporan .panel-title').html( 'Report: ' + result['report'] );
				$('#panel-tabel-laporan .panel-heading p').html( 'Last Update On : ' + result['last_update'] );
				$('#panel-tabel-laporan .panel-body').html( result['message'] );
				initialReportSetting();
                initialGraphSetting();
				setFilter( result['filter'] );
				setGrouping( result['grouping'] );
                $('.contentpanel').show();
			}

			if (res_status == 'error') showErrorMessage(result['message']);

		})
		.always(function() {
			$('#preloader').hide();
		});
    }

    function setFilter(filter)
    {
    	var status = filter['status'];

    	if (status == 0) {
    		initialFilterState();
    		return;
    	}

    	var filters_item = '<option value="0">Pilih Filter</option>';
    	$.each( filter['filters'], function( index, value) {
    		filters_item += '<option value="' + index + '">' + value + '</option>';
    	});

    	$('#filter-kosong').hide();
		$('#list-filter-laporan select').html( filters_item );
		$('#list-filter-laporan').show();
		$('#list-filter-item-container').html('').hide();
    }

    function setGrouping(grouping)
    {
    	var status = grouping['status'];

    	if (status == 0) {
    		initialGroupingState();
    		return;
    	}

    	var groupings_item = '';
    	$.each( grouping['groupings'], function ( index, value) {
    		groupings_item += '<a href="' + index + '"><li><h4 class="sender">' + value + '</h4></li></a>';
    	});

    	$('#grouping-kosong').hide();
		$('#list-grouping-laporan').html( groupings_item ).show();
        $('#grouping-reset').show();
		bindGroupingTrigger();
    }

    function setFilterList(filters)
    {
		var filter_list = '',
			filter_type = filters['type'],
			filter_interval = filters['interval'],
			tmp = '';

		$.each( filters['item'], function (index, filter) {
			tmp = 'filter-type = "' + filter_type + '" ';
			tmp += 'filter-interval = "' + filter_interval + '" ';
			tmp += 'filter-inc = "' + filter['inc'] + '" ';
			tmp += 'filter-base = "' + filter['base'] + '" ';
			tmp += 'id = "' + filters['id'] + '-' + index + '" '
			filter_list += '<a href="#" '+ tmp +'><li><h4 class="sender">' + filter['text'] + '</h4></li></a>'
		});

		$('#list-filter-item-container').html( filter_list ).show();

		$.each(fSelected, function (index, value) {
			$('a#' + value).children('li').addClass('selected-list');
		});

		bindFilterTrigger();	
    }

    function bindFilterTrigger()
    {
    	$('#list-filter-item-container a').on('click', function () {
    		var status = $(this).children('li').hasClass('selected-list'),
    			f_type = $(this).attr('filter-type'),
    			f_interval = $(this).attr('filter-interval'),
    			f_base = parseInt($(this).attr('filter-base')),
    			f_inc = parseInt($(this).attr('filter-inc')),
    			f_id = $(this).attr('id');

    		if (status) {
    			// show filtered element
    			(f_type == 'baris') ? toggleFilterRowTable(f_interval, f_base, f_inc, false) : toggleFilterColumnTable(f_interval, f_base, f_inc, false);
    			$(this).children('li').removeClass('selected-list');
    			fSelected.splice(fSelected.indexOf(f_id), 1);
    		} else {
    			// hide filtered element
    			(f_type == 'baris') ? toggleFilterRowTable(f_interval, f_base, f_inc, true) : toggleFilterColumnTable(f_interval, f_base, f_inc, true);
    			$(this).children('li').addClass('selected-list');
    			fSelected.push(f_id);
    		}

    		// automatic run grouping table
    		groupingTable();

    		return false;
    	});
    }

	function bindGroupingTrigger()
    {
    	$('#list-grouping-laporan a').on('click', function () {
    		var status = $(this).children('li').hasClass('selected-list');

    		if (status) {
    			$(this).children('li').removeClass('selected-list');
    		} else {
    			$(this).closest('ul').find('li').removeClass('selected-list');
    			$(this).children('li').addClass('selected-list');
    		}

    		$.ajax({
				url: './action/reportpage.loadgrouping.php',
				method: 'POST',
				data: 'e_grouping=' + $(this).attr('href'),
				dataType: 'json',
				cache: false,
				beforeSend: function() {
					$('#preloader').show();
				}
			})
			.done(function(result) {
				var res_status = result['status'];

				if (res_status == 'success') {
					var groups = result['message'],
						g_type = groups['type'];

					if (g_type == 'kolom') gColumnSetting = groups;
 					if (g_type == 'baris') gRowSetting = groups;
					groupingTable();
				}

				if (res_status == 'error') showErrorMessage(result['message']);

			})
			.always(function() {
				$('#preloader').hide();
			});

    		return false;
    	});
    }

    function toggleFilterRowTable(f_interval, f_base, f_inc, hidden)
    {
    	var table = $('#panel-tabel-laporan').find('#table-report'),
    		row_size = fRow.length,
    		loop_limit = (f_interval == 'range') ? f_inc : column_size,
    		f_inc = (f_interval == 'range') ? 1 : f_inc;

    	for (var i = f_base; i <= loop_limit; i += f_inc) {
    		if (i <= row_size) {
    			if (hidden) {
    				if (fRow[i] != 0) $('tbody tr:eq(' + (i - 1) + ')', table).hide();
    				fRow[i] = 0;
    			} else {
    				$('tbody tr:eq(' + (i - 1) + ')', table).show();
    				fRow[i] = 1
    			}
    		}
    	}
    }

    function toggleFilterColumnTable(f_interval, f_base, f_inc, hidden)
    {
    	var table = $('#panel-tabel-laporan').find('#table-report'),
    		column_size = fColumn.length,
    		loop_limit = (f_interval == 'range') ? f_inc : column_size,
    		f_inc = (f_interval == 'range') ? 1 : f_inc;

    	for (var i = f_base; i <= loop_limit; i += f_inc) {
    		if (i <= column_size) {
    			if (hidden) {
    				if (fColumn[i] != 0) table.hideColumns([i]);
    				fColumn[i] = 0;
    			} else {
    				table.showColumns([i]);
    				fColumn[i] = 1;
    			}
    		}
    	}
    }

    function groupingTable()
    {
    	var head = '',
    		body = '';
 
        $('#table-report').show();
 		if (gColumnSetting != '') {
 			head = getGroupingColumnHead();
 			body = getGroupingColumnBody();
 			renderGroupingTable(head, body, false);
 		}
        if (gRowSetting != '') {
            head = getGroupingRowHead();
            body = getGroupingRowBody();
            renderGroupingTable(head, body, true);
        }
    }

    function getGroupingColumnHead()
    {
    	var head = [];

    	$('#table-report thead tr:eq(0) th:lt(' + (gColumnSetting['firstColumn'] - 1) + ')').each(function ( index, th ) {
    		if ( $(th).is(':visible') ) head.push( $(th).text() );
    	});

    	$.each( gColumnSetting['item'], function (index, group) {
    		head.push( group['text'] );
    	});

    	return head;
    }

    function getGroupingRowHead()
    {
        var table = $('#table-report').is(':visible') ? $('#table-report') : $('#table-grouping'),
            head = $('thead', table).html();

        return head;
    }

    function getGroupingColumnBody()
    {
    	var records = [];

    	$('#table-report tbody tr:visible').each( function (idx_tr, tr) {
    		var temp = [];

    		$('td:lt(' + (gColumnSetting['firstColumn'] - 1) + ')', tr).each(function (idx_td, td) {
    			temp.push( $(td).text() );
    		});

    		$.each( gColumnSetting['item'], function (index, group) {
    			var loop_limit = (gColumnSetting['interval'] == 'range') ? group['inc'] : fColumn.length,
    				g_inc = (gColumnSetting['interval'] == 'range') ? 1 : group['inc'],
    				group_val = 0;

    			for (var i = group['base']; i <= loop_limit; i += g_inc) {
    				var temp_td = $('td:eq(' + (i - 1) + ')', tr);
    				
    				if (temp_td.is(':visible')) {
    					group_val += parseFloat(temp_td.text());
    				}
    			}
    			
    			temp.push(group_val);
    		});

    		records.push(temp);
    	});

    	return records;
    }

    function getGroupingRowBody()
    {
        var table = $('#table-report').is(':visible') ? $('#table-report') : $('#table-grouping'),
            max_row = 0,
            zero_counter = 0,
            has_rowspan = false,
            rowspan = 0,
            blank_column = gRowSetting['firstColumn'] - 2,
            records = [];

        $('thead tr', table).each( function (tr_idx, tr) {
            var col_counter = $('th:visible:gt(' + (gRowSetting['firstColumn'] - 2) + ')', tr).length;

            $('th:lt(' + (gRowSetting['firstColumn'] - 1) + ')', tr).each( function (index, th) {
                rowspan = $(th).attr('rowspan') == '' ? 0 : parseInt($(th).attr('rowspan'));
                has_rowspan = rowspan > 0 ? true : has_rowspan;
            });

            max_row = (max_row < col_counter) ? col_counter : max_row;
            if (tr_idx > 0 && has_rowspan) max_row += 1;
        });

        $.each( gRowSetting['item'], function (index, group) {
            var loop_limit = (gRowSetting['interval'] == 'range') ? group['inc'] : fRow.length,
                g_inc = (gRowSetting['interval'] == 'range') ? 1 : group['inc'],
                temp = [];

            temp.push(group['text']);
            for (var i = 0; i < max_row + blank_column; i++) {
                temp.push(0);
            }

            for (var i = group['base']; i <= loop_limit; i += g_inc) {
                var tr = $('tbody tr:eq(' +  (i - 1 - zero_counter) + ')', table),
                    tr_original = $('#table-report tbody tr:eq(' +  (i - 1) + ')')
                    tmp_idx = blank_column + 1;

                // check from table-report data
                if ( tr_original.css('display') != 'none' ) {
                    if (tr.is(':visible')) {
                        $('td:gt(' + (gRowSetting['firstColumn'] - 2) + ')', tr).each(function (idx_td, td) {
                            if ($(td).is(':visible')) {
                                temp[tmp_idx] += parseFloat($(td).text());
                                tmp_idx++;
                            }
                        });
                    }
                } else {
                    // check if the column already in group or not
                    zero_counter = $('#table-report').is(':visible') ? 0 : zero_counter + 1;
                }
            }

            records.push(temp);
        });

        return records;
    }

    function renderGroupingTable(head, body, isHeadText)
    {
    	var thead = '',
    		tbody = '';

    	// print head
    	if (isHeadText) {
    		thead = '<thead>' + head + '</thead>';
    	} else {
    		$.each(head, function (index, value) {
    			thead += '<th>' + value + '</th>'
    		});
    		thead = '<thead><tr>' + thead + '</tr></thead>'
    	}

    	// print body
    	$.each(body, function (row_idx, row) {
    		tbody += '<tr>';

    		$.each(row, function(col_idx, col) {
    			tbody += '<td>' + col + '</td>';
    		});

    		tbody += '</tr>';
    	});

    	tbody = '<tbody>' + tbody + '</tbody>';

    	// add table gouping to panel
    	$('#table-grouping').remove();
    	tbody = '<table id="table-grouping" class="table table-primary table-bordered table-striped table-hover">' + thead + tbody + '</table>';
    	$('#panel-tabel-laporan .panel-body').append(tbody);
    	$('#table-report').hide();

        if (isHeadText) {
            $('#table-grouping thead th:eq(0)').html(gRowSetting['columnName']);
            for (var i = 2; i < gRowSetting['firstColumn']; i++) {
                $('#table-grouping').hideColumns(i);
            }
        }
    }

    function buildChart()
    {
        var options = setChartOption();

        if (graphSetting['type'] == 'pie') {
            options.series = [];
            options.series[0] = (graphSetting['orientation'] == 'baris') ? getPieDataSeriesRowOrientation() : getPieDataSeriesColumnOrientation();
        } else {
            options.xAxis.categories = (graphSetting['orientation'] == 'baris') ? getCategoriesRowOrientation() : getCategoriesColumnOrientation();
            options.series = (graphSetting['orientation'] == 'baris') ? getChartDataSeriesRowOrientation() : getChartDataSeriesColumnOrientation();
        }

        $('#panel-chart-laporan .panel-body').html('');
        $('#panel-chart-laporan').slideDown(400, function () {
             var chart = new Highcharts.Chart(options);
        });
    }

    function getCategoriesRowOrientation()
    {
        var categories = [],
            table = $('#table-report').is(':visible') ? $('#table-report') : $('#table-grouping');

        $('thead tr:eq(0) th:gt(' + (graphSetting['headerRow'] - 1) + ')', table).each(function (index, column) {
            if ($(column).is(':visible')) categories.push($(column).text());
        });
        
        return categories;
    }

    function getCategoriesColumnOrientation()
    {
        var categories = [],
            table = $('#table-report').is(':visible') ? $('#table-report') : $('#table-grouping');

        $('tbody tr:visible', table).each(function (row_index, row) {
            $('td:eq(' + graphSetting['headerColumn'] + ')', row).each(function (column_index, column) {
                if ($(column).is(':visible')) categories.push($(column).text());
            });
        })
            
        return categories;
    }

    function getChartDataSeriesRowOrientation()
    {
        var table = $('#table-report').is(':visible') ? $('#table-report') : $('#table-grouping'),
            series = [];

        $('tbody tr:visible', table).each(function (row_index, row) {

            var temp = {
                name: $('td:eq(' + graphSetting['headerColumn'] + ')', row).text(),
                data: []
            };
            
            $('td:gt(' + (graphSetting['headerRow'] - 1) + ')', row).each(function (column_index, column) {
                if ($(column).is(':visible')) temp.data.push(parseFloat($(column).text()));
            });

            series.push(temp);
        });
        
        return series;
    }

    function getChartDataSeriesColumnOrientation()
    {
        var table = $('#table-report').is(':visible') ? $('#table-report') : $('#table-grouping'),
            series = [];

        $('thead tr:eq(0) th:gt(' + (graphSetting['headerRow'] - 1) + ')', table).each(function (index, col) {
            var temp = {
                name: $(col).text(),
                data: []
            };

            series.push(temp);
        });

        $('tbody tr:visible', table).each(function (row_index, row) {
            var i = 0;
            $('td:gt(' + (graphSetting['headerRow'] - 1) + ')', row).each(function (column_index, column) {
                if ($(column).is(':visible')) series[i].data.push(parseFloat($(column).text()));
                i++;
            });

        });
        
        return series;
    }

    function getPieDataSeriesRowOrientation()
    {
        var table = $('#table-report').is(':visible') ? $('#table-report') : $('#table-grouping'),
            series = {
                name: 'Jumlah',
                data: []
            };

        $('tbody tr:visible', table).each(function (row_index, row) {

            var temp = {
                name: $('td:eq(' + graphSetting['headerColumn'] + ')', row).text(),
                y: 0
            };
            
            $('td:gt(' + (graphSetting['headerRow'] - 1) + ')', row).each(function (column_index, column) {
                if ($(column).is(':visible')) temp.y += (parseFloat($(column).text()));
            });

            series.data.push(temp);
        });
        
        return series;
    }

    function getPieDataSeriesColumnOrientation()
    {
        var table = $('#table-report').is(':visible') ? $('#table-report') : $('#table-grouping'),
            series = {
                name: 'Jumlah',
                data: []
            };

        $('thead tr:eq(0) th:gt(' + (graphSetting['headerRow'] - 1) + ')', table).each(function (index, col) {
            var temp = {
                    name: $(col).text(),
                    y: 0
                };

            series.data.push(temp);
        });

        $('tbody tr:visible', table).each(function (row_index, row) {
            var i = 0;
            $('td:gt(' + (graphSetting['headerRow'] - 1) + ')', row).each(function (column_index, column) {
                if ($(column).is(':visible')) series.data[i].y += parseFloat($(column).text());
                i++;
            });

        });
        
        return series;
    }

    function setChartOption() {
        var options = {
            chart: {
              renderTo: 'container-chart',
              type: graphSetting['type'],
              height: graphSetting['height'],
              width: graphSetting['width']
            },
            title: {
              text: graphSetting['title']
            },
            subtitle: {
                text: graphSetting['subtitle']
            },
            xAxis: {
              title: {
                 text: ''
              },
              labels: {
                 enabled: true,
                 style: {
                   fontSize: '10px'
                 }
              }
            },
            yAxis: {
              title: {
                 text: ''
              }
            },
            lagend: {
                margin: 30
            },
            plotOptions: {
                pie: {
                    dataLabels : {
                        formatter: function() {
                            return '<b>'+ this.point.name +'</b>: '+ this.y;
                        }
                    },
                    tooltip : {
                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                    }
                },
                column: {
                    groupPadding: 0.05
                }
            }
        };

        return options;
    }

    function setGraphSetting(form)
    {
        var default_width = $('#panel-tabel-laporan .panel-body').width();

        default_width = default_width < 500 ? 500 : default_width;

        graphSetting['headerRow'] = ! isValidNumber($('input[name = e_baris]', form).val()) ? 1 : parseInt($('input[name = e_baris]', form).val()) - 1;
        graphSetting['headerColumn'] = ! isValidNumber($('input[name = e_kolom]', form).val()) ? 0 : parseInt($('input[name = e_kolom]', form).val()) - 1;
        graphSetting['width'] = ! isValidNumber($('input[name = e_lebar]', form).val()) ? default_width : parseFloat($('input[name = e_lebar]', form).val()) - 1;
        graphSetting['height'] = ! isValidNumber($('input[name = e_tinggi]', form).val()) ? 500 : parseFloat($('input[name = e_tinggi]', form).val()) - 1;
        graphSetting['orientation'] = $('select[name = e_orientasi]').val();
        graphSetting['type'] = $('select[name = e_tipe]').val();  
    }

    function initialFilterState()
    {
    	$('#filter-kosong').show();
		$('#list-filter-laporan').hide();
		$('#list-filter-item-container').hide();
    }

    function initialGroupingState()
    {
    	$('#grouping-kosong').show();
        $('#list-grouping-laporan').hide();
    	$('#grouping-reset').hide();
    }

    function initialGraphSetting()
    {
        graphSetting = [];
        graphSetting['title'] = 'REPORT: ' + $('#report-list option:selected').text();
        graphSetting['subtitle'] = $('#panel-tabel-laporan .panel-heading p').text();
    }

    function initialReportSetting()
    {
    	var table = $('#panel-tabel-laporan').find('#table-report'),
    		column_size = $('tbody tr:lt(1) td', table).size(),
    		row_size = $('tbody tr', table).size();

    	fColumn = [];
    	fRow = [];
    	fSelected  = [];
    	gColumnSetting = '';
    	gRowSetting = '';

    	for (var i = 0; i <= column_size; i++) {
			fColumn[i] = 1;
		}

		for (i = 0; i <= row_size; i++) {
			fRow[i] = 1;
		}
    }

    function isValidNumber(numberText) {
        var validChar = "1234567890",
            charText;

        if (numberText == '') return false;

        for (var i = 0; i < numberText.length; i++) { 
            charText = numberText.charAt(i); 
            if (validChar.indexOf(charText) == -1) return false;
        }

        if (Number(numberText) <= 0) return false;

        return true;
    }

});
