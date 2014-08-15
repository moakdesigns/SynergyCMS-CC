<?php
class ModelCatalogPost extends Model {
	public function addPost($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "post 
			SET location = '" . $this->db->escape($data['location']) . "', 
			date_available = '" . $this->db->escape($data['date_available']) . "', 
			manufacturer_id = '" . (int)$data['manufacturer_id'] . "', 
			status = '" . (int)$data['status'] . "', 
			sort_order = '" . (int)$data['sort_order'] . "', 
			date_added = NOW()");

		$post_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "post 
				SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' 
				WHERE post_id = '" . (int)$post_id . "'");
		}

		foreach ($data['post_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "post_description SET post_id = '" . (int)$post_id . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($value['title']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', description = '" . $this->db->escape($value['description']) . "', tag = '" . $this->db->escape($value['tag']) . "'");
		}

		if (isset($data['post_site'])) {
			foreach ($data['post_site'] as $site_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "post_to_site 
					SET post_id = '" . (int)$post_id . "', 
					site_id = '" . (int)$site_id . "'");
			}
		}

		if (isset($data['post_attribute'])) {
			foreach ($data['post_attribute'] as $post_attribute) {
				if ($post_attribute['attribute_id']) {
					$this->db->query("DELETE FROM " . DB_PREFIX . "post_attribute WHERE post_id = '" . (int)$post_id . "' AND attribute_id = '" . (int)$post_attribute['attribute_id'] . "'");

					foreach ($post_attribute['post_attribute_description'] as $language_id => $post_attribute_description) {				
						$this->db->query("INSERT INTO " . DB_PREFIX . "post_attribute SET post_id = '" . (int)$post_id . "', attribute_id = '" . (int)$post_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" .  $this->db->escape($post_attribute_description['text']) . "'");
					}
				}
			}
		}

		// if (isset($data['post_option'])) {
		// 	foreach ($data['post_option'] as $post_option) {
		// 		if ($post_option['type'] == 'select' || $post_option['type'] == 'radio' || $post_option['type'] == 'checkbox' || $post_option['type'] == 'image') {
		// 			$this->db->query("INSERT INTO " . DB_PREFIX . "post_option SET post_id = '" . (int)$post_id . "', option_id = '" . (int)$post_option['option_id'] . "', required = '" . (int)$post_option['required'] . "'");

		// 			$post_option_id = $this->db->getLastId();

		// 			if (isset($post_option['post_option_value']) && count($post_option['post_option_value']) > 0 ) {
		// 				foreach ($post_option['post_option_value'] as $post_option_value) {
		// 					$this->db->query("INSERT INTO " . DB_PREFIX . "post_option_value SET post_option_id = '" . (int)$post_option_id . "', post_id = '" . (int)$post_id . "', option_id = '" . (int)$post_option['option_id'] . "', option_value_id = '" . (int)$post_option_value['option_value_id'] . "', quantity = '" . (int)$post_option_value['quantity'] . "', subtract = '" . (int)$post_option_value['subtract'] . "', price = '" . (float)$post_option_value['price'] . "', price_prefix = '" . $this->db->escape($post_option_value['price_prefix']) . "', points = '" . (int)$post_option_value['points'] . "', points_prefix = '" . $this->db->escape($post_option_value['points_prefix']) . "', weight = '" . (float)$post_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($post_option_value['weight_prefix']) . "'");
		// 				} 
		// 			}else{
		// 				$this->db->query("DELETE FROM " . DB_PREFIX . "post_option WHERE post_option_id = '".$post_option_id."'");
		// 			}
		// 		} else { 
		// 			$this->db->query("INSERT INTO " . DB_PREFIX . "post_option SET post_id = '" . (int)$post_id . "', option_id = '" . (int)$post_option['option_id'] . "', option_value = '" . $this->db->escape($post_option['option_value']) . "', required = '" . (int)$post_option['required'] . "'");
		// 		}
		// 	}
		// }

		// if (isset($data['post_discount'])) {
		// 	foreach ($data['post_discount'] as $post_discount) {
		// 		$this->db->query("INSERT INTO " . DB_PREFIX . "post_discount SET post_id = '" . (int)$post_id . "', member_group_id = '" . (int)$post_discount['member_group_id'] . "', quantity = '" . (int)$post_discount['quantity'] . "', priority = '" . (int)$post_discount['priority'] . "', price = '" . (float)$post_discount['price'] . "', date_start = '" . $this->db->escape($post_discount['date_start']) . "', date_end = '" . $this->db->escape($post_discount['date_end']) . "'");
		// 	}
		// }

		// if (isset($data['post_special'])) {
		// 	foreach ($data['post_special'] as $post_special) {
		// 		$this->db->query("INSERT INTO " . DB_PREFIX . "post_special SET post_id = '" . (int)$post_id . "', member_group_id = '" . (int)$post_special['member_group_id'] . "', priority = '" . (int)$post_special['priority'] . "', price = '" . (float)$post_special['price'] . "', date_start = '" . $this->db->escape($post_special['date_start']) . "', date_end = '" . $this->db->escape($post_special['date_end']) . "'");
		// 	}
		// }

		if (isset($data['post_image'])) {
			foreach ($data['post_image'] as $post_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "post_image SET post_id = '" . (int)$post_id . "', image = '" . $this->db->escape(html_entity_decode($post_image['image'], ENT_QUOTES, 'UTF-8')) . "', sort_order = '" . (int)$post_image['sort_order'] . "'");
			}
		}

		if (isset($data['post_download'])) {
			foreach ($data['post_download'] as $download_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "post_to_download SET post_id = '" . (int)$post_id . "', download_id = '" . (int)$download_id . "'");
			}
		}

		if (isset($data['post_category'])) {
			foreach ($data['post_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "post_to_category SET post_id = '" . (int)$post_id . "', category_id = '" . (int)$category_id . "'");
			}
		}

		if (isset($data['post_filter'])) {
			foreach ($data['post_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "post_filter SET post_id = '" . (int)$post_id . "', filter_id = '" . (int)$filter_id . "'");
			}
		}

		// if (isset($data['post_related'])) {
		// 	foreach ($data['post_related'] as $related_id) {
		// 		$this->db->query("DELETE FROM " . DB_PREFIX . "post_related WHERE post_id = '" . (int)$post_id . "' AND related_id = '" . (int)$related_id . "'");
		// 		$this->db->query("INSERT INTO " . DB_PREFIX . "post_related SET post_id = '" . (int)$post_id . "', related_id = '" . (int)$related_id . "'");
		// 		$this->db->query("DELETE FROM " . DB_PREFIX . "post_related WHERE post_id = '" . (int)$related_id . "' AND related_id = '" . (int)$post_id . "'");
		// 		$this->db->query("INSERT INTO " . DB_PREFIX . "post_related SET post_id = '" . (int)$related_id . "', related_id = '" . (int)$post_id . "'");
		// 	}
		// }

		// if (isset($data['post_reward'])) {
		// 	foreach ($data['post_reward'] as $member_group_id => $post_reward) {
		// 		$this->db->query("INSERT INTO " . DB_PREFIX . "post_reward SET post_id = '" . (int)$post_id . "', member_group_id = '" . (int)$member_group_id . "', points = '" . (int)$post_reward['points'] . "'");
		// 	}
		// }

		if (isset($data['post_layout'])) {
			foreach ($data['post_layout'] as $site_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "post_to_layout SET post_id = '" . (int)$post_id . "', site_id = '" . (int)$site_id . "', layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'post_id=" . (int)$post_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		// if (isset($data['post_profiles'])) {
		// 	foreach ($data['post_profiles'] as $profile) {
		// 		$this->db->query("INSERT INTO `" . DB_PREFIX . "post_profile` SET `post_id` = " . (int)$post_id . ", member_group_id = " . (int)$profile['member_group_id'] . ", `profile_id` = " . (int)$profile['profile_id']);
		// 	}
		// } 

		$this->cache->delete('post');
	}

	public function editPost($post_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "post 
			SET location = '" . $this->db->escape($data['location']) . "', 
			date_available = '" . $this->db->escape($data['date_available']) . "', 
			manufacturer_id = '" . (int)$data['manufacturer_id'] . "',  
			status = '" . (int)$data['status'] . "', 
			sort_order = '" . (int)$data['sort_order'] . "', 
			date_modified = NOW() WHERE post_id = '" . (int)$post_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "post 
				SET image = '" . $this->db->escape(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8')) . "' 
				WHERE post_id = '" . (int)$post_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "post_description WHERE post_id = '" . (int)$post_id . "'");

		foreach ($data['post_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "post_description 
				SET post_id = '" . (int)$post_id . "', 
				language_id = '" . (int)$language_id . "', 
				title = '" . $this->db->escape($value['title']) . "', 
				meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "', 
				meta_description = '" . $this->db->escape($value['meta_description']) . "', 
				description = '" . $this->db->escape($value['description']) . "', 
				tag = '" . $this->db->escape($value['tag']) . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "post_to_site WHERE post_id = '" . (int)$post_id . "'");

		if (isset($data['post_site'])) {
			foreach ($data['post_site'] as $site_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "post_to_site 
					SET post_id = '" . (int)$post_id . "', 
					site_id = '" . (int)$site_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "post_attribute WHERE post_id = '" . (int)$post_id . "'");

		if (!empty($data['post_attribute'])) {
			foreach ($data['post_attribute'] as $post_attribute) {
				if ($post_attribute['attribute_id']) {
					$this->db->query("DELETE FROM " . DB_PREFIX . "post_attribute 
						WHERE post_id = '" . (int)$post_id . "' 
						AND attribute_id = '" . (int)$post_attribute['attribute_id'] . "'");

					foreach ($post_attribute['post_attribute_description'] as $language_id => $post_attribute_description) {				
						$this->db->query("INSERT INTO " . DB_PREFIX . "post_attribute 
							SET post_id = '" . (int)$post_id . "', 
							attribute_id = '" . (int)$post_attribute['attribute_id'] . "', 
							language_id = '" . (int)$language_id . "', 
							text = '" .  $this->db->escape($post_attribute_description['text']) . "'");
					}
				}
			}
		}

		// $this->db->query("DELETE FROM " . DB_PREFIX . "post_option WHERE post_id = '" . (int)$post_id . "'");
		// $this->db->query("DELETE FROM " . DB_PREFIX . "post_option_value WHERE post_id = '" . (int)$post_id . "'");

		// if (isset($data['post_option'])) {
		// 	foreach ($data['post_option'] as $post_option) {
		// 		if ($post_option['type'] == 'select' || $post_option['type'] == 'radio' || $post_option['type'] == 'checkbox' || $post_option['type'] == 'image') {
		// 			$this->db->query("INSERT INTO " . DB_PREFIX . "post_option SET post_option_id = '" . (int)$post_option['post_option_id'] . "', post_id = '" . (int)$post_id . "', option_id = '" . (int)$post_option['option_id'] . "', required = '" . (int)$post_option['required'] . "'");

		// 			$post_option_id = $this->db->getLastId();

		// 			if (isset($post_option['post_option_value'])  && count($post_option['post_option_value']) > 0 ) {
		// 				foreach ($post_option['post_option_value'] as $post_option_value) {
		// 					$this->db->query("INSERT INTO " . DB_PREFIX . "post_option_value SET post_option_value_id = '" . (int)$post_option_value['post_option_value_id'] . "', post_option_id = '" . (int)$post_option_id . "', post_id = '" . (int)$post_id . "', option_id = '" . (int)$post_option['option_id'] . "', option_value_id = '" . (int)$post_option_value['option_value_id'] . "', quantity = '" . (int)$post_option_value['quantity'] . "', subtract = '" . (int)$post_option_value['subtract'] . "', price = '" . (float)$post_option_value['price'] . "', price_prefix = '" . $this->db->escape($post_option_value['price_prefix']) . "', points = '" . (int)$post_option_value['points'] . "', points_prefix = '" . $this->db->escape($post_option_value['points_prefix']) . "', weight = '" . (float)$post_option_value['weight'] . "', weight_prefix = '" . $this->db->escape($post_option_value['weight_prefix']) . "'");
		// 				}
		// 			}else{
		// 				$this->db->query("DELETE FROM " . DB_PREFIX . "post_option WHERE post_option_id = '".$post_option_id."'");
		// 			}
		// 		} else { 
		// 			$this->db->query("INSERT INTO " . DB_PREFIX . "post_option SET post_option_id = '" . (int)$post_option['post_option_id'] . "', post_id = '" . (int)$post_id . "', option_id = '" . (int)$post_option['option_id'] . "', option_value = '" . $this->db->escape($post_option['option_value']) . "', required = '" . (int)$post_option['required'] . "'");
		// 		}					
		// 	}
		// }

		// $this->db->query("DELETE FROM " . DB_PREFIX . "post_discount WHERE post_id = '" . (int)$post_id . "'");

		// if (isset($data['post_discount'])) {
		// 	foreach ($data['post_discount'] as $post_discount) {
		// 		$this->db->query("INSERT INTO " . DB_PREFIX . "post_discount SET post_id = '" . (int)$post_id . "', member_group_id = '" . (int)$post_discount['member_group_id'] . "', quantity = '" . (int)$post_discount['quantity'] . "', priority = '" . (int)$post_discount['priority'] . "', price = '" . (float)$post_discount['price'] . "', date_start = '" . $this->db->escape($post_discount['date_start']) . "', date_end = '" . $this->db->escape($post_discount['date_end']) . "'");
		// 	}
		// }

		// $this->db->query("DELETE FROM " . DB_PREFIX . "post_special WHERE post_id = '" . (int)$post_id . "'");

		// if (isset($data['post_special'])) {
		// 	foreach ($data['post_special'] as $post_special) {
		// 		$this->db->query("INSERT INTO " . DB_PREFIX . "post_special SET post_id = '" . (int)$post_id . "', member_group_id = '" . (int)$post_special['member_group_id'] . "', priority = '" . (int)$post_special['priority'] . "', price = '" . (float)$post_special['price'] . "', date_start = '" . $this->db->escape($post_special['date_start']) . "', date_end = '" . $this->db->escape($post_special['date_end']) . "'");
		// 	}
		// }

		$this->db->query("DELETE FROM " . DB_PREFIX . "post_image WHERE post_id = '" . (int)$post_id . "'");

		if (isset($data['post_image'])) {
			foreach ($data['post_image'] as $post_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "post_image 
					SET post_id = '" . (int)$post_id . "', 
					image = '" . $this->db->escape(html_entity_decode($post_image['image'], ENT_QUOTES, 'UTF-8')) . "', 
					sort_order = '" . (int)$post_image['sort_order'] . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "post_to_download WHERE post_id = '" . (int)$post_id . "'");

		if (isset($data['post_download'])) {
			foreach ($data['post_download'] as $download_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "post_to_download 
					SET post_id = '" . (int)$post_id . "', 
					download_id = '" . (int)$download_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "post_to_category WHERE post_id = '" . (int)$post_id . "'");

		if (isset($data['post_category'])) {
			foreach ($data['post_category'] as $category_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "post_to_category 
					SET post_id = '" . (int)$post_id . "', 
					category_id = '" . (int)$category_id . "'");
			}		
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "post_filter WHERE post_id = '" . (int)$post_id . "'");

		if (isset($data['post_filter'])) {
			foreach ($data['post_filter'] as $filter_id) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "post_filter 
					SET post_id = '" . (int)$post_id . "', 
					filter_id = '" . (int)$filter_id . "'");
			}		
		}

		// $this->db->query("DELETE FROM " . DB_PREFIX . "post_related WHERE post_id = '" . (int)$post_id . "'");
		// $this->db->query("DELETE FROM " . DB_PREFIX . "post_related WHERE related_id = '" . (int)$post_id . "'");

		// if (isset($data['post_related'])) {
		// 	foreach ($data['post_related'] as $related_id) {
		// 		$this->db->query("DELETE FROM " . DB_PREFIX . "post_related WHERE post_id = '" . (int)$post_id . "' AND related_id = '" . (int)$related_id . "'");
		// 		$this->db->query("INSERT INTO " . DB_PREFIX . "post_related SET post_id = '" . (int)$post_id . "', related_id = '" . (int)$related_id . "'");
		// 		$this->db->query("DELETE FROM " . DB_PREFIX . "post_related WHERE post_id = '" . (int)$related_id . "' AND related_id = '" . (int)$post_id . "'");
		// 		$this->db->query("INSERT INTO " . DB_PREFIX . "post_related SET post_id = '" . (int)$related_id . "', related_id = '" . (int)$post_id . "'");
		// 	}
		// }

		// $this->db->query("DELETE FROM " . DB_PREFIX . "post_reward WHERE post_id = '" . (int)$post_id . "'");

		// if (isset($data['post_reward'])) {
		// 	foreach ($data['post_reward'] as $member_group_id => $value) {
		// 		$this->db->query("INSERT INTO " . DB_PREFIX . "post_reward SET post_id = '" . (int)$post_id . "', member_group_id = '" . (int)$member_group_id . "', points = '" . (int)$value['points'] . "'");
		// 	}
		// }

		$this->db->query("DELETE FROM " . DB_PREFIX . "post_to_layout WHERE post_id = '" . (int)$post_id . "'");

		if (isset($data['post_layout'])) {
			foreach ($data['post_layout'] as $site_id => $layout) {
				if ($layout['layout_id']) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "post_to_layout 
						SET post_id = '" . (int)$post_id . "', 
						site_id = '" . (int)$site_id . "', 
						layout_id = '" . (int)$layout['layout_id'] . "'");
				}
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'post_id=" . (int)$post_id. "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'post_id=" . (int)$post_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		// $this->db->query("DELETE FROM `" . DB_PREFIX . "post_profile` WHERE post_id = " . (int)$post_id);		
		// if (isset($data['post_profiles'])) {
		// 	foreach ($data['post_profiles'] as $profile) {
		// 		$this->db->query("INSERT INTO `" . DB_PREFIX . "post_profile` 
		// 			SET `post_id` = " . (int)$post_id . ", 
		// 			member_group_id = " . (int)$profile['member_group_id'] . ", 
		// 			`profile_id` = " . (int)$profile['profile_id']);			
		// 	}		
		// }		

		$this->cache->delete('post');
	}

	public function copyPost($post_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "post p 
			LEFT JOIN " . DB_PREFIX . "post_description pd ON (p.post_id = pd.post_id) 
			WHERE p.post_id = '" . (int)$post_id . "' 
			AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		if ($query->num_rows) {
			$data = array();

			$data = $query->row;

			// $data['sku'] = '';
			// $data['upc'] = '';
			$data['viewed'] = '0';
			$data['keyword'] = '';
			$data['status'] = '0';

			$data = array_merge($data, array('post_attribute' => $this->getPostAttributes($post_id)));
			$data = array_merge($data, array('post_description' => $this->getPostDescriptions($post_id)));			
			// $data = array_merge($data, array('post_discount' => $this->getPostDiscounts($post_id)));
			$data = array_merge($data, array('post_filter' => $this->getPostFilters($post_id)));
			$data = array_merge($data, array('post_image' => $this->getPostImages($post_id)));		
			// $data = array_merge($data, array('post_option' => $this->getPostOptions($post_id)));
			// $data = array_merge($data, array('post_related' => $this->getPostRelated($post_id)));
			// $data = array_merge($data, array('post_reward' => $this->getPostRewards($post_id)));
			// $data = array_merge($data, array('post_special' => $this->getPostSpecials($post_id)));
			$data = array_merge($data, array('post_category' => $this->getPostCategories($post_id)));
			$data = array_merge($data, array('post_download' => $this->getPostDownloads($post_id)));
			$data = array_merge($data, array('post_layout' => $this->getPostLayouts($post_id)));
			$data = array_merge($data, array('post_site' => $this->getPostStores($post_id)));
			// $data = array_merge($data, array('post_profiles' => $this->getProfiles($post_id)));
			$this->addPost($data);
		}
	}

	public function deletePost($post_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "post WHERE post_id = '" . (int)$post_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "post_attribute WHERE post_id = '" . (int)$post_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "post_description WHERE post_id = '" . (int)$post_id . "'");
		// $this->db->query("DELETE FROM " . DB_PREFIX . "post_discount WHERE post_id = '" . (int)$post_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "post_filter WHERE post_id = '" . (int)$post_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "post_image WHERE post_id = '" . (int)$post_id . "'");
		// $this->db->query("DELETE FROM " . DB_PREFIX . "post_option WHERE post_id = '" . (int)$post_id . "'");
		// $this->db->query("DELETE FROM " . DB_PREFIX . "post_option_value WHERE post_id = '" . (int)$post_id . "'");
		// $this->db->query("DELETE FROM " . DB_PREFIX . "post_related WHERE post_id = '" . (int)$post_id . "'");
		// $this->db->query("DELETE FROM " . DB_PREFIX . "post_related WHERE related_id = '" . (int)$post_id . "'");
		// $this->db->query("DELETE FROM " . DB_PREFIX . "post_reward WHERE post_id = '" . (int)$post_id . "'");
		// $this->db->query("DELETE FROM " . DB_PREFIX . "post_special WHERE post_id = '" . (int)$post_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "post_to_category WHERE post_id = '" . (int)$post_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "post_to_download WHERE post_id = '" . (int)$post_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "post_to_layout WHERE post_id = '" . (int)$post_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "post_to_site WHERE post_id = '" . (int)$post_id . "'");
		// $this->db->query("DELETE FROM `" . DB_PREFIX . "post_profile` WHERE `post_id` = " . (int)$post_id);
		$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE post_id = '" . (int)$post_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'post_id=" . (int)$post_id. "'");

		$this->cache->delete('post');
	}

	public function getPost($post_id) {
		$query = $this->db->query("SELECT DISTINCT *, 
			(SELECT keyword FROM " . DB_PREFIX . "url_alias 
				WHERE query = 'post_id=" . (int)$post_id . "') AS keyword 
				FROM " . DB_PREFIX . "post p 
				LEFT JOIN " . DB_PREFIX . "post_description pd ON (p.post_id = pd.post_id) 
				WHERE p.post_id = '" . (int)$post_id . "' 
				AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getPosts($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "post p LEFT JOIN " . DB_PREFIX . "post_description pd ON (p.post_id = pd.post_id)";

		if (!empty($data['filter_category_id'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "post_to_category p2c ON (p.post_id = p2c.post_id)";			
		}

		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'"; 

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.title LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		// if (!empty($data['filter_model'])) {
		// 	$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		// }

		// if (!empty($data['filter_price'])) {
		// 	$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		// }

		// if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
		// 	$sql .= " AND p.quantity = '" . $this->db->escape($data['filter_quantity']) . "'";
		// }

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		$sql .= " GROUP BY p.post_id";

		$sort_data = array(
			'pd.title',
			'p.post_id',
			'p.status',
			'p.sort_order'
		);	

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY pd.title";	
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}				

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}	

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getPostsByCategoryId($category_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "post p 
			LEFT JOIN " . DB_PREFIX . "post_description pd ON (p.post_id = pd.post_id) 
			LEFT JOIN " . DB_PREFIX . "post_to_category p2c ON (p.post_id = p2c.post_id) 
			WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
			AND p2c.category_id = '" . (int)$category_id . "' 
			ORDER BY pd.title ASC");

		return $query->rows;
	} 

	public function getPostDescriptions($post_id) {
		$post_description_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "post_description WHERE post_id = '" . (int)$post_id . "'");

		foreach ($query->rows as $result) {
			$post_description_data[$result['language_id']] = array(
				'title'             => $result['title'],
				'description'      => $result['description'],
				'meta_keyword'     => $result['meta_keyword'],
				'meta_description' => $result['meta_description'],
				'tag'              => $result['tag']
			);
		}

		return $post_description_data;
	}

	public function getPostCategories($post_id) {
		$post_category_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "post_to_category WHERE post_id = '" . (int)$post_id . "'");

		foreach ($query->rows as $result) {
			$post_category_data[] = $result['category_id'];
		}

		return $post_category_data;
	}

	public function getPostFilters($post_id) {
		$post_filter_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "post_filter WHERE post_id = '" . (int)$post_id . "'");

		foreach ($query->rows as $result) {
			$post_filter_data[] = $result['filter_id'];
		}

		return $post_filter_data;
	}

	public function getPostAttributes($post_id) {
		$post_attribute_data = array();

		$post_attribute_query = $this->db->query("SELECT attribute_id FROM " . DB_PREFIX . "post_attribute WHERE post_id = '" . (int)$post_id . "' GROUP BY attribute_id");

		foreach ($post_attribute_query->rows as $post_attribute) {
			$post_attribute_description_data = array();

			$post_attribute_description_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "post_attribute WHERE post_id = '" . (int)$post_id . "' AND attribute_id = '" . (int)$post_attribute['attribute_id'] . "'");

			foreach ($post_attribute_description_query->rows as $post_attribute_description) {
				$post_attribute_description_data[$post_attribute_description['language_id']] = array('text' => $post_attribute_description['text']);
			}

			$post_attribute_data[] = array(
				'attribute_id'                  => $post_attribute['attribute_id'],
				'post_attribute_description' => $post_attribute_description_data
			);
		}

		return $post_attribute_data;
	}

	// public function getPostOptions($post_id) {
	// 	$post_option_data = array();

	// 	$post_option_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "post_option` po LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id) LEFT JOIN `" . DB_PREFIX . "option_description` od ON (o.option_id = od.option_id) WHERE po.post_id = '" . (int)$post_id . "' AND od.language_id = '" . (int)$this->config->get('config_language_id') . "'");

	// 	foreach ($post_option_query->rows as $post_option) {
	// 		$post_option_value_data = array();	

	// 		$post_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "post_option_value WHERE post_option_id = '" . (int)$post_option['post_option_id'] . "'");

	// 		foreach ($post_option_value_query->rows as $post_option_value) {
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

	// 		$post_option_data[] = array(
	// 			'post_option_id'    => $post_option['post_option_id'],
	// 			'option_id'            => $post_option['option_id'],
	// 			'name'                 => $post_option['name'],
	// 			'type'                 => $post_option['type'],			
	// 			'post_option_value' => $post_option_value_data,
	// 			'option_value'         => $post_option['option_value'],
	// 			'required'             => $post_option['required']				
	// 		);
	// 	}

	// 	return $post_option_data;
	// }

	public function getPostImages($post_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "post_image WHERE post_id = '" . (int)$post_id . "'");

		return $query->rows;
	}

	// public function getPostDiscounts($post_id) {
	// 	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "post_discount WHERE post_id = '" . (int)$post_id . "' ORDER BY quantity, priority, price");

	// 	return $query->rows;
	// }

	// public function getPostSpecials($post_id) {
	// 	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "post_special WHERE post_id = '" . (int)$post_id . "' ORDER BY priority, price");

	// 	return $query->rows;
	// }

	// public function getPostRewards($post_id) {
	// 	$post_reward_data = array();

	// 	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "post_reward WHERE post_id = '" . (int)$post_id . "'");

	// 	foreach ($query->rows as $result) {
	// 		$post_reward_data[$result['member_group_id']] = array('points' => $result['points']);
	// 	}

	// 	return $post_reward_data;
	// }

	public function getPostDownloads($post_id) {
		$post_download_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "post_to_download WHERE post_id = '" . (int)$post_id . "'");

		foreach ($query->rows as $result) {
			$post_download_data[] = $result['download_id'];
		}

		return $post_download_data;
	}

	public function getPostSites($post_id) {
		$post_site_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "post_to_site WHERE post_id = '" . (int)$post_id . "'");

		foreach ($query->rows as $result) {
			$post_site_data[] = $result['site_id'];
		}

		return $post_site_data;
	}

	public function getPostLayouts($post_id) {
		$post_layout_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "post_to_layout WHERE post_id = '" . (int)$post_id . "'");

		foreach ($query->rows as $result) {
			$post_layout_data[$result['site_id']] = $result['layout_id'];
		}

		return $post_layout_data;
	}

	// public function getPostRelated($post_id) {
	// 	$post_related_data = array();

	// 	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "post_related WHERE post_id = '" . (int)$post_id . "'");

	// 	foreach ($query->rows as $result) {
	// 		$post_related_data[] = $result['related_id'];
	// 	}

	// 	return $post_related_data;
	// }

	// public function getProfiles($post_id) {
	// 	return $this->db->query("SELECT * FROM `" . DB_PREFIX . "post_profile` WHERE post_id = " . (int)$post_id)->rows;
	// }

	public function getTotalPosts($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.post_id) AS total 
		FROM " . DB_PREFIX . "post p 
		LEFT JOIN " . DB_PREFIX . "post_description pd ON (p.post_id = pd.post_id)";

		if (!empty($data['filter_category_id'])) {
			$sql .= " LEFT JOIN " . DB_PREFIX . "post_to_category p2c ON (p.post_id = p2c.post_id)";			
		}

		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.title LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		// if (!empty($data['filter_model'])) {
		// 	$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		// }

		// if (!empty($data['filter_price'])) {
		// 	$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		// }

		// if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
		// 	$sql .= " AND p.quantity = '" . $this->db->escape($data['filter_quantity']) . "'";
		// }

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}	

	// public function getTotalPostsByTaxClassId($tax_class_id) {
	// 	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "post WHERE tax_class_id = '" . (int)$tax_class_id . "'");

	// 	return $query->row['total'];
	// }

	// public function getTotalPostsByStockStatusId($stock_status_id) {
	// 	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "post WHERE stock_status_id = '" . (int)$stock_status_id . "'");

	// 	return $query->row['total'];
	// }

	// public function getTotalPostsByWeightClassId($weight_class_id) {
	// 	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "post WHERE weight_class_id = '" . (int)$weight_class_id . "'");

	// 	return $query->row['total'];
	// }

	// public function getTotalPostsByLengthClassId($length_class_id) {
	// 	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "post WHERE length_class_id = '" . (int)$length_class_id . "'");

	// 	return $query->row['total'];
	// }

	public function getTotalPostsByDownloadId($download_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "post_to_download WHERE download_id = '" . (int)$download_id . "'");

		return $query->row['total'];
	}

	public function getTotalPostsByManufacturerId($manufacturer_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "post WHERE manufacturer_id = '" . (int)$manufacturer_id . "'");

		return $query->row['total'];
	}

	public function getTotalPostsByAttributeId($attribute_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "post_attribute WHERE attribute_id = '" . (int)$attribute_id . "'");

		return $query->row['total'];
	}	

	// public function getTotalPostsByOptionId($option_id) {
	// 	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "post_option WHERE option_id = '" . (int)$option_id . "'");

	// 	return $query->row['total'];
	// }	

	public function getTotalPostsByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "post_to_layout WHERE layout_id = '" . (int)$layout_id . "'");

		return $query->row['total'];
	}
}
?>
