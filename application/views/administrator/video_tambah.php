<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<div id="page-wrapper">
	<h3>Crawler Data Video</h3>
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
				<!-- <a href="<?php echo base_url() . 'administrator/video/'; ?>" class="btn btn-secondary col-sm-12">Kembali</a> -->
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
				<input type="button" class="btn btn-primary col-sm-12" id="cari" name="cari" value="Next" onclick="carivideo();" />
			</div>
			<div class="col-sm-2">
				<a href="<?php echo base_url() . 'administrator/thread/ubah/' . $record->idmaskapai; ?>" class="btn btn-primary col-sm-12">Next Thread </a>
			</div>
		</div>

		<div class="row form-group">
			<label for="statusajax" class="col-sm-2 control-label">Status</label>
			<div class="col-sm-10">
				<input type="text" class="form-control" id="statusajax" name="statusajax" value="" readonly />
			</div>
		</div>

		<div class="row form-group">
			<label for="maxvideo" class="col-sm-2 control-label">Max Video</label>
			<div class="col-sm-4">
				<input type="text" class="form-control" id="maxvideo" name="maxvideo" value="" readonly />
			</div>
			<label for="videocount" class="col-sm-2 control-label">Video Count</label>
			<div class="col-sm-4">
				<input type="text" class="form-control" id="videocount" name="videocount" value="" readonly />
			</div>
		</div>
	</form>
	<br>
	<div>
		<table border="0" class="table table-striped table-bordered">
			<tr>
				<th class="col-sm-1">No.</th>
				<th class="col-sm-2">Video ID</th>
				<th class="col-sm-7">Judul</th>
			</tr>
			<tbody id="dataVideo">
			<?php $nomor = 1; ?>
			<?php if($dataVideo->num_rows() >= 1): ?>
				<?php foreach ($dataVideo->result() as $row):?>
						<tr>
							<td><?php echo $nomor; ?></td>
							<td><?php echo $row->videoid; ?></td>
							<td><?php echo $row->judul; ?></td>
						</tr>
				<?php 
				$nomor++;
				endforeach; ?>
			<?php endif; ?>

			</tbody>
		</table>
	</div>

</div>

<script type="text/javascript">

	var urlstring = '';
	var resultNumbering = '<?php echo $nomor; ?>';
	function carivideo()
	{
		$("#cari").attr("disabled", true);
		$("#statusajax").val("Sedang memindai ...");

		var timestamp = new Date().getUTCMilliseconds();
		urlstring = '<?php echo base_url() . "administrator/video/searchvideo/" . $record->idmaskapai; ?>/' + timestamp;

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
				$("#maxvideo").val(response.maxvideo);
				$("#videocount").val(response.videocount);
				if (response.success == 1)
				{
					if (response.videocount < response.maxvideo)
					{
						if (response.nextpagetoken != "")
						{
							$("#statusajax").val("Berhasil: Menunggu video berikutnya");
							setTimeout(carivideo, 1000);
							appendVideo(response.dataVideo[0]);
						} else
						{
							$("#cari").attr("disabled", false);
							$("#statusajax").val("Selesai: Daftar video habis");
							appendVideo(response.dataVideo[0]);
							// window.location.href = "<?php echo base_url() . 'administrator/thread/ubah/' . $record->idmaskapai; ?>";
						}
					} else
					{
						$("#cari").attr("disabled", false);
						$("#statusajax").val("Selesai: Banyak max video tercapai");
						// window.location.href = "<?php echo base_url() . 'administrator/thread/ubah/' . $record->idmaskapai; ?>";
						appendVideo(response.dataVideo[0]);
					}
				} else {
					$("#cari").attr("disabled", false);
					$("#statusajax").val("Gagal : " + response.message);
				}
			},
		});


	}

	function appendVideo(data)
	{
		var html = '<tr>';
				html += '<td>' + resultNumbering + '</td>';
				html += '<td>'+data['videoid']+'</td>';
				html += '<td>'+data['judul']+' </td>';
			html += '</tr>';
		$('#dataVideo').append(html);

		resultNumbering++;
	}

</script>



<?php include('footer.php'); ?>