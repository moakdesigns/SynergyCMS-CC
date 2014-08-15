<?php
class ControllerModuleFeatured extends Controller {
	protected function index($setting) {
		$this->language->load('module/featured'); 

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->load->model('catalog/post'); 
		
		$this->load->model('tool/image');

		$this->data['posts'] = array();

		$posts = explode(',', $this->config->get('featured_post'));		

		if (empty($setting['limit'])) {
			$setting['limit'] = 5;
		}
		
		$posts = array_slice($posts, 0, (int)$setting['limit']);
		
		foreach ($posts as $post_id) {
			$post_info = $this->model_catalog_post->getPost($post_id);
			
			if ($post_info) {
				if ($post_info['image']) {
					$image = $this->model_tool_image->resize($post_info['image'], $setting['image_width'], $setting['image_height']);
				} else {
					$image = false;
				}
				
				if ($this->config->get('config_review_status')) {
					$rating = $post_info['rating'];
				} else {
					$rating = false;
				}
					
				$this->data['posts'][] = array(
					'post_id' => $post_info['post_id'],
					'thumb'   	 => $image,
					'title'    	 => $post_info['title'],
					'rating'     => $rating,
					'reviews'    => sprintf($this->language->get('text_reviews'), (int)$post_info['reviews']),
					'href'    	 => $this->url->link('post/post', 'post_id=' . $post_info['post_id'])
				);
			}
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/featured.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/featured.tpl';
		} else {
			$this->template = 'default/template/module/featured.tpl';
		}

		$this->render();
	}
}
?>