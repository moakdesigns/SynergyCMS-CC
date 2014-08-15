<?php
class ModelSettingSite extends Model {
	public function addSite($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "site SET name = '" . $this->db->escape($data['config_name']) . "', `url` = '" . $this->db->escape($data['config_url']) . "', `ssl` = '" . $this->db->escape($data['config_ssl']) . "'");

		$this->cache->delete('site');

		return $this->db->getLastId();
	}

	public function editSite($site_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "site SET name = '" . $this->db->escape($data['config_name']) . "', `url` = '" . $this->db->escape($data['config_url']) . "', `ssl` = '" . $this->db->escape($data['config_ssl']) . "' WHERE site_id = '" . (int)$site_id . "'");

		$this->cache->delete('site');
	}

	public function deleteSite($site_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "site WHERE site_id = '" . (int)$site_id . "'");

		$this->cache->delete('site');
	}

	public function getSite($site_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "site WHERE site_id = '" . (int)$site_id . "'");

		return $query->row;
	}

	public function getSites($data = array()) {
		$site_data = $this->cache->get('site');

		if (!$site_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "site ORDER BY url");

			$site_data = $query->rows;

			$this->cache->set('site', $site_data);
		}

		return $site_data;
	}

	public function getTotalSites() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "site");

		return $query->row['total'];
	}	

	public function getTotalSitesByLayoutId($layout_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'config_layout_id' AND `value` = '" . (int)$layout_id . "' AND site_id != '0'");

		return $query->row['total'];		
	}

	public function getTotalSitesByLanguage($language) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'config_language' AND `value` = '" . $this->db->escape($language) . "' AND site_id != '0'");

		return $query->row['total'];		
	}

	// public function getTotalSitesByCurrency($currency) {
	// 	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'config_currency' AND `value` = '" . $this->db->escape($currency) . "' AND site_id != '0'");

	// 	return $query->row['total'];		
	// }

	public function getTotalSitesByCountryId($country_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'config_country_id' AND `value` = '" . (int)$country_id . "' AND site_id != '0'");

		return $query->row['total'];		
	}

	public function getTotalSitesByZoneId($zone_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'config_zone_id' AND `value` = '" . (int)$zone_id . "' AND site_id != '0'");

		return $query->row['total'];		
	}

	public function getTotalSitesByMemberGroupId($member_group_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'config_member_group_id' AND `value` = '" . (int)$member_group_id . "' AND site_id != '0'");

		return $query->row['total'];		
	}	

	public function getTotalSitesByInformationId($information_id) {
		$account_query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'config_account_id' AND `value` = '" . (int)$information_id . "' AND site_id != '0'");

		// $checkout_query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'config_checkout_id' AND `value` = '" . (int)$information_id . "' AND site_id != '0'");

		return ($account_query->row['total']);
	}

	// public function getTotalSitesByOrderStatusId($order_status_id) {
	// 	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "setting WHERE `key` = 'config_order_status_id' AND `value` = '" . (int)$order_status_id . "' AND site_id != '0'");

	// 	return $query->row['total'];		
	// }	
}
?>