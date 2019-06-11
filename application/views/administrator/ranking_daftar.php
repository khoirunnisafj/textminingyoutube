<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<div id="page-wrapper">
	<h3>Daftar Peringkat Maskapai</h3>
	<p>&nbsp;</p>

	<table class="table table-bordered table-hover">
		<tr>
			<th class="col-sm-1 right">No.</th>
			<th class="col-sm-6">Nama Maskapai</th>
			<th class="col-sm-2">Skor</th>
			<th class="col-sm-2">Rating</th>
			<th class="col-sm-1">Perintah</th>
		</tr>

		<?php
		if ($ranking->num_rows() == 0) {
			?>
			<tr>
				<td colspan="4">Data tidak ditemukan</td>
			</tr>
			<?php
		} else {
			$nomor = 1;
			foreach ($ranking->result() as $row) {
				?>
				<tr>
					<td class="right"><?php echo $nomor; ?></td>
					<td><?php echo $row->namamaskapai; ?></td>
					<td><?php echo number_format($row->rating, 4, ".", ","); ?></td>
					<td><div class="rating rating-<?php echo $row->idmaskapai; ?>"></div>
						<script>
							var options<?php echo $row->idmaskapai; ?> = {
								max_value: <?php echo $maxrating * 2; ?>,
								step_size: 1,
								initial_value: <?php echo $row->rating * 2; ?>,
								readonly: true,
							}
							$(".rating-<?php echo $row->idmaskapai; ?>").rate(options<?php echo $row->idmaskapai; ?>);
						</script>
					</td>
					<td>
						<a href="<?php echo base_url() . 'administrator/ranking/lihat/' . $row->idmaskapai; ?>" class="btn btn-info" title="Lihat"><span class="fa fa-list"></span></a>
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
