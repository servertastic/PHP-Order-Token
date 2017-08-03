<?php
include_once 'includes/header.php';
if (!$order->isSmarterTools()) {
  header("Location: /order");
}
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
	<li><a href="/order">Start</a></li>
	<li><a href="order_type.php" class="active">SmarterTools Email</a></li>
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
<div class="container">
	<form id="validetta" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
		<?php if ($order->fieldIsAllowed('smartertools_email')): ?>
			<div class="row">
				<div class="form-group col-md-6">
					<label for="smartertools_email">Smartertools Email Address</label>
					<input
						type="email"
						name="smartertools_email"
						id="smartertools_email"
						class="form-control"
						data-validetta="<?= $order->fieldHasRequired('smartertools_email') ?>,email"
						value="<?= $order->populateFormField('smartertools_email') ?>"
					>
				</div>
			</div>
		<?php endif ?>
		<div class="f-control">
			<input type="hidden" name="next_page" value="order_review">
			<input class="btn btn-primary btn-lg back" type="button" value="Back" onClick="history.go(-1);return true;">
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
