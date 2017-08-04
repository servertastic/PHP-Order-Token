<?php  
	include_once 'includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Web Order - Order Type</title>
	<link rel="stylesheet" href="css/vendor/normalize.css">
	<link rel="stylesheet" href="css/vendor/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/vendor/validetta.min.css">
	<link rel="stylesheet" href="css/main-style.css">
	<link rel="stylesheet" href="css/user-style.css">
</head>
<body>
	<div class="container header">
	  <?php if ($order->hasLogo()):?>
        <div class="logo-area">
          <img src="<?= $order->logoLocation ?>">
        </div>
	  <?php endif;?>
		<h2><?= $order->formdata->product_name ?></h2>
		<?php if ($order->TEST_MODE): ?>
			<p class="alert alert-danger">TEST MODE</p>
		<?php endif ?>
	</div>
	<ol class="container breadcrumb">
		<li><a href="/">Start</a></li>
		<li><a href="order_type.php" class="active">Order Type</a></li>
		<li><a href="contact_info.php" class="unlinked">Contact Information</a></li>
		<li><a href="order_csr.php" class="unlinked">CSR</a></li>
		<li><a href="order_organisation.php" class="unlinked">Organisation</a></li>
		<li><a href="order_review.php" class="unlinked">Review</a></li>
	</ol>
	<?php if (isset($order)): ?>
		<?php if (!empty(array_filter($order->error_log))): ?>
			<div class="container alert-box">
				<?php foreach ($order->error_log as $value): ?>
					<?php if (strpos($value, '401')): ?>
						<p class="alert alert-danger">Invalid Order Token, Please check it and try again.</p>
					<?php else: ?>
						<p class="alert alert-danger"><?= $value ?></p>
					<?php endif ?>
				<?php endforeach ?>
			</div>
		<?php endif ?>
	<?php endif ?>
	<div class="container order_type">
		<form id="validetta" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
			<div class="admin-details page-header">
				<h3>Order Type</h3>
			</div>
			<div class="radio new_order">
				<label>
					<input type="radio" name="order_type" value="new" id="new_order" data-validetta="required" <?= (isset($order->formdata->order_type) && $order->formdata->order_type == 'new'?'checked': '')?>>
					New Order
				</label>
				<p>Select this option if this is the first time you have ordered an SSL certificate for this domain</p>
			</div>
			<?php if ($order->fieldIsAllowed('competitive_upgrade')): ?>
				<div class="radio competitive">
					<label>
						<input type="radio" name="order_type" value="competitive_upgrade" id="competitive_upgrade" data-validetta="required" <?= (isset($order->formdata->order_type) && $order->formdata->order_type == 'competitive_upgrade'?'checked': '')?>>
						Competitive Upgrade
					</label>
					<p>Select this option if you have an existing certificate from GoDaddy, GlobalSign or Comodo. You might be entitled to up to 12 months free.</p>
				</div>
			<?php endif ?>
			<?php if ($order->fieldIsAllowed('renewal')): ?>
				<div class="radio renewal">
					<label for="ren">
						<input type="radio" name="order_type" value="renewal" id="renewal" data-validetta="required" <?= (isset($order->formdata->order_type) && $order->formdata->order_type == 'renewal'?'checked': '')?>>
						Renewal
					</label>
					<p>Select this option if you are renewing an order for the exact same domain (must be within 90 days of expiry).</p>
				</div>
			<?php endif ?>
			<div class="f-control">
				<input type="hidden" name="next_page" value="contact_info">
				<a class="btn btn-primary btn-lg back" href="/">Back</a>
				<input class="btn btn-success btn-lg forw" type="submit" name="page_submit" value="Next">
			</div>
		</form>
	</div>
	<script type="text/javascript" src="js/vendor/jquery-3.1.1.min.js"></script>
	<script type="text/javascript" src="js/vendor/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/vendor/validetta.min.js"></script>
	<script type="text/javascript" src="js/main.js" ></script>
</body>
</html>