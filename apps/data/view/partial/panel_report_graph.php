
<div class="col-md-12 col-sm-4">
    <form action="#" id="form-graph">
        <div class="panel panel-default panel-alt" id="panel-grafik">
            <div class="panel-heading">
                <div class="panel-btns">
                    <a href="" class="minimize maximize">&plus;</a>
                </div>
                <h4 class="panel-title">Pengaturan Grafik</h4>
            </div>

            <div class="panel-body target-column-height" style="display: none;">
                <input name="e_baris" type="text" class="form-control mb10" placeholder="Awal kolom kalkulasi">
                <input name="e_kolom" type="text" class="form-control mb10" placeholder="Kolom Nilai Axis">
               <div class="row mb10">
                    <div class="col-xs-6" style="padding-right: 5px;"><input type="text" class="form-control" name="e_lebar" placeholder="lebar (px)"></div>
                    <div class="col-xs-6" style="padding-left: 5px;"><input type="text" class="form-control col-xs-6" name="e_tinggi" placeholder="tinggi (px)"></div>
               </div>
                <select name="e_orientasi" class="form-control mb10">
                    <option value="baris">Orientasi Axis Baris</option>
                    <option value="kolom">Orientasi Axis Kolom</option>
                </select>
                <select name="e_tipe" class="form-control mb10">
                    <option value="column">Grafik Batang</option>
                    <option value="line">Grafik Garis</option>
                    <option value="pie">Grafik Lingkaran</option>
                </select>

                <div class="btn-group btn-group-justified" role="group">
                    <a href="#" class="btn btn-primary btn-graph-show tooltips" data-toggle="tooltip" data-placement="top" title="Tampilkan laporan dalam grafik"><i class="fa fa-bar-chart-o"></i></a>
                    <a href="#" class="btn btn-primary btn-remote-link tooltips" data-toggle="tooltip" data-placement="top" title="tampilkan remote link laporan"><i class="fa fa-chain"></i></a>
                </div>
    
            </div>
        </div>
    </form>
</div>