<?php
class ControllerMembersMemberGroup extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('members/member_group');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('members/member_group');

		$this->getList();
	}

	public function insert() {
		$this->language->load('members/member_group');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('members/member_group');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_members_member_group->addMemberGroup($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('members/member_group', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function update() {
		$this->language->load('members/member_group');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('members/member_group');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_members_member_group->editMemberGroup($this->request->get['member_group_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('members/member_group', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() { 
		$this->language->load('members/member_group');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('members/member_group');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $member_group_id) {
				$this->model_members_member_group->deleteMemberGroup($member_group_id);	
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->redirect($this->url->link('members/member_group', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'cgd.name';
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
			'href'      => $this->url->link('members/member_group', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' / '
		);

		$this->data['insert'] = $this->url->link('members/member_group/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$this->data['delete'] = $this->url->link('members/member_group/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');	

		$this->data['member_groups'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);

		$member_group_total = $this->model_members_member_group->getTotalMemberGroups();

		$results = $this->model_members_member_group->getMemberGroups($data);

		foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => $this->language->get('text_edit'),
				'href' => $this->url->link('members/member_group/update', 'token=' . $this->session->data['token'] . '&member_group_id=' . $result['member_group_id'] . $url, 'SSL')
			);		

			$this->data['member_groups'][] = array(
				'member_group_id' => $result['member_group_id'],
				'name'              => $result['name'] . (($result['member_group_id'] == $this->config->get('config_member_group_id')) ? $this->language->get('text_default') : null),
				'sort_order'        => $result['sort_order'],
				'selected'          => isset($this->request->post['selected']) && in_array($result['member_group_id'], $this->request->post['selected']),
				'action'            => $action
			);
		}	

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_sort_order'] = $this->language->get('column_sort_order');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');

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

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['sort_name'] = $this->url->link('members/member_group', 'token=' . $this->session->data['token'] . '&sort=cgd.name' . $url, 'SSL');
		$this->data['sort_sort_order'] = $this->url->link('members/member_group', 'token=' . $this->session->data['token'] . '&sort=cg.sort_order' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $member_group_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('members/member_group', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();				

		$this->data['sort'] = $sort; 
		$this->data['order'] = $order;

		$this->template = 'members/member_group_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function getForm() {
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');

		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['entry_description'] = $this->language->get('entry_description');
		$this->data['entry_approval'] = $this->language->get('entry_approval');
		// $this->data['entry_company_id_display'] = $this->language->get('entry_company_id_display');
		// $this->data['entry_company_id_required'] = $this->language->get('entry_company_id_required');
		// $this->data['entry_tax_id_display'] = $this->language->get('entry_tax_id_display');
		// $this->data['entry_tax_id_required'] = $this->language->get('entry_tax_id_required');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = array();
		}

		$url = '';

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
			'href'      => $this->url->link('members/member_group', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' / '
		);

		if (!isset($this->request->get['member_group_id'])) {
			$this->data['action'] = $this->url->link('members/member_group/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$this->data['action'] = $this->url->link('members/member_group/update', 'token=' . $this->session->data['token'] . '&member_group_id=' . $this->request->get['member_group_id'] . $url, 'SSL');
		}

		$this->data['cancel'] = $this->url->link('members/member_group', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['member_group_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$member_group_info = $this->model_members_member_group->getMemberGroup($this->request->get['member_group_id']);
		}

		$this->load->model('localisation/language');

		$this->data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['member_group_description'])) {
			$this->data['member_group_description'] = $this->request->post['member_group_description'];
		} elseif (isset($this->request->get['member_group_id'])) {
			$this->data['member_group_description'] = $this->model_members_member_group->getMemberGroupDescriptions($this->request->get['member_group_id']);
		} else {
			$this->data['member_group_description'] = array();
		}	

		if (isset($this->request->post['approval'])) {
			$this->data['approval'] = $this->request->post['approval'];
		} elseif (!empty($member_group_info)) {
			$this->data['approval'] = $member_group_info['approval'];
		} else {
			$this->data['approval'] = '';
		}	

		// if (isset($this->request->post['company_id_display'])) {
		// 	$this->data['company_id_display'] = $this->request->post['company_id_display'];
		// } elseif (!empty($member_group_info)) {
		// 	$this->data['company_id_display'] = $member_group_info['company_id_display'];
		// } else {
		// 	$this->data['company_id_display'] = '';
		// }			

		// if (isset($this->request->post['company_id_required'])) {
		// 	$this->data['company_id_required'] = $this->request->post['company_id_required'];
		// } elseif (!empty($member_group_info)) {
		// 	$this->data['company_id_required'] = $member_group_info['company_id_required'];
		// } else {
		// 	$this->data['company_id_required'] = '';
		// }		

		// if (isset($this->request->post['tax_id_display'])) {
		// 	$this->data['tax_id_display'] = $this->request->post['tax_id_display'];
		// } elseif (!empty($member_group_info)) {
		// 	$this->data['tax_id_display'] = $member_group_info['tax_id_display'];
		// } else {
		// 	$this->data['tax_id_display'] = '';
		// }			

		// if (isset($this->request->post['tax_id_required'])) {
		// 	$this->data['tax_id_required'] = $this->request->post['tax_id_required'];
		// } elseif (!empty($member_group_info)) {
		// 	$this->data['tax_id_required'] = $member_group_info['tax_id_required'];
		// } else {
		// 	$this->data['tax_id_required'] = '';
		// }	

		if (isset($this->request->post['sort_order'])) {
			$this->data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($member_group_info)) {
			$this->data['sort_order'] = $member_group_info['sort_order'];
		} else {
			$this->data['sort_order'] = '';
		}	

		$this->template = 'members/member_group_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render()); 
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'members/member_group')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['member_group_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 32)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'members/member_group')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/site');
		$this->load->model('members/member');

		foreach ($this->request->post['selected'] as $member_group_id) {
			if ($this->config->get('config_member_group_id') == $member_group_id) {
				$this->error['warning'] = $this->language->get('error_default');
			}  

			$site_total = $this->model_setting_site->getTotalSitesByMemberGroupId($member_group_id);

			if ($site_total) {
				$this->error['warning'] = sprintf($this->language->get('error_site'), $site_total);
			}

			$member_total = $this->model_members_member->getTotalMembersByMemberGroupId($member_group_id);

			if ($member_total) {
				$this->error['warning'] = sprintf($this->language->get('error_member'), $member_total);
			}
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>