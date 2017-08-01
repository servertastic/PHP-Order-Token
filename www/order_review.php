<?php
include_once 'includes/header.php';
?>
<?php $order->getAppoverList(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Web Order - Order Type</title>
  <link rel="stylesheet" href="css/vendor/normalize.css">
  <link rel="stylesheet" href="css/vendor/bootstrap.css">
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
  <li><a href="order_type.php">Order Type</a></li>
  <li><a href="contact_info.php">Contact Information</a></li>
  <li><a href="order_csr.php">CSR</a></li>
  <li><a href="order_organisation.php">Organisation</a></li>
  <li><a href="order_review.php" class="active">Review</a></li>
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
<div class="container order_review">
  <div class="admin-details page-header">
    <h3>Review Your Order</h3>
  </div>
  <div class="panel panel-primary">
    <div class="panel-body">
      <div class="product_name"><strong>Product: </strong><?= $order->formdata->product_name ?></div>
      <div class="unique-reference"><strong>Unique Reference: </strong><?= $order->formdata->reseller_order_id ?></div>
      <div class="order_type"><strong>Order Type: </strong><?= $order->formdata->order_type ?></div>
      <div class="order_token"><strong>Order Token: </strong><?= $order->formdata->order_token ?></div>
    </div>
  </div>
  <div class="page-header">
    <h4>Contact Details</h4>
  </div>
  <div class="row">
    <div class="col-sm-6">
      <div class="panel panel-primary admin_contact_details">
        <div class="panel-heading">
          <h5 class="panel-title">Administrative</h5>
        </div>
        <div class="panel-body">
            <?php if ($order->fieldIsAllowed('admin_contact_first_name')): ?>
              <div class="admin_contact_first_name">
                <strong>First Name: </strong>
                  <?= $order->formdata->admin_contact_first_name ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('admin_contact_last_name')): ?>
              <div class="admin_contact_last_name">
                <strong>Last Name: </strong>
                  <?= $order->formdata->admin_contact_last_name ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('admin_contact_title')): ?>
              <div class="admin_contact_title">
                <strong>Title: </strong>
                  <?= $order->formdata->admin_contact_title ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('admin_contact_phone')): ?>
              <div class="admin_contact_phone">
                <strong>Phone Number: </strong>
                  <?= $order->formdata->admin_contact_phone ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('admin_contact_email')): ?>
              <div class="admin_contact_email">
                <strong>Email Address: </strong>
                  <?= $order->formdata->admin_contact_email ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('admin_contact_organisation_name')): ?>
              <div class="admin_contact_organisation_name">
                <strong>Organisation Name: </strong>
                  <?= $order->formdata->admin_contact_organisation_name ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('admin_contact_address_line1')): ?>
              <div class="admin_contact_address_line1">
                <strong>Address Line 1: </strong>
                  <?= $order->formdata->admin_contact_address_line1 ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('admin_contact_address_line2')): ?>
              <div class="admin_contact_address_line2">
                <strong>Address Line 2: </strong>
                  <?= $order->formdata->admin_contact_address_line2 ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('admin_contact_address_post_code')): ?>
              <div class="admin_contact_address_post_code">
                <strong>Postcode: </strong>
                  <?= $order->formdata->admin_contact_address_post_code ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('admin_contact_address_city')): ?>
              <div class="admin_contact_address_city">
                <strong>City: </strong>
                  <?= $order->formdata->admin_contact_address_city ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('admin_contact_address_region')): ?>
              <div class="admin_contact_address_region">
                <strong>Region: </strong>
                  <?= $order->formdata->admin_contact_address_region ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('admin_contact_address_country')): ?>
              <div class="admin_contact_address_country">
                <strong>Country: </strong>
                  <?= $order->formdata->admin_contact_address_country ?>
              </div>
            <?php endif ?>
          <br>
          <a href="contact_info.php" class="btn btn-primary changer">Change Information</a>
        </div>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="panel panel-primary tech_contact_details">
        <div class="panel-heading">
          <h5 class="panel-title">Technical</h5>
        </div>
        <div class="panel-body">
            <?php if ($order->fieldIsAllowed('tech_contact_first_name')): ?>
              <div class="tech_contact_first_name">
                <strong>First Name: </strong>
                  <?= $order->formdata->tech_contact_first_name ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('tech_contact_last_name')): ?>
              <div class="tech_contact_last_name">
                <strong>Last Name: </strong>
                  <?= $order->formdata->tech_contact_last_name ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('tech_contact_title')): ?>
              <div class="tech_contact_title">
                <strong>Title: </strong>
                  <?= $order->formdata->tech_contact_title ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('tech_contact_phone')): ?>
              <div class="tech_contact_phone">
                <strong>Phone Number: </strong>
                  <?= $order->formdata->tech_contact_phone ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('tech_contact_email')): ?>
              <div class="tech_contact_email">
                <strong>Email Address: </strong>
                  <?= $order->formdata->tech_contact_email ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('tech_contact_organisation_name')): ?>
              <div class="tech_contact_organisation_name">
                <strong>Organisation Name: </strong>
                  <?= $order->formdata->tech_contact_organisation_name ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('tech_contact_address_line1')): ?>
              <div class="tech_contact_address_line1">
                <strong>Address Line 1: </strong>
                  <?= $order->formdata->tech_contact_address_line1 ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('tech_contact_address_line2')): ?>
              <div class="tech_contact_address_line2">
                <strong>Address Line 2: </strong>
                  <?= $order->formdata->tech_contact_address_line2 ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('tech_contact_address_post_code')): ?>
              <div class="tech_contact_address_post_code">
                <strong>Postcode: </strong>
                  <?= $order->formdata->tech_contact_address_post_code ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('tech_contact_address_city')): ?>
              <div class="tech_contact_address_city">
                <strong>City: </strong>
                  <?= $order->formdata->tech_contact_address_city ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('tech_contact_address_region')): ?>
              <div class="tech_contact_address_region">
                <strong>Region: </strong>
                  <?= $order->formdata->tech_contact_address_region ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('tech_contact_address_country')): ?>
              <div class="tech_contact_address_country">
                <strong>Country: </strong>
                  <?= $order->formdata->tech_contact_address_country ?>
              </div>
            <?php endif ?>
          <br>
          <a href="contact_info.php" class="btn btn-primary changer">Change Information</a>
        </div>
      </div>
    </div>
  </div>
    <?php // if org name is now allowed then none of the fields will be so just dont display the container  ?>
    <?php if ($order->fieldIsAllowed('org_name')): ?>

      <div class="panel panel-primary organisation_details">
        <div class="panel-heading">
          <h4 class="panel-title">Organisation</h4>
        </div>
        <div class="panel-body">
            <?php if ($order->fieldIsAllowed('org_name')): ?>
              <div class="org_name">
                <strong>Name: </strong>
                  <?= $order->formdata->org_name ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('org_duns')): ?>
              <div class="org_duns">
                <strong>DUNS: </strong>
                  <?= $order->formdata->org_duns ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('org_division')): ?>
              <div class="org_division">
                <strong>Division: </strong>
                  <?= $order->formdata->org_division ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('org_incorporating_agency')): ?>
              <div class="org_incorporating_agency">
                <strong>Incorperating Agency: </strong>
                  <?= $order->formdata->org_incorporating_agency ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('org_registration_number')): ?>
              <div class="org_registration_number">
                <strong>Registration Number: </strong>
                  <?= $order->formdata->org_registration_number ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('org_incorporating_date')): ?>
              <div class="org_incorporating_date">
                <strong>Incorporation Date: </strong>
                  <?= $order->formdata->org_incorporating_date ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('org_assumed_name')): ?>
              <div class="org_assumed_name">
                <strong>Assumed Name: </strong>
                  <?= $order->formdata->org_assumed_name ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('org_business_category')): ?>
              <div class="org_business_category">
                <strong>Business Category: </strong>
                  <?= $order->formdata->org_business_category ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('org_jurisdiction_city')): ?>
              <div class="org_jurisdiction_city">
                <strong>Jurisdiction City: </strong>
                  <?= $order->formdata->org_jurisdiction_city ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('org_jurisdiction_region')): ?>
              <div class="org_jurisdiction_region">
                <strong>Jurisdiction Region: </strong>
                  <?= $order->formdata->org_jurisdiction_region ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('org_jurisdiction_country')): ?>
              <div class="org_jurisdiction_country">
                <strong>Jurisdiction Country: </strong>
                  <?= $order->formdata->org_jurisdiction_country ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('org_address_line1')): ?>
              <div class="org_address_line1">
                <strong>Address Line 1: </strong>
                  <?= $order->formdata->org_address_line1 ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('org_address_line2')): ?>
              <div class="org_address_line2">
                <strong>Address Line 2: </strong>
                  <?= $order->formdata->org_address_line2 ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('org_address_line3')): ?>
              <div class="org_address_line3">
                <strong>Address Line 3: </strong>
                  <?= $order->formdata->org_address_line3 ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('org_address_postal_code')): ?>
              <div class="org_address_postal_code">
                <strong>Postcode: </strong>
                  <?= $order->formdata->org_address_postal_code ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('org_address_city')): ?>
              <div class="org_address_city">
                <strong>City: </strong>
                  <?= $order->formdata->org_address_city ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('org_address_region')): ?>
              <div class="org_address_region">
                <strong>Region: </strong>
                  <?= $order->formdata->org_address_region ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('org_address_country')): ?>
              <div class="org_address_country">
                <strong>Country: </strong>
                  <?= $order->formdata->org_address_country ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('org_address_phone')): ?>
              <div class="org_address_phone">
                <strong>Telephone: </strong>
                  <?= $order->formdata->org_address_phone ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('ida_email_address')): ?>
              <div class="ida_email_address">
                <strong>TrustLogo Email Address: </strong>
                  <?= $order->formdata->ida_email_address ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('ida_telephone_number')): ?>
              <div class="ida_telephone_number">
                <strong>TrustLogo Telephone: </strong>
                  <?= $order->formdata->ida_telephone_number ?>
              </div>
            <?php endif ?>
            <?php if ($order->fieldIsAllowed('ida_fax_number')): ?>
              <div class="ida_fax_number">
                <strong>TrustLogo Fax: </strong>
                  <?= $order->formdata->ida_fax_number ?>
              </div>
            <?php endif ?>
          <br>
          <a href="order_organisation.php" class="btn btn-primary changer">Change Information</a>
        </div>
      </div>
    <?php endif ?>
  <div class="panel panel-primary">
    <div class="panel-heading">
      <h4 class="panel-title">CSR</h4>
    </div>
    <div class="panel-body">
        <?php if ($order->fieldIsAllowed('domain_name')): ?>
          <div class="domain_name">
            <strong>Domain Name: </strong>
              <?= $order->formdata->domain_name ?>
          </div>
        <?php endif ?>
        <?php if ($order->formdata->domain_or_csr=='csr'): ?>
            <?php if ($order->fieldIsAllowed('csr')): ?>
            <div class="csr">
              <strong>CSR: </strong>
              <p>
                  <?= $order->formdata->csr ?>
              </p>
            </div>
            <?php endif ?>
        <?php endif ?>
        <?php if ($order->fieldIsAllowed('hashing_algorithm')): ?>
          <div class="hashing_algorithm">
            <strong>Hashing Algorithm: </strong>
              <?= $order->formdata->hashing_algorithm ?>
          </div>
        <?php endif ?>
        <?php if ($order->fieldIsAllowed('san_domains')&&$order->formdata->san_count>0): ?>
          <div class="san_domains">
            <strong>San Domains: </strong>
              <?php foreach ($order->formdata->san_domains as $value): ?>
                  <?= $value ?>,
              <?php endforeach ?>
          </div>
        <?php endif ?>
      <br>
      <a href="order_csr.php" class="btn btn-primary changer">Change Information</a>
    </div>
  </div>
  <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
      <?php if ($order->fieldIsAllowed('dv_auth_method')): ?>
        <div class="page-header">
          <h4>Domain Approver</h4>
        </div>
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h5 class="panel-title">Approver Method</h5>
          </div>
          <div class="panel-body">
            <div class="radio email">
              <label><input type="radio" name="dv_auth_method" value="EMAIL" checked="">Email</label>
            </div>
            <div class="radio file">
              <label><input type="radio" name="dv_auth_method" value="FILE">File</label>
            </div>
            <div class="radio dns">
              <label><input type="radio" name="dv_auth_method" value="DNS">DNS</label>
            </div>
            <p>You can approve your certificate via email, a DNS record or adding a file to your website.</p>
          </div>
        </div>
        <div class="panel panel-primary approver_email_address">
          <div class="panel-heading">
            <h4 class="panel-title">Approver Email</h4>
          </div>
          <div class="panel-body">
              <?php foreach ($order->formdata->approver_list as $value): ?>
                <div class="radio">
                  <label><input type="radio" name="approver_email_address" required=""
                                value="<?= $value['email'] ?>"><?= $value['email'] ?></label>
                </div>
              <?php endforeach ?>
          </div>
        </div>
      <?php endif ?>
      <?php if ($order->fieldIsAllowed('web_server_type')): ?>
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h4 class="panel-title">Web Server Type</h4>
          </div>
          <div class="panel-body row">
            <div class="form-group col-sm-4">
              <select name="web_server_type" class="form-control">
                  <?php $web_server_type_opts=$order->getSelectData('web_server_type') ?>
                  <?php foreach ($web_server_type_opts as $value): ?>
                    <option value="<?= $value ?>"><?= $value ?></option>
                  <?php endforeach ?>
              </select>
            </div>
          </div>
        </div>
      <?php endif ?>

    <div class="clearfix f-control">
      <input type="hidden" name="next_page" value="place_order">
      <input class="btn btn-primary btn-lg back" type="button" value="Back" onClick="history.go(-1);return true;">
      <input class="btn btn-success btn-lg forw" type="submit" name="page_submit" value="Place Order">
    </div>
  </form>
</div>
<script type="text/javascript" src="js/vendor/jquery-3.1.1.min.js"></script>
<script type="text/javascript" src="js/vendor/bootstrap.min.js"></script>
<script type="text/javascript" src="js/main.js"></script>
</body>
</html>