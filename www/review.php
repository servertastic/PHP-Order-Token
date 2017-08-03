<?php include 'includes/header.php';?>
<?php


// if get actions are set. check here for here they are going
if (isset($_GET['action']) && $_GET['action'] != '') {
	$action = $_GET['action'];
	switch ($action) {
	case 'resend':
		$action_fire = $order->resendEmail($_GET['email_type']);
		// set alert response here
		if ($action_fire) {
			$action_response = "Email has now been resent";
		}
		break;
	case 'approver':
		$action_fire = $order->changeApprover($_GET['email']);
		// set alert response here
		if ($action_fire) {
			$action_response = "Approver Email is now changed";
		}
		break;
	case 'cancel':
		$action_fire = $order->cancelOrder();
		// set alert response here
		if ($action_fire) {
			$action_response = "Your order cancellation request has been sent";
		}
		break;
	case 'change_auth':
		$action_fire = $order->changeAuth();
		// set alert response here
		if ($action_fire) {
			$action_response = "Your authorization method has now been changed";
		}
		break;
	case 'poll_auth':
		$action_fire = $order->pollAuth();
		// set alert response here
		if ($action_fire) {
			$action_response = "Order has been polled: $action_fire->status";
		}
		break;
		# unknown get handle so just cancel the request
		exit();
		break;
	}
	// action fire returns true or false so check it here
	if (isset($action_fire) && !$action_fire) {
		// push a generic error just incase false is returned but the requests are good
		array_push($order->error_log, 'Something went wrong on action!');
	} else {
	//fire this script to strip the GET off the headers once finished as we do not want users refreshing it and sending multiple GETs ?>
	<script>    
	    if(typeof window.history.pushState == 'function') {
	        window.history.pushState({}, "Hide", "<?= $_SERVER['PHP_SELF'] ?>");
	    }
	</script> <?php
	}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Web Order - Review</title>
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
		<h2><?=$order->formdata->product_name;?></h2>
		<?php if ($order->TEST_MODE): ?>
			<p class="alert alert-danger">TEST MODE</p>
		<?php endif ?>
	</div>
	<ol class="container breadcrumb">
		<li><a href="/order" class="">Start</a></li>
		<li><a href="contact_info.php" class="active">Review</a></li>
	</ol>
	<?php if (isset($order)): ?>
		<?php if (!empty(array_filter($order->error_log))): ?>
			<div class="container alert-box">
				<?php foreach ($order->error_log as $value): ?>
					<?php if (strpos($value, '401')): ?>
						<p class="alert alert-danger">Invalid Order Token, Please check it and try again.</p>
					<?php else: ?>
						<p class="alert alert-danger"><?=$value;?></p>
					<?php endif;?>
				<?php endforeach;?>
			</div>
		<?php endif;?>
	<?php endif;?>
	<?php if (isset($order->formdata->order_completion)&&$order->formdata->order_status!='Completed'): ?>
		<div class="container">
			<div class="page-header">
				<h1>Order Completed</h1>
			<?php if (isset($order->formdata->order_completion['private_key']) && isset($order->formdata->order_completion['csr'])): ?>
				<p class="alert alert-danger">Please take a copy your CSR and Private Key and keep them safe, we will not store them and they cannot be retrieved from this system.</p>
				<?php elseif (isset($order->formdata->order_completion['private_key'])): ?>
				    <p class="alert alert-danger">Please take a copy your Private Key and keep it safe, we will not store them and they cannot be retrieved from this system.</p>
				<?php elseif (isset($order->formdata->order_completion['csr'])): ?>
				    <p class="alert alert-danger">Please take a copy your CSR and keep it safe, we will not store them and they cannot be retrieved from this system.</p>
			<?php endif;?>
			</div>
			<div class="panel panel-default">
				<div class="panel-body">
					<a href="<?= $_SERVER['PHP_SELF'] ?>?forcerefresh" class="btn btn-success">
					  Check Token Status
					</a>
				</div>
			</div>
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4 class="panel-title">Your Reference</h4>
				</div>
				<div class="panel-body">
					<?=$order->formdata->order_completion['reseller_order_id'];?>
				</div>
			</div>
			<?php if (isset($order->formdata->order_completion['invite_url'])): ?>
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4 class="panel-title">Order management link</h4>
					</div>
					<div class="panel-body">
						<?=$order->formdata->order_completion['invite_url'];?>
					</div>
				</div>
			<?php endif;?>
			<?php if (isset($order->formdata->order_completion['private_key'])): ?>
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4 class="panel-title">Private Key</h4>
					</div>
					<div class="panel-body">
            <code>
              <pre>
                <?=$order->formdata->order_completion['private_key'];?>
              </pre>
            </code>
					</div>
				</div>
			<?php endif;?>
			<?php if (isset($order->formdata->order_completion['csr'])): ?>
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h4 class="panel-title">CSR</h4>
					</div>
					<div class="panel-body">
            <code>
                <pre>
                <?=$order->formdata->order_completion['csr'];?>
                </pre>
            </code>
					</div>
				</div>
			<?php endif;?>
		</div>
	<?php elseif (isset($order->formdata->order_status) && $order->formdata->order_status != 'Order Placed'): ?>
			<?php if (isset($action_response)): ?>
				<div class="container action-container">
					<p class="alert alert-info"><?=$action_response;?></p>
				</div>
			<?php endif;?>
  <?php if($order->isSmarterTools()): ?>
      <div class="container">
        <div class="page-header">
          <h2>Your Order is <?=$order->formdata->order_status;?></h2>
        </div>
        <div class='panel panel-primary'>
          <div class='panel-body'>
            <p>Congratulations! Your order has been completed. </p>
          </div>
        </div>
        <div class="panel panel-primary">
          <div class="panel-heading">
            <h2 class="panel-title">Licence Details</h2>
          </div>
          <div class="panel-body">
	          <?php foreach ( $order->formdata->orders_licenses->license as $item ):?>
                <div class="smartertool_bundle">
                  <h3><?= $item->product_name ?></h3>
                  <p><?= $item->license_key ?></p>
                  <h4>SmarterTools Email</h4>
                  <p><?= $item->smartertools_email ?></p>
                </div>
	          <?php endforeach;?>
          </div>
        </div>
      </div>

	  <?php else:?>
				<div class="container">
					<div class="page-header">
						<h2>Your Order is <?=$order->formdata->order_status;?></h2>
					</div>

					<?php if ($order->formdata->order_status == 'Completed'): ?>
						<div class='panel panel-primary'>
							<div class='panel-body'>
								<p>Congratulations! Your order has been completed. Your administrative and technical contacts should have received an email containing details. Alternatively you can review the order below.</p>
							</div>
						</div>

						<div class="panel panel-default">
							<div class="panel-body">
								<!-- Button trigger modal -->
								<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ResendFulfillment">
								  Resend Fulfillment Email
								</button>
							</div>
						</div>

						<!-- Resend Fulfillment Modal -->
								<div class="modal fade" id="ResendFulfillment" tabindex="-1" role="dialog" aria-labelledby="ResendFulfillment">
								  <div class="modal-dialog" role="document">
								    <div class="modal-content">
								      <div class="modal-header">
								        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								        <h4 class="modal-title" id="myModalLabel">Are you sure?</h4>
								      </div>
								      <div class="modal-body">
								        <strong>Resend Fulfillment Email</strong>
								      </div>
								      <div class="modal-footer">
								        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
								        <a href="<?= $_SERVER['PHP_SELF']."?action=resend&email_type=fulfillment" ?>" class="btn btn-primary">Confirm</a>
								      </div>
								    </div>
								  </div>
								</div>
								<!-- end Resend Fulfillment Modal -->
					<?php endif; ?>
					<?php if ($order->formdata->order_status == 'Awaiting Provider Approval'): ?>
						<div class='panel panel-primary'>
							<div class='panel-body'>
								<p>Your order is awaiting approval by the Certificate Authority. Depending on your business and the certificate ordered this can take up to 10 working days. The Certificate Authority may contact you requesting additional documentation. This is not always required but when requested your order will not progress until the supplied documentation is provided.</p>

								<p>If you are having problems supplying the requested documentation or you have not heard anything about your order for more than 5 working days you can contact us for escalation.</p>

								<p>Please note: Once the order has been cancelled it cannot be reversed.</p>
							</div>
						</div>

						<div class="panel panel-default">
							<div class="panel-body">
								<a href="<?= $_SERVER['PHP_SELF'] ?>?forcerefresh" class="btn btn-success">
								  Refresh
								</a>
								<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#CancelOrder">
								  Cancel Order
								</button>
							</div>
						</div>

														<!-- Cancel Modal -->
						<div class="modal fade" id="CancelOrder" tabindex="-1" role="dialog" aria-labelledby="CancelOrder">
						  <div class="modal-dialog" role="document">
						    <div class="modal-content">
						      <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						        <h4 class="modal-title" id="myModalLabel">Are you sure?</h4>
						      </div>
						      <div class="modal-body">
						        <strong class="text-danger">This will permanently cancel your order</strong>
						      </div>
						      <div class="modal-footer">
						        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						        <a href="<?= $_SERVER['PHP_SELF']."?action=cancel" ?>" class="btn btn-danger">Confirm</a>
						      </div>
						    </div>
						  </div>
						</div>
						<!-- end Cancel Modal -->
					<?php endif;?>
						<?php if ($order->formdata->order_status == 'Awaiting Customer Verification'): ?>

							<?php if ($order->formdata->dv_auth_method == 'EMAIL'): ?>


								<div class="panel panel-default">
									<div class="panel-body">
									<a href="<?= $_SERVER['PHP_SELF'] ?>?forcerefresh" class="btn btn-success">
									  Refresh
									</a>
									<!-- Button trigger modal -->
									<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ResendApprover">
									  Resend Approver Email
									</button>
									<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ChangeApprover">
									  Change Domain Approver
									</button>
									<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#CancelOrder">
									  Cancel Order
									</button>
									</div>
								</div>
								<!-- Resend Approver Modal -->
								<div class="modal fade" id="ResendApprover" tabindex="-1" role="dialog" aria-labelledby="ResendApprover">
								  <div class="modal-dialog" role="document">
								    <div class="modal-content">
								      <div class="modal-header">
								        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								        <h4 class="modal-title" id="myModalLabel">Are you sure?</h4>
								      </div>
								      <div class="modal-body">
								        <strong>Resend Approver Email</strong>
								      </div>
								      <div class="modal-footer">
								        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
								        <a href="<?= $_SERVER['PHP_SELF']."?action=resend&email_type=Approver" ?>" class="btn btn-primary">Confirm</a>
								      </div>
								    </div>
								  </div>
								</div>
								<!-- end Resend Approver Modal -->


								<!-- Change Approver Modal -->
								<div class="modal fade" id="ChangeApprover" tabindex="-1" role="dialog" aria-labelledby="ChangeApprover">
								  <div class="modal-dialog" role="document">
								    <div class="modal-content">
								      <div class="modal-header">
								        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								        <h4 class="modal-title" id="myModalLabel">Change Approver Email</h4>
								      </div>
							      	<form action="<?= $_SERVER['PHP_SELF'] ?>" method="GET">
							      		<input type="hidden" name="action" value="approver">
									      <div class="modal-body">
									        <div class='approver-list'>
														<?php foreach ($order->formdata->approver_list as $value): ?>
															<div class="radio">
																<label><input type="radio" name="email" required="" value="<?=$value['email'];?>"><?=$value['email'];?></label>
															</div>
														<?php endforeach;?>
													</div>
									      </div>
									      <div class="modal-footer">
									        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
									        <input type="submit" class="btn btn-primary" value="Change">
									      </div>
							      	</form>
								    </div>
								  </div>
								</div>
								<!-- end Change Approver Modal -->

								<!-- Cancel Modal -->
								<div class="modal fade" id="CancelOrder" tabindex="-1" role="dialog" aria-labelledby="CancelOrder">
								  <div class="modal-dialog" role="document">
								    <div class="modal-content">
								      <div class="modal-header">
								        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								        <h4 class="modal-title" id="myModalLabel">Are you sure?</h4>
								      </div>
								      <div class="modal-body">
								        <strong class="text-danger">This will permanently cancel your order</strong>
								      </div>
								      <div class="modal-footer">
								        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
								        <a href="<?= $_SERVER['PHP_SELF']."?action=cancel" ?>" class="btn btn-danger">Confirm</a>
								      </div>
								    </div>
								  </div>
								</div>
								<!-- end Cancel Modal -->

								
							<?php endif;?>
							<?php if ($order->formdata->dv_auth_method == 'DNS'): ?>
								<div class="panel panel-primary">
									<div class="panel-body">
										<p>
											Your order has been configured but we are waiting for the domain to be approved. You need to create a TXT record on your DNS server with the following:
										</p>
										<p><?=$order->formdata->dv_auth_dns_string;?></p>
										<p>Check if your DNS TXT is set-up <a target="_blank" href="http://www.dnsstuff.com/tools#dnsLookup|type=domain&&value=<?=$order->formdata->domain_name;?>&&recordType=TXT&&displaytype=pretty">Click Here</a></p>
									</div>
								</div>


								<div class="panel panel-default">
									<div class="panel-body">
										<a href="<?= $_SERVER['PHP_SELF'] ?>?forcerefresh" class="btn btn-success">
									  Refresh
									</a>
									<!-- Button trigger modal -->
									<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#SwitchAuth">
									  Switch to Email Authentication
									</button>
									<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#PollAuth">
									  Poll Authentication
									</button>
									<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#CancelOrder">
									  Cancel Order
									</button>
									</div>
								</div>
								<!-- SwitchAuth Modal -->
								<div class="modal fade" id="SwitchAuth" tabindex="-1" role="dialog" aria-labelledby="SwitchAuth">
								  <div class="modal-dialog" role="document">
								    <div class="modal-content">
								      <div class="modal-header">
								        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								        <h4 class="modal-title" id="myModalLabel">Are you sure?</h4>
								      </div>
								      <div class="modal-body">
								        <strong>Switch your authentication to Email only</strong>
								        <br>
								        <strong class="text-danger">You will be unable to switch back</strong>
								      </div>
								      <div class="modal-footer">
								        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
								        <a href="<?= $_SERVER['PHP_SELF']."?action=change_auth" ?>" class="btn btn-primary">Confirm</a>
								      </div>
								    </div>
								  </div>
								</div>
								<!-- end SwitchAuth Modal -->

								<!-- PollAuth Modal -->
								<div class="modal fade" id="PollAuth" tabindex="-1" role="dialog" aria-labelledby="PollAuth">
								  <div class="modal-dialog" role="document">
								    <div class="modal-content">
								      <div class="modal-header">
								        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								        <h4 class="modal-title" id="myModalLabel">Are you sure?</h4>
								      </div>
								      <div class="modal-body">
								        <strong>Poll Authentication</strong>
								      </div>
								      <div class="modal-footer">
								        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
								        <a href="<?= $_SERVER['PHP_SELF']."?action=poll_auth" ?>" class="btn btn-primary">Confirm</a>
								      </div>
								    </div>
								  </div>
								</div>
								<!-- end PollAuth Modal -->

								<!-- CancelOrder Modal -->
								<div class="modal fade" id="CancelOrder" tabindex="-1" role="dialog" aria-labelledby="CancelOrder">
								  <div class="modal-dialog" role="document">
								    <div class="modal-content">
								      <div class="modal-header">
								        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								        <h4 class="modal-title" id="myModalLabel">Are you sure?</h4>
								      </div>
								      <div class="modal-body">
								        <strong class="text-danger">This will permanently cancel your order</strong>
								      </div>
								      <div class="modal-footer">
								        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
								        <a href="<?= $_SERVER['PHP_SELF']."?action=cancel" ?>" class="btn btn-danger">Confirm</a>
								      </div>
								    </div>
								  </div>
								</div>
								<!-- end CancelOrder Modal -->

							<?php endif;?>
							<?php if ($order->formdata->dv_auth_method == 'FILE'): ?>
								<div class="panel panel-primary">
									<div class="panel-body">
										<p>
											Your order has been configured but we are waiting for the domain to be approved. You need to create a file on your domain with the filename and content specified below:
										</p>
										<p><strong>File Name: </strong>/.well-known/pki-validation/fileauth.txt</p>
										<p><strong>File Contents: </strong><?=$order->formdata->dv_auth_file_contents;?></p>
									</div>
								</div>



								<div class="panel panel-default">
									<div class="panel-body">
										<a href="<?= $_SERVER['PHP_SELF'] ?>?forcerefresh" class="btn btn-success">
									  Refresh
									</a>
									<!-- Button trigger modal -->
									<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#SwitchAuth">
									  Switch to Email Authentication
									</button>
									<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#PollAuth">
									  Poll Authentication
									</button>
									<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#CancelOrder">
									  Cancel Order
									</button>
									</div>
								</div>
								<!-- SwitchAuth Modal -->
								<div class="modal fade" id="SwitchAuth" tabindex="-1" role="dialog" aria-labelledby="SwitchAuth">
								  <div class="modal-dialog" role="document">
								    <div class="modal-content">
								      <div class="modal-header">
								        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								        <h4 class="modal-title" id="myModalLabel">Are you sure?</h4>
								      </div>
								      <div class="modal-body">
								        <strong>Switch your authentication to Email only</strong>
								        <br>
								        <strong class="text-danger">You will be unable to switch back</strong>
								      </div>
								      <div class="modal-footer">
								        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
								        <a href="<?= $_SERVER['PHP_SELF']."?action=change_auth" ?>" class="btn btn-primary">Confirm</a>
								      </div>
								    </div>
								  </div>
								</div>
								<!-- end SwitchAuth Modal -->

								<!-- PollAuth Modal -->
								<div class="modal fade" id="PollAuth" tabindex="-1" role="dialog" aria-labelledby="PollAuth">
								  <div class="modal-dialog" role="document">
								    <div class="modal-content">
								      <div class="modal-header">
								        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								        <h4 class="modal-title" id="myModalLabel">Are you sure?</h4>
								      </div>
								      <div class="modal-body">
								        <strong>Poll Authentication</strong>
								      </div>
								      <div class="modal-footer">
								        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
								        <a href="<?= $_SERVER['PHP_SELF']."?action=poll_auth" ?>" class="btn btn-primary">Confirm</a>
								      </div>
								    </div>
								  </div>
								</div>
								<!-- end PollAuth Modal -->

								<!-- CancelOrder Modal -->
								<div class="modal fade" id="CancelOrder" tabindex="-1" role="dialog" aria-labelledby="CancelOrder">
								  <div class="modal-dialog" role="document">
								    <div class="modal-content">
								      <div class="modal-header">
								        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								        <h4 class="modal-title" id="myModalLabel">Are you sure?</h4>
								      </div>
								      <div class="modal-body">
								        <strong class="text-danger">This will permanently cancel your order</strong>
								      </div>
								      <div class="modal-footer">
								        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
								        <a href="<?= $_SERVER['PHP_SELF']."?action=cancel" ?>" class="btn btn-danger">Confirm</a>
								      </div>
								    </div>
								  </div>
								</div>
								<!-- end CancelOrder Modal -->
							<?php endif ?>
						<?php endif;?>
							<div class="panel panel-primary">
								<div class="panel-heading">
									<h3 class="panel-title">Details</h3>
								</div>
								<div class="panel-body">
								<div class="row">
									<div class="col-md-6">
										<h4>Order Status</h4>
										<p><?= $order->formdata->order_status ?></p>
									</div>
									<div class="col-md-6">
										<h4>Further Information</h4>
										<p><?= $order->formdata->order_state_further_info ?></p>
									</div>
								</div>
									<div class="row">
										<div class="col-md-6">
											<h4>Domain Name</h4>
											<p><?=$order->formdata->domain_name;?></p>
										</div>
										<div class="col-md-6">
											<h4>CA Order ID</h4>
											<p><?= $order->formdata->provider_order_id ?></p>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<h4>Domain Approver</h4>
											<p><?= $order->formdata->approver_email_address ?></p>
										</div>
									</div>
								</div>
							</div>
							<?php if ($order->formdata->order_status == 'Completed'): ?>
								<div class="panel panel-primary">
									<div class="panel-heading">
										<h3 class="panel-title">Certificate Details</h3>
									</div>
									<div class="panel-body">
										<div class="row">
											<div class="col-md-7">
												<h4>Certificate</h4>
												<pre><?= $order->formdata->certificate ?></pre>
											</div>
											<div class="col-md-5">
												<h4>Expiry Date</h4>
												<p><?= $order->formdata->expiry_date ?></p>
											</div>
										</div>
										<div class="row">
											<div class="col-md-7">
												<h4>PKCS7</h4>
												<pre><?= (isset($order->formdata->pkcs7)? $order->formdata->pkcs7 :'') ?></pre>
											</div>
										</div>
									</div>
								</div>
                <?php if (!empty($order->formdata->ca_certs->certificate_info)):?>
                  <div class="panel panel-primary">
                    <div class="panel-heading">
                      <h3 class="panel-title">CA Certificates</h3>
                    </div>
                    <div class="panel-body">
                        <?php foreach ($order->formdata->ca_certs->certificate_info as $cert):?>
                            <div class="row">
                              <div class="col-md-7">
                                <h4><?= $cert->type ?></h4>
                                <pre><?= $cert->certificate ?></pre>
                              </div>
                            </div>
                        <?php endforeach;?>
                    </div>
                  </div>
                <?php endif;?>
							<?php endif; ?>
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
										</div>
								</div>
								</div>
							</div>
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
									</div>
								</div>
							<?php endif ?>
	<?php endif;?>
	<script type="text/javascript" src="js/vendor/jquery-3.1.1.min.js"></script>
	<script type="text/javascript" src="js/vendor/bootstrap.min.js"></script>
			<?php endif; ?>
</body>
</html>