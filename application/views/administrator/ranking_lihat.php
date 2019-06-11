<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<div id="page-wrapper">
	<h3>Rincian Peringkat Maskapai</h3>
	<p>&nbsp;</p>

	<div class="row form-group">
		<label class="col-sm-10 control-label">&nbsp;</label>
		<div class="col-sm-2">
			<!-- <a href="<?php echo base_url() . 'administrator/ranking/'; ?>" class="btn btn-secondary col-sm-12">Kembali</a> -->
			<a href="javascript:window.history.back();" class="btn btn-secondary col-sm-12">Kembali</a>
		</div>
	</div>
 
	<center>
		<h3>
			<?php echo $ranking->namamaskapai; ?><br />
			<div class="rating rating-<?php echo $ranking->idmaskapai; ?>" style="width:500px;"></div>
			<?php echo number_format($ranking->rating, 4, ".", ","); ?>
		</h3>
	</center>
	<p>&nbsp;</p>

	<script>
		var options<?php echo $ranking->idmaskapai; ?> = {
			max_value: <?php echo $maxrating * 2; ?>,
			step_size: 1,
			initial_value: <?php echo $ranking->rating * 2; ?>,
			readonly: true,
		}
		$(".rating-<?php echo $ranking->idmaskapai; ?>").rate(options<?php echo $ranking->idmaskapai; ?>);
		$(".rating-<?php echo $ranking->idmaskapai; ?>").width(210);
	</script>


	<table class="table table-bordered table-hover">
		<tr>
			<th class="col-sm-1 right">No.</th>
			<th class="col-sm-6">Kategori</th>
			<th class="col-sm-2">Skor</th>
			<th class="col-sm-3">Rating</th>
		</tr>

		<?php
		if ($rankings->num_rows() == 0) {
			?>
			<tr>
				<td colspan="4">Data tidak ditemukan</td>
			</tr>
			<?php
		} else {
			$nomor = 1;
			foreach ($rankings->result() as $row) {
				$rating = $row->ratingkategori;
				if ($rating < 0) {
					// $rating = 0;
				}
				?>
				<tr>
					<td class="right"><?php echo $nomor; ?></td>
					<td><?php echo $row->namakategori; ?></td>
					<td><?php echo number_format($rating, 4, ".", ","); ?></td>
					<td><div class="rating rating-<?php echo $row->idnilaikategori; ?>"></div>
						<script>
							var options<?php echo $row->idnilaikategori; ?> = {
								max_value: <?php echo $maxrating * 2; ?>,
								step_size: 1,
								initial_value: <?php echo $rating * 2; ?>,
								readonly: true,
							}
							$(".rating-<?php echo $row->idnilaikategori; ?>").rate(options<?php echo $row->idnilaikategori; ?>);
						</script>
					</td>
				</tr>
				<?php
				$nomor++;
			}
		}
		?>
	</table>

</div>


<?php include('footer.php'); ?>
