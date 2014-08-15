<?php
class ModelCatalogPost extends Model {
	public function updateViewed($post_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "post SET viewed = (viewed + 1) WHERE post_id = '" . (int)$post_id . "'");
	}

	public function getPost($post_id) {
		if ($this->member->isLogged()) {
			$member_group_id = $this->member->getMemberGroupId();
		} else {
			$member_group_id = $this->config->get('config_member_group_id');
		}	

		$query = $this->db->query("SELECT DISTINCT *, 
			pd.title AS title, 
			p.image, 
			m.name AS manufacturer, 
			(SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 
				WHERE r1.post_id = p.post_id 
				AND r1.status = '1' 
				GROUP BY r1.post_id) AS rating, 
			(SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 
				WHERE r2.post_id = p.post_id 
				AND r2.status = '1' 
				GROUP BY r2.post_id) AS reviews, 
			p.sort_order FROM " . DB_PREFIX . "post p 
			LEFT JOIN " . DB_PREFIX . "post_description pd ON (p.post_id = pd.post_id) 
			LEFT JOIN " . DB_PREFIX . "post_to_site p2s ON (p.post_id = p2s.post_id) 
			LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) 
			WHERE p.post_id = '" . (int)$post_id . "' 
			AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
			AND p.status = '1' 
			AND p.date_available <= NOW() 
			AND p2s.site_id = '" . (int)$this->config->get('config_site_id') . "'");

		if ($query->num_rows) {
			return array(
				'post_id'       => $query->row['post_id'],
				'title'             => $query->row['title'],
				'description'      => $query->row['description'],
				'meta_description' => $query->row['meta_description'],
				'meta_keyword'     => $query->row['meta_keyword'],
				'tag'              => $query->row['tag'],
				'location'         => $query->row['location'],
				'image'            => $query->row['image'],
				'manufacturer_id'  => $query->row['manufacturer_id'],
				'manufacturer'     => $query->row['manufacturer'],
				'date_available'   => $query->row['date_available'],
				'rating'           => round($query->row['rating']),
				'reviews'          => $query->row['reviews'] ? $query->row['reviews'] : 0,
				'sort_order'       => $query->row['sort_order'],
				'status'           => $query->row['status'],
				'date_added'       => $query->row['date_added'],
				'date_modified'    => $query->row['date_modified'],
				'viewed'           => $query->row['viewed']
			);
		} else {
			return false;
		}
	}

	public function getPosts($data = array()) {
		if ($this->member->isLogged()) {
			$member_group_id = $this->member->getMemberGroupId();
		} else {
			$member_group_id = $this->config->get('config_member_group_id');
		}	

		$sql = "SELECT p.post_id, 
		(SELECT AVG(rating) AS total 
			FROM " . DB_PREFIX . "review r1 
			WHERE r1.post_id = p.post_id 
			AND r1.status = '1' 
			GROUP BY r1.post_id) AS rating"; 

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "post_to_category p2c ON (cp.category_id = p2c.category_id)";			
			} else {
				$sql .= " FROM " . DB_PREFIX . "post_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "post_filter pf ON (p2c.post_id = pf.post_id) LEFT JOIN " . DB_PREFIX . "post p ON (pf.post_id = p.post_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "post p ON (p2c.post_id = p.post_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "post p";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "post_description pd ON (p.post_id = pd.post_id) 
		LEFT JOIN " . DB_PREFIX . "post_to_site p2s ON (p.post_id = p2s.post_id) 
		WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
		AND p.status = '1' 
		AND p.date_available <= NOW() 
		AND p2s.site_id = '" . (int)$this->config->get('config_site_id') . "'";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";	
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";			
			}	

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";				
			}
		}	

		if (!empty($data['filter_title']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_title'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s\s+/', ' ', $data['filter_title'])));

				foreach ($words as $word) {
					$implode[] = "pd.title LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_title']) . "%'";
				}
			}

			if (!empty($data['filter_title']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$sql .= "pd.tag LIKE '%" . $this->db->escape($data['filter_tag']) . "%'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		$sql .= " GROUP BY p.post_id";

		$sort_data = array(
			'pd.title',
			'rating',
			'p.sort_order',
			'p.date_added'
		);	

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.title') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";	
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.title) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.title) ASC";
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

		$post_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$post_data[$result['post_id']] = $this->getPost($result['post_id']);
		}

		return $post_data;
	}


	public function getLatestPosts($limit) {
		if ($this->member->isLogged()) {
			$member_group_id = $this->member->getMemberGroupId();
		} else {
			$member_group_id = $this->config->get('config_member_group_id');
		}	

		$post_data = $this->cache->get('post.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_site_id') . '.' . $member_group_id . '.' . (int)$limit);

		if (!$post_data) { 
			$query = $this->db->query("SELECT p.post_id 
				FROM " . DB_PREFIX . "post p 
				LEFT JOIN " . DB_PREFIX . "post_to_site p2s ON (p.post_id = p2s.post_id) 
				WHERE p.status = '1' 
				AND p.date_available <= NOW() 
				AND p2s.site_id = '" . (int)$this->config->get('config_site_id') . "' 
				ORDER BY p.date_added DESC LIMIT " . (int)$limit);

			foreach ($query->rows as $result) {
				$post_data[$result['post_id']] = $this->getPost($result['post_id']);
			}

			$this->cache->set('post.latest.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_site_id'). '.' . $member_group_id . '.' . (int)$limit, $post_data);
		}

		return $post_data;
	}

	public function getPopularPosts($limit) {
		$post_data = array();

		$query = $this->db->query("SELECT p.post_id 
			FROM " . DB_PREFIX . "post p 
			LEFT JOIN " . DB_PREFIX . "post_to_site p2s ON (p.post_id = p2s.post_id) 
			WHERE p.status = '1' 
			AND p.date_available <= NOW() 
			AND p2s.site_id = '" . (int)$this->config->get('config_site_id') . "' 
			ORDER BY p.viewed, 
			p.date_added DESC LIMIT " . (int)$limit);

		foreach ($query->rows as $result) { 		
			$post_data[$result['post_id']] = $this->getPost($result['post_id']);
		}

		return $post_data;
	}

	public function getPostAttributes($post_id) {
		$post_attribute_group_data = array();

		$post_attribute_group_query = $this->db->query(
			"SELECT ag.attribute_group_id, agd.name 
			FROM " . DB_PREFIX . "post_attribute pa 
			LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) 
			LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) 
			LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id) 
			WHERE pa.post_id = '" . (int)$post_id . "' 
			AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
			GROUP BY ag.attribute_group_id 
			ORDER BY ag.sort_order, agd.name");

		foreach ($post_attribute_group_query->rows as $post_attribute_group) {
			$post_attribute_data = array();

			$post_attribute_query = $this->db->query(
				"SELECT a.attribute_id, ad.name, pa.text 
				FROM " . DB_PREFIX . "post_attribute pa 
				LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) 
				LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) 
				WHERE pa.post_id = '" . (int)$post_id . "' 
				AND a.attribute_group_id = '" . (int)$post_attribute_group['attribute_group_id'] . "' 
				AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' 
				AND pa.language_id = '" . (int)$this->config->get('config_language_id') . "' 
				ORDER BY a.sort_order, ad.name");

			foreach ($post_attribute_query->rows as $post_attribute) {
				$post_attribute_data[] = array(
					'attribute_id' => $post_attribute['attribute_id'],
					'name'         => $post_attribute['name'],
					'text'         => $post_attribute['text']		 	
				);
			}

			$post_attribute_group_data[] = array(
				'attribute_group_id' => $post_attribute_group['attribute_group_id'],
				'name'               => $post_attribute_group['name'],
				'attribute'          => $post_attribute_data
			);			
		}

		return $post_attribute_group_data;
	}


	public function getPostImages($post_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "post_image 
			WHERE post_id = '" . (int)$post_id . "' 
			ORDER BY sort_order ASC");

		return $query->rows;
	}


	public function getPostLayoutId($post_id) {
		$query = $this->db->query("SELECT * 
			FROM " . DB_PREFIX . "post_to_layout 
			WHERE post_id = '" . (int)$post_id . "' 
			AND site_id = '" . (int)$this->config->get('config_site_id') . "'");

		if ($query->num_rows) {
			return $query->row['layout_id'];
		} else {
			return false;
		}
	}

	public function getCategories($post_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "post_to_category WHERE post_id = '" . (int)$post_id . "'");

		return $query->rows;
	}	

	public function getTotalPosts($data = array()) {
		if ($this->member->isLogged()) {
			$member_group_id = $this->member->getMemberGroupId();
		} else {
			$member_group_id = $this->config->get('config_member_group_id');
		}	

		$sql = "SELECT COUNT(DISTINCT p.post_id) AS total"; 

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp 
				LEFT JOIN " . DB_PREFIX . "post_to_category p2c ON (cp.category_id = p2c.category_id)";			
			} else {
				$sql .= " FROM " . DB_PREFIX . "post_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "post_filter pf ON (p2c.post_id = pf.post_id) 
				LEFT JOIN " . DB_PREFIX . "post p ON (pf.post_id = p.post_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "post p ON (p2c.post_id = p.post_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "post p";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "post_description pd ON (p.post_id = pd.post_id) 
		LEFT JOIN " . DB_PREFIX . "post_to_site p2s ON (p.post_id = p2s.post_id) 
		WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
		AND p.status = '1' 
		AND p.date_available <= NOW() 
		AND p2s.site_id = '" . (int)$this->config->get('config_site_id') . "'";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";	
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";			
			}	

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";				
			}
		}

		if (!empty($data['filter_title']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_title'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s\s+/', ' ', $data['filter_title'])));

				foreach ($words as $word) {
					$implode[] = "pd.title LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_title']) . "%'";
				}
			}

			if (!empty($data['filter_title']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$sql .= "pd.tag LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_tag'])) . "%'";
			}

			$sql .= ")";				
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

}
?>