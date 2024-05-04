
<div class="form-group mt10">
	<label class="control-label col-sm-3 hidden-xs">Nama Grouping</label>
	<div class="col-sm-9">
		<input type="text" class="form-control required primary" name="e_group_nama" placeholder="Nama Grouping">
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-3 hidden-xs">Jenis Grouping</label>
	<div class="col-sm-6">
		<select name="e_group_jenis" class="form-control required primary">
			<option value="baris">GROUPING BARIS</option>
			<option value="kolom">GROUPING KOLOM</option>
		</select>
	</div>
</div>

<div class="form-group mt10">
	<label class="control-label col-sm-3 hidden-xs">Nama Header Grouping</label>
	<div class="col-sm-9">
		<input type="text" class="form-control" name="e_group_header_title" placeholder="Nama Header Grouping">

		<span class="help-block">
			Nama Header Grouping diisi apabila melakukan <span class="text-danger">Grouping Baris</span>.
		</span>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-3 hidden-xs">Jenis Interval</label>
	<div class="col-sm-6">
		<select name="e_group_interval" class="form-control required primary">
			<option value="interval">INTERVAL</option>
			<option value="range">RANGE</option>
		</select>

		<span class="help-block">
			<span class="text-danger">Interval</span> : Grouping bekerja untuk Kolom A, Kolom B, dst.<br>
			<span class="text-danger">Range</span> : Grouping bekerja untuk Kolom A-B, Kolom B-C, dst.
		</span>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-3 hidden-xs">Kolom Perhitungan</label>
	<div class="col-sm-6">
		<input type="text" name="e_group_kolom_pertama" class="form-control required primary" placeholder="Indeks Kolom">
		<span class="help-block">Kolom pertama yang akan di-grouping</span>
	</div>
</div>

<div class="form-group">
	<div class="col-sm-9 col-sm-offset-3">
		Untuk membuat list grouping secara otomatis dari field datasource, maka pilih <span class="text-danger">datasource</span> kemudian pilih <span class="text-danger">field</span> yang akan dijadikan grouping laporan.<br>
		<span class="text-danger">Base</span> adalah indeks kolom/baris pertama yang akan di-grouping.<br>
		<span class="text-danger">Inc</span> adalah banyaknya kolom/baris yang akan di-grouping.
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-3 hidden-xs">Datasource</label>
	<div class="col-sm-6">
		<select name="e_group_datasource" class="form-control">
			
			<?php echo $select_datasources; ?>

		</select>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-3 hidden-xs">Field Datasource</label>
	<div class="col-sm-6">
		<select name="e_group_field" class="form-control">
			<option value="0">PILIH DATASOURCE</option>
		</select>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-3 hidden-xs">Kalkulasi Interval</label>
	<div class="col-sm-2 col-xs-6 mb5 mt5"><input type="text" name="e_group_base_auto" class="form-control" placeholder="Base"></div>
	<div class="col-sm-2 col-xs-6 mb5 mt5"><input type="text" name="e_group_inc_auto" class="form-control" placeholder="Inc"></div>
</div>

<div class="form-group">
	<div class="col-sm-6 col-sm-offset-3">
		<button class="btn btn-primary btn-generate-grouping tooltips" data-toggle="tooltip" title="Generete field filter otomatis">Generate</button>
		<button class="btn btn-primary tooltips" data-toogle="tooltip" dynamic-type="add" dynamic-source="group-item" dynamic-target="list-group-item" dynamic-callback="calculateGroupingItemIndex" title="Tambah field header" type="button"><i class="fa fa-plus"></i></button>
		<button class="btn btn-danger tooltips" data-toogle="tooltip" dynamic-type="del" dynamic-target="list-group-item" title="Hilangkan field header" type="button"><i class="fa fa-minus"></i></button>
	</div>
</div>

<div class="form-group" id="list-group-item">
	<div id="group-item">
		<div class="col-sm-5 col-xs-12 col-sm-offset-3 mb5 mt5"><input type="text" name="e_group_text[1]" class="form-control required primary" placeholder="Tuliskan data grouping"></div>
		<div class="col-sm-2 col-xs-6 mb5 mt5"><input type="text" name="e_group_base[1]" class="form-control" placeholder="Mulai"></div>
		<div class="col-sm-2 col-xs-6 mb5 mt5"><input type="text" name="e_group_inc[1]" class="form-control" placeholder="Interval"></div>
	</div>
</div>

<div class="form-group background-white">
	<div class="col-sm-9 col-sm-offset-3 hidden-xs">
		<button class="btn btn-primary" type="submit">Simpan Grouping</button>
		<button class="btn btn-primary btn-load-grouping" type="button">Tampilkan Grouping</button>
		<button class="btn btn-primary btn-load-laporan" type="button">Tampilkan Laporan</button>
	</div>
	<div class="col-sm-9 col-sm-offset-3 visible-xs">
		<button class="btn btn-primary btn-block" type="submit">Simpan Grouping</button>
		<button class="btn btn-primary btn-block btn-load-grouping" type="button">Tampilkan Grouping</button>
		<button class="btn btn-primary btn-block btn-load-laporan" type="button">Tampilkan Laporan</button>
	</div>
</div>