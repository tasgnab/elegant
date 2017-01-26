<?php include_once('common/header.php'); ?>
<script src="<?php echo base_url();?>js/common.js"></script>
<script src="<?php echo base_url();?>js/filter.js"></script>
</head><!--/head-->
<body>
<?php include_once('common/nav_menu.php'); ?>
	<section>
		<div class="container">
			<div class="row">
				<?php include_once('common/left_sidebar.php'); ?>
				<?php include_once('common/site_map.php'); ?><div class="col-sm-5">
				<div class="product-input">
					<p>Product Information</p>
					<form id="product-input-form" name="product-input-form" enctype="multipart/form-data">
						<input id="name" name="name" type="text" placeholder="Display Name" required>
						<textarea id="description" name="description"  placeholder="Product Descriptions" rows="10"></textarea>
						<div>
							<ul id="brand-selection">
								<li>
									<input id="brand-input" name="brand-input" type="text" placeholder="Click to add Brand" >
									<div class="hide-div">
										<input id="brand" name="brand" type="text" required>
									</div>
								</li>
							</ul>
							<ul id="brand-list" class="list hide">
								<?php $rel=0 ?>
								<?php foreach ($allBrand as $brand): ?>
									<li id="brand-<?php echo $brand->brand; ?>" rel ="<?php echo $rel; ?>" onClick="$.fn.addSelectionSingle('brand-<?php echo $brand->brand; ?>')"><?php echo $brand->brand; ?></li>
									<?php $rel++ ?>
								<?php endforeach; ?>
							</ul>
						</div>
						<div class="price-input">
							<span>Rp</span>
							<input id="price" name="price" type="text" min="0" class="text-align-right" placeholder="Price"required>
						</div>
						<div>
							<ul id="category-selection">
								<li>
									<input id="category-input" name="category-input" type="text" placeholder="Click to add Category" >
									<div class="hide-div">
										<input id="category" name="category" type="text" required>
									</div>
								</li>
							</ul>
							<ul id="category-list" class="list hide">
								<?php $rel=0 ?>
								<?php foreach ($allCategory as $category): ?>
									<?php if (empty($category['subCat'])): ?>
										<li id="category-<?php echo $category['id']; ?>" rel ="<?php echo $rel; ?>" onClick="$.fn.addSelection('category-<?php echo $category['id']; ?>')"><?php echo $category['category_name']; ?></li>
										<?php $rel++ ?>
									<?php else: ?>
										<?php foreach ($category['subCat'] as $subCat): ?>
											<li id="category-<?php echo $subCat['id']; ?>" rel ="<?php echo $rel; ?>" onClick="$.fn.addSelection('category-<?php echo $subCat['id']; ?>')"><?php echo $subCat['category_name']; ?></li>
											<?php $rel++ ?>
										<?php endforeach; ?>
									<?php endif; ?>
								<?php endforeach; ?>
							</ul>
						</div>
						<input id="size" name="size" type="text" placeholder="Size" required>
						<input id="timestamp" name="timestamp" type="hidden" value="<?php echo time();?>" required>
						<div id="btnSelect" class="input-file">Click to upload files</div>
						<div class="hide-div">
							<input name="images[]" id="images" type="file" value="upload" accept="image/jpg,image/png,image/jpeg" required multiple/>
						</div>
						<button id="form-submit" type="submit" class="btn btn-primary">Submit</a>
					</form>
				</div>
			</div>
			<div class="col-sm-5">
				<h2 class="title text-center">Recent Product</h2>
				<?php foreach ($productList as $product): ?>
					<div class="col-sm-6">
					<div class="product-image-wrapper">
						<div class="single-products">
								<div class="productinfo text-center">
									<img class="img-rightpane" src="<?php echo base_url();?>images/product/<?php echo $product->filename; ?>" alt="" />
									<h2>Rp <?php echo $product->price; ?> </h2>
									<p><?php echo $product->name; ?></p>
									<a href="<?php echo base_url();?>admin/product/view/<?php echo $product->id;?>" class="btn btn-default add-to-cart"><i class="fa fa-search"></i>View details</a>
								</div>
						</div>
						<div class="choose">
							<ul class="nav nav-pills nav-justified">
								<li><a href="#"><i class="fa fa-pencil"></i>Edit</a></li>
								<li><a href="#"><i class="fa fa-times-circle"></i>Delete</a></li>
							</ul>
						</div>
					</div>
					</div>
				<?php endforeach; ?>
			</div>

			<script src="<?php echo base_url();?>js/jquery.number.js"></script>
			<script type="text/javascript">
				$(document).ready(function() {
					$('#price').number( true, 2 );
				});

				$('#form-submit').click(function(e){
					// e.preventDefault();
					//console.log($('#price').val());
					var listItems = $("#category-selection li");
					var cat = "";
					listItems.each(function(idx, li) {
						if (idx>0){
							cat += $(li).attr('id').split('-')[1]+"#";
						}
					});
					if (cat !== ""){
						$('#category').val(cat);	
					}

					if ($("#brand-selection li").length > 1){
						$('#brand').val($("#brand-selection li:last").text());
					} else {
						$('#brand').val($('#brand-input').val());
					}

					$('#product-input-form').attr('action', '<?php echo base_url('admin/products/doInputProduct');?>');
					$('#product-input-form').attr('method', 'post');
				});

				$('#btnSelect').click(function(){
					$.fn.hideList('category');
					$.fn.hideList('brand');
					$('#images').trigger("click");
				});

				$('#brand-input').keyup(function(){
					$.fn.filter("#brand-input","#brand-list");
				});

				$('#category-input').keyup(function(){
					$.fn.filter("#category-input","#category-list");
				});

				$('#images').change(function(){
					var files = this.files;
					if (files.length>1){
						$('#btnSelect').text(files.length + ' files selected');
					} else if (files.length == 1){
						var fileName = files[0].name.split("\\");
						$('#btnSelect').text(files.length + ' files selected');
					}
				});

				$('input').focus(function(){
					$.fn.hideList('category');
					$.fn.hideList('brand');
				});

				$('textarea').focus(function(){
					$.fn.hideList('category');
					$.fn.hideList('brand');
				});

				$('#category-input').focus(function(){
					$.fn.hideList('brand');
					$.fn.showList('category');
				});

				$('#brand-input').focus(function(){
					$.fn.hideList('category');
					$.fn.showList('brand');
				});
			</script>
			</div>
		</div>
	</section>
<?php include_once('common/footer.php'); ?>