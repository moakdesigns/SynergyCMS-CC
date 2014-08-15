<?php
class ModelToolOnline extends Model {	
	public function whosonline($ip, $member_id, $url, $referer) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "member_online` 
			WHERE (UNIX_TIMESTAMP(`date_added`) + 3600) < UNIX_TIMESTAMP(NOW())");

		$this->db->query("REPLACE INTO `" . DB_PREFIX . "member_online` 
			SET `ip` = '" . $this->db->escape($ip) . "', 
			`member_id` = '" . (int)$member_id . "', 
			`url` = '" . $this->db->escape($url) . "', 
			`referer` = '" . $this->db->escape($referer) . "', 
			`date_added` = NOW()");
	}
}
?>