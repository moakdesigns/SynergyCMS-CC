<?php 

// Default Shipping Address
			if ($this->config->get('config_tax_member') == 'shipping') {
				$this->session->data['shipping_country_id'] = $this->request->post['country_id'];
				$this->session->data['shipping_zone_id'] = $this->request->post['zone_id'];
				$this->session->data['shipping_zipcode'] = $this->request->post['zipcode'];				
			}

			// Default Payment Address
			if ($this->config->get('config_tax_member') == 'payment') {
				$this->session->data['payment_country_id'] = $this->request->post['country_id'];
				$this->session->data['payment_zone_id'] = $this->request->post['zone_id'];			
			}

$this->data['entry_company_id'] = $this->language->get('entry_company_id');
$this->data['entry_tax_id'] = $this->language->get('entry_tax_id');
		
if (isset($this->error['company_id'])) {
			$this->data['error_company_id'] = $this->error['company_id'];
		} else {
			$this->data['error_company_id'] = '';
		}

		if (isset($this->error['tax_id'])) {
			$this->data['error_tax_id'] = $this->error['tax_id'];
		} else {
			$this->data['error_tax_id'] = '';
		}
// Company ID
		if (isset($this->request->post['company_id'])) {
			$this->data['company_id'] = $this->request->post['company_id'];
		} else {
			$this->data['company_id'] = '';
		}

		// Tax ID
		if (isset($this->request->post['tax_id'])) {
			$this->data['tax_id'] = $this->request->post['tax_id'];
		} else {
			$this->data['tax_id'] = '';
		}

		} elseif (isset($this->session->data['shipping_zipcode'])) {
			$this->data['zipcode'] = $this->session->data['shipping_zipcode'];	
 
} elseif (isset($this->session->data['shipping_country_id'])) {
			$this->data['country_id'] = $this->session->data['shipping_country_id'];		
		
} elseif (isset($this->session->data['shipping_zone_id'])) {
			$this->data['zone_id'] = $this->session->data['shipping_zone_id'];			
if ($member_group) {	
			// Company ID
			if ($member_group['company_id_display'] && $member_group['company_id_required'] && empty($this->request->post['company_id'])) {
				$this->error['company_id'] = $this->language->get('error_company_id');
			}

			// Tax ID 
			if ($member_group['tax_id_display'] && $member_group['tax_id_required'] && empty($this->request->post['tax_id'])) {
				$this->error['tax_id'] = $this->language->get('error_tax_id');
			}						
		}


		// VAT Validation
			$this->load->helper('vat');

			if ($this->config->get('config_vat') && $this->request->post['tax_id'] && (vat_validation($country_info['iso_code_2'], $this->request->post['tax_id']) == 'invalid')) {
				$this->error['tax_id'] = $this->language->get('error_vat');
			}		
 ?>