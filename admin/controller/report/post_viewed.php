<?php
class ControllerReportPostViewed extends Controller {
	public function index() {     
		$this->language->load('report/post_viewed');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

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
			'href'      => $this->url->link('report/post_viewed', 'token=' . $this->session->data['token'] . $url, 'SSL'),
			'separator' => ' / '
		);

		$this->load->model('report/post');

		$data = array(
			'start' => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit' => $this->config->get('config_admin_limit')
		);

		$post_viewed_total = $this->model_report_post->getTotalPostsViewed($data); 

		$post_views_total = $this->model_report_post->getTotalPostViews(); 

		$this->data['posts'] = array();

		$results = $this->model_report_post->getPostsViewed($data);

		foreach ($results as $result) {
			if ($result['viewed']) {
				$percent = round($result['viewed'] / $post_views_total * 100, 2);
			} else {
				$percent = 0;
			}

			$this->data['posts'][] = array(
				'title'    => $result['title'],
				'model'   => $result['model'],
				'viewed'  => $result['viewed'],
				'percent' => $percent . '%'			
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');

		$this->data['column_title'] = $this->language->get('column_title');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_viewed'] = $this->language->get('column_viewed');
		$this->data['column_percent'] = $this->language->get('column_percent');

		$this->data['button_reset'] = $this->language->get('button_reset');

		$url = '';		

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$this->data['reset'] = $this->url->link('report/post_viewed/reset', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$pagination = new Pagination();
		$pagination->total = $post_viewed_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('report/post_viewed', 'token=' . $this->session->data['token'] . '&page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->template = 'report/post_viewed.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function reset() {
		$this->language->load('report/post_viewed');

		$this->load->model('report/post');

		$this->model_report_post->reset();

		$this->session->data['success'] = $this->language->get('text_success');

		$this->redirect($this->url->link('report/post_viewed', 'token=' . $this->session->data['token'], 'SSL'));
	}
}
?>