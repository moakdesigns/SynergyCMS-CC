<?php 
// $this->data['text_model'] = $this->language->get('text_model');
			// $this->data['text_reward'] = $this->language->get('text_reward');
			// $this->data['text_points'] = $this->language->get('text_points');
			// $this->data['text_discount'] = $this->language->get('text_discount');
			// $this->data['text_stock'] = $this->language->get('text_stock');
			// $this->data['text_price'] = $this->language->get('text_price');
			// $this->data['text_tax'] = $this->language->get('text_tax');
			// $this->data['text_discount'] = $this->language->get('text_discount');
			// $this->data['text_option'] = $this->language->get('text_option');
			// $this->data['text_qty'] = $this->language->get('text_qty');
			// $this->data['text_minimum'] = sprintf($this->language->get('text_minimum'), $post_info['minimum']);


// $this->data['button_cart'] = $this->language->get('button_cart');
			// $this->data['button_wishlist'] = $this->language->get('button_wishlist');
			// $this->data['button_compare'] = $this->language->get('button_compare');

$this->data['tab_related'] = $this->language->get('tab_related');

// $this->data['model'] = $post_info['model'];
			// $this->data['reward'] = $post_info['reward'];
			// $this->data['points'] = $post_info['points'];

			// if ($post_info['quantity'] <= 0) {
			// 	$this->data['stock'] = $post_info['stock_status'];
			// } elseif ($this->config->get('config_stock_display')) {
			// 	$this->data['stock'] = $post_info['quantity'];
			// } else {
			// 	$this->data['stock'] = $this->language->get('text_instock');
			// }
// if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
			// 	$this->data['price'] = $this->currency->format($this->tax->calculate($post_info['price'], $post_info['tax_class_id'], $this->config->get('config_tax')));
			// } else {
			// 	$this->data['price'] = false;
			// }

			// if ((float)$post_info['special']) {
			// 	$this->data['special'] = $this->currency->format($this->tax->calculate($post_info['special'], $post_info['tax_class_id'], $this->config->get('config_tax')));
			// } else {
			// 	$this->data['special'] = false;
			// }

			// if ($this->config->get('config_tax')) {
			// 	$this->data['tax'] = $this->currency->format((float)$post_info['special'] ? $post_info['special'] : $post_info['price']);
			// } else {
			// 	$this->data['tax'] = false;
			// }

			// $discounts = $this->model_catalog_post->getPostDiscounts($this->request->get['post_id']);

			// $this->data['discounts'] = array();

			// foreach ($discounts as $discount) {
			// 	$this->data['discounts'][] = array(
			// 		'quantity' => $discount['quantity'],
			// 		'price'    => $this->currency->format($this->tax->calculate($discount['price'], $post_info['tax_class_id'], $this->config->get('config_tax')))
			// 	);
			// }

			// $this->data['options'] = array();

			// foreach ($this->model_catalog_post->getPostOptions($this->request->get['post_id']) as $option) {
			// 	if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox' || $option['type'] == 'image') {
			// 		$option_value_data = array();

			// 		foreach ($option['option_value'] as $option_value) {
			// 			if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
			// 				if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
			// 					$price = $this->currency->format($this->tax->calculate($option_value['price'], $post_info['tax_class_id'], $this->config->get('config_tax')));
			// 				} else {
			// 					$price = false;
			// 				}

			// 				$option_value_data[] = array(
			// 					'post_option_value_id' => $option_value['post_option_value_id'],
			// 					'option_value_id'         => $option_value['option_value_id'],
			// 					'name'                    => $option_value['name'],
			// 					'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
			// 					'price'                   => $price,
			// 					'price_prefix'            => $option_value['price_prefix']
			// 				);
			// 			}
			// 		}

			// 		$this->data['options'][] = array(
			// 			'post_option_id' => $option['post_option_id'],
			// 			'option_id'         => $option['option_id'],
			// 			'name'              => $option['name'],
			// 			'type'              => $option['type'],
			// 			'option_value'      => $option_value_data,
			// 			'required'          => $option['required']
			// 		);
			// 	} elseif ($option['type'] == 'text' || $option['type'] == 'textarea' || $option['type'] == 'file' || $option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
			// 		$this->data['options'][] = array(
			// 			'post_option_id' => $option['post_option_id'],
			// 			'option_id'         => $option['option_id'],
			// 			'name'              => $option['name'],
			// 			'type'              => $option['type'],
			// 			'option_value'      => $option['option_value'],
			// 			'required'          => $option['required']
			// 		);
			// 	}
			// }

			// if ($post_info['minimum']) {
			// 	$this->data['minimum'] = $post_info['minimum'];
			// } else {
			// 	$this->data['minimum'] = 1;
			// }
// if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				// 	$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
				// } else {
				// 	$price = false;
				// }

				// if ((float)$result['special']) {
				// 	$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
				// } else {
				// 	$special = false;
				// }

// $this->data['text_payment_profile'] = $this->language->get('text_payment_profile');
			// $this->data['profiles'] = $this->model_catalog_post->getProfiles($post_info['post_id']);
 

public function getRecurringDescription() {
		$this->language->load('post/post');
		$this->load->model('catalog/post');

		if (isset($this->request->post['post_id'])) {
			$post_id = $this->request->post['post_id'];
		} else {
			$post_id = 0;
		}

		// if (isset($this->request->post['profile_id'])) {
		// 	$profile_id = $this->request->post['profile_id'];
		// } else {
		// 	$profile_id = 0;
		// }

		// if (isset($this->request->post['quantity'])) {
		// 	$quantity = $this->request->post['quantity'];
		// } else {
		// 	$quantity = 1;
		// }

		$post_info = $this->model_catalog_post->getPost($post_id);
		// $profile_info = $this->model_catalog_post->getProfile($post_id, $profile_id);

		$json = array();

		// if ($post_info) {

		// 	if (!$json) {
		// 		$frequencies = array(
		// 			'day' => $this->language->get('text_day'),
		// 			'week' => $this->language->get('text_week'),
		// 			'semi_month' => $this->language->get('text_semi_month'),
		// 			'month' => $this->language->get('text_month'),
		// 			'year' => $this->language->get('text_year'),
		// 		);

		// 		if ($profile_info['trial_status'] == 1) {
		// 			$price = $this->currency->format($this->tax->calculate($profile_info['trial_price'] * $quantity, $post_info['tax_class_id'], $this->config->get('config_tax')));
		// 			$trial_text = sprintf($this->language->get('text_trial_description'), $price, $profile_info['trial_cycle'], $frequencies[$profile_info['trial_frequency']], $profile_info['trial_duration']) . ' ';
		// 		} else {
		// 			$trial_text = '';
		// 		}

		// 		$price = $this->currency->format($this->tax->calculate($profile_info['price'] * $quantity, $post_info['tax_class_id'], $this->config->get('config_tax')));

		// 		if ($profile_info['duration']) {
		// 			$text = $trial_text . sprintf($this->language->get('text_payment_description'), $price, $profile_info['cycle'], $frequencies[$profile_info['frequency']], $profile_info['duration']);
		// 		} else {
		// 			$text = $trial_text . sprintf($this->language->get('text_payment_until_canceled_description'), $price, $profile_info['cycle'], $frequencies[$profile_info['frequency']], $profile_info['duration']);
		// 		}

		// 		$json['success'] = $text;
		// 	}
		// }

		$this->response->setOutput(json_encode($json));
	}


 ?>