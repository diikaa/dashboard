<div class="row kontrol-sql">

	<!-- PANEL KONEKSI DATABASE -->
	<div class="col-sm-4">
		<form action="action/showtable.php" method="POST" id="form-koneksi">
			<div class="panel panel-default panel-alt widget-messaging" id="panel-koneksi">
				<div class="panel-heading">
					<div class="panel-btns">
						<a href="" class="minimize">&minus;</a>
					</div>
					<h4 class="panel-title">List of Tables</h4>
				</div>

				<div class="panel-body target-column-height" style="padding: 15px;">
					
					<?php include('./view/partial/form_koneksi.php'); ?>
					
					<button type="submit" class="btn btn-primary btn-block mt10">Connect</button>

				</div>

			</div>
		</form>	
	</div>
	<!-- END OF PANEL KONEKSI -->

	<!-- PANEL DAFTAR TABEL -->
	<div class="col-sm-4">
		<div class="panel panel-default panel-alt widget-messaging" id="panel-tabel">
			<div class="panel-heading">
				<div class="panel-btns">
					<a href="" class="minimize">&minus;</a>
				</div>
				<h4 class="panel-title">Table List Panel</h4>
			</div>

			<div class="panel-body same-column-height scrollbar-inner" id="list-tabel-database" style="overflow: auto;">
				<p class="padding15">Tidak ada tabel yang ditampilkan.<br>Hubungkan aplikasi dengan database terlebih dahulu.</p>
			</div>
		</div>
	</div>
	<!-- END OF PANEL DAFTAR TABEL -->

	<!-- PANEL DAFTAR TABEL -->
	<div class="col-sm-4">
		<div class="panel panel-default panel-alt widget-messaging" id="panel-struktur">
			<div class="panel-heading">
				<div class="panel-btns">
					<a href="" class="minimize">&minus;</a>
				</div>
				<h4 class="panel-title">Table Structure Panel</h4>
			</div>

			<div class="panel-body same-column-height scrollbar-inner" id="list-struktur-tabel" style="overflow: auto;">
				<p class="padding15">Tidak ada struktur tabel yang ditampilkan.<br>Hubungkan aplikasi dengan database dan pilih tabel yang akan ditampilkan strukturnya.</p>
			</div>
		</div>
	</div>
	<!-- END OF PANEL DAFTAR TABEL -->

</div>


<!-- SQL SCRIPT WINDOW -->
<div class="row">

	<div class="col-sm-12">
		<form action="action/exequery.php" method="POST" id="form-query">
			<div class="panel panel-default panel-alt" id="panel-query">
				<div class="panel-heading">
					<div class="panel-btns">
						<a href="" class="minimize">&minus;</a>
					</div>
					<h4 class="panel-title">Query Panel</h4>
					<p>Enter the query in SQL format</p>
				</div>

				<div class="panel-body">
	
					<?php include('./view/partial/form_query.php'); ?>
	
				</div>

				<div class="panel-footer">
					<div class="hidden-xs">
						<button type="submit" class="btn btn-primary">Execute Query</button>
						<button type="button" class="btn btn-primary save-data-source">Save as Data Source</button>
					</div>
					<div class="visible-xs">
						<button type="submit" class="btn btn-primary btn-block">Eksekusi Query</button>
						<button type="button" class="btn btn-primary btn-block save-data-source">Simpan Sebagai Data Source</button>
					</div>
				</div>
			</div>
		</form>
	</div>

</div>


<!-- SQL RESULT WINDOW -->
<div class="row">

	<div class="col-sm-12">
		<div class="panel panel-default panel-alt" id="panel-hasil-query">
			<div class="panel-heading">
				<div class="panel-btns">
					<a href="" class="minimize">&minus;</a>
				</div>
				<h4 class="panel-title">Panel Hasil Query</h4>
			</div>

			<div class="panel-body" id="list-hasil-query" style="max-height: 500px; overflow: auto;">
					Tidak ada hasil query yang ditampilkan.<br>
					Silahkan atur koneksi dan masukkan SQL code yang akan di-query.<br>
					Hasil query akan ditampilkan disini.
			</div>
		</div>
	</div>

</div>
