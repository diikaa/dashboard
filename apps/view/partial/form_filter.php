
<div class="form-group mt10">
	<label class="control-label col-sm-3 hidden-xs">Nama Filter</label>
	<div class="col-sm-9">
		<input type="text" class="form-control required primary" name="e_filter_nama" placeholder="Nama Filter">
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-3 hidden-xs">Jenis Filter</label>
	<div class="col-sm-6">
		<select name="e_filter_jenis" class="form-control required primary">
			<option value="baris">FILTER BARIS</option>
			<option value="kolom">FILTER KOLOM</option>
		</select>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-3 hidden-xs">Jenis Interval</label>
	<div class="col-sm-6">
		<select name="e_filter_interval" class="form-control required primary">
			<option value="interval">INTERVAL</option>
			<option value="range">RANGE</option>
		</select>
		<span class="help-block">
			<span class="text-danger">Interval</span> : Filter bekerja untuk Kolom A, Kolom B, dst.<br>
			<span class="text-danger">Range</span> : Filter bekerja untuk Kolom A-B, Kolom B-C, dst.
		</span>
	</div>
</div>

<div class="form-group">
	<div class="col-sm-9 col-sm-offset-3">
		Untuk membuat list filter secara otomatis dari field datasource, maka pilih <span class="text-danger">datasource</span> kemudian pilih <span class="text-danger">field</span> yang akan dijadikan filter laporan.<br>
		<span class="text-danger">Base</span> adalah indeks kolom/baris pertama yang akan di-filter.<br>
		<span class="text-danger">Inc</span> adalah banyaknya kolom/baris yang akan di-filter.
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-3 hidden-xs">Datasource</label>
	<div class="col-sm-6">
		<select name="e_filter_datasource" class="form-control">
			
			<?php echo $select_datasources; ?>

		</select>
		<span class="help-block">Datasource untuk meng-Generate field filter secara otomatis</span>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-3 hidden-xs">Field Datasource</label>
	<div class="col-sm-6">
		<select name="e_filter_field" class="form-control">
			<option value="0">PILIH FIELD DATASOURCE</option>
		</select>
		<span class="help-block">Pilih field yang akan dijadikan filter</span>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-3 hidden-xs">Kalkulasi Interval</label>
	<div class="col-sm-2 col-xs-6 mb5 mt5"><input type="text" name="e_filter_base_auto" class="form-control" name="e_filter_base_auto" placeholder="Base"></div>
	<div class="col-sm-2 col-xs-6 mb5 mt5"><input type="text" name="e_filter_inc_auto" class="form-control" name="e_filter_inc_auto" placeholder="Inc"></div>
</div>

<div class="form-group">
	<div class="col-sm-6 col-sm-offset-3">
		<button class="btn btn-primary btn-generate-filter tooltips" data-toggle="tooltip" title="Generete field filter otomatis">Generate</button>
		<button class="btn btn-primary tooltips" data-toogle="tooltip" dynamic-type="add" dynamic-source="filter-item" dynamic-target="list-filter-item" dynamic-callback="calculateFilterItemIndex" title="Tambah field filter" type="button"><i class="fa fa-plus"></i></button>
		<button class="btn btn-danger tooltips" data-toogle="tooltip" dynamic-type="del" dynamic-target="list-filter-item" title="Hilangkan field filter" type="button"><i class="fa fa-minus"></i></button>
	</div>
</div>

<div class="form-group" id="list-filter-item">
	<div id="filter-item">
		<div class="col-sm-5 col-xs-12 col-sm-offset-3 mb5 mt5"><input name="e_filter_text[1]" type="text" class="form-control required primary" placeholder="Tuliskan data filter"></div>
		<div class="col-sm-2 col-xs-6 mb5 mt5"><input name="e_filter_base[1]" type="text" class="form-control" placeholder="Mulai"></div>
		<div class="col-sm-2 col-xs-6 mb5 mt5"><input name="e_filter_inc[1]" type="text" class="form-control" placeholder="Interval"></div>
	</div>
</div>

<div class="form-group background-white">
	<div class="col-sm-9 col-sm-offset-3 hidden-xs">
		<button class="btn btn-primary" type="submit">Simpan Filter</button>
		<button class="btn btn-primary btn-load-filter" type="button">Tampilkan Filter</button>
		<button class="btn btn-primary btn-load-laporan" type="button">Tampilkan Laporan</button>
	</div>
	<div class="col-sm-9 col-sm-offset-3 visible-xs">
		<button class="btn btn-primary btn-block" type="submit">Simpan Filter</button>
		<button class="btn btn-primary btn-block btn-load-filter" type="button">Tampilkan Filter</button>
		<button class="btn btn-primary btn-block btn-load-laporan" type="button">Tampilkan Laporan</button>
	</div>
</div>