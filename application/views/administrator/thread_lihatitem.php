<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<div id="page-wrapper">
	<h3>Formulir Thread</h3>
	<p>&nbsp;</p>

	<form name="form_furnitureitem" method="post" action="" class="form-horizontal" accept-charset="utf-8" enctype="multipart/form-data">

		<div class="row form-group">
			<label class="col-sm-10 control-label">&nbsp;</label>
			<div class="col-sm-2">
				<a href="<?php echo base_url() . 'administrator/thread/lihat/' . $idmaskapai; ?>" class="btn btn-secondary col-sm-12">Kembali</a>
			</div>
		</div>

		<div class="row form-group">
			<label for="threadid" class="col-sm-2 control-label">Thread ID</label>
			<div class="col-sm-4">
				<input type="text" class="form-control" id="threadid" name="threadid" value="<?php echo set_value('threadid', @$thread->threadid); ?>" readonly />
			</div>
			<label for="commentid" class="col-sm-2 control-label">Comment ID</label>
			<div class="col-sm-4">
				<input type="text" class="form-control" id="commentid" name="commentid" value="<?php echo set_value('commentid', @$thread->commentid); ?>" readonly />
			</div>
		</div>

		<div class="row form-group">
			<label for="isi" class="col-sm-2 control-label">Isi</label>
			<div class="col-sm-10">
				<textarea class="form-control" id="isi" name="isi" readonly ><?php echo set_value('isi', @$thread->isi); ?></textarea>
			</div>
		</div>

	</form>
</div>

<?php include('footer.php'); ?>