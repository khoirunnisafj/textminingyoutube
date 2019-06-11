<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<div id="page-wrapper">
	<h3>Formulir Thread</h3>
	<p>&nbsp;</p>

	<?php include('pesan.php'); ?>

	<form id="editform" name="form_maskapai" method="post" action="" class="form-horizontal" accept-charset="utf-8" enctype="multipart/form-data">
		<?php
		if (isset($record)) {
			?>
			<input type="hidden" name="idmaskapai" value="<?php echo $record->idmaskapai; ?>" />
			<?php
		}
		?>

		<div class="row form-group">
			<label class="col-sm-10 control-label">&nbsp;</label>
			<div class="col-sm-2">
				<!-- <a href="<?php echo base_url() . 'administrator/thread/'; ?>" class="btn btn-secondary col-sm-12">Kembali</a> -->
				<a href="javascript:window.history.back();" class="btn btn-secondary col-sm-12">Kembali</a>
			</div>
		</div>
		
		<div class="row form-group">
			<label for="namamaskapai" class="col-sm-2 control-label">Nama Maskapai</label>
			<div class="col-sm-4">
				<input type="text" class="form-control" id="namamaskapai" name="namamaskapai" value="<?php echo set_value('namamaskapai', @$record->namamaskapai); ?>" readonly />
			</div>
		</div>

		<div class="row form-group">
			<label class="col-sm-2 control-label">&nbsp;</label>
			<div class="col-sm-2">
				<input type="button" class="btn btn-primary col-sm-12" id="cari" name="cari" value="Pindai" onclick="carithread();" />
			</div>
		</div>

		<div class="row form-group">
			<label for="statusajax" class="col-sm-2 control-label">Status</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="statusajax" name="statusajax" value="" readonly />
			</div>
		</div>

		<div class="row form-group">
			<label for="maxthread" class="col-sm-2 control-label">Max Thread</label>
			<div class="col-sm-4">
				<input type="text" class="form-control" id="maxthread" name="maxthread" value="" readonly />
			</div>
			<label for="threadcount" class="col-sm-2 control-label">Thread Count</label>
			<div class="col-sm-4">
				<input type="text" class="form-control" id="threadcount" name="threadcount" value="" readonly />
			</div>
		</div>

	</form>
</div>

<script type="text/javascript">

	var urlstring = '';
	function carithread()
	{
		$("#cari").attr("disabled", true);
		$("#statusajax").val("Sedang memindai ...");

		var timestamp = new Date().getUTCMilliseconds();
		urlstring = '<?php echo base_url() . "administrator/thread/searchthread/" . $record->idmaskapai; ?>/' + timestamp;

		$.ajax({
			type: 'get',
			async: false,
			dataType: 'json',
			url: urlstring,
			error: function () {
				$("#cari").attr("disabled", false);
				$("#statusajax").val("Gagal: Alasan tidak diketahui");
			},
			beforeSend: function () {
			},
			success: function (response) {
				$("#maxthread").val(response.maxthread);
				$("#threadcount").val(response.threadcount);
				if (response.finish == 1)
				{
					$("#cari").attr("disabled", false);
					$("#statusajax").val("Selesai: Banyak max thread tercapai");
					window.location.href = "<?php echo base_url() . 'administrator/rating/ubah/' . $record->idmaskapai; ?>";
				} else {
					if (response.success == 1)
					{
						if (response.threadcount < response.maxthread)
						{
							if (response.nextpagetoken != "")
							{
								$("#statusajax").val("Berhasil: Menunggu thread berikutnya");
								setTimeout(carithread, 1000);
							} else
							{
								$("#statusajax").val("Berhasil: Menunggu thread dari video berikutnya.");
								setTimeout(carithread, 1000);
							}
						} else
						{
							$("#cari").attr("disabled", false);
							$("#statusajax").val("Selesai: Banyak max thread tercapai");
							window.location.href = "<?php echo base_url() . 'administrator/rating/ubah/' . $record->idmaskapai; ?>";
						}
					} else {
						$("#cari").attr("disabled", false);
						$("#statusajax").val("Gagal : " + response.message);
					}
				}
			},
		});

	}

</script>



<?php include('footer.php'); ?>