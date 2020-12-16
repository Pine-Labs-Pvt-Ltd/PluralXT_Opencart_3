<?php 
class ControllerExtensionPaymentPluralXTPinelabs extends Controller {
	private $error = array(); 

	public function index() {

		$this->load->language('extension/payment/pluralxtpinelabs');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_pluralxtpinelabs', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_live'] = $this->language->get('text_live');
		$data['text_successful'] = $this->language->get('text_successful');
		$data['text_fail'] = $this->language->get('text_fail');
		$data['demo'] = $this->language->get('demo');		

		$data['entry_merchantid'] = $this->language->get('entry_merchantid');
		$data['entry_access_code'] = $this->language->get('entry_access_code');
		$data['entry_secure_secret'] = $this->language->get('entry_secure_secret');
		$data['entry_algo_type'] = $this->language->get('entry_algo_type');	
		$data['entry_mode'] = $this->language->get('entry_mode');		
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['payment_entry_status'] = $this->language->get('entry_status');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_payment_mode'] = $this->language->get('entry_payment_mode');

		$data['entry_preferred_gateway'] = $this->language->get('entry_preferred_gateway');		

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		$data['help_merchantid'] = $this->language->get('help_merchantid');

		$data['help_access_code'] = $this->language->get('help_access_code');
		$data['help_secure_secret'] = $this->language->get('help_secure_secret');

		$data['help_algo_type'] = $this->language->get('help_algo_type');
		$data['help_payment_mode'] = $this->language->get('help_payment_mode');
		$data['entry_algo_type_MD5'] = $this->language->get('entry_algo_type_MD5');
		$data['entry_algo_type_SHA_256'] = $this->language->get('entry_algo_type_SHA_256');
		$data['entry_mode_live'] = $this->language->get('entry_mode_live');
		$data['entry_mode_test'] = $this->language->get('entry_mode_test');
		$data['payment_entry_status_enabled'] = $this->language->get('entry_status_enabled');
		$data['payment_entry_status_disabled'] = $this->language->get('entry_status_disabled');

		$data['entry_payment_mode_Credit_Debit'] = $this->language->get('entry_payment_mode_Credit_Debit');
		$data['entry_payment_mode_EMI'] = $this->language->get('entry_payment_mode_EMI');
		$data['tab_general'] = $this->language->get('tab_general');

		$data['CreditCard'] = $this->language->get('CreditCard');
		$data['DebitCard'] = $this->language->get('DebitCard');
		$data['NetBanking'] = $this->language->get('NetBanking');

		$data['payment_pluralxtpinelabs_merchantid'] = '';
		$data['payment_pluralxtpinelabs_access_code'] = '';
		$data['payment_pluralxtpinelabs_secure_secret'] = '';

		$data['payment_pluralxtpinelabs_sort_order'] = '';
		$data['payment_pluralxtpinelabs_secure_secret'] = '';
		$data['payment_pluralxtpinelabs_payment_mode'] = '';

		$data['payment_pluralxtpinelabs_preferred_gateway'] = '';

		$data['error_access_code'] = '';
		$data['error_secure_secret'] = '';
		$data['error_sort_order'] = '';
	   				
	   
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['merchant'])) {
			$data['error_merchant'] = $this->error['merchant'];
		} else {
			$data['error_merchant'] = '';
		}

		if (isset($this->error['access_code'])) {
			$data['error_access_code'] = $this->error['access_code'];
		} else {
			$data['error_access_code'] = '';
		}

		if (isset($this->error['secure_secret'])) {
			$data['error_secure_secret'] = $this->error['secure_secret'];
		} else {
			$data['error_secure_secret'] = '';
		}

		if (isset($this->error['sort_order'])) {
			$data['error_sort_order'] = $this->error['sort_order'];
		} else {
			$data['error_sort_order'] = '';
		}

		   if (isset($this->error['payment_mode'])) {
			$data['error_payment_mode'] = $this->error['payment_mode'];
		} else {
			$data['error_payment_mode'] = '';
		}


		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
		'href'      => $this->url->link('common/home', 'user_token=' . $this->session->data['user_token'], 'SSL'),
			'separator' => false
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_payment'),
		'href'      => $this->url->link('extension/payment', 'user_token=' . $this->session->data['user_token'], 'SSL'),
			'separator' => ' :: '
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
		'href'      => $this->url->link('extension/payment/pluralxtpinelabs', 'user_token=' . $this->session->data['user_token'], 'SSL'),
			'separator' => ' :: '
		);
				
		$data['action'] = $this->url->link('extension/payment/pluralxtpinelabs', 'user_token=' . $this->session->data['user_token'], 'SSL');
		$data['cancel'] = $this->url->link('extension/payment', 'user_token=' . $this->session->data['user_token'], 'SSL');


		if (isset($this->request->post['payment_pluralxtpinelabs_merchantid'])) {
			$data['payment_pluralxtpinelabs_merchantid'] = $this->request->post['payment_pluralxtpinelabs_merchantid'];
		} else {
			$data['payment_pluralxtpinelabs_merchantid'] = $this->config->get('payment_pluralxtpinelabs_merchantid');
		}

		if (isset($this->request->post['payment_pluralxtpinelabs_access_code'])) {
			$data['payment_pluralxtpinelabs_access_code'] = $this->request->post['payment_pluralxtpinelabs_access_code'];
		} else {
			$data['payment_pluralxtpinelabs_access_code'] = $this->config->get('payment_pluralxtpinelabs_access_code');
		}



		if (isset($this->request->post['payment_pluralxtpinelabs_secure_secret'])) {
			$data['payment_pluralxtpinelabs_secure_secret'] = $this->request->post['payment_pluralxtpinelabs_secure_secret'];
		} else {
			$data['payment_pluralxtpinelabs_secure_secret'] = $this->config->get('payment_pluralxtpinelabs_secure_secret');
		}



		if (isset($this->request->post['pluralxtpinelabs_algo_type'])) {
			$data['pluralxtpinelabs_algo_type'] = $this->request->post['pluralxtpinelabs_algo_type'];
		} else {
			$data['pluralxtpinelabs_algo_type'] = $this->config->get('pluralxtpinelabs_algo_type');
		}

		if (isset($this->request->post['payment_pluralxtpinelabs_mode'])) {
			$data['payment_pluralxtpinelabs_mode'] = $this->request->post['payment_pluralxtpinelabs_mode'];
		} else {
			$data['payment_pluralxtpinelabs_mode'] = $this->config->get('payment_pluralxtpinelabs_mode');
		}

			
		if (isset($this->request->post['payment_pluralxtpinelabs_status'])) {
			$data['payment_pluralxtpinelabs_status'] = $this->request->post['payment_pluralxtpinelabs_status'];
		} else {
			$data['payment_pluralxtpinelabs_status'] = $this->config->get('payment_pluralxtpinelabs_status');
		}

		if (isset($this->request->post['payment_pluralxtpinelabs_sort_order'])) {
			$data['payment_pluralxtpinelabs_sort_order'] = $this->request->post['payment_pluralxtpinelabs_sort_order'];
		} else {
			$data['payment_pluralxtpinelabs_sort_order'] = $this->config->get('payment_pluralxtpinelabs_sort_order');
		}

		if (isset($this->request->post['payment_pluralxtpinelabs_payment_mode'])) {
			$data['payment_pluralxtpinelabs_payment_mode'] = $this->request->post['payment_pluralxtpinelabs_payment_mode'];
		} else {
			$data['payment_pluralxtpinelabs_payment_mode'] = $this->config->get('payment_pluralxtpinelabs_payment_mode');
		}

		if (isset($this->request->post['payment_pluralxtpinelabs_preferred_gateway'])) {
			$data['payment_pluralxtpinelabs_preferred_gateway'] = $this->request->post['payment_pluralxtpinelabs_preferred_gateway'];
		} else {
			$data['payment_pluralxtpinelabs_preferred_gateway'] = $this->config->get('payment_pluralxtpinelabs_preferred_gateway');
		}		

		$data['gateway_options'] = array(
											"AMEX",
											"AMEX_ENHANCED",
											"AXIS",
											"AXISB24",
											"BANKTEK",
											"BFL",
											"BHARATQR_HDFC",
											"BILLDESK",
											"BOB",
											"CCAVENUE_NET_BANKING",
											"CITI",
											"CITRUS_NET_BANKING",
											"CORP",
											"DEBIT_PIN_FSS",
											"EBS_NETBANKING",
											"EDGE",
											"FEDERAL",
											"FSS_NETBANKING",
											"HDFC",
											"HDFC_DEBIT_EMI",
											"HDFC_PRIZM",
											"HSBC",
											"ICICI",
											"ICICI_SHAKTI",
											"IDBI",
											"LVB",
											"MASHREQ",
											"OPUS",
											"PAYTM",
											"PayU",
											"RAZOR_PAY",
											"SBI",
											"SBI87",
											"SI_HDFC",
											"SI_PAYNIMO",
											"UBI",
											"UPI_AXIS",
											"UPI_HDFC",
											"WALLET_PAYZAPP",
											"WALLET_PHONEPE",
											"YES",
											"ZEST_MONEY"
										);

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
				
		$this->load->model('localisation/geo_zone');
										
		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/pluralxtpinelabs', $data));
	}

	private function validate() {
		
		if (!$this->user->hasPermission('modify', 'extension/payment/pluralxtpinelabs')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if ($this->request->post['payment_pluralxtpinelabs_merchantid'] == '' || ctype_space($this->request->post['payment_pluralxtpinelabs_merchantid'])) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}
			
		if ($this->request->post['payment_pluralxtpinelabs_access_code'] == '' || ctype_space($this->request->post['payment_pluralxtpinelabs_access_code'])) {
			$this->error['access_code'] = $this->language->get('error_access_code');
		}
		
		if ($this->request->post['payment_pluralxtpinelabs_secure_secret'] == '' || ctype_space($this->request->post['payment_pluralxtpinelabs_secure_secret'])) {
			$this->error['secure_secret'] = $this->language->get('error_secure_secret');
		}
		
	//sort order is not mandatory	
		// if ($this->request->post['payment_pluralxtpinelabs_sort_order'] == '' || ctype_space($this->request->post['payment_pluralxtpinelabs_sort_order'])) {
		// 	$this->error['sort_order'] = $this->language->get('error_sort_order');
		// }
		
		if ($this->request->post['payment_pluralxtpinelabs_payment_mode'] == '' || ctype_space($this->request->post['payment_pluralxtpinelabs_payment_mode'])) {
			$this->error['payment_mode'] = $this->language->get('error_payment_mode');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>