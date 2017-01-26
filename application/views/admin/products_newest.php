<div class="features_items"><!--features_items-->
	<h2 class="title text-center">Features Items</h2>
	<?php foreach ($productList as $product): ?>
		<div class="col-sm-4">
			<div class="product-image-wrapper">
				<div class="single-products">
						<div class="productinfo text-center">
							<img src="<?php echo base_url();?>images/product/<?php echo $product->filename; ?>" alt="" />
							<h2>Rp<?php echo $product->price; ?> </h2>
							<p><?php echo $product->name; ?></p>
							<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
						</div>
						<div class="product-overlay">
							<div class="overlay-content">
								<h2>Rp<?php echo $product->price; ?> </h2>
								<p><?php echo $product->name; ?></p>
								<a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</a>
							</div>
						</div>
				</div>
				<div class="choose">
					<ul class="nav nav-pills nav-justified">
						<li><a href="#"><i class="fa fa-plus-square"></i>Add to wishlist</a></li>
						<li><a href="#"><i class="fa fa-plus-square"></i>Add to compare</a></li>
					</ul>
				</div>
			</div>
		</div>
	<?php endforeach; ?>						
</div><!--features_items-->