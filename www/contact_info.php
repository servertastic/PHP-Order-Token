<?php  
	include_once 'includes/header.php';
?>
<?php $order->getISOCodes(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Web Order - Contact Information</title>
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
		<li><a href="order_type.php">Order Type</a></li>
		<li><a href="contact_info.php" class="active">Contact Information</a></li>
	  <?php if(strpos( $order->formdata->st_product_code,'AntiMalware')===0): ?>
        <li><a href="order_csr.php" class="unlinked">Domain Name</a></li>
	  <?php else:?>
        <li><a href="order_csr.php" class="unlinked">CSR</a></li>
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
			<div class="admin-details page-header">
				<h3>Admin Details</h3>
			</div>
			<div class="row admin_contact">
				<div class="col-sm-12">
					<div class="row">
						<?php if ($order->fieldIsAllowed('admin_contact_title')): ?>
							<div class="form-group col-xs-2 col-sm-2 col-md-2">
								<label for="admin_contact_title">Title</label>
								<input 
									type="text" 
									class="form-control" 
									id="admin_contact_title" 
									name="admin_contact_title" 
									data-validetta="<?= $order->fieldHasRequired('admin_contact_title') ?>" 
									value="<?= $order->populateFormField('admin_contact_title') ?>"
								>
						</div>
						<?php endif ?>
						<?php if ($order->fieldIsAllowed('admin_contact_first_name')): ?>
							<div class="form-group col-xs-4 col-sm-4 col-md-4">
								<label for="admin_contact_first_name">First Name</label>
								<input 
									type="text" 
									class="form-control" 
									id="admin_contact_first_name" 
									name="admin_contact_first_name" 
									data-validetta="<?= $order->fieldHasRequired('admin_contact_first_name') ?>" 
									value="<?= $order->populateFormField('admin_contact_first_name') ?>"
								>
							</div>
						<?php endif ?>
                        <?php if ($order->fieldIsAllowed('admin_contact_last_name')): ?>
							<div class="form-group col-xs-6 col-sm-6 col-md-6">
									<label for="admin_contact_last_name">Last Name</label>
									<input 
										type="text" 
										class="form-control" 
										id="admin_contact_last_name" 
										name="admin_contact_last_name" 
										data-validetta="<?= $order->fieldHasRequired('admin_contact_last_name') ?>" 
										value="<?= $order->populateFormField('admin_contact_last_name') ?>"
									>
							</div>
						<?php endif ?>
					</div>
                    <div class="row">
						<?php if ($order->fieldIsAllowed('admin_contact_email')): ?>
							<div class="form-group col-md-6">
								<label for="admin_contact_email">Email</label>
								<input 
									type="text" 
									class="form-control" 
									id="admin_contact_email" 
									name="admin_contact_email" 
									data-validetta="<?= $order->fieldHasRequired('admin_contact_email') ?>,email" 
									value="<?= $order->populateFormField('admin_contact_email') ?>"
								>
							</div>
						<?php endif ?>
						<?php if ($order->fieldIsAllowed('admin_contact_phone')): ?>
							<div class="form-group col-md-6">
								<label for="admin_contact_phone">Phone:</label>
								<input 
									type="tel" 
									class="form-control" 
									id="admin_contact_phone" 
									name="admin_contact_phone" 
									data-validetta="<?= $order->fieldHasRequired('admin_contact_phone') ?>,number" 
									value="<?= $order->populateFormField('admin_contact_phone') ?>"
								>
							</div>
						<?php endif ?>
					</div>
					<div class="row">
						<?php if ($order->fieldIsAllowed('admin_contact_organisation_name')): ?>
							<div class="form-group col-xs-6 col-sm-6 col-md-6">
								<label for="admin_contact_organisation_name">Organisation Name</label>
								<input 
									type="text" 
									id="admin_contact_organisation_name" 
									class="form-control" 
									name="admin_contact_organisation_name" 
									data-validetta="<?= $order->fieldHasRequired('admin_contact_organisation_name') ?>"
									value="<?= $order->populateFormField('admin_contact_organisation_name') ?>" 
								>
							</div>
						<?php endif ?>
					</div>
                    <div class="row">
						<?php if ($order->fieldIsAllowed('admin_contact_address_line1')): ?>
							<div class="form-group col-md-6">
								<label for="admin_contact_address_line1">Address Line 1</label>
								<input 
									type="text" 
									id="admin_contact_address_line1" 
									class="form-control" 
									name="admin_contact_address_line1" 
									data-validetta="<?= $order->fieldHasRequired('admin_contact_address_line1') ?>"
									value="<?= $order->populateFormField('admin_contact_address_line1') ?>" 
								>
							</div>
						<?php endif ?>
						<?php if ($order->fieldIsAllowed('admin_contact_address_line2')): ?>
							<div class="form-group col-md-6">
								<label for="admin_contact_address_line2">Address Line 2</label>
								<input 
									type="text" 
									id="admin_contact_address_line2" 
									class="form-control" 
									name="admin_contact_address_line2" 
									data-validetta="<?= $order->fieldHasRequired('admin_contact_address_line2') ?>"
									value="<?= $order->populateFormField('admin_contact_address_line2') ?>" 
								>
							</div>
						<?php endif ?>
					</div>
					<div class="row">
						<?php if ($order->fieldIsAllowed('admin_contact_address_city')): ?>
							<div class="form-group col-md-3">
								<label for="admin_contact_address_city">City</label>
								<input 
									type="text" 
									id="admin_contact_address_city" 
									class="form-control" 
									name="admin_contact_address_city" 
									data-validetta="<?= $order->fieldHasRequired('admin_contact_address_city') ?>"
									value="<?= $order->populateFormField('admin_contact_address_city') ?>" 
								>
							</div>
						<?php endif ?>
						<?php if ($order->fieldIsAllowed('admin_contact_address_country')): ?>
							<div class="form-group col-md-3">
								<label for="admin_contact_address_country">Country </label>
								<select 
									name="admin_contact_address_country" 
									data-validetta="<?= $order->fieldHasRequired('admin_contact_address_country') ?>" 
									id="admin_contact_address_country" 
									class="form-control"
								>
									<?php foreach ($order->iso_codes as $value): ?>
										<option value="<?= $value['code'] ?>" <?= $order->isSelected('admin_contact_address_country',$value['code']) ?>><?= $value['name'] ?></option>
									<?php endforeach ?>
								</select>
							</div>
						<?php endif ?>
                        <?php if ($order->fieldIsAllowed('admin_contact_address_region')): ?>
							<div class="form-group col-md-3">
								<label for="admin_contact_address_region">Region</label>
								<input 
									type="text" 
									id="admin_contact_address_region" 
									class="form-control" 
									name="admin_contact_address_region" 
									data-validetta="<?= $order->fieldHasRequired('admin_contact_address_region') ?>" 
									value="<?= $order->populateFormField('admin_contact_address_region') ?>"
								>
							</div>
						<?php endif ?>
						<?php if ($order->fieldIsAllowed('admin_contact_address_post_code')): ?>
							<div class="form-group col-md-3">
								<label for="admin_contact_address_post_code">Post Code</label>
								<input 
									type="text" 
									id="admin_contact_address_post_code" 
									class="form-control" 
									name="admin_contact_address_post_code" 
									data-validetta="<?= $order->fieldHasRequired('admin_contact_address_post_code') ?>" 
									value="<?= $order->populateFormField('admin_contact_address_post_code') ?>"
								>
							</div>
						<?php endif ?>
					</div>
				</div>
			</div>
			<div class="admin-details page-header">
				<h3>Tech Details</h3>
				<div class="checkbox">
			    <label>
			      <input type="checkbox" autocomplete="off" id="same_as_admin"> Tech details are the same as Admin
			    </label>
			  </div>
			</div>
            <div class="row tech_contact">
				<div class="col-sm-12">
					<div class="row">
						<?php if ($order->fieldIsAllowed('tech_contact_title')): ?>
							<div class="form-group col-xs-2 col-sm-2 col-md-2">
								<label for="tech_contact_title">Title</label>
								<input 
									type="text" 
									class="form-control" 
									id="tech_contact_title" 
									name="tech_contact_title" 
									data-validetta="<?= $order->fieldHasRequired('tech_contact_title') ?>" 
									value="<?= $order->populateFormField('tech_contact_title') ?>"
								>
						</div>
						<?php endif ?>
						<?php if ($order->fieldIsAllowed('tech_contact_first_name')): ?>
							<div class="form-group col-xs-4 col-sm-4 col-md-4">
								<label for="tech_contact_first_name">First Name</label>
								<input 
									type="text" 
									class="form-control" 
									id="tech_contact_first_name" 
									name="tech_contact_first_name" 
									data-validetta="<?= $order->fieldHasRequired('tech_contact_first_name') ?>" 
									value="<?= $order->populateFormField('tech_contact_first_name') ?>"
								>
							</div>
						<?php endif ?>
                        <?php if ($order->fieldIsAllowed('tech_contact_last_name')): ?>
							<div class="form-group col-xs-6 col-sm-6 col-md-6">
									<label for="tech_contact_last_name">Last Name</label>
									<input 
										type="text" 
										class="form-control" 
										id="tech_contact_last_name" 
										name="tech_contact_last_name" 
										data-validetta="<?= $order->fieldHasRequired('tech_contact_last_name') ?>" 
										value="<?= $order->populateFormField('tech_contact_last_name') ?>"
									>
							</div>
						<?php endif ?>
					</div>
                    <div class="row">
						<?php if ($order->fieldIsAllowed('tech_contact_email')): ?>
							<div class="form-group col-md-6">
								<label for="tech_contact_email">Email</label>
								<input 
									type="text" 
									class="form-control" 
									id="tech_contact_email" 
									name="tech_contact_email" 
									data-validetta="<?= $order->fieldHasRequired('tech_contact_email') ?>,email" 
									value="<?= $order->populateFormField('tech_contact_email') ?>"
								>
							</div>
						<?php endif ?>
						<?php if ($order->fieldIsAllowed('tech_contact_phone')): ?>
							<div class="form-group col-md-6">
								<label for="tech_contact_phone">Phone:</label>
								<input 
									type="tel" 
									class="form-control" 
									id="tech_contact_phone" 
									name="tech_contact_phone" 
									data-validetta="<?= $order->fieldHasRequired('tech_contact_phone') ?>,number" 
									value="<?= $order->populateFormField('tech_contact_phone') ?>"
								>
							</div>
						<?php endif ?>
					</div>
					<div class="row">
						<?php if ($order->fieldIsAllowed('tech_contact_organisation_name')): ?>
							<div class="form-group col-xs-6 col-sm-6 col-md-6">
								<label for="tech_contact_organisation_name">Organisation Name</label>
								<input 
									type="text" 
									id="tech_contact_organisation_name" 
									class="form-control" 
									name="tech_contact_organisation_name" 
									data-validetta="<?= $order->fieldHasRequired('tech_contact_organisation_name') ?>"
									value="<?= $order->populateFormField('tech_contact_organisation_name') ?>" 
								>
							</div>
						<?php endif ?>
					</div>
                    <div class="row">
						<?php if ($order->fieldIsAllowed('tech_contact_address_line1')): ?>
							<div class="form-group col-md-6">
								<label for="tech_contact_address_line1">Address Line 1</label>
								<input 
									type="text" 
									id="tech_contact_address_line1" 
									class="form-control" 
									name="tech_contact_address_line1" 
									data-validetta="<?= $order->fieldHasRequired('tech_contact_address_line1') ?>"
									value="<?= $order->populateFormField('tech_contact_address_line1') ?>" 
								>
							</div>
						<?php endif ?>
						<?php if ($order->fieldIsAllowed('tech_contact_address_line2')): ?>
							<div class="form-group col-md-6">
								<label for="tech_contact_address_line2">Address Line 2</label>
								<input 
									type="text" 
									id="tech_contact_address_line2" 
									class="form-control" 
									name="tech_contact_address_line2" 
									data-validetta="<?= $order->fieldHasRequired('tech_contact_address_line2') ?>"
									value="<?= $order->populateFormField('tech_contact_address_line2') ?>" 
								>
							</div>
						<?php endif ?>
					</div>
					<div class="row">
						<?php if ($order->fieldIsAllowed('tech_contact_address_city')): ?>
							<div class="form-group col-md-3">
								<label for="tech_contact_address_city">City</label>
								<input 
									type="text" 
									id="tech_contact_address_city" 
									class="form-control" 
									name="tech_contact_address_city" 
									data-validetta="<?= $order->fieldHasRequired('tech_contact_address_city') ?>"
									value="<?= $order->populateFormField('tech_contact_address_city') ?>" 
								>
							</div>
						<?php endif ?>
						<?php if ($order->fieldIsAllowed('tech_contact_address_country')): ?>
							<div class="form-group col-md-3">
								<label for="tech_contact_address_country">Country </label>
								<select 
									name="tech_contact_address_country" 
									data-validetta="<?= $order->fieldHasRequired('tech_contact_address_country') ?>" 
									id="tech_contact_address_country" 
									class="form-control"
								>
									<?php foreach ($order->iso_codes as $value): ?>
										<option value="<?= $value['code'] ?>" <?= $order->isSelected('tech_contact_address_country',$value['code']) ?>><?= $value['name'] ?></option>
									<?php endforeach ?>
								</select>
							</div>
						<?php endif ?>
                        <?php if ($order->fieldIsAllowed('tech_contact_address_region')): ?>
							<div class="form-group col-md-3">
								<label for="tech_contact_address_region">Region</label>
								<input 
									type="text" 
									id="tech_contact_address_region" 
									class="form-control" 
									name="tech_contact_address_region" 
									data-validetta="<?= $order->fieldHasRequired('tech_contact_address_region') ?>" 
									value="<?= $order->populateFormField('tech_contact_address_region') ?>"
								>
							</div>
						<?php endif ?>
						<?php if ($order->fieldIsAllowed('tech_contact_address_post_code')): ?>
							<div class="form-group col-md-3">
								<label for="tech_contact_address_post_code">Post Code</label>
								<input 
									type="text" 
									id="tech_contact_address_post_code" 
									class="form-control" 
									name="tech_contact_address_post_code" 
									data-validetta="<?= $order->fieldHasRequired('tech_contact_address_post_code') ?>" 
									value="<?= $order->populateFormField('tech_contact_address_post_code') ?>"
								>
							</div>
						<?php endif ?>
					</div>
				</div>
			</div>
			<div class="clearfix f-control">
				<input type="hidden" name="next_page" value="order_csr">
				<input class="btn btn-primary btn-lg back" type="button" value="Back" onClick="history.go(-1);return true;">
        <?php if ($order->fieldIsAllowed('domain_name')): ?>
          <?php if(strpos( $order->formdata->st_product_code,'AntiMalware')===0): ?>
                <input type="submit" class="btn btn-success btn-lg forw" name="page_submit" value="Next">
          <?php else:?>
                <input class="btn btn-success btn-lg forw" type="submit" name="page_submit" value="Auto Generate CSR">
          <?php endif; ?>
        <?php endif; ?>
        <?php if ($order->fieldIsAllowed('csr')): ?>
              <input class="btn btn-success btn-lg forw" type="submit" name="page_submit" value="Supply CSR">
        <?php endif; ?>
			</div>
		</form>
	</div>
<?php include "includes/footer.php"?>