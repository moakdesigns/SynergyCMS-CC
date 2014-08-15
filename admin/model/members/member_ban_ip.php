<?php
class ModelMembersMemberBanIp extends Model {
	public function addMemberBanIp($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "member_ban_ip` SET `ip` = '" . $this->db->escape($data['ip']) . "'");
	}

	public function editMemberBanIp($member_ban_ip_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "member_ban_ip` SET `ip` = '" . $this->db->escape($data['ip']) . "' WHERE member_ban_ip_id = '" . (int)$member_ban_ip_id . "'");
	}

	public function deleteMemberBanIp($member_ban_ip_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "member_ban_ip` WHERE member_ban_ip_id = '" . (int)$member_ban_ip_id . "'");
	}

	public function getMemberBanIp($member_ban_ip_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "member_ban_ip` WHERE member_ban_ip_id = '" . (int)$member_ban_ip_id . "'");

		return $query->row;
	}

	public function getMemberBanIps($data = array()) {
		$sql = "SELECT *, (SELECT COUNT(DISTINCT member_id) FROM `" . DB_PREFIX . "member_ip` ci WHERE ci.ip = cbi.ip) AS total FROM `" . DB_PREFIX . "member_ban_ip` cbi";

		$sql .= " ORDER BY `ip`";	

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

	public function getTotalMemberBanIps($data = array()) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "member_ban_ip`");

		return $query->row['total'];
	}
}
?>