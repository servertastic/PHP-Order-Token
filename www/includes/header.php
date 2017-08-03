<?php
	require("../../vendor/autoload.php");
	require("../../classes/STOrderManager.php");

	session_start();


	// need to check if the user is allowed to proceed
	if ($_SERVER['SCRIPT_NAME'] != '/index.php') {
		// if on index then no need to check
		if (isset($_SESSION['st_webform']['order_token'])) {
			// build the class with session data
			if (isset($_GET['forcerefresh'])) {
				// specifically for review if the get parameters calls for a force refresh.
				// then remake the order manager with newly gathered info
				$order = new STOrderManager($_SESSION['st_webform']['order_token'],true);
			} else {
				$order = new STOrderManager($_SESSION['st_webform']['order_token']);
			}
			if (!empty($order->formdata->error_log)) {
				header("Location:/");
			}
			if (isset($_POST['page_submit'])&&$_POST['page_submit']) {
				if ($order->processFormData($_POST)) {
					// formdata processed, function will relocate to next page
					// use value from hidden input next_page to relocate after process
				} else {
					// processFormData has returned false so throw errors instead.
				}
			}
		} else {
			header("Location:/order");
			die();
		}
	}


