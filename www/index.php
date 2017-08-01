<?php  
	include_once 'includes/header.php';
	
	// check if the page has been submitted
	if (isset($_POST['indexsub'])) {
		// start to build the form
		$order = new STOrderManager($_POST['order_token'],true);

		// if no errors then go to the first step
		if (empty($order->error_log)) {
			// check that the order hasnt already been tested before
			header('Location:order_type.php');
			die();
			// if ($order->checkTokenStatus($_POST['order_token'])) {
			// 	die();
			// }
				
		}
	} elseif (isset($_GET['order_token'])) {
		// check if the user hasnt stored the order token in the get request
		$order = new STOrderManager($_GET['order_token'],true);

		if (empty($order->error_log)) {
			header('Location:order_type.php');
			die();
			// if ($order->checkTokenStatus($_GET['order_token'])) {
			// 	die();
			// }
		}
	} elseif (isset($_SESSION['st_webform'])&&!empty($_SESSION['st_webform'])) {
		if (isset($_SESSION['st_webform']['order_completion'])) {
			// destruct the session here as they have gone back to start after completing the order
			
			// keep the orderkey just incase they are looking to check authentication
			$orderkey = $_SESSION['st_webform']['order_token'];
			unset($_SESSION['st_webform']);
			$_SESSION['st_webform']['order_token'] = $orderkey;
		} else {
			// if session is already set, then pass the data to the class to populate data
			$order = new STOrderManager($_SESSION['st_webform']['order_token']);
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Web Order - Start</title>
	<link rel="stylesheet" href="css/vendor/normalize.css">
	<link rel="stylesheet" href="css/vendor/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/vendor/validetta.min.css">
	<link rel="stylesheet" href="css/main-style.css">
	<link rel="stylesheet" href="css/user-style.css">
</head>
<body>
	<div class="container header">
	  <?php if (isset($order)): ?>
	  <?php if ($order->hasLogo()):?>
        <div class="logo-area">
          <img src="<?= $order->logoLocation ?>">
        </div>
	  <?php endif;?>
	  <?php endif;?>
		<?php if (isset($order)): ?>
			<?php if (isset($order->formdata->product_name)): ?>
				<h2><?= $order->formdata->product_name ?></h2>
			<?php endif ?>
			<?php if ($order->TEST_MODE): ?>
				<p class="alert alert-danger">TEST MODE</p>
			<?php endif ?>
		<?php endif ?>
	</div>
	<ol class="container breadcrumb">
		<li><a href="/" class="active">Start</a></li>
		<li><a href="order_type.php" class="unlinked">Order Type</a></li>
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
	<div class="container">
		<form id="validetta" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
			<div class="form-group">
				<label for="order_token">Order Token</label>
				<input type="text" id="order_token" class="form-control" name="order_token" data-validetta='required' <?php if (isset($order->formdata->order_token)): ?>
					value="<?= $order->populateFormField('order_token') ?>"
				<?php elseif(isset($_SESSION['st_webform']['order_token'])): ?>
					value="<?= $_SESSION['st_webform']['order_token'] ?>"
				<?php endif ?>>
			</div>
			<div class="f-control">
				<input class="btn btn-success btn-lg" type="submit" name="indexsub" value="Start Order">
			</div>
		</form>
	</div>
	<script type="text/javascript" src="js/vendor/jquery-3.1.1.min.js"></script>
	<script type="text/javascript" src="js/vendor/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/vendor/validetta.min.js"></script>
	<script type="text/javascript" src="js/main.js" ></script>
</body>
</html>