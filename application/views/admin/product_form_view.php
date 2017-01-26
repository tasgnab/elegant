<div class="col-sm-9 padding-right">
	<!--product-details-->
	<form id="product-view-form" name="product-view-form">
	<div class="product-details">
		<div class="col-sm-1 product-thumbnail">
			<?php for ($i=0; $i<count($imageList); $i++): ?>
				<a href="javascript:void(0);"><div class="thumbnail"><img src="<?php echo base_url();?>images/product/<?php echo $imageList[$i]->filename; ?>" alt=""></div></a>
			<?php endfor; ?>
			<div id="btnSelect" class="input-file">add Images</div>
			<div class="hide-div">
				<input name="images[]" id="images" type="file" value="upload" accept="image/jpg,image/png,image/jpeg" required multiple/>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="view-product">
				<span class="helper"></span>
				<img id="main-image" src="<?php echo base_url();?>images/product/<?php echo $product->filename; ?>" alt="" />
			</div>
		</div>
		<div class="col-sm-5">
			<!--/product-information-->
			<div class="product-information">
				<?php $interval=(new DateTime(date('Y-m-d', strtotime($product->create_datetime))))->diff(new DateTime("now"))->days; ?>
				<?php if($interval<30): ?>
					<img src="<?php echo base_url();?>images/tags/new.jpg" class="newarrival" alt="" />
				<?php endif; ?>
				<h2><input id="name" name="name" type="text" value="<?php echo $product->name; ?>" required></h2>
				<p>Product ID: <?php echo $product->id; ?></p>
				<div id="rateYo"></div>
				<span>
					<span>IDR <input id="price" name="price" type="number" min="0" class="text-align-right" value="<?php echo $product->price; ?>" required></span>
				</span>
				<p><b>Availability:</b></p>
				<?php $index = 0; ?>
				<div id="stockList" style="width: 40%; padding-left: 20px;">
				<?php foreach ($stockList as $stock): ?>
					<p><span style="width:40%; margin: 0px">size</span><span style="width:10%; margin: 0px">:</span><span style="width: 50%;margin: 0px">stock</span></p>
					<p><input id="stock<?php echo $index; ?>" name="stock<?php echo $index; ?>" type="text" value="<?php echo $stock->size; ?>" style="width:40%" required> : <input id="stock<?php echo $index; ?>" name="stock<?php echo $index; ?>" type="number" value="<?php echo $stock->stock; ?>" style="width:50%" required></p>
					<?php $index++; ?>
				<?php endforeach; ?>
				</div>
				<p><b>Brand:</b> <input id="brand" name="brand" type="text" value="<?php echo $product->brand; ?>" required></p>
				<ul id="brand-view-list" class="list hide">
					<?php $rel=0 ?>
					<?php foreach ($allBrand as $brand): ?>
						<li id="brand-<?php echo $brand->brand; ?>" rel ="<?php echo $rel; ?>" onClick="$.fn.changeBrand('<?php echo $brand->brand; ?>')"><?php echo $brand->brand; ?></li>
						<?php $rel++ ?>
					<?php endforeach; ?>
				</ul>
				<p><b>Category:</b>
				<ul>
					<li><input id="category" name="category" type="hidden" required></li>
					<li>
						<ul id="category-view-selection" class="float-left list">
							<li><span id='button-add-category'>add Category</span></li>
							<li><span id='button-save-category'>SAVE</span></li>
						</ul>
						<ul id="category-view-list" class="list hide">
							<?php $rel=0 ?>
							<?php foreach ($allCategory as $category): ?>
								<?php if (empty($category['subCat'])): ?>
									<li id="category-<?php echo $category['id']; ?>" rel ="<?php echo $rel; ?>" onClick="$.fn.selectCategory('category-<?php echo $category['id']; ?>')"><?php echo $category['category_name']; ?></li>
									<?php $rel++ ?>
								<?php else: ?>
									<?php foreach ($category['subCat'] as $subCat): ?>
										<li id="category-<?php echo $subCat['id']; ?>" rel ="<?php echo $rel; ?>" onClick="$.fn.selectCategory('category-<?php echo $subCat['id']; ?>')"><?php echo $subCat['category_name']; ?></li>
										<?php $rel++ ?>
									<?php endforeach; ?>
								<?php endif; ?>
							<?php endforeach; ?>
						</ul>
					</li>
				</ul>
				<input id="timestamp" name="timestamp" type="hidden" value="<?php echo time();?>" required>
				<input id="idProduct" name="text" type="hidden" value="<?php echo $product->id;?>" required>
				<span id='button-save-product'>SAVE PRODUCT</span>
			</div>
			<!--/product-information-->
		</div>
	</div>
	<!--/product-details-->
	
	<div class="category-tab shop-details-tab"><!--category-tab-->
		<div class="col-sm-12">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#details" data-toggle="tab">Description</a></li>
			</ul>
		</div>
		<div class="tab-content">
			<div class="tab-pane fade active in" id="details" >
				<div class="col-sm-12">
					<textarea id="description" name="description" style="background: none; height: 200px;"><?php echo $product->description;?></textarea>
				</div>
			</div>
		</div>
	</div><!--/category-tab-->
	</form>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		var arrCategory = <?php echo json_encode($categoryList); ?>;
		for (i=0; i<arrCategory.length; i++) {
			$.fn.selectCategory("category-"+arrCategory[i]['id']);
		}
		$("#rateYo").rateYo({
			rating: "<?php echo $product->rating; ?>"
  		});
  		$('#button-save-product').hide();
		$('#button-save-category').hide();
		$("#button-save-category").click(function(e){
			e.preventDefault();
			var listItems = $("#category-view-selection li");
			var cat = "";
			listItems.each(function(idx, li) {
				if (idx<$("#category-view-selection li").length-2){
					cat += $(li).attr('id').split('-')[1]+"#";
				}
			});

			var idCategories = cat;
			var idProduct = $('#idProduct').val();
			jQuery.ajax({
				type: "POST",
				url: "<?php echo base_url(); ?>" + "admin/products/update_category",
				dataType: 'json',
				data: {product: idProduct, category: idCategories},
				success: function(res){
					var message="";
					if (res.result==1){
						message = "<div class=\"alert alert-success alert-dismissible\" role=\"alert\">";
					} else if (res.result==0){
						message = "<div class=\"alert alert-danger alert-dismissible\" role=\"alert\">";
					}
					message+="<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>";
					message+=res.message;
					message+="</div>";
					$('#message').append(message);
				}
			});
		});
	});

	$('#button-save-product').click(function(e){
		if( $('#button-save-category').css('display') == 'inline-block' ) {
			e.preventDefault();
		}
	});

	$('#btnSelect').click(function(){
		$.fn.hideList('category');
		$.fn.hideList('brand');
		$('#images').trigger("click");
	});

	$('#brand').bind({
		click: function(){
			$.fn.showList('brand-view');
		},
		keyup: function(){
			$.fn.filter('#brand','#brand-view-list');
		},
		change: function(){
			$('#button-save-product').show();
		}
	});
	$('#name').change(function(){
		$('#button-save-product').show();
	});
	$('#price').change(function(){
		$('#button-save-product').show();
	});
	$('#description').change(function(){
		$('#button-save-product').show();
	});

	$('#button-save-product').click(function(){

	});


	$('.thumbnail > img').bind({
		'click': function(){
			$('#main-image').attr('src',$(this).attr('src'));
		},
		'mouseover': function(){
			$('#main-image').attr('src',$(this).attr('src'));
		}
	});

	$.fn.selectCategory = function(id){
		var li = "<li id=\""+id+"\" rel=\""+$('#'+id).attr('rel')+"\"><span>"+$('#'+id).text()+" <a class=\"search_choice_close_view\" href=\"javascript:void(0);\" onClick=\"$.fn.removeCategory('"+id+"')\"></a></span></li>";
		$('#'+id).remove();
		$(li).insertAt($('#category-view-selection > li').length-2,$('#category-view-selection'));
		$.fn.hideList('category-view');
		$('#button-save-category').show();
	};

	$.fn.removeCategory = function(id){
		var li = "<li id=\""+id+"\" rel=\""+$('#'+id).attr('rel')+"\" onCLick=\"$.fn.selectCategory('"+id+"')\">"+$('#'+id).text()+"</li>";
		var rel = parseInt($('#'+id).attr('rel'));
		var lastIndex = $('#category-view-list li').length;
		$('#'+id).remove();
		if(lastIndex < rel){
			$(li).insertAt(lastIndex,$('#category-view-list'));
		} else {
			$(li).insertAt(rel,$('#category-view-list'));
		}
		if($('#category-view-selection li').length === 2){
			$('#button-save-category').hide();
		} else {
			$('#button-save-category').show();
		}
	};

	$.fn.changeBrand = function(brand){
		$('#brand').val(brand);
		$('#button-save-product').show();
	}

	$(document).bind('click', function(e) {
		if(!$(e.target).is('#button-add-category')) {
			$.fn.hideList('category-view');
  		} else {
  			$.fn.showList('category-view');
  		}
  		if(!$(e.target).is('#brand')) {
			$.fn.hideList('brand-view');
  		} else {
  			$.fn.showList('brand-view');
  		}
	});
</script>