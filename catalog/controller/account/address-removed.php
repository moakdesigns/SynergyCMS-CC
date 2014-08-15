<?php 
public function update() {

if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
	$this->model_account_address->editAddress($this->request->get['address_id'], $this->request->post);


// Default Shipping Address
			// if (isset($this->session->data['shipping_address_id']) && ($this->request->get['address_id'] == $this->session->data['shipping_address_id'])) {
			// 	$this->session->data['shipping_country_id'] = $this->request->post['country_id'];
			// 	$this->session->data['shipping_zone_id'] = $this->request->post['zone_id'];
			// 	$this->session->data['shipping_zipcode'] = $this->request->post['zipcode'];

			// 	unset($this->session->data['shipping_method']);	
			// 	unset($this->session->data['shipping_methods']);
			// }

			// Default Payment Address
			// if (isset($this->session->data['payment_address_id']) && ($this->request->get['address_id'] == $this->session->data['payment_address_id'])) {
			// 	$this->session->data['payment_country_id'] = $this->request->post['country_id'];
			// 	$this->session->data['payment_zone_id'] = $this->request->post['zone_id'];

			// 	unset($this->session->data['payment_method']);
			// 	unset($this->session->data['payment_methods']);
			// }

}

$this->session->data['success'] = $this->language->get('text_update');

			$this->redirect($this->url->link('account/address', '', 'SSL'));
		}

$this->getForm();
	}

public function delete() {
		if (!$this->member->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/address', '', 'SSL');

			$this->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->language->load('account/address');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/address');

		if (isset($this->request->get['address_id']) && $this->validateDelete()) {
			$this->model_account_address->deleteAddress($this->request->get['address_id']);	

			// Default Shipping Address
			// if (isset($this->session->data['shipping_address_id']) && ($this->request->get['address_id'] == $this->session->data['shipping_address_id'])) {
			// 	unset($this->session->data['shipping_address_id']);
			// 	unset($this->session->data['shipping_country_id']);
			// 	unset($this->session->data['shipping_zone_id']);
			// 	unset($this->session->data['shipping_zipcode']);				
			// 	unset($this->session->data['shipping_method']);
			// 	unset($this->session->data['shipping_methods']);
			// }

			// Default Payment Address
			// if (isset($this->session->data['payment_address_id']) && ($this->request->get['address_id'] == $this->session->data['payment_address_id'])) {
			// 	unset($this->session->data['payment_address_id']);
			// 	unset($this->session->data['payment_country_id']);
			// 	unset($this->session->data['payment_zone_id']);				
			// 	unset($this->session->data['payment_method']);
			// 	unset($this->session->data['payment_methods']);
			// }

			$this->session->data['success'] = $this->language->get('text_delete');

			$this->redirect($this->url->link('account/address', '', 'SSL'));
		}

		$this->getList();	
	}

	// $this->data['entry_company_id'] = $this->language->get('entry_company_id');
		// $this->data['entry_tax_id'] = $this->language->get('entry_tax_id');		


// if (isset($this->error['company_id'])) {
		// 	$this->data['error_company_id'] = $this->error['company_id'];
		// } else {
		// 	$this->data['error_company_id'] = '';
		// }

		// if (isset($this->error['tax_id'])) {
		// 	$this->data['error_tax_id'] = $this->error['tax_id'];
		// } else {
		// 	$this->data['error_tax_id'] = '';
		// }

// if (isset($this->request->post['company_id'])) {
		// 	$this->data['company_id'] = $this->request->post['company_id'];
		// } elseif (!empty($address_info)) {
		// 	$this->data['company_id'] = $address_info['company_id'];			
		// } else {
		// 	$this->data['company_id'] = '';
		// }

		// if (isset($this->request->post['tax_id'])) {
		// 	$this->data['tax_id'] = $this->request->post['tax_id'];
		// } elseif (!empty($address_info)) {
		// 	$this->data['tax_id'] = $address_info['tax_id'];			
		// } else {
		// 	$this->data['tax_id'] = '';
		// }

// if ($member_group_info) {
		// 	$this->data['company_id_display'] = $member_group_info['company_id_display'];
		// } else {
		// 	$this->data['company_id_display'] = '';
		// }

		// if ($customer_group_info) {
		// 	$this->data['tax_id_display'] = $customer_group_info['tax_id_display'];
		// } else {
		// 	$this->data['tax_id_display'] = '';
		// }
// VAT Validation
			// $this->load->helper('vat');

			// if ($this->config->get('config_vat') && !empty($this->request->post['tax_id']) && (vat_validation($country_info['iso_code_2'], $this->request->post['tax_id']) == 'invalid')) {
			// 	$this->error['tax_id'] = $this->language->get('error_vat');
			// }	
 ?>