
<div class="row">
	<div class="alert alert-info fade in">
		<h4>Informasi Tambah Laporan</h4>
		<p>
		Pastikan laporan yang ingin ditambahkan memiliki nama unik. Setelah laoran ditambahkan maka dapat dilanjutkan dengan mengatur isi laporan :
		<ul style="padding-left: 15px;">
			<li>Mengatur Header laporan.</li>
			<li>Mengatur mapping datasource ke dalam laporan</li>
			<li>Mengatur filter laporan.</li>
			<li>Mengatur grouping laporan.</li>
		</ul>
		</p>
	</div>	
</div>

<div class="row">
	<form action="./action/createreport.php" id="form-tambah-laporan">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="panel-btns"><a href="#" class="minimize">&minus;</a></div>
				<h4 class="panel-title">Form Tambah Laporan</h4>
			</div>
			<div class="panel-body">
				
				<div class="form-group col-sm-6 nopadding"><input type="text" class="form-control" name="e_laporan" placeholder="Tuliskan nama laporan"></div>
				<button class="btn btn-primary hidden-xs" style="margin-left: 10px" type="submit">Tambah Laporan</button>
				<button class="btn btn-primary btn-block visible-xs" type="submit">Tambah Laporan</button>

			</div>
		</div>
	</form>
</div>