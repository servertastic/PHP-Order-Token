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
	<?php  
	include_once 'includes/headtag.php';
	?>
</head>
<body>
	<?php  
	include_once 'includes/bodytagtop.php';
	?>
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
		<li><a href="order_type.php" class="">Order Type</a></li>
		<li><a href="contact_info.php" class="">Contact Information</a></li>
	  <?php if(strpos( $order->formdata->st_product_code,'AntiMalware')===0): ?>
        <li><a href="order_csr.php" class="active">Domain Name</a></li>
	  <?php else:?>
        <li><a href="order_csr.php" class="active">CSR</a></li>
	  <?php endif;?>
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
			<?php if ($order->formdata->domain_or_csr == 'csr'): ?>
				<div class="admin-details page-header">
					<h3>CSR Generation</h3>
				</div>
				<div class="alert alert-info alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					If you want to provide a CSR please use the form below.
				</div>
				<div class="form-group">
					<label for="csr">Provide CSR:</label>
					<textarea name="csr" class="form-control" data-validetta="required" id="csr" cols="30"  rows="10" ><?= $order->populateFormField('csr') ?></textarea>
				</div>
			<?php else: ?>
				<div class="admin-details page-header">
					<h3>Provide Domain</h3>
				</div>
				<div class="row">
					<div class="form-group col-md-4">
						<label for="domain_name">Domain Name</label>
						<input 
							type="text"
							class="form-control"
							id="domain_name"
							data-validetta="required"
							name="domain_name"
							value="<?= $order->populateFormField('domain_name') ?>" 
						>
					</div>
				</div>
			<?php endif ?>
			<?php if ($order->fieldIsAllowed('san_domains') && $order->formdata->san_count>0): ?>
				<div class="form-group san_domains">
					<label for=""><?= (isset($order->formdata->san_count)?$order->formdata->san_count:"") ?> SAN Domains (comma seperated): </label>
					<textarea 
						type="text" 
						class="form-control" 
						name="san_domains"
						data-validetta="required" 
					><?php if ($order->populateFormField('san_domains')): ?><?= implode(',',$order->populateFormField('san_domains')) ?><?php endif ?></textarea>
				</div>
			<?php endif ?>
			<?php if ($order->fieldIsAllowed('hashing_algorithm')): ?>
				<label>Hashing Algorithm: </label>
					<?php foreach ($order->hash_algo_fields as $value): ?>
						<div class="radio hashing_algorithm">
							<label><input type="radio" name="hashing_algorithm" data-validetta="required" value="<?= $value ?>" <?= ((isset($order->formdata->hashing_algorithm)&&$order->formdata->hashing_algorithm == $value)||(count($order->hash_algo_fields)===1)?'checked=""': '')?>><?= $value ?></label>
						</div>
					<?php endforeach ?>
			<?php endif ?>
			
			<div class="f-control">
				<input type="hidden" name="next_page" value="order_organisation">
				<input class="btn btn-primary btn-lg back" type="button" value="Back" onClick="history.go(-1);return true;">
				<input class="btn btn-success btn-lg forw" type="submit" name="page_submit" value="Next">
			</div>
		</form>
	</div>
<?php include "includes/footer.php"?>