<?php 
class ControllerCatalogPost extends Controller {
	private $error = array(); 

	public function index() {
		$this->language->load('catalog/post');

		$this->document->setTitle($this->language->get('heading_title')); 

		$this->load->model('catalog/post');

		$this->getList();
	}

	public function insert() {
		$this->language->load('catalog/post');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/post');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_post->addPost($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			// if (isset($this->request->get['filter_model'])) {
			// 	$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			// }

			// if (isset($this->request->get['filter_price'])) {
			// 	$url .= '&filter_price=' . $this->request->get['filter_price'];
			// }

			// if (isset($this->request->get['filter_quantity'])) {
			// 	$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			// }

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('catalog/post', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('catalog/post');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/post');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_post->editPost($this->request->get['post_id'], $this->request->post);

			// $this->openbay->postUpdateListen($this->request->get['post_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			// if (isset($this->request->get['filter_model'])) {
			// 	$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			// }

			// if (isset($this->request->get['filter_price'])) {
			// 	$url .= '&filter_price=' . $this->request->get['filter_price'];
			// }

			// if (isset($this->request->get['filter_quantity'])) {
			// 	$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			// }	

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('catalog/post', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->language->load('catalog/post');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/post');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $post_id) {
				$this->model_catalog_post->deletePost($post_id);
				// $this->openbay->deletePost($post_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			// if (isset($this->request->get['filter_model'])) {
			// 	$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			// }

			// if (isset($this->request->get['filter_price'])) {
			// 	$url .= '&filter_price=' . $this->request->get['filter_price'];
			// }

			// if (isset($this->request->get['filter_quantity'])) {
			// 	$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			// }	

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('catalog/post', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	public function copy() {
		$this->language->load('catalog/post');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/post');

		if (isset($this->request->post['selected']) && $this->validateCopy()) {
			foreach ($this->request->post['selected'] as $post_id) {
				$this->model_catalog_post->copyPost($post_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			// if (isset($this->request->get['filter_model'])) {
			// 	$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
			// }

			// if (isset($this->request->get['filter_price'])) {
			// 	$url .= '&filter_price=' . $this->request->get['filter_price'];
			// }

			// if (isset($this->request->get['filter_quantity'])) {
			// 	$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
			// }	

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('catalog/post', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		// if (isset($this->request->get['filter_model'])) {
		// 	$filter_model = $this->request->get['filter_model'];
		// } else {
		// 	$filter_model = null;
		// }

		// if (isset($this->request->get['filter_price'])) {
		// 	$filter_price = $this->request->get['filter_price'];
		// } else {
		// 	$filter_price = null;
		// }

		// if (isset($this->request->get['filter_quantity'])) {
		// 	$filter_quantity = $this->request->get['filter_quantity'];
		// } else {
		// 	$filter_quantity = null;
		// }

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pd.title';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		// if (isset($this->request->get['filter_model'])) {
		// 	$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		// }

		// if (isset($this->request->get['filter_price'])) {
		// 	$url .= '&filter_price=' . $this->request->get['filter_price'];
		// }

		// if (isset($this->request->get['filter_quantity'])) {
		// 	$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		// }		

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/post', 'token=' . $this->session->data['token'] . $url, 'SSL'),       		
			'separator' => ' / '
		);

		$this->data['insert'] = $this->url->link('catalog/post/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['copy'] = $this->url->link('catalog/post/copy', 'token=' . $this->session->data['token'] . $url, 'SSL');	
		$this->data['delete'] = $this->url->link('catalog/post/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$this->data['posts'] = array();

		$data = array(
			'filter_name'	  => $filter_name, 
			'filter_status'   => $filter_status,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);

		$this->load->model('tool/image');

		$post_total = $this->model_catalog_post->getTotalPosts($data);

		$results = $this->model_catalog_post->getPosts($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('catalog/post/update', 'token=' . $this->session->data['token'] . '&post_id=' . $result['post_id'] . $url, 'SSL')
			);

			if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 40, 40);
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', 40, 40);
			}

			// $special = false;

			// $post_specials = $this->model_catalog_post->getPostSpecials($result['post_id']);

			// foreach ($post_specials  as $post_special) {
			// 	if (($post_special['date_start'] == '0000-00-00' || $post_special['date_start'] < date('Y-m-d')) && ($post_special['date_end'] == '0000-00-00' || $post_special['date_end'] > date('Y-m-d'))) {
			// 		$special = $post_special['price'];

			// 		break;
			// 	}					
			// }

			$this->data['posts'][] = array(
				'post_id' => $result['post_id'],
				'name'       => $result['title'],
				'image'      => $image,
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'selected'   => isset($this->request->post['selected']) && in_array($result['post_id'], $this->request->post['selected']),
				'action'     => $action
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');		

		$this->data['text_enabled'] = $this->language->get('text_enabled');		
		$this->data['text_disabled'] = $this->language->get('text_disabled');		
		$this->data['text_no_results'] = $this->language->get('text_no_results');		
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');		

		$this->data['column_image'] = $this->language->get('column_image');		
		$this->data['column_title'] = $this->language->get('column_title');		
		$this->data['column_id'] = $this->language->get('column_id');		
		// $this->data['column_price'] = $this->language->get('column_price');		
		// $this->data['column_quantity'] = $this->language->get('column_quantity');		
		$this->data['column_status'] = $this->language->get('column_status');		
		$this->data['column_action'] = $this->language->get('column_action');		

		$this->data['button_copy'] = $this->language->get('button_copy');		
		$this->data['button_insert'] = $this->language->get('button_insert');		
		$this->data['button_delete'] = $this->language->get('button_delete');		
		$this->data['button_filter'] = $this->language->get('button_filter');

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		// if (isset($this->request->get['filter_model'])) {
		//	$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		//}

		// if (isset($this->request->get['filter_price'])) {
		// 	$url .= '&filter_price=' . $this->request->get['filter_price'];
		// }

		// if (isset($this->request->get['filter_quantity'])) {
		// 	$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		// }

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['sort_title'] = $this->url->link('catalog/post', 'token=' . $this->session->data['token'] . '&sort=pd.title' . $url, 'SSL');
		$this->data['sort_post_id'] = $this->url->link('catalog/post', 'token=' . $this->session->data['token'] . '&sort=p.post_id' . $url, 'SSL');
		// $this->data['sort_price'] = $this->url->link('catalog/post', 'token=' . $this->session->data['token'] . '&sort=p.price' . $url, 'SSL');
		// $this->data['sort_quantity'] = $this->url->link('catalog/post', 'token=' . $this->session->data['token'] . '&sort=p.quantity' . $url, 'SSL');
		$this->data['sort_status'] = $this->url->link('catalog/post', 'token=' . $this->session->data['token'] . '&sort=p.status' . $url, 'SSL');
		$this->data['sort_order'] = $this->url->link('catalog/post', 'token=' . $this->session->data['token'] . '&sort=p.sort_order' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		// if (isset($this->request->get['filter_model'])) {
		// 	$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		// }

		// if (isset($this->request->get['filter_price'])) {
		// 	$url .= '&filter_price=' . $this->request->get['filter_price'];
		// }

		// if (isset($this->request->get['filter_quantity'])) {
		// 	$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		// }

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $post_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('catalog/post', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_name'] = $filter_name;
		// $this->data['filter_model'] = $filter_model;
		// $this->data['filter_price'] = $filter_price;
		// $this->data['filter_quantity'] = $filter_quantity;
		$this->data['filter_status'] = $filter_status;

		$this->data['sort'] = $sort;
		$this->data['order'] = $order;

		$this->template = 'catalog/post_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_plus'] = $this->language->get('text_plus');
		$this->data['text_minus'] = $this->language->get('text_minus');
		$this->data['text_default'] = $this->language->get('text_default');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['text_browse'] = $this->language->get('text_browse');
		$this->data['text_clear'] = $this->language->get('text_clear');
		$this->data['text_option'] = $this->language->get('text_option');
		$this->data['text_option_value'] = $this->language->get('text_option_value');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_percent'] = $this->language->get('text_percent');
		$this->data['text_amount'] = $this->language->get('text_amount');

		$this->data['entry_title'] = $this->language->get('entry_title');
		$this->data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$this->data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_site'] = $this->language->get('entry_site');
		$this->data['entry_keyword'] = $this->language->get('entry_keyword');
		// $this->data['entry_model'] = $this->language->get('entry_model');
		// $this->data['entry_sku'] = $this->language->get('entry_sku');
		// $this->data['entry_upc'] = $this->language->get('entry_upc');
		// $this->data['entry_ean'] = $this->language->get('entry_ean');
		// $this->data['entry_jan'] = $this->language->get('entry_jan');
		// $this->data['entry_isbn'] = $this->language->get('entry_isbn');
		// $this->data['entry_mpn'] = $this->language->get('entry_mpn');
		$this->data['entry_location'] = $this->language->get('entry_location');
		// $this->data['entry_minimum'] = $this->language->get('entry_minimum');
		$this->data['entry_manufacturer'] = $this->language->get('entry_manufacturer');
		// $this->data['entry_shipping'] = $this->language->get('entry_shipping');
		$this->data['entry_date_available'] = $this->language->get('entry_date_available');
		// $this->data['entry_quantity'] = $this->language->get('entry_quantity');
		$this->data['entry_stock_status'] = $this->language->get('entry_stock_status');
		// $this->data['entry_price'] = $this->language->get('entry_price');
		// $this->data['entry_tax_class'] = $this->language->get('entry_tax_class');
		// $this->data['entry_points'] = $this->language->get('entry_points');
		// $this->data['entry_option_points'] = $this->language->get('entry_option_points');
		// $this->data['entry_subtract'] = $this->language->get('entry_subtract');
		// $this->data['entry_weight_class'] = $this->language->get('entry_weight_class');
		// $this->data['entry_weight'] = $this->language->get('entry_weight');
		// $this->data['entry_dimension'] = $this->language->get('entry_dimension');
		// $this->data['entry_length'] = $this->language->get('entry_length');
		$this->data['entry_image'] = $this->language->get('entry_image');
		$this->data['entry_download'] = $this->language->get('entry_download');
		$this->data['entry_category'] = $this->language->get('entry_category');
		$this->data['entry_filter'] = $this->language->get('entry_filter');
		// $this->data['entry_related'] = $this->language->get('entry_related');
		$this->data['entry_attribute'] = $this->language->get('entry_attribute');
		$this->data['entry_text'] = $this->language->get('entry_text');
		// $this->data['entry_option'] = $this->language->get('entry_option');
		// $this->data['entry_option_value'] = $this->language->get('entry_option_value');
		$this->data['entry_required'] = $this->language->get('entry_required');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_date_start'] = $this->language->get('entry_date_start');
		$this->data['entry_date_end'] = $this->language->get('entry_date_end');
		$this->data['entry_priority'] = $this->language->get('entry_priority');
		$this->data['entry_tag'] = $this->language->get('entry_tag');
		$this->data['entry_member_group'] = $this->language->get('entry_member_group');
		// $this->data['entry_reward'] = $this->language->get('entry_reward');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		// $this->data['entry_profile'] = $this->language->get('entry_profile');

		// $this->data['text_recurring_help'] = $this->language->get('text_recurring_help');
		// $this->data['text_recurring_title'] = $this->language->get('text_recurring_title');
		// $this->data['text_recurring_trial'] = $this->language->get('text_recurring_trial');
		// $this->data['entry_recurring'] = $this->language->get('entry_recurring');
		// $this->data['entry_recurring_price'] = $this->language->get('entry_recurring_price');
		// $this->data['entry_recurring_freq'] = $this->language->get('entry_recurring_freq');
		// $this->data['entry_recurring_cycle'] = $this->language->get('entry_recurring_cycle');
		// $this->data['entry_recurring_length'] = $this->language->get('entry_recurring_length');
		// $this->data['entry_trial'] = $this->language->get('entry_trial');
		// $this->data['entry_trial_price'] = $this->language->get('entry_trial_price');
		// $this->data['entry_trial_freq'] = $this->language->get('entry_trial_freq');
		// $this->data['entry_trial_length'] = $this->language->get('entry_trial_length');
		// $this->data['entry_trial_cycle'] = $this->language->get('entry_trial_cycle');

		$this->data['text_length_day'] = $this->language->get('text_length_day');
		$this->data['text_length_week'] = $this->language->get('text_length_week');
		$this->data['text_length_month'] = $this->language->get('text_length_month');
		$this->data['text_length_month_semi'] = $this->language->get('text_length_month_semi');
		$this->data['text_length_year'] = $this->language->get('text_length_year');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_attribute'] = $this->language->get('button_add_attribute');
		// $this->data['button_add_option'] = $this->language->get('button_add_option');
		// $this->data['button_add_option_value'] = $this->language->get('button_add_option_value');
		// $this->data['button_add_discount'] = $this->language->get('button_add_discount');
		// $this->data['button_add_special'] = $this->language->get('button_add_special');
		$this->data['button_add_image'] = $this->language->get('button_add_image');
		$this->data['button_remove'] = $this->language->get('button_remove');
		// $this->data['button_add_profile'] = $this->language->get('button_add_profile');

		$this->data['tab_general'] = $this->language->get('tab_general');
		$this->data['tab_data'] = $this->language->get('tab_data');
		$this->data['tab_attribute'] = $this->language->get('tab_attribute');
		// $this->data['tab_option'] = $this->language->get('tab_option');		
		// $this->data['tab_profile'] = $this->language->get('tab_profile');
		// $this->data['tab_discount'] = $this->language->get('tab_discount');
		// $this->data['tab_special'] = $this->language->get('tab_special');
		$this->data['tab_image'] = $this->language->get('tab_image');
		$this->data['tab_links'] = $this->language->get('tab_links');
		// $this->data['tab_reward'] = $this->language->get('tab_reward');
		$this->data['tab_design'] = $this->language->get('tab_design');
		// $this->data['tab_marketplace_links'] = $this->language->get('tab_marketplace_links');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['title'])) {
			$this->data['error_title'] = $this->error['title'];
		} else {
			$this->data['error_title'] = array();
		}

		if (isset($this->error['meta_description'])) {
			$this->data['error_meta_description'] = $this->error['meta_description'];
		} else {
			$this->data['error_meta_description'] = array();
		}		

		if (isset($this->error['description'])) {
			$this->data['error_description'] = $this->error['description'];
		} else {
			$this->data['error_description'] = array();
		}	

		// if (isset($this->error['model'])) {
		// 	$this->data['error_model'] = $this->error['model'];
		// } else {
		// 	$this->data['error_model'] = '';
		// }		

		if (isset($this->error['date_available'])) {
			$this->data['error_date_available'] = $this->error['date_available'];
		} else {
			$this->data['error_date_available'] = '';
		}	

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		// if (isset($this->request->get['filter_model'])) {
		// 	$url .= '&filter_model=' . urlencode(html_entity_decode($this->request->get['filter_model'], ENT_QUOTES, 'UTF-8'));
		// }

		// if (isset($this->request->get['filter_price'])) {
		// 	$url .= '&filter_price=' . $this->request->get['filter_price'];
		// }

		// if (isset($this->request->get['filter_quantity'])) {
		// 	$url .= '&filter_quantity=' . $this->request->get['filter_quantity'];
		// }	

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('catalog/post', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' / '
		);

		if (!isset($this->request->get['post_id'])) {
			$this->data['action'] = $this->url->link('catalog/post/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('catalog/post/update', 'token=' . $this->session->data['token'] . '&post_id=' . $this->request->get['post_id'] . $url, 'SSL');
		}

		$this->data['cancel'] = $this->url->link('catalog/post', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['post_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$post_info = $this->model_catalog_post->getPost($this->request->get['post_id']);
		}

		$this->data['token'] = $this->session->data['token'];

		$this->load->model('localisation/language');

		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['post_description'])) {
			$this->data['post_description'] = $this->request->post['post_description'];
		} elseif (isset($this->request->get['post_id'])) {
			$this->data['post_description'] = $this->model_catalog_post->getPostDescriptions($this->request->get['post_id']);
		} else {
			$this->data['post_description'] = array();
		}

		// if (isset($this->request->post['model'])) {
		// 	$this->data['model'] = $this->request->post['model'];
		// } elseif (!empty($post_info)) {
		// 	$this->data['model'] = $post_info['model'];
		// } else {
		// 	$this->data['model'] = '';
		// }

		// if (isset($this->request->post['sku'])) {
		// 	$this->data['sku'] = $this->request->post['sku'];
		// } elseif (!empty($post_info)) {
		// 	$this->data['sku'] = $post_info['sku'];
		// } else {
		// 	$this->data['sku'] = '';
		// }

		// if (isset($this->request->post['upc'])) {
		// 	$this->data['upc'] = $this->request->post['upc'];
		// } elseif (!empty($post_info)) {
		// 	$this->data['upc'] = $post_info['upc'];
		// } else {
		// 	$this->data['upc'] = '';
		// }

		// if (isset($this->request->post['ean'])) {
		// 	$this->data['ean'] = $this->request->post['ean'];
		// } elseif (!empty($post_info)) {
		// 	$this->data['ean'] = $post_info['ean'];
		// } else {
		// 	$this->data['ean'] = '';
		// }

		// if (isset($this->request->post['jan'])) {
		// 	$this->data['jan'] = $this->request->post['jan'];
		// } elseif (!empty($post_info)) {
		// 	$this->data['jan'] = $post_info['jan'];
		// } else {
		// 	$this->data['jan'] = '';
		// }

		// if (isset($this->request->post['isbn'])) {
		// 	$this->data['isbn'] = $this->request->post['isbn'];
		// } elseif (!empty($post_info)) {
		// 	$this->data['isbn'] = $post_info['isbn'];
		// } else {
		// 	$this->data['isbn'] = '';
		// }

		// if (isset($this->request->post['mpn'])) {
		// 	$this->data['mpn'] = $this->request->post['mpn'];
		// } elseif (!empty($post_info)) {
		// 	$this->data['mpn'] = $post_info['mpn'];
		// } else {
		// 	$this->data['mpn'] = '';
		// }

		if (isset($this->request->post['location'])) {
			$this->data['location'] = $this->request->post['location'];
		} elseif (!empty($post_info)) {
			$this->data['location'] = $post_info['location'];
		} else {
			$this->data['location'] = '';
		}

		$this->load->model('setting/site');

		$this->data['sites'] = $this->model_setting_site->getSites();

		if (isset($this->request->post['post_site'])) {
			$this->data['post_site'] = $this->request->post['post_site'];
		} elseif (isset($this->request->get['post_id'])) {
			$this->data['post_site'] = $this->model_catalog_post->getPostSites($this->request->get['post_id']);
		} else {
			$this->data['post_site'] = array(0);
		}	

		if (isset($this->request->post['keyword'])) {
			$this->data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($post_info)) {
			$this->data['keyword'] = $post_info['keyword'];
		} else {
			$this->data['keyword'] = '';
		}

		if (isset($this->request->post['image'])) {
			$this->data['image'] = $this->request->post['image'];
		} elseif (!empty($post_info)) {
			$this->data['image'] = $post_info['image'];
		} else {
			$this->data['image'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && file_exists(DIR_IMAGE . $this->request->post['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($post_info) && $post_info['image'] && file_exists(DIR_IMAGE . $post_info['image'])) {
			$this->data['thumb'] = $this->model_tool_image->resize($post_info['image'], 100, 100);
		} else {
			$this->data['thumb'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		}

		// if (isset($this->request->post['shipping'])) {
		// 	$this->data['shipping'] = $this->request->post['shipping'];
		// } elseif (!empty($post_info)) {
		// 	$this->data['shipping'] = $post_info['shipping'];
		// } else {
		// 	$this->data['shipping'] = 1;
		// }

		// if (isset($this->request->post['price'])) {
		// 	$this->data['price'] = $this->request->post['price'];
		// } elseif (!empty($post_info)) {
		// 	$this->data['price'] = $post_info['price'];
		// } else {
		// 	$this->data['price'] = '';
		// }

		// $this->load->model('catalog/profile');

		// $this->data['profiles'] = $this->model_catalog_profile->getProfiles();

		// if (isset($this->request->post['post_profiles'])) {
		// 	$this->data['post_profiles'] = $this->request->post['post_profiles'];
		// } elseif (!empty($post_info)) {
		// 	$this->data['post_profiles'] = $this->model_catalog_post->getProfiles($post_info['post_id']);
		// } else {
		// 	$this->data['post_profiles'] = array();
		// }

		// $this->load->model('localisation/tax_class');

		// $this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		// if (isset($this->request->post['tax_class_id'])) {
		// 	$this->data['tax_class_id'] = $this->request->post['tax_class_id'];
		// } elseif (!empty($post_info)) {
		// 	$this->data['tax_class_id'] = $post_info['tax_class_id'];
		// } else {
		// 	$this->data['tax_class_id'] = 0;
		// }

		if (isset($this->request->post['date_available'])) {
			$this->data['date_available'] = $this->request->post['date_available'];
		} elseif (!empty($post_info)) {
			$this->data['date_available'] = date('Y-m-d', strtotime($post_info['date_available']));
		} else {
			$this->data['date_available'] = date('Y-m-d', time() - 86400);
		}

		// if (isset($this->request->post['quantity'])) {
		// 	$this->data['quantity'] = $this->request->post['quantity'];
		// } elseif (!empty($post_info)) {
		// 	$this->data['quantity'] = $post_info['quantity'];
		// } else {
		// 	$this->data['quantity'] = 1;
		// }

		// if (isset($this->request->post['minimum'])) {
		// 	$this->data['minimum'] = $this->request->post['minimum'];
		// } elseif (!empty($post_info)) {
		// 	$this->data['minimum'] = $post_info['minimum'];
		// } else {
		// 	$this->data['minimum'] = 1;
		// }

		// if (isset($this->request->post['subtract'])) {
		// 	$this->data['subtract'] = $this->request->post['subtract'];
		// } elseif (!empty($post_info)) {
		// 	$this->data['subtract'] = $post_info['subtract'];
		// } else {
		// 	$this->data['subtract'] = 1;
		// }

		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($post_info)) {
			$this->data['sort_order'] = $post_info['sort_order'];
		} else {
			$this->data['sort_order'] = 1;
		}

		// $this->load->model('localisation/stock_status');

		// $this->data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();

		// if (isset($this->request->post['stock_status_id'])) {
		// 	$this->data['stock_status_id'] = $this->request->post['stock_status_id'];
		// } elseif (!empty($post_info)) {
		// 	$this->data['stock_status_id'] = $post_info['stock_status_id'];
		// } else {
		// 	$this->data['stock_status_id'] = $this->config->get('config_stock_status_id');
		// }

		if (isset($this->request->post['status'])) {
			$this->data['status'] = $this->request->post['status'];
		} elseif (!empty($post_info)) {
			$this->data['status'] = $post_info['status'];
		} else {
			$this->data['status'] = 1;
		}

		// if (isset($this->request->post['weight'])) {
		// 	$this->data['weight'] = $this->request->post['weight'];
		// } elseif (!empty($post_info)) {
		// 	$this->data['weight'] = $post_info['weight'];
		// } else {
		// 	$this->data['weight'] = '';
		// }

		// $this->load->model('localisation/weight_class');

		// $this->data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

		// if (isset($this->request->post['weight_class_id'])) {
		// 	$this->data['weight_class_id'] = $this->request->post['weight_class_id'];
		// } elseif (!empty($post_info)) {
		// 	$this->data['weight_class_id'] = $post_info['weight_class_id'];
		// } else {
		// 	$this->data['weight_class_id'] = $this->config->get('config_weight_class_id');
		// }

		// if (isset($this->request->post['length'])) {
		// 	$this->data['length'] = $this->request->post['length'];
		// } elseif (!empty($post_info)) {
		// 	$this->data['length'] = $post_info['length'];
		// } else {
		// 	$this->data['length'] = '';
		// }

		// if (isset($this->request->post['width'])) {
		// 	$this->data['width'] = $this->request->post['width'];
		// } elseif (!empty($post_info)) {	
		// 	$this->data['width'] = $post_info['width'];
		// } else {
		// 	$this->data['width'] = '';
		// }

		// if (isset($this->request->post['height'])) {
		// 	$this->data['height'] = $this->request->post['height'];
		// } elseif (!empty($post_info)) {
		// 	$this->data['height'] = $post_info['height'];
		// } else {
		// 	$this->data['height'] = '';
		// }

		// $this->load->model('localisation/length_class');

		// $this->data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();

		// if (isset($this->request->post['length_class_id'])) {
		// 	$this->data['length_class_id'] = $this->request->post['length_class_id'];
		// } elseif (!empty($post_info)) {
		// 	$this->data['length_class_id'] = $post_info['length_class_id'];
		// } else {
		// 	$this->data['length_class_id'] = $this->config->get('config_length_class_id');
		// }

		$this->load->model('catalog/manufacturer');

		if (isset($this->request->post['manufacturer_id'])) {
			$this->data['manufacturer_id'] = $this->request->post['manufacturer_id'];
		} elseif (!empty($post_info)) {
			$this->data['manufacturer_id'] = $post_info['manufacturer_id'];
		} else {
			$this->data['manufacturer_id'] = 0;
		}

		if (isset($this->request->post['manufacturer'])) {
			$this->data['manufacturer'] = $this->request->post['manufacturer'];
		} elseif (!empty($post_info)) {
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($post_info['manufacturer_id']);

			if ($manufacturer_info) {		
				$this->data['manufacturer'] = $manufacturer_info['name'];
			} else {
				$this->data['manufacturer'] = '';
			}	
		} else {
			$this->data['manufacturer'] = '';
		}

		// Categories
		$this->load->model('catalog/category');

		if (isset($this->request->post['post_category'])) {
			$categories = $this->request->post['post_category'];
		} elseif (isset($this->request->get['post_id'])) {		
			$categories = $this->model_catalog_post->getPostCategories($this->request->get['post_id']);
		} else {
			$categories = array();
		}

		$this->data['post_categories'] = array();

		foreach ($categories as $category_id) {
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {
				$this->data['post_categories'][] = array(
					'category_id' => $category_info['category_id'],
					'name'        => ($category_info['path'] ? $category_info['path'] . ' &gt; ' : '') . $category_info['name']
				);
			}
		}

		// Filters
		$this->load->model('catalog/filter');

		if (isset($this->request->post['post_filter'])) {
			$filters = $this->request->post['post_filter'];
		} elseif (isset($this->request->get['post_id'])) {
			$filters = $this->model_catalog_post->getPostFilters($this->request->get['post_id']);
		} else {
			$filters = array();
		}

		$this->data['post_filters'] = array();

		foreach ($filters as $filter_id) {
			$filter_info = $this->model_catalog_filter->getFilter($filter_id);

			if ($filter_info) {
				$this->data['post_filters'][] = array(
					'filter_id' => $filter_info['filter_id'],
					'name'      => $filter_info['group'] . ' &gt; ' . $filter_info['name']
				);
			}
		}		



//---------------- Keep Attributes ---------------------

		// Attributes
		$this->load->model('catalog/attribute');

		if (isset($this->request->post['post_attribute'])) {
			$post_attributes = $this->request->post['post_attribute'];
		} elseif (isset($this->request->get['post_id'])) {
			$post_attributes = $this->model_catalog_post->getPostAttributes($this->request->get['post_id']);
		} else {
			$post_attributes = array();
		}

		$this->data['post_attributes'] = array();

		foreach ($post_attributes as $post_attribute) {
			$attribute_info = $this->model_catalog_attribute->getAttribute($post_attribute['attribute_id']);

			if ($attribute_info) {
				$this->data['post_attributes'][] = array(
					'attribute_id'                  => $post_attribute['attribute_id'],
					'name'                          => $attribute_info['name'],
					'post_attribute_description' => $post_attribute['post_attribute_description']
				);
			}
		}	
//--------------------Keep Attribues ----------------------






		// Options
		// $this->load->model('catalog/option');

		// if (isset($this->request->post['post_option'])) {
		// 	$post_options = $this->request->post['post_option'];
		// } elseif (isset($this->request->get['post_id'])) {
		// 	$post_options = $this->model_catalog_post->getPostOptions($this->request->get['post_id']);			
		// } else {
		// 	$post_options = array();
		// }			

		// $this->data['post_options'] = array();

		// foreach ($post_options as $post_option) {
		// 	if ($post_option['type'] == 'select' || $post_option['type'] == 'radio' || $post_option['type'] == 'checkbox' || $post_option['type'] == 'image') {
		// 		$post_option_value_data = array();

		// 		foreach ($post_option['post_option_value'] as $post_option_value) {
		// 			$post_option_value_data[] = array(
		// 				'post_option_value_id' => $post_option_value['post_option_value_id'],
		// 				'option_value_id'         => $post_option_value['option_value_id'],
		// 				'quantity'                => $post_option_value['quantity'],
		// 				'subtract'                => $post_option_value['subtract'],
		// 				'price'                   => $post_option_value['price'],
		// 				'price_prefix'            => $post_option_value['price_prefix'],
		// 				'points'                  => $post_option_value['points'],
		// 				'points_prefix'           => $post_option_value['points_prefix'],						
		// 				'weight'                  => $post_option_value['weight'],
		// 				'weight_prefix'           => $post_option_value['weight_prefix']	
		// 			);
		// 		}

		// 		$this->data['post_options'][] = array(
		// 			'post_option_id'    => $post_option['post_option_id'],
		// 			'post_option_value' => $post_option_value_data,
		// 			'option_id'            => $post_option['option_id'],
		// 			'name'                 => $post_option['name'],
		// 			'type'                 => $post_option['type'],
		// 			'required'             => $post_option['required']
		// 		);				
		// 	} else {
		// 		$this->data['post_options'][] = array(
		// 			'post_option_id' => $post_option['post_option_id'],
		// 			'option_id'         => $post_option['option_id'],
		// 			'name'              => $post_option['name'],
		// 			'type'              => $post_option['type'],
		// 			'option_value'      => $post_option['option_value'],
		// 			'required'          => $post_option['required']
		// 		);				
		// 	}
		// }

		// $this->data['option_values'] = array();

		// foreach ($this->data['post_options'] as $post_option) {
		// 	if ($post_option['type'] == 'select' || $post_option['type'] == 'radio' || $post_option['type'] == 'checkbox' || $post_option['type'] == 'image') {
		// 		if (!isset($this->data['option_values'][$post_option['option_id']])) {
		// 			$this->data['option_values'][$post_option['option_id']] = $this->model_catalog_option->getOptionValues($post_option['option_id']);
		// 		}
		// 	}
		// }

		$this->load->model('members/member_group');

		$this->data['member_groups'] = $this->model_members_member_group->getMemberGroups();

		// if (isset($this->request->post['post_discount'])) {
		// 	$this->data['post_discounts'] = $this->request->post['post_discount'];
		// } elseif (isset($this->request->get['post_id'])) {
		// 	$this->data['post_discounts'] = $this->model_catalog_post->getPostDiscounts($this->request->get['post_id']);
		// } else {
		// 	$this->data['post_discounts'] = array();
		// }

		// if (isset($this->request->post['post_special'])) {
		// 	$this->data['post_specials'] = $this->request->post['post_special'];
		// } elseif (isset($this->request->get['post_id'])) {
		// 	$this->data['post_specials'] = $this->model_catalog_post->getPostSpecials($this->request->get['post_id']);
		// } else {
		// 	$this->data['post_specials'] = array();
		// }

		// Images
		if (isset($this->request->post['post_image'])) {
			$post_images = $this->request->post['post_image'];
		} elseif (isset($this->request->get['post_id'])) {
			$post_images = $this->model_catalog_post->getPostImages($this->request->get['post_id']);
		} else {
			$post_images = array();
		}

		$this->data['post_images'] = array();

		foreach ($post_images as $post_image) {
			if ($post_image['image'] && file_exists(DIR_IMAGE . $post_image['image'])) {
				$image = $post_image['image'];
			} else {
				$image = 'no_image.jpg';
			}

			$this->data['post_images'][] = array(
				'image'      => $image,
				'thumb'      => $this->model_tool_image->resize($image, 100, 100),
				'sort_order' => $post_image['sort_order']
			);
		}

		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

		// Downloads
		$this->load->model('catalog/download');

		if (isset($this->request->post['post_download'])) {
			$post_downloads = $this->request->post['post_download'];
		} elseif (isset($this->request->get['post_id'])) {
			$post_downloads = $this->model_catalog_post->getPostDownloads($this->request->get['post_id']);
		} else {
			$post_downloads = array();
		}

		$this->data['post_downloads'] = array();

		foreach ($post_downloads as $download_id) {
			$download_info = $this->model_catalog_download->getDownload($download_id);

			if ($download_info) {
				$this->data['post_downloads'][] = array(
					'download_id' => $download_info['download_id'],
					'name'        => $download_info['name']
				);
			}
		}

		// if (isset($this->request->post['post_related'])) {
		// 	$posts = $this->request->post['post_related'];
		// } elseif (isset($this->request->get['post_id'])) {		
		// 	$posts = $this->model_catalog_post->getPostRelated($this->request->get['post_id']);
		// } else {
		// 	$posts = array();
		// }

		// $this->data['post_related'] = array();

		// foreach ($posts as $post_id) {
		// 	$related_info = $this->model_catalog_post->getPost($post_id);

		// 	if ($related_info) {
		// 		$this->data['post_related'][] = array(
		// 			'post_id' => $related_info['post_id'],
		// 			'name'       => $related_info['name']
		// 		);
		// 	}
		// }

		// if (isset($this->request->post['points'])) {
		// 	$this->data['points'] = $this->request->post['points'];
		// } elseif (!empty($post_info)) {
		// 	$this->data['points'] = $post_info['points'];
		// } else {
		// 	$this->data['points'] = '';
		// }

		// if (isset($this->request->post['post_reward'])) {
		// 	$this->data['post_reward'] = $this->request->post['post_reward'];
		// } elseif (isset($this->request->get['post_id'])) {
		// 	$this->data['post_reward'] = $this->model_catalog_post->getPostRewards($this->request->get['post_id']);
		// } else {
		// 	$this->data['post_reward'] = array();
		// }

		if (isset($this->request->post['post_layout'])) {
			$this->data['post_layout'] = $this->request->post['post_layout'];
		} elseif (isset($this->request->get['post_id'])) {
			$this->data['post_layout'] = $this->model_catalog_post->getPostLayouts($this->request->get['post_id']);
		} else {
			$this->data['post_layout'] = array();
		}

		$this->load->model('design/layout');

		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->template = 'catalog/post_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/post')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['post_description'] as $language_id => $value) {
			if ((utf8_strlen($value['title']) < 1) || (utf8_strlen($value['title']) > 255)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}
		}

		// if ((utf8_strlen($this->request->post['model']) < 1) || (utf8_strlen($this->request->post['model']) > 64)) {
		// 	$this->error['model'] = $this->language->get('error_model');
		// }

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/post')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateCopy() {
		if (!$this->user->hasPermission('modify', 'catalog/post')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_category_id'])) {
			$this->load->model('catalog/post');
			//$this->load->model('catalog/option');

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			// if (isset($this->request->get['filter_model'])) {
			// 	$filter_model = $this->request->get['filter_model'];
			// } else {
			// 	$filter_model = '';
			// }

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];	
			} else {
				$limit = 20;	
			}			

			$data = array(
				'filter_name'  => $filter_name,
				'start'        => 0,
				'limit'        => $limit
			);

			$results = $this->model_catalog_post->getPosts($data);

			foreach ($results as $result) {
				// $option_data = array();

				// $post_options = $this->model_catalog_post->getPostOptions($result['post_id']);	

				// foreach ($post_options as $post_option) {
				// 	$option_info = $this->model_catalog_option->getOption($post_option['option_id']);

				// 	if ($option_info) {				
				// 		if ($option_info['type'] == 'select' || $option_info['type'] == 'radio' || $option_info['type'] == 'checkbox' || $option_info['type'] == 'image') {
				// 			$option_value_data = array();

				// 			foreach ($post_option['post_option_value'] as $post_option_value) {
				// 				$option_value_info = $this->model_catalog_option->getOptionValue($post_option_value['option_value_id']);

				// 				if ($option_value_info) {
				// 					$option_value_data[] = array(
				// 						'post_option_value_id' => $post_option_value['post_option_value_id'],
				// 						'option_value_id'         => $post_option_value['option_value_id'],
				// 						'name'                    => $option_value_info['name'],
				// 						'price'                   => (float)$post_option_value['price'] ? $this->currency->format($post_option_value['price'], $this->config->get('config_currency')) : false,
				// 						'price_prefix'            => $post_option_value['price_prefix']
				// 					);
				// 				}
				// 			}

				// 			$option_data[] = array(
				// 				'post_option_id' => $post_option['post_option_id'],
				// 				'option_id'         => $post_option['option_id'],
				// 				'name'              => $option_info['name'],
				// 				'type'              => $option_info['type'],
				// 				'option_value'      => $option_value_data,
				// 				'required'          => $post_option['required']
				// 			);	
				// 		} else {
				// 			$option_data[] = array(
				// 				'post_option_id' => $post_option['post_option_id'],
				// 				'option_id'         => $post_option['option_id'],
				// 				'name'              => $option_info['name'],
				// 				'type'              => $option_info['type'],
				// 				'option_value'      => $post_option['option_value'],
				// 				'required'          => $post_option['required']
				// 			);				
				// 		}
				// 	}
				// }

				$json[] = array(
					'post_id' => $result['post_id'],
					'name'       => strip_tags(html_entity_decode($result['title'], ENT_QUOTES, 'UTF-8'))
				);	
			}
		}

		$this->response->setOutput(json_encode($json));
	}
}
?>