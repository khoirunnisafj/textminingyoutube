<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<div id="page-wrapper">
	<h3>Formulir Video</h3>
	<p>&nbsp;</p>

	<form name="form_furnitureitem" method="post" action="" class="form-horizontal" accept-charset="utf-8" enctype="multipart/form-data">

		<div class="row form-group">
			<label class="col-sm-10 control-label">&nbsp;</label>
			<div class="col-sm-2">
				<a href="<?php echo base_url() . 'administrator/video/lihat/' . $idmaskapai; ?>" class="btn btn-secondary col-sm-12">Kembali</a>
			</div>
		</div>

		<div class="row form-group">
			<label for="videoid" class="col-sm-2 control-label">Video ID</label>
			<div class="col-sm-4">
				<input type="text" class="form-control" id="videoid" name="videoid" value="<?php echo set_value('videoid', @$video->videoid); ?>" readonly />
			</div>
		</div>

		<div class="row form-group">
			<label for="judul" class="col-sm-2 control-label">Judul</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="judul" name="judul" value="<?php echo set_value('judul', @$video->judul); ?>" readonly />
			</div>
		</div>

		<div class="row form-group">
			<label for="keterangan" class="col-sm-2 control-label">Keterangan</label>
			<div class="col-sm-10">
				<textarea class="form-control" id="keterangan" name="keterangan" readonly ><?php echo set_value('keterangan', @$video->keterangan); ?></textarea>
			</div>
		</div>

	</form>
</div>

<?php include('footer.php'); ?>