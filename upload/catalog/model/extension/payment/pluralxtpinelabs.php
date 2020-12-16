<?php
class ModelExtensionPaymentPluralXTPinelabs extends Model {
  public function getMethod($address, $total) {
    $this->load->language('extension/payment/pluralxtpinelabs');
  
    $method_data = array(
      'code'     => 'pluralxtpinelabs',
      'title'    => $this->language->get('text_title'),
      'sort_order' => $this->config->get('custom_sort_order'),
	  'terms'=>''
    );
  
    return $method_data;
  }
}