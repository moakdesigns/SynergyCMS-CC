<?php
class ModelSettingSite extends Model {
	public function getSites($data = array()) {
		$site_data = $this->cache->get('site');
	
		if (!$site_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "site ORDER BY url");

			$site_data = $query->rows;
		
			$this->cache->set('site', $site_data);
		}

		return $site_data;
	}	
}
?>