<?php  
class ControllerReportMemberOnline extends Controller {  
	public function index() {
		$this->language->load('report/member_online');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['filter_ip'])) {
			$filter_ip = $this->request->get['filter_ip'];
		} else {
			$filter_ip = null;
		}

		if (isset($this->request->get['filter_member'])) {
			$filter_member = $this->request->get['filter_member'];
		} else {
			$filter_member = null;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_member'])) {
			$url .= '&filter_member=' . urlencode($this->request->get['filter_member']);
		}

		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('text_home'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('report/member_online', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' / '
		);

		$this->load->model('report/online');
		$this->load->model('members/member');

		$this->data['members'] = array();

		$data = array(
			'filter_ip'       => $filter_ip, 
			'filter_member' => $filter_member, 
			'start'           => ($page - 1) * 20,
			'limit'           => 20
		);

		$member_total = $this->model_report_online->getTotalMembersOnline($data);

		$results = $this->model_report_online->getMembersOnline($data);

		foreach ($results as $result) {
			$action = array();

			if ($result['member_id']) {
				$action[] = array(
					'text' => 'Edit',
					'href' => $this->url->link('members/member/update', 'token=' . $this->session->data['token'] . '&member_id=' . $result['member_id'], 'SSL')
				);
			}

			$member_info = $this->model_members_member->getMember($result['member_id']);

			if ($member_info) {
				$member = $member_info['firstname'] . ' ' . $member_info['lastname'];
			} else {
				$member = $this->language->get('text_guest');
			}

			$this->data['members'][] = array(
				'ip'         => $result['ip'],
				'member'     => $member,
				'url'        => $result['url'],
				'referer'    => $result['referer'],
				'date_added' => date('d/m/Y H:i:s', strtotime($result['date_added'])),
				'action'     => $action
			);
		}	

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_ip'] = $this->language->get('column_ip');
		$this->data['column_member'] = $this->language->get('column_member');
		$this->data['column_url'] = $this->language->get('column_url');
		$this->data['column_referer'] = $this->language->get('column_referer');
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_filter'] = $this->language->get('button_filter');

		$this->data['token'] = $this->session->data['token'];

		$url = '';

		if (isset($this->request->get['filter_member'])) {
			$url .= '&filter_member=' . urlencode($this->request->get['filter_member']);
		}

		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
		}

		$pagination = new Pagination();
		$pagination->total = $member_total;
		$pagination->page = $page;
		$pagination->limit = 20; 
		$pagination->url = $this->url->link('report/member_online', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['filter_member'] = $filter_member;
		$this->data['filter_ip'] = $filter_ip;		

		$this->template = 'report/member_online.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);

		$this->response->setOutput($this->render());
	}
}
?>