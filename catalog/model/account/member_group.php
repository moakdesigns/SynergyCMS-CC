<?php
class ModelAccountMemberGroup extends Model {
	public function getMemberGroup($member_group_id) {
		$query = $this->db->query("SELECT DISTINCT * 
			FROM " . DB_PREFIX . "member_group cg 
			LEFT JOIN " . DB_PREFIX . "member_group_description cgd ON (cg.member_group_id = cgd.member_group_id) 
			WHERE cg.member_group_id = '" . (int)$member_group_id . "' 
			AND cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		
		return $query->row;
	}

	public function getMemberGroups() {
		$query = $this->db->query("SELECT * 
			FROM " . DB_PREFIX . "member_group cg 
			LEFT JOIN " . DB_PREFIX . "member_group_description cgd ON (cg.member_group_id = cgd.member_group_id) 
			WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "' 
			ORDER BY cg.sort_order ASC, cgd.name ASC");
		
		return $query->rows;
	}
}
?>