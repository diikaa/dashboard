
<div class="form-group mt10">
	<label class="control-label col-sm-3 hidden-xs">Datasource</label>
	<div class="col-sm-6">
		<div class="input-group">
			<span class="input-group-addon">
				<input type="checkbox" value="1" name="e_header_dinamis" class="toggle-active-datasource">
			</span>
			<select class="form-control datasource-selected" name="e_header_datasource" disabled="disabled">

				<?php echo $select_datasources; ?>				
				
			</select>
			<div class="hidden datasource-field" id="header-ds-field"></div>
		</div>
		<span class="help-block">Pilih checkbox dan datasource untuk membuat header dinamis</span>
	</div>
</div>

<div class="form-group">
	<label class="control-label col-sm-3 hidden-xs">Index Baris Header</label>
	<div class="col-sm-3">
		<input type="text" class="form-control required primary" name="e_header_idx_baris" placeholder="Index Baris">
	</div>
</div>

<div class="form-group">
	<div class="col-sm-6 col-sm-offset-3">
		<button class="btn btn-primary tooltips" data-toogle="tooltip" dynamic-type="add" dynamic-source="header-item" dynamic-target="list-header-item" dynamic-callback="calculateHeaderItemIndex" title="Tambah field header" type="button"><i class="fa fa-plus"></i></button>
		<button class="btn btn-danger tooltips" data-toogle="tooltip" dynamic-type="del" dynamic-target="list-header-item" title="Hapus field header" type="button"><i class="fa fa-minus"></i></button>

		<span class="help-block mt10">
			Tuliskan field datasource yang akan dijadikan header dinamis.<br>
			Format penulisan : <span class="text-danger">[ ::nama_field ]</span>.
		</span>
	</div>
</div>

<div class="form-group" id="list-header-item">
	<div id="header-item">
		<div class="col-sm-5 col-xs-12 col-sm-offset-3 mb5 mt5">
			<div class="input-group">
				<span class="input-group-addon show-xml-field" ds-name="e_header_datasource" style="cursor: pointer;"><i class="glyphicon glyphicon-list"></i></span>
				<input type="text" name="e_header_text[1]" class="form-control required primary" placeholder="Tuliskan header">
			</div>
		</div>
		<div class="col-sm-2 col-xs-6 mb5 mt5"><input type="text" name="e_header_row[1]" class="form-control" placeholder="Baris"></div>
		<div class="col-sm-2 col-xs-6 mb5 mt5"><input type="text" name="e_header_col[1]" class="form-control" placeholder="Kolom"></div>
	</div>
</div>

<div class="form-group background-white">
	<div class="col-sm-9 col-sm-offset-3 hidden-xs">
		<button class="btn btn-primary" type="submit">Simpan Header</button>
		<button class="btn btn-primary btn-tampilkan-header" type="button">Tampilkan Header</button>
		<button class="btn btn-danger btn-delete-header" type="button">Hapus Header</button>
	</div>
	<div class="col-sm-9 col-sm-offset-3 visible-xs">
		<button class="btn btn-primary btn-block" type="submit">Simpan Header</button>
		<button class="btn btn-primary btn-block btn-tampilkan-header" type="button">Tampilkan Header</button>
		<button class="btn btn-danger btn-block btn-delete-header" type="button">Hapus Header</button>
	</div>
</div>