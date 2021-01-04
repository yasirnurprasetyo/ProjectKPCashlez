<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
	<?php $this->load->view('layout/header'); ?>
</head>

<body class="layout-fixed">
	<div class="preloader">
		<img src="<?= base_url(); ?>assets/images/logos/logo-cashlez.png" alt="Logo Cashlez" width="150px" height="50px">
		<h4>Distro Merchant</h4>
	</div>
	<div class="mdk-header-layout js-mdk-header-layout">
		<?php $this->load->view('layout/navheader'); ?>
		<div class="mdk-header-layout__content">
			<div class="mdk-drawer-layout js-mdk-drawer-layout" data-push data-responsive-width="992px">
				<?php $this->load->view($page); ?>
				<?php $this->load->view('layout/sidebar'); ?>
			</div>
		</div>
	</div>
	<?php $this->load->view('layout/scriptfooter'); ?>
	<!-- Modal -->
	<div class="modal fade" id="modal-details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<div class="position-relative mr-3">
						<img src="<?= base_url(); ?>assets/images/logos/shop.png" alt="Logo Merchant" width="55px" height="55px">
					</div>
					<div class="col-sm-6">
						<h5 id="nama_mer" style="color: #000000;"></h5>
						<h5 id="value_am" style="color: #000000;"></h5>
					</div>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div id="modal-detail" class="modal-body">
				</div>
			</div>
		</div>
	</div>
</body>

</html>
