<div class="mdk-drawer-layout__content page">
	<div class="container-fluid page__heading-container">
		<div class="page__heading d-flex align-items-end">
			<i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">assignment</i>
			<div class="flex">
				<h5 class="m-0 content">Transactions</h5>
			</div>
		</div>
	</div>
	<div class="container-fluid page__container">
		<div class="card">
			<div class="card-body-lg">
				<!-- <div id="tableID"> -->
				<div data-toggle="lists" data-lists-values='["js-lists-values-merchant-name", "js-lists-values-total-transaction", "js-lists-values-sales-volume", "js-lists-values-total-incentive"]' class="table-responsive border-bottom">
					<table id="examples" class="table table-striped" style="width:100%">
						<thead>
							<tr>
								<th><a href="javascript:void(0)" class="sort" data-sort="js-lists-values-merchant-name">Merchant Name</a></th>
								<th><a href="javascript:void(0)" class="sort" data-sort="js-lists-values-total-transaction">Total Transaction</a></th>
								<th><a href="javascript:void(0)" class="sort" data-sort="js-lists-values-sales-volume">Total Sales Volume</a></th>
								<th><a href="javascript:void(0)" class="sort" data-sort="js-lists-values-total-incentive">Total Incentive</a></th>
							</tr>
						</thead>
						<tbody class="list">
							<?php
							foreach ($hasil as $ff) {
							?>
								<tr>
									<td class="js-lists-values-merchant-name"><a id="btn-detail" href="#" type="button" class="modaldetails" data-nama="<?= $ff['name'] ?>" data-total="<?= formatRupiah($ff['sales_volume']) ?>" type="button"><?= $ff['name'] ?><a /></td>
									<td class="js-lists-values-total-transaction"><?= $ff['sales_numbers'] ?></td>
									<td class="js-lists-values-sales-volume"><?= formatRupiah($ff['sales_volume'])	?></td>
									<td class="js-lists-values-total-incentive"><?= formatRupiah($ff['sales_incentive'])	?></td>
								</tr>
							<?php
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(function() {
		$('#examples').DataTable({
			"responsive": true,
			"autoWidth": false,
			"lengthChange": false,
			"ordering": false,
			"paging": true,
			"searching": false,
			"responsive": true,
			"bInfo": false,
			lengthMenu: [
				[11, 25, 50, 100, -1],
				[11, 25, 50, 100, "All"]
			],
		});
		$(document).on('click', '#btn-detail', function(e) {
			e.preventDefault();
			var nama = $(this).data('nama');
			var total = $(this).data('total');
			var json = JSON.stringify(nama);
			$.ajax({
				type: "POST",
				url: '<?= base_url() ?>' + 'Transaction/getDetail',
				data: {
					name: json
				},
				success: function(data) {
					var data = $.parseJSON(data);
					console.log(data)
					var table_header = "<table class='table table-striped'><thead><tr><td>Payment Method</td><td>Amount</td></tr></thead><tbody>";
					var table_footer = "</tbody></table>";
					var html = "";
					jQuery.each(data.data, function(index, value) {
						num = addPeriod(this.amount );
						html += "<tr><td>" + value.payment_method + "</td><td>" + "Rp. " + num + "</td></tr>";
					});
					var all = table_header + html + table_footer;
					$('#nama_mer').html(nama);
					$('#value_am').html(total);
					$('#modal-detail').html(all);
					$('#modal-details').modal('show');
				}
			});
		});

		function addPeriod(nStr) {
			nStr += '';
			x = nStr.split('.');
			x1 = x[0];
			x2 = x.length > 1 ? '.' + x[1] : '';
			var rgx = /(\d+)(\d{3})/;
			while (rgx.test(x1)) {
				x1 = x1.replace(rgx, '$1' + '.' + '$2');
			}
			return x1 + x2;
		}
	});
</script>
