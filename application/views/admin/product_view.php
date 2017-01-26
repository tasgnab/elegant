<?php include_once('common/header.php'); ?>
<link rel="stylesheet" href="<?php echo base_url();?>css/jquery.rateyo.css"/>
<link rel="stylesheet" href="<?php echo base_url();?>css/jquery.spzoom.css"/>
<script src="<?php echo base_url();?>js/jquery.rateyo.js"></script>
<script src="<?php echo base_url();?>js/common.js"></script>
<script src="<?php echo base_url();?>js/filter.js"></script>
<script src="<?php echo base_url();?>js/jquery.spzoom.js"></script>
</head><!--/head-->
<body>
<?php include_once('common/nav_menu.php'); ?>
	<section>
		<div class="container">
			<div class="row">
				<?php include_once('common/left_sidebar.php'); ?>
				<?php include_once('common/site_map.php'); ?>
				<?php include_once('product_form_view.php'); ?>
			</div>
		</div>
	</section>
<?php include_once('common/footer.php'); ?>