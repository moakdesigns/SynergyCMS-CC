<?php
class ModelReportOnline extends Model {
	public function getMembersOnline($data = array()) { 
		$sql = "SELECT co.ip, co.member_id, co.url, co.referer, co.date_added FROM " . DB_PREFIX . "member_online co LEFT JOIN " . DB_PREFIX . "member c ON (co.member_id = c.member_id)";

		$implode = array();
				
		if (isset($data['filter_ip']) && !is_null($data['filter_ip'])) {
			$implode[] = "co.ip LIKE '" . $this->db->escape($data['filter_ip']) . "'";
		}
		
		if (isset($data['filter_member']) && !is_null($data['filter_member'])) {
			$implode[] = "co.member_id > 0 AND CONCAT(c.firstname, ' ', c.lastname) LIKE '" . $this->db->escape($data['filter_member']) . "'";
		}
				
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
				
		$sql .= " ORDER BY co.date_added DESC";
				
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

	public function getTotalMembersOnline($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "member_online` co LEFT JOIN " . DB_PREFIX . "member c ON (co.member_id = c.member_id)";
		
		$implode = array();
		
		if (isset($data['filter_ip']) && !is_null($data['filter_ip'])) {
			$implode[] = "co.ip LIKE '" . $this->db->escape($data['filter_ip']) . "'";
		}
		
		if (isset($data['filter_member']) && !is_null($data['filter_member'])) {
			$implode[] = "co.member_id > 0 AND CONCAT(c.firstname, ' ', c.lastname) LIKE '" . $this->db->escape($data['filter_member']) . "'";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
				
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}
?>