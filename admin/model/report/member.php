<?php
class ModelReportMember extends Model {
	public function getOrders($data = array()) { 
		$sql = "SELECT tmp.member_id, tmp.member, tmp.email, tmp.member_group, tmp.status, COUNT(tmp.order_id) AS orders, SUM(tmp.posts) AS posts, SUM(tmp.total) AS total FROM (SELECT o.order_id, c.member_id, CONCAT(o.firstname, ' ', o.lastname) AS member, o.email, cgd.name AS member_group, c.status, (SELECT SUM(op.quantity) FROM `" . DB_PREFIX . "order_post` op WHERE op.order_id = o.order_id GROUP BY op.order_id) AS posts, o.total FROM `" . DB_PREFIX . "order` o LEFT JOIN `" . DB_PREFIX . "member` c ON (o.member_id = c.member_id) LEFT JOIN " . DB_PREFIX . "member_group_description cgd ON (c.member_group_id = cgd.member_group_id) WHERE o.member_id > 0 AND cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND o.order_status_id > '0'";
		}
				
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		
		$sql .= ") tmp GROUP BY tmp.member_id ORDER BY total DESC";
				
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

	public function getTotalOrders($data = array()) {
		$sql = "SELECT COUNT(DISTINCT o.member_id) AS total FROM `" . DB_PREFIX . "order` o WHERE o.member_id > '0'";
		
		if (!empty($data['filter_order_status_id'])) {
			$sql .= " AND o.order_status_id = '" . (int)$data['filter_order_status_id'] . "'";
		} else {
			$sql .= " AND o.order_status_id > '0'";
		}
						
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(o.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(o.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
						
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	
	public function getRewardPoints($data = array()) { 
		$sql = "SELECT cr.member_id, CONCAT(c.firstname, ' ', c.lastname) AS member, c.email, cgd.name AS member_group, c.status, SUM(cr.points) AS points, COUNT(o.order_id) AS orders, SUM(o.total) AS total FROM " . DB_PREFIX . "member_reward cr LEFT JOIN `" . DB_PREFIX . "member` c ON (cr.member_id = c.member_id) LEFT JOIN " . DB_PREFIX . "member_group_description cgd ON (c.member_group_id = cgd.member_group_id) LEFT JOIN `" . DB_PREFIX . "order` o ON (cr.order_id = o.order_id) WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		if (!empty($data['filter_date_start'])) {
			$sql .= " AND DATE(cr.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= " AND DATE(cr.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
				
		$sql .= " GROUP BY cr.member_id ORDER BY points DESC";
				
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

	public function getTotalRewardPoints() {
		$sql = "SELECT COUNT(DISTINCT member_id) AS total FROM `" . DB_PREFIX . "member_reward`";
		
		$implode = array();
		
		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(cr.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(cr.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
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
		
		return $query->row['total'];
	}
	
	public function getCredit($data = array()) { 
		$sql = "SELECT ct.member_id, CONCAT(c.firstname, ' ', c.lastname) AS member, c.email, cgd.name AS member_group, c.status, SUM(ct.amount) AS total FROM " . DB_PREFIX . "member_transaction ct LEFT JOIN `" . DB_PREFIX . "member` c ON (ct.member_id = c.member_id) LEFT JOIN " . DB_PREFIX . "member_group_description cgd ON (c.member_group_id = cgd.member_group_id) WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		if (!empty($data['filter_date_start'])) {
			$sql .= "DATE(ct.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$sql .= "DATE(ct.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
				
		$sql .= " GROUP BY ct.member_id ORDER BY total DESC";
				
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

	public function getTotalCredit() {
		$sql = "SELECT COUNT(DISTINCT member_id) AS total FROM `" . DB_PREFIX . "member_transaction`";
		
		$implode = array();
		
		if (!empty($data['filter_date_start'])) {
			$implode[] = "DATE(cr.date_added) >= '" . $this->db->escape($data['filter_date_start']) . "'";
		}

		if (!empty($data['filter_date_end'])) {
			$implode[] = "DATE(cr.date_added) <= '" . $this->db->escape($data['filter_date_end']) . "'";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
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
		
		return $query->row['total'];
	}
}
?>