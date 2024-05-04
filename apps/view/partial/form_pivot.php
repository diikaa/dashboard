
<div class="form-group mt10">
	<label class="control-label col-sm-3 hidden-xs">Datasource 1</label>
	<div class="col-sm-6">
		<select name="e_pivot_datasource_1" class="form-control datasource-selected required primary">
			
			<?php echo $select_datasources; ?>

		</select>
		<div class="hidden datasource-field"></div>
		<span class="help-block">Selanjutnya disebut sebagai <span class="text-danger">data1</span></span>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-3 hidden-xs">Datasource 2</label>
	<div class="col-sm-6">
		<div class="input-group">
			<span class="input-group-addon">
				<input type="checkbox" value="1" name="e_pivot_2" class="toggle-active-datasource">
			</span>
			<select name="e_pivot_datasource_2" class="form-control datasource-selected" disabled="disabled">
					
				<?php echo $select_datasources; ?>

			</select>
			<div class="hidden datasource-field"></div>
		</div>
		<span class="help-block">
			Pilih checkbox dan datasource untuk membuat pivot dengan 2 datasource<br>
			Selanjutnya disebut sebagai <span class="text-danger">data2</span>
		</span>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-3 hidden-xs">Datasource 3</label>
	<div class="col-sm-6">
		<div class="input-group">
			<span class="input-group-addon">
				<input type="checkbox" value="1" name="e_pivot_3" class="toggle-active-datasource">
			</span>
			<select name="e_pivot_datasource_3" class="form-control datasource-selected" disabled="disabled">
				
				<?php echo $select_datasources; ?>

			</select>
			<div class="hidden datasource-field"></div>
		</div>
		<span class="help-block">
			Pilih checkbox dan datasource untuk membuat pivot dengan 3 datasource<br>
			Selanjutnya disebut sebagai <span class="text-danger">data3</span>
		</span>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-3 hidden-xs">Syarat Join Datasource</label>
	<div class="col-sm-9">
		<div class="btn-group">
			<a class="btn btn-primary show-xml-field" ds-name="e_pivot_datasource_1" type="buttton">Data 1</a>
			<a class="btn btn-primary show-xml-field" ds-name="e_pivot_datasource_2" type="buttton">Data 2</a>
			<a class="btn btn-primary show-xml-field" ds-name="e_pivot_datasource_3" type="buttton">Data 3</a>
		</div>
		<input type="text" class="form-control" name="e_pivot_syarat_1" placeholder="Syarat penggabungan pertama">
		<input type="text" class="form-control mt10" name="e_pivot_syarat_2" placeholder="Syarat penggabungan kedua">
		<span class="help-block">
			Tuliskan syarat untuk menggabungkan datasource.<br>
			Format penulisan : <span class="text-danger">[ datasource_index::nama_field=datasource_index::nama_field ]</span>
		</span>
	</div>
</div>

<div class="form-group">
	<div class="col-sm-6 col-sm-offset-3">
		<button class="btn btn-primary tooltips" data-toogle="tooltip" dynamic-type="add" dynamic-source="pivot-1-item" dynamic-target="list-pivot-1-item" dynamic-callback="calculatePivotItemIndex_1" title="Tambah field pivot" type="button"><i class="fa fa-plus"></i></button>
		<button class="btn btn-danger tooltips" data-toogle="tooltip" dynamic-type="del" dynamic-target="list-pivot-1-item" title="Hilangkan field pivot" type="button"><i class="fa fa-minus"></i></button>
		<span class="help-block mt10">
			Tuliskan field datasource 1 yang akan di pivot.<br>
			Format penulisan : <span class="text-danger">[ ::nama_field ]</span> untuk pivot 1 datasource.<br>
			Format penulisan : <span class="text-danger">[ data1::nama_field ]</span> untuk pivot 2 atau 3 datasource.
		</span>
	</div>
</div>

<div class="form-group" id="list-pivot-1-item">
	<div id="pivot-1-item">
		<div class="col-sm-6 col-sm-offset-3 mb5 mt5">
			<div class="input-group">
				<span class="input-group-addon show-xml-field" ds-name="e_pivot_datasource_1" style="cursor: pointer;"><i class="glyphicon glyphicon-list"></i></span>
				<input type="text" class="form-control required primary" name="e_field_one[1]" placeholder="Nama field">
			</div>
		</div>
	</div>
</div>

<div class="form-group">
	<div class="col-sm-6 col-sm-offset-3">
		<button class="btn btn-primary tooltips" data-toogle="tooltip" dynamic-type="add" dynamic-source="pivot-2-item" dynamic-target="list-pivot-2-item" dynamic-callback="calculatePivotItemIndex_2" title="Tambah field pivot" type="button"><i class="fa fa-plus"></i></button>
		<button class="btn btn-danger tooltips" data-toogle="tooltip" dynamic-type="del" dynamic-target="list-pivot-2-item" title="Hilangkan field pivot" type="button"><i class="fa fa-minus"></i></button>
		<span class="help-block mt10">
			Tuliskan field datasource 2 atau datasource 3.<br>
			Format penulisan : <span class="text-danger">[ data2::nama_field ]</span> untuk pivot 2 datasource.<br>
			Format penulisan : <span class="text-danger">[ data3::nama_field ]</span> untuk pivot 3 datasource.
		</span>
	</div>
</div>

<div class="form-group" id="list-pivot-2-item">
	<div id="pivot-2-item">
		<div class="col-sm-6 col-sm-offset-3 mb5 mt5">
			<div class="input-group">
				<span class="input-group-addon show-xml-field" ds-name="e_pivot_datasource_2" style="cursor: pointer;"><i class="glyphicon glyphicon-list"></i></span>
				<input type="text" class="form-control" name="e_field_two[1]" placeholder="Nama field">
			</div>
		</div>
	</div>
</div>

<div class="form-group background-white">
	<div class="col-sm-6 col-sm-offset-3 hidden-xs">
		<button class="btn btn-primary" type="submit">Simpan Pivot</button>
		<button class="btn btn-primary btn-load-laporan" type="button">Tampilkan Laporan</button>
	</div>
	<div class="col-sm-6 col-sm-offset-3 visible-xs">
		<button class="btn btn-primary btn-block" type="submit">Simpan Pivot</button>
		<button class="btn btn-primary btn-block btn-load-laporan" type="button">Tampilkan Laporan</button>
	</div>
</div>