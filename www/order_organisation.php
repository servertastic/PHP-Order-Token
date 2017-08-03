<?php
include_once 'includes/header.php';
?>
<?php $order->getISOCodes(); ?>
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
  <li><a href="order_type.php" class="">Order Type</a></li>
  <li><a href="contact_info.php" class="">Contact Information</a></li>
  <li><a href="order_csr.php" class="">CSR</a></li>
  <li><a href="order_organisation.php" class="active">Organisation</a></li>
  <li><a href="order_review.php" class="unlinked">Review</a></li>
</ol>
<?php if (isset($order)): ?>
    <?php if ( ! empty(array_filter($order->error_log))): ?>
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
<div class="container order_organisation">
  <form id="validetta" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
    <div class="admin-details page-header">
      <h3>Organisation Details</h3>
    </div>
      <?php if ($order->fieldIsAllowed('org_name')): ?>
        <div class="row">
          <div class="form-group col-md-6">
            <label for="org_name">Name</label>
            <input
                type="text"
                class="form-control"
                id="org_name"
                name="org_name"
                class="form-control"
                data-validetta="<?= $order->fieldHasRequired('org_name') ?>"
                value="<?= $order->populateFormField('org_name') ?>"
            >
          </div>
        </div>
      <?php else: ?>
          <?php // if org_name is not allowed then none of it will be required so redirect to review ?>
          <?php header("Location:order_review.php") ?>
      <?php endif ?>
      <?php if ($order->fieldIsAllowed('org_duns')): ?>
        <div class="row">
          <div class="form-group col-md-6">
            <label for="org_duns">Organisation DUNS</label>
            <input
                type="text"
                class="form-control"
                name="org_duns"
                id="org_duns"
                data-validetta="<?= $order->fieldHasRequired('org_duns') ?>"
                value="<?= $order->populateFormField('org_duns') ?>"
            >
          </div>
        </div>
      <?php endif ?>
      <?php if ($order->fieldIsAllowed('org_division')): ?>
        <div class="row">
          <div class="form-group col-md-6">
            <label for="org_division">Division</label>
            <input
                type="text"
                name="org_division"
                id="org_division"
                class="form-control"
                data-validetta="<?= $order->fieldHasRequired('org_division') ?>"
                value="<?= $order->populateFormField('org_division') ?>"
            >
          </div>
        </div>
      <?php endif ?>
      <?php if ($order->fieldIsAllowed('org_incorporating_agency')): ?>
        <div class="row">
          <div class="form-group col-md-6">
            <label for="org_incorporating_agency">Organisation Incorporating Agency</label>
            <input
                type="text"
                class="form-control"
                name="org_incorporating_agency"
                id="org_incorporating_agency"
                data-validetta="<?= $order->fieldHasRequired('org_incorporating_agency') ?>"
                value="<?= $order->populateFormField('org_incorporating_agency') ?>"
            >
          </div>
        </div>
      <?php endif ?>
      <?php if ($order->fieldIsAllowed('org_registration_number')): ?>
        <div class="row">
          <div class="form-group col-md-6">
            <label for="org_registration_number">Organisation Registration Number</label>
            <input
                type="text"
                class="form-control"
                name="org_registration_number"
                id="org_registration_number"
                data-validetta="<?= $order->fieldHasRequired('org_registration_number') ?>"
                value="<?= $order->populateFormField('org_registration_number') ?>"
            >
          </div>
        </div>
      <?php endif ?>
      <?php if ($order->fieldIsAllowed('org_incorporating_date')): ?>
      <div class="row">
        <div class="form-group col-md-6">
          <label for="org_incorporating_date">Organisation Incorporating Date</label>
          <input
              type="date"
              class="form-control"
              name="org_incorporating_date"
              id="org_incorporating_date"
              data-validetta="<?= $order->fieldHasRequired('org_incorporating_date') ?>"
              value="<?= $order->populateFormField('org_incorporating_date') ?>"
          >
        </div>
      </div>
      <?php endif ?>
      <?php if ($order->fieldIsAllowed('org_assumed_name')): ?>
        <div class="row">
          <div class="form-group col-md-6">
            <label for="org_assumed_name">Organisation Assumed <small>(does business as)</small> Name</label>
            <input
                type="text"
                class="form-control"
                name="org_assumed_name"
                id="org_assumed_name"
                data-validetta="<?= $order->fieldHasRequired('org_assumed_name') ?>"
                value="<?= $order->populateFormField('org_assumed_name') ?>"
            >
          </div>
        </div>
      <?php endif ?>
      <?php if ($order->fieldIsAllowed('org_business_category')): ?>
        <div class="row">
          <div class="form-group col-md-6">
            <label for="org_business_category">Business Category</label>
            <select
                name="org_business_category"
                id="org_business_category"
                class="form-control"
                data-validetta="<?= $order->fieldHasRequired('org_business_category') ?>"
            >
                <?php foreach ($order->business_categories as $value): ?>
                  <option
                      value="<?= $value['code'] ?>" <?= $order->isSelected('org_business_category', $value['code']) ?>><?= $value['name'] ?></option>
                <?php endforeach ?>
            </select>
          </div>
        </div>
      <?php endif ?>
      <?php if ($order->fieldIsAllowed('org_jurisdiction_city')): ?>
        <div class="row">
          <div class="form-group col-md-6">
            <label for="org_jurisdiction_city">Organisation Jurisdiction City</label>
            <input
                type="text"
                class="form-control"
                name="org_jurisdiction_city"
                id="org_jurisdiction_city"
                data-validetta="<?= $order->fieldHasRequired('org_jurisdiction_city') ?>"
                value="<?= $order->populateFormField('org_jurisdiction_city') ?>"
            >
          </div>
        </div>
      <?php endif ?>
      <?php if ($order->fieldIsAllowed('org_jurisdiction_region')): ?>
        <div class="row">
          <div class="form-group col-md-6">
            <label for="org_jurisdiction_region">Organisation Jurisdiction Region</label>
            <input
                type="text"
                class="form-control"
                name="org_jurisdiction_region"
                id="org_jurisdiction_region"
                data-validetta="<?= $order->fieldHasRequired('org_jurisdiction_region') ?>"
                value="<?= $order->populateFormField('org_jurisdiction_region') ?>"
            >
          </div>
        </div>
      <?php endif ?>
      <?php if ($order->fieldIsAllowed('org_jurisdiction_country')): ?>
        <div class="row">
          <div class="form-group col-md-6">
            <label for="org_jurisdiction_country">Organisation Jurisdiction Country</label>
            <select
                name="org_jurisdiction_country"
                id="org_jurisdiction_country"
                class="form-control"
                data-validetta="<?= $order->fieldHasRequired('org_jurisdiction_country') ?>"
            >
                <?php foreach ($order->iso_codes as $value): ?>
                  <option
                      value="<?= $value['code'] ?>" <?= $order->isSelected('org_jurisdiction_country', $value['code']) ?>><?= $value['name'] ?></option>
                <?php endforeach ?>
            </select>
          </div>
        </div>
      <?php endif ?>
      <?php if ($order->fieldIsAllowed('org_address_line1')): ?>
        <div class="row">
          <div class="form-group col-md-6">
            <label for="org_address_line1">Address Line 1</label>
            <input
                type="text"
                name="org_address_line1"
                class="form-control"
                id="org_address_line1"
                data-validetta="<?= $order->fieldHasRequired('org_address_line1') ?>"
                value="<?= $order->populateFormField('org_address_line1') ?>"
            >
          </div>
        </div>
      <?php endif ?>
      <?php if ($order->fieldIsAllowed('org_address_line2')): ?>
        <div class="row">
          <div class="form-group col-md-6">
            <label for="org_address_line2">Address Line 2</label>
            <input
                type="text"
                name="org_address_line2"
                class="form-control"
                id="org_address_line2"
                data-validetta="<?= $order->fieldHasRequired('org_address_line2') ?>"
                value="<?= $order->populateFormField('org_address_line2') ?>"
            >
          </div>
        </div>
      <?php endif ?>
      <?php if ($order->fieldIsAllowed('org_address_line3')): ?>
        <div class="row">
          <div class="form-group col-md-6">
            <label for="org_address_line3">Address Line 3</label>
            <input
                type="text"
                name="org_address_line3"
                class="form-control"
                id="org_address_line3"
                data-validetta="<?= $order->fieldHasRequired('org_address_line3') ?>"
                value="<?= $order->populateFormField('org_address_line3') ?>"
            >
          </div>
        </div>
      <?php endif ?>
      <?php if ($order->fieldIsAllowed('org_address_city')): ?>
        <div class="row">
          <div class="form-group col-md-6">
            <label for="org_address_city">City</label>
            <input
                type="text"
                id="org_address_city"
                name="org_address_city"
                class="form-control"
                data-validetta="<?= $order->fieldHasRequired('org_address_city') ?>"
                value="<?= $order->populateFormField('org_address_city') ?>"
            >
          </div>
        </div>
      <?php endif ?>
      <?php if ($order->fieldIsAllowed('org_address_region')): ?>
        <div class="row">
          <div class="form-group col-md-6">
            <label for="org_address_region">Region</label>
            <input
                type="text"
                id="org_address_region"
                name="org_address_region"
                class="form-control"
                data-validetta="<?= $order->fieldHasRequired('org_address_region') ?>"
                value="<?= $order->populateFormField('org_address_region') ?>"
            >
          </div>
        </div>
      <?php endif ?>
      <?php if ($order->fieldIsAllowed('org_address_postal_code')): ?>
        <div class="row">
          <div class="form-group col-md-6">
            <label for="org_address_postal_code">Postal Code</label>
            <input
                class="form-control"
                type="text"
                name="org_address_postal_code"
                id="org_address_postal_code"
                data-validetta="<?= $order->fieldHasRequired('org_address_postal_code') ?>"
                value="<?= $order->populateFormField('org_address_postal_code') ?>"
            >
          </div>
        </div>
      <?php endif ?>
      <?php if ($order->fieldIsAllowed('org_address_country')): ?>
        <div class="row">
          <div class="form-group col-md-6">
            <label for="org_address_country">Country</label>
            <select
                name="org_address_country"
                id="org_address_country"
                class="form-control"
                data-validetta="<?= $order->fieldHasRequired('org_address_country') ?>"
            >
              <option selected disabled hidden style='display: none' value=''></option>
                <?php foreach ($order->iso_codes as $value): ?>
                  <option <?= $order->isSelected("org_address_country", $value['code']) ?>
                      value="<?= $value['code'] ?>"><?= $value['name'] ?></option>
                <?php endforeach ?>
            </select>
          </div>
        </div>
      <?php endif ?>
      <?php if ($order->fieldIsAllowed('org_address_phone')): ?>
        <div class="row">
          <div class="form-group col-md-6">
            <label for="org_address_phone">Telephone</label>
            <input
                type="tel"
                name="org_address_phone"
                id="org_address_phone"
                class="form-control"
                data-validetta="<?= $order->fieldHasRequired('org_address_phone') ?>,number"
                value="<?= $order->populateFormField('org_address_phone') ?>"
            >
          </div>
        </div>
      <?php endif ?>
      <?php if ($order->fieldIsAllowed('ida_email_address')): ?>
        <div class="row">
          <div class="form-group col-md-6">
            <label for="ida_email_address">TrustLogo Email Address</label>
            <input
                type="email"
                name="ida_email_address"
                id="ida_email_address"
                class="form-control"
                data-validetta="<?= $order->fieldHasRequired('ida_email_address') ?>,email"
                value="<?= $order->populateFormField('ida_email_address') ?>"
            >
          </div>
        </div>
      <?php endif ?>
      <?php if ($order->fieldIsAllowed('ida_telephone_number')): ?>
        <div class="row">
          <div class="form-group col-md-6">
            <label for="ida_telephone_number">TrustLogo Telephone Number</label>
            <input
                type="tel"
                name="ida_telephone_number"
                id="ida_telephone_number"
                class="form-control"
                data-validetta="<?= $order->fieldHasRequired('ida_telephone_number') ?>,number"
                value="<?= $order->populateFormField('ida_telephone_number') ?>"
            >
          </div>
        </div>
      <?php endif ?>
      <?php if ($order->fieldIsAllowed('ida_fax_number')): ?>
        <div class="row">
          <div class="form-group col-md-6">
            <label for="org_address_phone">TrustLogo Fax Number</label>
            <input
                type="text"
                name="ida_fax_number"
                id="ida_fax_number"
                class="form-control"
                data-validetta="<?= $order->fieldHasRequired('ida_fax_number') ?>"
                value="<?= $order->populateFormField('ida_fax_number') ?>"
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
<script type="text/javascript" src="js/main.js"></script>
</body>
</html>