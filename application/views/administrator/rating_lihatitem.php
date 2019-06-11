<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<div id="page-wrapper">
	<h3>Formulir Nilai Sub Kategori</h3>
	<p>&nbsp;</p>

	<form name="form_furnitureitem" method="post" action="" class="form-horizontal" accept-charset="utf-8" enctype="multipart/form-data">

		<div class="row form-group">
			<label class="col-sm-10 control-label">&nbsp;</label>
			<div class="col-sm-2">
				<a href="<?php echo base_url() . 'administrator/rating/lihat/' . $idmaskapai; ?>" class="btn btn-secondary col-sm-12">Kembali</a>
			</div>
		</div>

		<div class="row form-group">
			<label for="kategori" class="col-sm-2 control-label">Kategori</label>
			<div class="col-sm-4">
				<input type="text" class="form-control" id="kategori" name="kategori" value="<?php echo set_value('kategori', @$kategori->namakategori); ?>" readonly />
			</div>
			<label for="subkategori" class="col-sm-2 control-label">Sub Kategori</label>
			<div class="col-sm-4">
				<input type="text" class="form-control" id="subkategori" name="subkategori" value="<?php echo set_value('subkategori', @$subkategori->namasubkategori); ?>" readonly />
			</div>
		</div>

		<div class="row form-group">
			<label for="banyaksinonim" class="col-sm-2 control-label">Banyak Sinonim</label>
			<div class="col-sm-4">
				<input type="text" class="form-control" id="banyaksinonim" name="banyaksinonim" value="<?php echo set_value('banyaksinonim', @$nilaisubkategori->banyaksinonim); ?>" readonly />
			</div>
			<label for="banyakantonim" class="col-sm-2 control-label">Banyak Antonim</label>
			<div class="col-sm-4">
				<input type="text" class="form-control" id="banyakantonim" name="banyakantonim" value="<?php echo set_value('banyakantonim', @$nilaisubkategori->banyakantonim); ?>" readonly />
			</div>
		</div>

		<div class="row form-group">
			<label for="jumlah" class="col-sm-2 control-label">Jumlah</label>
			<div class="col-sm-4">
				<input type="text" class="form-control" id="jumlah" name="jumlah" value="<?php echo set_value('jumlah', @$nilaisubkategori->jumlah); ?>" readonly />
			</div>
			<label for="threshold" class="col-sm-2 control-label">Threshold</label>
			<div class="col-sm-4">
				<input type="text" class="form-control" id="threshold" name="threshold" value="<?php echo set_value('threshold', @$nilaisubkategori->threshold); ?>" readonly />
			</div>
		</div>

		<div class="row form-group">
			<label for="jejaksinonim" class="col-sm-2 control-label">Jejak Sinonim</label>
			<div class="col-sm-10">
				<textarea class="form-control" id="jejaksinonim" name="jejaksinonim" readonly ><?php echo set_value('jejaksinonim', @$nilaisubkategori->jejaksinonim); ?></textarea>
			</div>
		</div>

		<div class="row form-group">
			<label for="jejakantonim" class="col-sm-2 control-label">Jejak Antonim</label>
			<div class="col-sm-10">
				<textarea class="form-control" id="jejakantonim" name="jejakantonim" readonly ><?php echo set_value('jejakantonim', @$nilaisubkategori->jejakantonim); ?></textarea>
			</div>
		</div>

	</form>
</div>

<?php include('footer.php'); ?>