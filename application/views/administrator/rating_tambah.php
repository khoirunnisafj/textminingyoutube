<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<div id="page-wrapper">
	<h3>Perhitungan Rating</h3>
	<p>&nbsp;</p>

	<?php include('pesan.php'); ?>

	<form id="editform" name="form_maskapai" method="post" action="" class="form-horizontal" accept-charset="utf-8" enctype="multipart/form-data">
		<?php
		if (isset($maskapai)) {
			?>
			<input type="hidden" name="idmaskapai" value="<?php echo $maskapai->idmaskapai; ?>" />
			<?php
		}
		?>

		<!-- <div class="row form-group">
			<label class="col-sm-10 control-label">&nbsp;</label>
			<div class="col-sm-2">
				<!-- <a href="<?php echo base_url() . 'administrator/rating/'; ?>" class="btn btn-secondary col-sm-12">Kembali</a> 
				<a href="javascript:window.history.back();" class="btn btn-secondary col-sm-12">Kembali</a>
			</div>
		</div> -->

		<div class="row form-group">
			<label for="namamaskapai" class="col-sm-2 control-label">Nama Maskapai</label>
			<div class="col-sm-4">
				<input type="text" class="form-control" id="namamaskapai" name="namamaskapai" value="<?php echo set_value('namamaskapai', @$maskapai->namamaskapai); ?>" readonly />
			</div>
		</div>

		<div class="row form-group">
			<label class="col-sm-2 control-label">&nbsp;</label>
			<div class="col-sm-2">
				<input type="button" class="btn btn-primary col-sm-12" id="cari" name="cari" value="Next" onclick="preparedatabase();" />
			</div>
		</div>

		<div class="row form-group">
			<label for="statusajax" class="col-sm-2 control-label">Status</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="statusajax" name="statusajax" value="" readonly />
			</div>
		</div>

		<div class="row form-group">
			<label for="subcategorycount" class="col-sm-2 control-label">Sub Category Count</label>
			<div class="col-sm-4">
				<input type="text" class="form-control" id="subcategorycount" name="subcategorycount" value="" readonly />
			</div>
			<label for="currentsubcategory" class="col-sm-2 control-label">Current Sub Category</label>
			<div class="col-sm-4">
				<input type="text" class="form-control" id="currentsubcategory" name="currentsubcategory" value="" readonly />
			</div>
		</div>

	</form>
</div>

<script type="text/javascript">

	var urlstring = '';
	function preparedatabase()
	{
		$("#cari").attr("disabled", true);
		$("#statusajax").val("Sedang menyiapkan basis data ...");

		var timestamp = new Date().getUTCMilliseconds();
		urlstring = '<?php echo base_url() . "administrator/rating/preparedatabase/" . $maskapai->idmaskapai; ?>/' + timestamp;

		$.ajax({
			type: 'get',
			async: false,
			dataType: 'json',
			url: urlstring,
			error: function () {
				$("#cari").attr("disabled", false);
				$("#statusajax").val("Gagal menyiapkan basis data");
			},
			beforeSend: function () {
			},
			success: function (response) {
				if (response.success == 1)
				{
					$("#statusajax").val("Berhasil menyiapkan basis data");
					calculatesubcategory();
				} else {
					$("#cari").attr("disabled", false);
					$("#statusajax").val("Gagal menyiapkan basis data");
				}
			},
		});

	}

	function calculatesubcategory()
	{
		$("#cari").attr("disabled", true);
		$("#statusajax").val("Sedang memindai sub kategori ...");

		var timestamp = new Date().getUTCMilliseconds();
		urlstring = '<?php echo base_url() . "administrator/rating/calculatesubcategory/" . $maskapai->idmaskapai; ?>/' + timestamp;

		$.ajax({
			type: 'get',
			async: false,
			dataType: 'json',
			url: urlstring,
			error: function () {
				$("#cari").attr("disabled", false);
				$("#statusajax").val("Gagal memindai sub kategori");
			},
			beforeSend: function () {
			},
			success: function (response) {
				$("#subcategorycount").val(response.subcategorycount);
				$("#currentsubcategory").val(response.currentsubcategory);
				if (response.finish == 1)
				{
					$("#statusajax").val("Berhasil memindai sub kategori");
					calculatecategory();
				} else {
					if (response.success == 1)
					{
						$("#statusajax").val("Menunggu sub kategori berikutnya");
						calculatesubcategory();
					} else {
						$("#cari").attr("disabled", false);
						$("#statusajax").val("Gagal memindai sub kategori");
					}
				}
			},
		});

	}

	function calculatecategory()
	{
		$("#cari").attr("disabled", true);
		$("#statusajax").val("Sedang memindai kategori ...");

		var timestamp = new Date().getUTCMilliseconds();
		urlstring = '<?php echo base_url() . "administrator/rating/calculatecategory/" . $maskapai->idmaskapai; ?>/' + timestamp;

		$.ajax({
			type: 'get',
			async: false,
			dataType: 'json',
			url: urlstring,
			error: function () {
				$("#cari").attr("disabled", false);
				$("#statusajax").val("Gagal memindai kategori");
			},
			beforeSend: function () {
			},
			success: function (response) {
				$("#subcategorycount").val(response.subcategorycount);
				$("#currentsubcategory").val(response.currentsubcategory);
				if (response.finish == 1)
				{
					$("#cari").attr("disabled", false);
					$("#statusajax").val("Berhasil memindai kategori");
					window.location.href = "<?php echo base_url() . 'administrator/ranking/lihat/' . $maskapai->idmaskapai; ?>";
				} else {
					if (response.success == 1)
					{
						$("#cari").attr("disabled", false);
						$("#statusajax").val("Selesai");
						window.location.href = "<?php echo base_url() . 'administrator/ranking/lihat/' . $maskapai->idmaskapai; ?>";
					} else {
						$("#cari").attr("disabled", false);
						$("#statusajax").val("Gagal memindai kategori");
					}
				}
			},
		});

	}

</script>



<?php include('footer.php'); ?>