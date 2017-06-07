<?php
// uses guzzleHTTP for all its curl requests to servertastic
use GuzzleHttp\Client;

class STOrderManager {
	public $error_log = [];
	public $formdata;
	public $TEST_MODE;

	public function __construct($order_token = '',$force_review = false) {
		// OPTIONS // 
		// 20 minutes in seconds
		$this->timeout = '1200';
		// Test mode
		$this->TEST_MODE = true;
		/////////////////////////

		// this class stores all formdata passed to it. use this to access values as needed.
		$this->formdata = new stdClass();

		// use turnary to set if using test mode api or live mode api
		$this->base_uri = ($this->TEST_MODE ? 'https://test-api2.servertastic.com/order/' : 'https://api2.servertastic.com/order/');
		// prepare the GuzzleHTTP client with the base uri
		$this->client = new Client([
			'base_uri' => $this->base_uri,

			// REMOVE THIS BEFORE LIVE
			//'verify'   => false,
		]);

		// set ini cookie timeouts to handle destroying cookies
		ini_set("session.gc_maxlifetime", $this->timeout);
		ini_set("session.cookie_lifetime", $this->timeout);


		// if the session container is not set, then set it
		if (!isset($_SESSION['st_webform'])) {
			$_SESSION['st_webform'] = [];
		}
		// fire the manage order token to check if the token is new or not and clean the session if it is.
		$this->manageOrderToken($order_token);

		// grab all variables stored in session and pass them to the $formdata object
		$this->getAllData();

		// review the token and redirect to the right place if neccessary
		$this->reviewToken($force_review);

		// get the product fields using the order token provided on class construction
		$this->formdata->product_fields = ($this->checkSession('product_fields') ? $this->getSession('product_fields') : $this->getProductFields($this->formdata->order_token));

		$this->hashingAlgos();
	}

	public function __destruct() {
		// use the destruct function to store all inforation we have gathered in session storage
		$this->storeAll();
	}

	public function resendEmail($email_type) {
		if ($this->formdata->approver_email_address != '') {
			try {
				$resend_email = $this->client->get('resendemail.json', [
					"query" => [
						"order_token" => $this->formdata->order_token,
						"email_type" => $email_type
					]
				]);
				if ($resend_email->getStatusCode() == '200') {
					$returned = json_decode($resend_email->getBody());
					if (isset($returned->success)) {
						return true;
					} else {return false;}
				} else {return false;}
			} catch (Exception $e) {
				// array_push($this->error_log, 'Error trying to send resend email request');
				array_push($this->error_log, $e->getMessage());
			}
		}
	}

	public function changeApprover($email = '') {
		// check email arg is set and that its part of the approver list
		if ($email != '') {
			try {
				$resend_email = $this->client->get('changeapproveremail.json', [
					"query" => [
						"order_token" => $this->formdata->order_token,
						"email" => $email
					]
				]);
				if ($resend_email->getStatusCode() == '200') {
					$returned = json_decode($resend_email->getBody());
					if (isset($returned->success)) {
						// change approver for this session instance manually as its been changed at the api
						$this->formdata->approver_email_address = $email;
						return true;
					} else {array_push($this->error_log, 'Error trying to send resend email request');}
				} else {array_push($this->error_log, 'Error trying to send resend email request');}
			} catch (Exception $e) {
				array_push($this->error_log, 'Error trying to send resend email request');
			}
		} else {array_push($this->error_log, 'Error: email to change not in list of valid approvers');}
	}

	public function cancelOrder() {
		try {
			$order_cancel = $this->client->get('cancel.json',[
				"query" => [
					"order_token" => $this->formdata->order_token
				]
			]);
			if ($order_cancel->getStatusCode() == '200') {
				$returned = json_decode($order_cancel->getBody());
				if (isset($returned->success)) {
					// success returned so pass true to the return
					return true;
				} else {return false;}
			} else {return false;}
		} catch (Exception $e) {
			array_push($this->error_log, 'Error trying to send cancel order request');
		}
	}

	public function changeAuth() {
		try {
			$order_cancel = $this->client->get('changeauthmethod.json',[
				"query" => [
					"order_token" => $this->formdata->order_token
				]
			]);
			if ($order_cancel->getStatusCode() == '200') {
				$returned = json_decode($order_cancel->getBody());
				if (isset($returned->success)) {
					// change the value of dv_auth in class to email manually as its returned true for the api
					$this->formdata->dv_auth_method == 'EMAIL';

					// success returned so pass true to the return
					return true;
				} else {return false;}
			} else {return false;}
		} catch (Exception $e) {
			array_push($this->error_log, 'Error trying to send change auth method request');
		}
	}

	public function pollAuth() {
		try {
			$order_cancel = $this->client->get('pollauth.json',[
				"query" => [
					"order_token" => $this->formdata->order_token
				]
			]);
			if ($order_cancel->getStatusCode() == '200') {
				$returned = json_decode($order_cancel->getBody());
				if (isset($returned->success)) {
					// success returned so pass data to the return
					return $returned;
				} else {return false;}
			} else {return false;}
		} catch (Exception $e) {
			array_push($this->error_log, 'Error trying to send poll order request');
			array_push($this->error_log, $e->getMessage());
		}
	}

	public function checkIfCSR() {
		// used to check if csr is allowed for this order
		if (array_key_exists('csr', $this->formdata->product_fields)) {
			return true;
		} else {return false;}
	}

	public function fieldIsAllowed($fieldname) {
		foreach ($this->formdata->product_fields as $key => $value) {
			if (isset($this->formdata->product_fields[$key][$fieldname])) {
				return true;
			}
		}
		// not found so return false
		return false;
	}

	public function fieldHasRequired($fieldname) {
		foreach ($this->formdata->product_fields as $key => $value) {
			if (isset($this->formdata->product_fields[$key][$fieldname])) {
				if ($this->formdata->product_fields[$key][$fieldname]['required'] == 1) {
					return 'required';
				} else {return false;}
			}
		}
	}

	public function isSelected($fieldname, $optionData) {
		if (isset($this->formdata->$fieldname)&&$this->formdata->$fieldname == $optionData) {
			return 'selected=""';
		} else {return false;}
	}

	public function populateFormField($fieldname) {
		if (isset($this->formdata->$fieldname) && $this->formdata->$fieldname != '') {
			return $this->formdata->$fieldname;
		} else {return false;}
	}

	public function getISOCodes() {
		try {
			$iso_codes = $this->client->get('isocodes.json');

			if ($iso_codes->getStatusCode() == '200') {
				$returned        = json_decode($iso_codes->getBody(), true);
				$this->iso_codes = $returned;
				return $this->iso_codes;
			}
		} catch (Exception $e) {
			array_push($this->error_log, 'Error trying to get ISO Codes');
		}
	}

	public function getSelectData($fieldname) {
		foreach ($this->formdata->product_fields as $key => $value) {
			if (isset($this->formdata->product_fields[$key][$fieldname])) {
				return $this->formdata->product_fields[$key][$fieldname]['options'];
			}
		}
		return false;
	}

	public function processFormData($formdata) {
		foreach ($formdata as $key => $value) {
			if ($key == 'next_page' || $key == 'page_submit') {
				if ($formdata[$key] == 'Auto Generate CSR') {
					$this->formdata->domain_or_csr = 'domain';
				} elseif ($formdata[$key] == 'Supply CSR') {
					$this->formdata->domain_or_csr = 'csr';
				}
			} elseif ($key == 'csr') {
				$this->formdata->csr = $value;
				$this->decodeCSR($value);
			} elseif ($key == 'san_domains') {
				// need to explode this list out
				$san_domains                 = explode(',', $value);
				$this->formdata->san_domains = $san_domains;
				if (intval($this->formdata->san_count) < count($san_domains)) {
					array_push($this->error_log, 'Maximum allowed SAN Domains reached, Please remove enough to be on or below the allowed limit');
				}
			} else {
				$this->formdata->$key = $value;
			}
		}
		// only leave page if the error log is okay
		if ($this->error_log == []) {
			if ($formdata['next_page'] == 'place_order') {
				// do something else as we are now placing the order in full

				$this->formdata->order_completion = $this->placeOrder();

				header("Location:review.php?order_token=" . $this->formdata->order_token);
			} else {
				// use the relocate variable and fire on class destruction
				header("Location:" . $formdata['next_page'] . ".php");
			}
		}
	}

	public function getAppoverList() {
		if (isset($this->formdata->domain_name)) {
			try {
				$approver_list = $this->client->get('approverlist.json', [
					'query' => [
						'domain_name' => $this->formdata->domain_name,
					],
				]);
				if ($approver_list->getStatusCode() == '200') {
					$returned                      = json_decode($approver_list->getBody(), true);
					$this->formdata->approver_list = $returned['approver_email'];
					return true;
				}
			} catch (Exception $e) {
				array_push($this->error_log, $e->getMessage());
			}
		}
	}


	private function storeAll() {
		foreach ($this->formdata as $key => $value) {
			$this->storeInSession($key, $value);
		}
	}

	/**
	 * Grab the product fields using the order token
	 * @param  string $order_token
	 * @return array - product fields in an array
	 */
	private function getProductFields($order_token = '') {
		try {
			$productfields_response = $this->client->get('productfields.json', [
				'query' => [
					'order_token' => $order_token,
				],
			]);

			if ($productfields_response->getStatusCode() == '200') {
				// grab the json and pass it to the product fields array
				$decoded_array = json_decode($productfields_response->getBody(), true);
				// full product code is also passed in this so array shift the first line
				$this->formdata->st_product_code = array_shift($decoded_array);
				$this->formdata->product_name    = array_shift($decoded_array);
				return $decoded_array;
			}
		} catch (Exception $e) {
			array_push($this->error_log, $e->getMessage());
		}
	}

	private function getAllData() {
		// check that the session has anything stored in it before trying to get anything
		if ($_SESSION['st_webform'] != []) {
			foreach ($_SESSION['st_webform'] as $key => $value) {
				$this->formdata->$key = $value;
			}
		}
	}

	private function manageOrderToken($order_token) {
		// check if class has been passed a new token
		// if yes then destroy session and start again
		if (isset($_SESSION['st_webform']['order_token']) && $_SESSION['st_webform']['order_token'] != '' && $_SESSION['st_webform']['order_token'] != $order_token) {
			unset($_SESSION['st_webform']);
		}

		// add required pulled from the session or from post if new session
		$this->formdata->order_token = ($this->checkSession('order_token') ? $this->getSession('order_token') : $order_token);
	}

	private function getSession($value_name) {
		# shorthand to retrieve from the session var
		if (isset($_SESSION['st_webform'][$value_name])) {
			return $_SESSION['st_webform'][$value_name];
		} else {return NULL;}
	}

	private function checkSession($value_name) {
		# shorthand to check if the session var is set
		if (isset($_SESSION['st_webform'][$value_name])) {return true;} else {return false;}
	}

	private function storeInSession($value_name, $value) {
		// check if value isset and it has not changed
		if (isset($_SESSION['st_webform'][$value_name]) && $_SESSION['st_webform'][$value_name] == $value) {
			return true;
		} else {
			$_SESSION['st_webform'][$value_name] = $value;
			return true;
		}
	}

	private function placeOrder() {
		if ($this->formdata->approver_email_address == '' || !isset($this->formdata->approver_email_address)) {
			$this->formdata->approver_email_address = $this->formdata->approver_list[0]['email'];
		}

		if (!isset($this->formdata->dv_auth_method) || $this->formdata->dv_auth_method == '' || $this->formdata->dv_auth_method == 'Email') {
			// set them on email even if they dont require it or review has come back lower case
			$this->formdata->dv_auth_method = 'EMAIL';
		}

		$renewal             = 0;
		$competitive_upgrade = 0;
		if ($this->formdata->order_type == 'renewal') {
			$renewal = 1;
		} elseif ($this->formdata->order_type == 'competitive_upgrade') {
			$competitive_upgrade = 1;
		}
		// start placing the order via the restler post
		try {
			$place_order = $this->client->post('place.json', [
				'form_params' => [
					'domain_name'                     => (isset($this->formdata->domain_name) ? $this->formdata->domain_name : ''),
					'san_domains'                     => (isset($this->formdata->san_domains) ? implode(',', $this->formdata->san_domains) : ''),
					'integration_source_id'           => 2,
					'org_name'                        => (isset($this->formdata->org_name) ? $this->formdata->org_name : ''),
					'org_duns'                        => (isset($this->formdata->org_duns) ? $this->formdata->org_duns : ''),
					'org_division'                    => (isset($this->formdata->org_division) ? $this->formdata->org_division : ''),
					'org_incorperating_agency'        => (isset($this->formdata->org_incorperating_agency) ? $this->formdata->org_incorperating_agency : ''),
					'org_incorperating_number'        => (isset($this->formdata->org_incorperating_number) ? $this->formdata->org_incorperating_number : ''),
					'org_jurisdiction_city'           => (isset($this->formdata->org_jurisdiction_city) ? $this->formdata->org_jurisdiction_city : ''),
					'org_jurisdiction_region'         => (isset($this->formdata->org_jurisdiction_region) ? $this->formdata->org_jurisdiction_region : ''),
					'org_jurisdiction_country'        => (isset($this->formdata->org_jurisdiction_country) ? $this->formdata->org_jurisdiction_country : ''),
					'org_address_line1'               => (isset($this->formdata->org_address_line1) ? $this->formdata->org_address_line1 : ''),
					'org_address_line2'               => (isset($this->formdata->org_address_line2) ? $this->formdata->org_address_line2 : ''),
					'org_address_line3'               => (isset($this->formdata->org_address_line3) ? $this->formdata->org_address_line3 : ''),
					'org_address_city'                => (isset($this->formdata->org_address_city) ? $this->formdata->org_address_city : ''),
					'org_address_region'              => (isset($this->formdata->org_address_region) ? $this->formdata->org_address_region : ''),
					'org_address_postal_code'         => (isset($this->formdata->org_address_postal_code) ? $this->formdata->org_address_postal_code : ''),
					'org_address_country'             => (isset($this->formdata->org_address_country) ? $this->formdata->org_address_country : ''),
					'org_address_phone'               => (isset($this->formdata->org_address_phone) ? $this->formdata->org_address_phone : ''),
					'tech_contact_first_name'         => (isset($this->formdata->tech_contact_first_name) ? $this->formdata->tech_contact_first_name : ''),
					'tech_contact_last_name'          => (isset($this->formdata->tech_contact_last_name) ? $this->formdata->tech_contact_last_name : ''),
					'tech_contact_phone'              => (isset($this->formdata->tech_contact_phone) ? $this->formdata->tech_contact_phone : ''),
					'tech_contact_email'              => (isset($this->formdata->tech_contact_email) ? $this->formdata->tech_contact_email : ''),
					'tech_contact_title'              => (isset($this->formdata->tech_contact_title) ? $this->formdata->tech_contact_title : ''),
					'tech_contact_organisation_name'  => (isset($this->formdata->tech_contact_organisation_name) ? $this->formdata->tech_contact_organisation_name : ''),
					'tech_contact_address_line1'      => (isset($this->formdata->tech_contact_address_line1) ? $this->formdata->tech_contact_address_line1 : ''),
					'tech_contact_address_line2'      => (isset($this->formdata->tech_contact_address_line2) ? $this->formdata->tech_contact_address_line2 : ''),
					'tech_contact_address_city'       => (isset($this->formdata->tech_contact_address_city) ? $this->formdata->tech_contact_address_city : ''),
					'tech_contact_address_region'     => (isset($this->formdata->tech_contact_address_region) ? $this->formdata->tech_contact_address_region : ''),
					'tech_contact_address_post_code'  => (isset($this->formdata->tech_contact_address_post_code) ? $this->formdata->tech_contact_address_post_code : ''),
					'tech_contact_address_country'    => (isset($this->formdata->tech_contact_address_country) ? $this->formdata->tech_contact_address_country : ''),
					'admin_contact_first_name'        => (isset($this->formdata->admin_contact_first_name) ? $this->formdata->admin_contact_first_name : ''),
					'admin_contact_last_name'         => (isset($this->formdata->admin_contact_last_name) ? $this->formdata->admin_contact_last_name : ''),
					'admin_contact_phone'             => (isset($this->formdata->admin_contact_phone) ? $this->formdata->admin_contact_phone : ''),
					'admin_contact_email'             => (isset($this->formdata->admin_contact_email) ? $this->formdata->admin_contact_email : ''),
					'admin_contact_title'             => (isset($this->formdata->admin_contact_title) ? $this->formdata->admin_contact_title : ''),
					'admin_contact_title'             => (isset($this->formdata->admin_contact_title) ? $this->formdata->admin_contact_title : ''),
					'admin_contact_organisation_name' => (isset($this->formdata->admin_contact_organisation_name) ? $this->formdata->admin_contact_organisation_name : ''),
					'admin_contact_address_line1'     => (isset($this->formdata->admin_contact_address_line1) ? $this->formdata->admin_contact_address_line1 : ''),
					'admin_contact_address_line2'     => (isset($this->formdata->admin_contact_address_line2) ? $this->formdata->admin_contact_address_line2 : ''),
					'admin_contact_address_city'      => (isset($this->formdata->admin_contact_address_city) ? $this->formdata->admin_contact_address_city : ''),
					'admin_contact_address_region'    => (isset($this->formdata->admin_contact_address_region) ? $this->formdata->admin_contact_address_region : ''),
					'admin_contact_address_post_code' => (isset($this->formdata->admin_contact_address_post_code) ? $this->formdata->admin_contact_address_post_code : ''),
					'admin_contact_address_country'   => (isset($this->formdata->admin_contact_address_country) ? $this->formdata->admin_contact_address_country : ''),
					'approver_email_address'          => (isset($this->formdata->approver_email_address) ? $this->formdata->approver_email_address : ''),
					'csr'                             => (isset($this->formdata->csr) ? $this->formdata->csr : ''),
					'competitive_upgrade'             => $competitive_upgrade,
					'renewal'                         => $renewal,
					'legacy'                          => 0,
					'dv_auth_method'                  => (isset($this->formdata->dv_auth_method) ? $this->formdata->dv_auth_method : ''),
					'web_server_type'                 => (isset($this->formdata->web_server_type) ? $this->formdata->web_server_type : ''),
					'certificate_type'                => (isset($this->formdata->certificate_type) ? $this->formdata->certificate_type : ''),
					'hashing_algorithm'               => (isset($this->formdata->hashing_algorithm) ? $this->formdata->hashing_algorithm : ''),
					'smartertools_email'              => (isset($this->formdata->smartertools_email) ? $this->formdata->smartertools_email : ''),
					'order_token'                     => (isset($this->formdata->order_token) ? $this->formdata->order_token : ''),
				],
			]);

			if ($place_order->getStatusCode() == '200') {
				if (!isset(json_decode($place_order->getBody(), true)['error'])) {
					$returned = json_decode($place_order->getBody(), true);
					// assumed complete order so proceed to pass information to view
					return $returned;
				} else {
					// throw error returned from the place
					array_push($this->error_log, json_decode($place_order->getBody(), true)['error']);
				}
			}
		} catch (Exception $e) {
			array_push($this->error_log, $e->getMessage());
		}
	}


	private function reviewToken($force_refresh=false) {
		// normally do not keep calling review for info but if forced is set to true then do it.
		if (isset($this->formdata->order_status) && $this->formdata->order_status != '' && $this->formdata && !$force_refresh) {
			// order is already reviewed so dont call the api again
			if ($this->formdata->order_status != 'Order Placed') {
				if (basename($_SERVER['PHP_SELF']) != 'review.php' && basename($_SERVER['PHP_SELF']) != 'index.php') {
					header('Location:review.php');
				}
			}
		} else {
			try {
				$token_review = $this->client->get('review.json', [
					'query' => [
						'order_token' => $this->formdata->order_token,
					],
				]);
				if ($token_review->getStatusCode() == '200') {
					$returned = json_decode($token_review->getBody());

					// loop through all data and add it to the formdata session storage
					foreach ($returned as $key => $value) {
						// skip over anything we dont need in the session
						if ($key == 'success' || $key == 'modifications' || $key == 'order_token') {
							continue;
							// also loop through stuff that is another level deep
						} elseif ($key == 'organisation_info' || $key == 'admin_contact' || $key == 'tech_contact') {
							switch ($key) {
							case 'organisation_info':
								foreach ($returned->organisation_info as $orgkey => $orgvalue) {
									if ($orgkey == 'address') {
										// we need another loop here to get address info
										foreach ($returned->organisation_info->$orgkey as $addrkey => $addrvalue) {
											$this->formdata->{'org_address_' . $addrkey} = $addrvalue;
										}
									} else {
										$this->formdata->{'org_' . $orgkey} = $orgvalue;
									}
								}
								break;
							case 'admin_contact':
								foreach ($returned->admin_contact as $adminkey => $adminvalue) {
									$this->formdata->{'admin_contact_' . $adminkey} = $adminvalue;
								}
								break;
							case 'tech_contact':
								foreach ($returned->tech_contact as $techkey => $techvalue) {
									$this->formdata->{'tech_contact_' . $techkey} = $techvalue;
								}
								break;
							default:
								break;
							}
						} else {
							$this->formdata->$key = $value;
						}
					}

					// check order status for if its a new order or not
					if ($returned->order_status == 'Order Placed') {
						// order is new so move to next form place
						return true;
					} else {
						$this->getAppoverList();
						header('Location:review.php');
					}
				}
			} catch (Exception $e) {
				// array_push($this->error_log, 'Invalid Order Token, Please check it and try again.');
				array_push($this->error_log, $e->getMessage());
			}
		}
	}

	private function hashingAlgos() {
		if (!isset($this->hash_algo_fields)) {
			if (isset($this->formdata->product_fields)) {
				foreach ($this->formdata->product_fields as $key => $value) {
					if (isset($this->formdata->product_fields[$key]['hashing_algorithm']) && $this->formdata->product_fields[$key]['hashing_algorithm']) {
						$this->hash_algo_fields = [];
						foreach ($this->formdata->product_fields[$key]['hashing_algorithm']['options'] as $optkey => $optvalue) {
							array_push($this->hash_algo_fields, $optvalue);
						}
					}
				}
			}
		}
	}

	private function decodeCSR($csr) {
		if ($csr != '') {
			try {
				$csr_request = $this->client->post('decodecsr.json', [
					'form_params' => [
						'order_token' => $this->formdata->order_token,
						'csr'         => $csr,
					],
				]);
				if ($csr_request->getStatusCode() == '200') {
					$returned = json_decode($csr_request->getBody());
					if ($returned->QueryResponseHeader->SuccessCode == -1) {
						array_push($this->error_log, 'CSR Error');
					} else {
						$this->formdata->domain_name         = $returned->DomainName;
						$this->formdata->org_name            = $returned->Organization;
						$this->formdata->org_division        = $returned->OrganizationUnit;
						$this->formdata->org_address_city    = $returned->Locality;
						$this->formdata->org_address_region  = $returned->State;
						$this->formdata->org_address_country = $returned->Country;
					}
				}
			} catch (Exception $e) {
				array_push($this->error_log, $e->getMessage());
			}
		} else {return false;}
	}
}