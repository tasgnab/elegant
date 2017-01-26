<div class="col-sm-10">
	<div class="contactinfo">
		<ul class="nav nav-pills">
			<li><a href="#"><i class="fa fa-phone"></i> <?php echo config_item('phone_number');?></a></li>
			<li><a href="#"><i class="fa fa-envelope"></i> <?php echo config_item('email_address');?></a></li>
		</ul>
	</div>
</div>
<div class="col-sm-10" id="message">
<?php 
	if (isset($result)){
		if (isset($message)){
			if ($result === 0){
				echo "<div class=\"alert alert-danger alert-dismissible\" role=\"alert\">";
			} else if ($result === 1){
				echo "<div class=\"alert alert-success alert-dismissible\" role=\"alert\">";
			} else if ($result === 2){
				echo "<div class=\"alert alert-warning alert-dismissible\" role=\"alert\">";
			}
			echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>";
				echo $message;
				echo "</div>";
		}
	}
?>
</div>