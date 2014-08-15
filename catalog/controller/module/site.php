<?php   
class ControllerModuleSite extends Controller {
	protected function index() {
		$status = true;
		
		if ($this->config->get('site_admin')) {
			$this->load->library('user');
		
			$this->user = new User($this->registry);
			
			$status = $this->user->isLogged();
		}
		
		if ($status) {
			$this->language->load('module/site');
			
			$this->data['heading_title'] = $this->language->get('heading_title');
			
			$this->data['text_site'] = $this->language->get('text_site');
			
			$this->data['site_id'] = $this->config->get('config_site_id');
			
			$this->data['sites'] = array();
			
			$this->data['sites'][] = array(
				'site_id' => 0,
				'name'     => $this->language->get('text_default'),
				'url'      => HTTP_SERVER . 'index.php?route=common/home&session_id=' . $this->session->getId()
			);
			
			$this->load->model('setting/site');
			
			$results = $this->model_setting_site->getSites();
			
			foreach ($results as $result) {
				$this->data['sites'][] = array(
					'site_id' => $result['site_id'],
					'name'     => $result['name'],
					'url'      => $result['url'] . 'index.php?route=common/home&session_id=' . $this->session->getId()
				);
			}
	
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/site.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/site.tpl';
			} else {
				$this->template = 'default/template/module/site.tpl';
			}
			
			$this->render();
		}
	}
}
?>