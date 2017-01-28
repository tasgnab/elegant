<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">
	<?php include_once('common/header.php'); ?>
	<body id="page-top">
		<?php include_once('common/nav.php'); ?>

		<section class="templatemo-container padding-0">
			
				<div class="templatemo-block-left contact">
					<div class="templatemo-contact-info">
						<h2 class="text-uppercase">about us</h2>
					<hr class="templatemo-section-header-hr w-120">
					<p class="text-uppercase templatemo-section-subheader">Information</p>
					<p class="margin-bottom-20">Aenean non ex neque. Sed vitae purus in urna volutpat iaculis. Ut congue vel tortor in tincidunt. Maecenas varius pellentesque.</p>
					<form action="#" method="post" class="tm-contact-form">
						<div class="tm-contact-form">
							<div class="form-group">
								<input type="text" id="contact_name" class="form-control" placeholder="Name..." />
							</div>
							<div class="form-group">
								<input type="email" id="contact_email" class="form-control" placeholder="Email..." />
							</div>
							<div class="form-group">
								<input type="text" id="contact_subject" class="form-control" placeholder="Subject..." />
							</div>
							<div class="form-group">
								<textarea id="contact_message" class="form-control" rows="8" placeholder="Message..."></textarea>
							</div>
						</div>
						<button type="submit" name="submit" class="btn text-uppercase templatemo-btn-gold">Submit</button>
					</form> 
					</div>					
				</div>
				<div class="templatemo-block-right">
					<div id="google-map"></div>
				</div>
			
		</section>	 

		<footer class="text-center">
			<p class="text-uppercase">
				Copyright &copy; 2084 Company Name 
				| Design: <a href="http://www.templatemo.com">template mo</a>
			</p>
		</footer>

		<?php include_once('common/jquery.php'); ?>
		<script>
			$(document).ready(function(){				
				loadGoogleMap();				
			});
		</script>
	</body>
</html>
