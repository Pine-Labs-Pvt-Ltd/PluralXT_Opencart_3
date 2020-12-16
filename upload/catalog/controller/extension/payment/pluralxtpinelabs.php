<?php
class Controllerextensionpaymentpluralxtpinelabs extends Controller {
  
	public function index() 
	{
	    $this->load->language('extension/payment/pluralxtpinelabs');
		$this->logger = new Log('pluralxtpinelabs_'. date("Y-m-d").'.log');
	    $this->load->model('checkout/order');
		$Order_Id=$this->session->data['order_id'];
	    $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
	    
	    $this->logger->write('[Order ID]:' . $Order_Id.'  Order Info: ' . serialize($order_info));
    
	    if ($order_info) 
	    {    
			$orderamount = $this->currency->format($order_info['total'], $order_info['currency_code'] , false, false);
			$ppc_MerchantID = $this->config->get('payment_pluralxtpinelabs_merchantid');
			$ppc_MerchantAccessCode = $this->config->get('payment_pluralxtpinelabs_access_code');
			$secret_key   =   $this -> Hex2String($this->config->get('payment_pluralxtpinelabs_secure_secret'));
			$ppc_PayModeOnLandingPage = $this->config->get('payment_pluralxtpinelabs_payment_mode');
			$preferred_gateway = $this->config->get('payment_pluralxtpinelabs_preferred_gateway');
			$ppc_Amount = intval(floatval($orderamount) * 100);//$orderamount * 100;
			$ppc_MerchantReturnURL =  $this->url->link('extension/payment/pluralxtpinelabs/callback');
			//appended time
			$ppc_UniqueMerchantTxnID =$this->session->data['order_id'] . '_' . date("ymdHis");
			$ppc_CustomerMobile =html_entity_decode($order_info['telephone'], ENT_QUOTES, 'UTF-8') ;
			$ppc_CustomerEmail =$order_info['email'] ;
	
			$ppc_CustomerFirstName =$order_info['payment_firstname'] ;
			$ppc_CustomerLastName =$order_info['payment_lastname'] ;
			$ppc_CustomerCity =$order_info['payment_city'] ;
			$ppc_CustomerState =$order_info['payment_zone'] ;
			$ppc_CustomerCountry =$order_info['payment_country'] ;
			$ppc_CustomerAddress1 =$order_info['payment_address_1'] ;
			$ppc_CustomerAddress2 =$order_info['payment_address_2'] ;
			$ppc_CustomerAddressPIN =$order_info['payment_postcode'] ;

			$ppc_ShippingFirstName =$order_info['shipping_firstname'] ;
			$ppc_ShippingLastName =$order_info['shipping_lastname'] ;
			$ppc_ShippingAddress1 =$order_info['shipping_address_1'] ;
			$ppc_ShippingAddress2 =$order_info['shipping_address_2'] ;
			$ppc_ShippingCity =$order_info['shipping_city'] ;
			$ppc_ShippingState =$order_info['shipping_zone'] ;
			$ppc_ShippingCountry =$order_info['shipping_country'] ;
			$ppc_ShippingZipCode =$order_info['shipping_postcode'] ;

			$ppc_UdfField1 = 'OpenCart_v_3.0.3.2';
			$ppc_MerchantProductInfo = '';

			$product_info_data = new \stdClass();

	        $i = 0;

	        foreach ($this->cart->getProducts() as $product) 
	        {
				for ($j = 0; $j < $product['quantity']; $j++)
				{
		            $ppc_MerchantProductInfo .= $product['name'] . '|';

		            $product_details = new \stdClass();
					$product_details->product_code = $product['product_id'];
					$product_details->product_amount = intval(floatval($product['price']) * 100);
	
					$product_info_data->product_details[$i++] = $product_details;
				}

	        }
		
			$ppc_MerchantProductInfo = substr($ppc_MerchantProductInfo, 0, -1);

			$merchant_data = new \stdClass();		
			$merchant_data->merchant_return_url = $ppc_MerchantReturnURL;
			$merchant_data->merchant_access_code = $ppc_MerchantAccessCode;
			$merchant_data->order_id = $ppc_UniqueMerchantTxnID;
			$merchant_data->merchant_id = $ppc_MerchantID;

			$payment_info_data = new \stdClass();
			$payment_info_data->amount = $ppc_Amount;
			$payment_info_data->currency_code = "INR";
			$payment_info_data->preferred_gateway = $preferred_gateway;
			$payment_info_data->order_desc = $ppc_MerchantProductInfo;

			$customer_data = new \stdClass();
			// $customer_data->customer_id = $order_info['customer_id'];
			$customer_data->customer_ref_no = $order_info['customer_id'];
			// $customer_data->mobile_no = $ppc_CustomerMobile;
			$customer_data->mobile_number = $ppc_CustomerMobile;
			$customer_data->email_id = $ppc_CustomerEmail;
			$customer_data->first_name = $ppc_CustomerFirstName;
			$customer_data->last_name = $ppc_CustomerLastName;
			$customer_data->country_code = "91";

			$billing_address_data = new \stdClass();
			$billing_address_data->first_name = $ppc_CustomerFirstName;
			$billing_address_data->last_name = $ppc_CustomerLastName;
			$billing_address_data->address1 = $ppc_CustomerAddress1;
			$billing_address_data->address2 = $ppc_CustomerAddress2;
			$billing_address_data->address3 = "";
			$billing_address_data->pincode = $ppc_CustomerAddressPIN;
			$billing_address_data->city = $ppc_CustomerCity;
			$billing_address_data->state = $ppc_CustomerState;
			$billing_address_data->country = $ppc_CustomerCountry;

			$shipping_address_data = new \stdClass();
			$shipping_address_data->first_name = $ppc_ShippingFirstName;
			$shipping_address_data->last_name = $ppc_ShippingLastName;
			$shipping_address_data->address1 = $ppc_ShippingAddress1;
			$shipping_address_data->address2 = $ppc_ShippingAddress2;
			$shipping_address_data->address3 = "";
			$shipping_address_data->pincode = $ppc_ShippingZipCode;
			$shipping_address_data->city = $ppc_ShippingCity;
			$shipping_address_data->state = $ppc_ShippingState;
			$shipping_address_data->country = $ppc_ShippingCountry;

			$additional_info_data = new \stdClass();
			$additional_info_data->rfu1 = '';//$ppc_UdfField1;      

			$orderData = new \stdClass();

			$orderData->merchant_data = $merchant_data;
			$orderData->payment_info_data = $payment_info_data;
			$orderData->customer_data = $customer_data;
			$orderData->billing_address_data = $billing_address_data;
			$orderData->shipping_address_data = $shipping_address_data;
			$orderData->product_info_data = $product_info_data;
			$orderData->additional_info_data = $additional_info_data;

			$orderData = json_encode($orderData);

			$requestData = new \stdClass();
			$requestData->request = base64_encode($orderData);
			
			$x_verify = strtoupper(hash_hmac("sha256", $requestData->request, $this->Hex2String($this->config->get('payment_pluralxtpinelabs_secure_secret'))));

			$requestData = json_encode($requestData);

			$gateway_mode = $this->config->get('payment_pluralxtpinelabs_mode');

			$data['order_request'] = base64_encode($orderData);
			$data['x_verify'] = $x_verify;
			$data['gateway_mode'] = $gateway_mode;
			$data['ppc_PayModeOnLandingPage'] = $ppc_PayModeOnLandingPage;
			$data['order_id'] = $Order_Id;

			$data['action'] =  $this->url->link('extension/payment/pluralxtpinelabs/processpayment');
		  
			$data['button_confirm'] = $this->language->get('button_confirm');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . 'extension/payment/pluralxtpinelabs')){
				return $this->load->view($this->config->get('config_template') . 'extension/payment/pluralxtpinelabs', $data);
			} else {
				return $this->load->view('extension/payment/pluralxtpinelabs', $data);
			}		

			exit;
	    }
	}

	function processpayment()
	{
		$this->logger = new Log('pluralxtpinelabs_'. date("Y-m-d").'.log');
		$this->logger->write('processpayment() called');

		$this->load->model('checkout/order');

		$postdata = $_REQUEST;

		$x_verify = $postdata['x_verify'];
		$ppc_PayModeOnLandingPage = $postdata['ppc_PayModeOnLandingPage'];		
		$order_id = $postdata['order_id'];

		$requestData = new \stdClass();
		$requestData->request = $postdata['order_request'];

		$requestData = json_encode($requestData);

		$pluralxtHostUrl = 'https://paymentoptimizer.pinepg.in';

		if ($postdata['gateway_mode'] == 'test')
		{
			$pluralxtHostUrl = 'https://paymentoptimizertest.pinepg.in';
		}

		$orderCreationUrl = $pluralxtHostUrl . '/api/v2/order/create';

		$order_creation = $this->callOrderCreationAPI($orderCreationUrl, $requestData, $x_verify);

		$response = json_decode($order_creation, true);

		$response_code = null;
		$token = null;

		// exit;

		if (!empty($response))
		{	
			if (array_key_exists('response_code', $response))
			{	
				$response_code = $response['response_code'];
			}

			if (array_key_exists('token', $response))
			{
				$token = $response['token'];
			}	
		}

		// $response_code = null;

		if ($response_code != 1 || empty($token))
		{
			$this->logger->write('[Order ID]:' . $order_id.'  Order creation for processing payment by Plural XT failed. ');
			$comment='Processing payment by Plural XT failed.';
 			$Order_Status='10';

			$this->model_checkout_order->addOrderHistory($order_id, $Order_Status,$comment,true,false);
			$this->response->redirect($this->url->link('checkout/failure', 'path=59'));
		}
		else
		{
			$payment_redirect_url = $pluralxtHostUrl . '/pinepg/v2/process/payment/redirect?orderToken=' . $token . '&paymentmodecsv=' . $ppc_PayModeOnLandingPage;

			$this->response->redirect($payment_redirect_url);
		}	
	}
  
	function callOrderCreationAPI($url, $data, $x_verify)
	{
	   	$curl = curl_init();
		
		curl_setopt($curl, CURLOPT_POST, 1);
		
		if ($data)
		{
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		// OPTIONS:
		curl_setopt($curl, CURLOPT_URL, $url);

		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
		  'X-VERIFY: ' . $x_verify,
		  'Content-Type: application/json',
		));

		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		// EXECUTE:
		$result = curl_exec($curl);

		if (!$result) {
			die("Connection Failure");
		}

		curl_close($curl);

		return $result;
	}

	public function Hex2String($hex)
	{
	    $string='';
	    for ($i=0; $i < strlen($hex)-1; $i+=2){
	        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
	    }
	    return $string;
	}
			
    public function callback() 
	{
		$this->logger = new Log('pluralxtpinelabs_'. date("Y-m-d").'.log');
		$this->logger->write('callback() called');
		
		// if (isset($this->request->post['unique_merchant_txn_id'])) 
		// {
		// 	$merchantTxnID = $this->request->post['unique_merchant_txn_id'];
		if (isset($this->request->post['order_id'])) 
		{
			$merchantTxnID = $this->request->post['order_id'];
			$order_id = explode('_', $merchantTxnID);
			$order_id = (int)$order_id[0];    //get rid of time part

			$this->logger->write('Order ID received: '.$order_id);
		} 
		else 
		{
			$this->logger->write('Received order id is null: ');
			die('Illegal Access ORDER ID NOT PASSED');
		}
	
	    $this->load->model('checkout/order');
	    $order_info = $this->model_checkout_order->getOrder($order_id);
		
		if ($order_info) 
		{
			if ( !empty($_POST) ) 
			{
			
				$DiaSecretType='';
				$DiaSecret='';
			    
			    if (isset($this->request->post['dia_secret_type'])) {
					$DiaSecretType = $this->request->post['dia_secret_type'];
				} 
				
				if (isset($this->request->post['dia_secret'])) {
					$DiaSecret = $this->request->post['dia_secret'];
				} 
				
				$strString="";
				
				ksort($_POST);
				
				foreach ($_POST as $key => $value)
				{
					$strString.=$key."=".$value."&";
				}
				
				$this->logger->write('[Order ID]:' . $order_id.' Received parameters : '.$strString);
				
				unset($_POST['dia_secret_type']);
				unset($_POST['dia_secret']);
				
				$strString="";
				$secret_key   =   $this -> Hex2String($this->config->get('payment_pluralxtpinelabs_secure_secret'));
				
				ksort($_POST);
				
				foreach ($_POST as $key => $value)
				{
					$strString.=$key."=".$value."&";
				}
				
				$strString = substr($strString, 0, -1);
				
				$SecretHashCode = strtoupper(hash_hmac('sha256', $strString, $secret_key));
				
				$this->logger->write('[Order ID]:' . $order_id.'  Generated Secure hash of Received parameters ' .$SecretHashCode);
				
				if("" == trim($DiaSecret))
				{
					$this->logger->write('[Order ID]:' . $order_id.'  Transaction failed. Plural XT Secure hash is empty');
					 $comment='Transaction failed. Plural XT Secure hash is empty';
				     $Order_Status='10';
					$this->model_checkout_order->addOrderHistory($order_id, $Order_Status,$comment,true,false);
					$this->response->redirect($this->url->link('checkout/failure', 'path=59'));
				}   
				else
				{
					if(trim($DiaSecret)==trim($SecretHashCode))
					{
						$this->logger->write('[Order ID]:' . $order_id.'     Secure Hash is matched ');
								   //assigning failed status =10(open cart status)
						$Order_Status='10'; 	
						if ($this->request->post['payment_status'] == 'CAPTURED' && $this->request->post['payment_response_code'] == '1') 
						{
						$this->logger->write('[Order ID]:' . $order_id.'  Payment Transaction is successfull ');
						$comment='Payment Transaction is successful';
						$Order_Status='2';

						}
						else if($this->request->post['payment_status'] == 'CANCELLED')
						{
						  $this->logger->write('[Order ID]:' . $order_id.'  Transaction cancelled by user ');
						  $comment='Transaction cancelled by user';
						  $Order_Status='7';
						}
						else if($this->request->post['payment_status'] == 'REJECTED')
						{
						   $this->logger->write('[Order ID]:' . $order_id.'  Transaction rejected by system ');
						  $comment='Transaction rejected by system';
						  $Order_Status='8';
						}
						else
						{
						   $this->logger->write('[Order ID]:' . $order_id.'  Transaction failed  ');
						  $comment='Transaction failed';
						  $Order_Status='10';
						}
					  
						$this->model_checkout_order->addOrderHistory($order_id, $Order_Status,$comment,true,false);

						if($Order_Status=='2')
						{
							$this->session->data['ppc_Amount']=$this->request->post['amount_in_paisa']; 
							$this->session->data['Order_No']= $order_id;//$this->request->post['ppc_UniqueMerchantTxnID'];
							$this->session->data['ppc_PinePGTransactionID']=$this->request->post['pine_pg_transaction_id']; 
							$this->session->data['ppc_UniqueMerchantTxnID']=$this->request->post['unique_merchant_txn_id']; 
							 
							$this->response->redirect($this->url->link('extension/payment/pluralxtpinelabssuccess', 'path=59'));
						}
						else if($Order_Status=='7')
						{
							$this->response->redirect($this->url->link('extension/payment/pluralxtpinelabscancelledtxn', 'path=59'));
						}
						else
						{
							$this->response->redirect($this->url->link('checkout/failure', 'path=59'));
						}
					}
					else
					{
						$this->logger->write('[Order ID]:' . $order_id.'  Transaction failed. Secure_Hash not matched with Plural XT Secure Hash ');
						$comment='Transaction failed. Secure_Hash not matched with Plural XT Secure Hash';
						 $Order_Status='10';
						$this->model_checkout_order->addOrderHistory($order_id, $Order_Status,$comment,true,false);
						$this->response->redirect($this->url->link('checkout/failure', 'path=59'));
					}
				}		
			}
			else
			{ 		
					$this->logger->write('Post parameters received is empty');
					die('Illegal Access POST REQUEST IS EMPTY');
			}

		}
		else 
		{
			$this->logger->write('[Order id]:' . $order_id.'  No order info exist ');
			echo $order_id;
			die('Illegal Access NO ORDER INFO EXISTS');
		}
	}
}
?>