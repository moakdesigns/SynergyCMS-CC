<?php
class ModelMembersMemberGroup extends Model {
	public function addMemberGroup($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "member_group SET 
			approval = '" . (int)$data['approval'] . "',  
			sort_order = '" . (int)$data['sort_order'] . "'");
	
		$member_group_id = $this->db->getLastId();
		
		foreach ($data['member_group_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "member_group_description SET 
				member_group_id = '" . (int)$member_group_id . "', 
				language_id = '" . (int)$language_id . "', 
				name = '" . $this->db->escape($value['name']) . "', 
				description = '" . $this->db->escape($value['description']) . "'");
		}	
	}
	
	public function editMemberGroup($member_group_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "member_group SET 
			approval = '" . (int)$data['approval'] . "', 
			sort_order = '" . (int)$data['sort_order'] . "' 
			WHERE member_group_id = '" . (int)$member_group_id . "'");
	
		$this->db->query("DELETE FROM " . DB_PREFIX . "member_group_description WHERE member_group_id = '" . (int)$member_group_id . "'");

		foreach ($data['member_group_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "member_group_description SET 
				member_group_id = '" . (int)$member_group_id . "', 
				language_id = '" . (int)$language_id . "', 
				name = '" . $this->db->escape($value['name']) . "', 
				description = '" . $this->db->escape($value['description']) . "'");
		}
	}
	
	public function deleteMemberGroup($member_group_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "member_group WHERE member_group_id = '" . (int)$member_group_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "member_group_description WHERE member_group_id = '" . (int)$member_group_id . "'");
		// $this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE member_group_id = '" . (int)$member_group_id . "'");
		// $this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE member_group_id = '" . (int)$member_group_id . "'");
		// $this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE member_group_id = '" . (int)$member_group_id . "'");
	}
	
	public function getMemberGroup($member_group_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "member_group cg LEFT JOIN " . DB_PREFIX . "member_group_description cgd ON (cg.member_group_id = cgd.member_group_id) WHERE cg.member_group_id = '" . (int)$member_group_id . "' AND cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->row;
	}
	
	public function getMemberGroups($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "member_group cg LEFT JOIN " . DB_PREFIX . "member_group_description cgd ON (cg.member_group_id = cgd.member_group_id) WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
		
		$sort_data = array(
			'cgd.name',
			'cg.sort_order'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY cgd.name";	
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
	
	public function getMemberGroupDescriptions($member_group_id) {
		$member_group_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "member_group_description WHERE member_group_id = '" . (int)$member_group_id . "'");
				
		foreach ($query->rows as $result) {
			$member_group_data[$result['language_id']] = array(
				'name'        => $result['name'],
				'description' => $result['description']
			);
		}
		
		return $member_group_data;
	}
		
	public function getTotalMemberGroups() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "member_group");
		
		return $query->row['total'];
	}
}
?>