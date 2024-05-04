
$(document).ready(function () {

	var fRow, fColumn,
        gColumnSetting, gRowSetting
        filterList = [];
        graphSetting = [];

	loadReport();
	$('#panel-laporan').outerHeight($(window).height() - 25);


	function loadReport()
	{
		$.ajax({
			url: './action/reportpage.loadreport.php',
			method: 'POST',
			data: 'e_id=' + $('#id-laporan').val(),
			dataType: 'json',
			cache: false,
			beforeSend: function() {
				$('#preloader').show();
			}
		})
		.done(function(result) {
			if (result.status == 'success') {
                showReport(result);
            }
		})
		.always(function() {
			$('#preloader').hide();

			if ($('#auto-refresh').val() == 1) {
				setTimeout(loadReport, parseInt($('#auto-rate').val() * 60000) );
			}
		});
	}

    function loadFilter(filterIndexs, index, maxSize)
    {
        if (index == maxSize) {
            filteringTable();
            groupingTable();
            return;
        }

        $.ajax({
            url: './action/reportpage.loadfilter.php',
            method: 'POST',
            data: 'e_filter=' + filterIndexs[index],
            dataType: 'json',
            cache: false
        })
        .done(function(result) {
            var res_status = result.status;

            if (res_status == 'success') {
                filterList[ result.message.id ] = result.message;
            }

            loadFilter(filterIndexs, index + 1, maxSize);
        });
    }

    function loadGrouping(groupingIndexs, index, maxSize)
    {
        console.log(maxSize);
        if (index == maxSize) {
            groupingProcess();   
            graphRandering(); 
            return;
        }

        $.ajax({
            url: './action/reportpage.loadgrouping.php',
            method: 'POST',
            data: 'e_grouping=' + groupingIndexs[index],
            dataType: 'json',
            cache: false
        })
        .done(function(result) {
            var res_status = result.status;

            if (res_status == 'success') {
                var groups = result.message,
                    g_type = groups.type;

                if (g_type == 'kolom') gColumnSetting = groups;
                if (g_type == 'baris') gRowSetting = groups;
            }

            loadGrouping(groupingIndexs, index + 1, maxSize);
        });
    }

	function showReport(result)
	{
		var filterIndexs = [];

		$('#panel-laporan').html(result['message']);
        $('#panel-laporan').show();
        initialReportSetting();
        graphSetting['title'] = 'Laporan ' + result['report'];
        graphSetting['subtitle'] = 'Update : ' + result['last_update'];

        if ( result.filter.status == 1 ) {
            $.each( result.filter.filters, function( index, value) {
                filterIndexs.push(index);
            });
            loadFilter(filterIndexs, 0, filterIndexs.length);
        } else {
            groupingTable();
        }
	}

    function graphRandering()
    {
        var tipe = $('#tipe-chart').val();

        if (tipe != 'table') {
            setGraphSetting();
            buildChart();
            $('#panel-laporan').hide();
        }
    }

    function initialReportSetting()
    {
        var table = $('#panel-laporan').find('#table-report'),
            column_size = $('tbody tr:lt(1) td', table).size(),
            row_size = $('tbody tr', table).size();

        fColumn = [];
        fRow = [];
        gRowSetting = '';
        gColumnSetting = '';

        for (var i = 0; i <= column_size; i++) {
            fColumn[i] = 1;
        }

        for (i = 0; i <= row_size; i++) {
            fRow[i] = 1;
        }
    }

    function filteringTable()
    {
        var strFilter = $('#filter').val(),
            filter = strFilter == '' ? [] : strFilter.split('_');

        // run filter from filterbag
        $(filter).each(function (index, filterBag) {
            filterBag = filterBag.split('-');
            var f_index = filterBag[0],
                f_item = filterBag[1],
                f_type = filterList[f_index].type,
                f_interval = filterList[f_index].interval,
                f_base = filterList[f_index].item[f_item].base,
                f_inc = filterList[f_index].item[f_item].inc;

            (f_type == 'baris') ? filterRowTable(f_interval, f_base, f_inc) : filterColumnTable(f_interval, f_base, f_inc)
        });
    }

    function filterRowTable(f_interval, f_base, f_inc)
    {
        var table = $('#panel-laporan').find('#table-report'),
            row_size = fRow.length,
            loop_limit = (f_interval == 'range') ? f_inc : column_size,
            f_inc = (f_interval == 'range') ? 1 : f_inc;

        for (var i = f_base; i <= loop_limit; i += f_inc) {
            if (i <= row_size) {
                if (fRow[i] != 0) $('tbody tr:eq(' + (i - 1) + ')', table).hide();
                fRow[i] = 0;
            }
        }
    }

    function filterColumnTable(f_interval, f_base, f_inc)
    {
        var table = $('#panel-laporan').find('#table-report'),
            column_size = fColumn.length,
            loop_limit = (f_interval == 'range') ? f_inc : column_size,
            f_inc = (f_interval == 'range') ? 1 : f_inc;

        for (var i = f_base; i <= loop_limit; i += f_inc) {
            if (i <= column_size) {
                if (fColumn[i] != 0) table.hideColumns([i]);
                fColumn[i] = 0;
            }
        }
    }

    function groupingTable()
    {
        var head = '',
            body = '',
            strGrouping = $('#grouping').val();
            strGrouping =  strGrouping == '' ? [] : strGrouping.split('_'),
            groupingIndexs = [];

         // grab all filter data
        $(strGrouping).each(function (index, groupingId) {
           groupingIndexs.push(groupingId);
        });

        if (groupingIndexs.length > 0) {
            loadGrouping(groupingIndexs, 0, groupingIndexs.length);
        } else {
            graphRandering();
        }
    }

    function groupingProcess()
    {
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
        $('#panel-laporan').append(tbody);
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

        $('#panel-chart').highcharts(options);
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
        console.log(series);
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
        console.log(series);
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

    function setGraphSetting()
    {
        var default_width = $('#panel-chart').width(),
        	default_height = $(window).height() - 25;

        graphSetting['headerRow'] = $('#header-row').val()
        graphSetting['headerColumn'] = $('#header-col').val()
        graphSetting['width'] = default_width;
        graphSetting['height'] = default_height;
        graphSetting['orientation'] = $('#orientasi-chart').val();
        graphSetting['type'] = $('#tipe-chart').val();
    }

});