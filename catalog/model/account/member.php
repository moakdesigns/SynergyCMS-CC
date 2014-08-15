<?php
class ModelAccountMember extends Model {
	public function addMember($data) {
		if (isset($data['member_group_id']) && is_array($this->config->get('config_member_group_display')) && in_array($data['member_group_id'], $this->config->get('config_member_group_display'))) {
			$member_group_id = $data['member_group_id'];
		} else {
			$member_group_id = $this->config->get('config_member_group_id');
		}

		$this->load->model('account/member_group');

		$member_group_info = $this->model_account_member_group->getMemberGroup($member_group_id);

		$this->db->query("INSERT INTO " . DB_PREFIX . "member 
			SET site_id = '" . (int)$this->config->get('config_site_id') . "', 
			firstname = '" . $this->db->escape($data['firstname']) . "', 
			lastname = '" . $this->db->escape($data['lastname']) . "', 
			email = '" . $this->db->escape($data['email']) . "', 
			telephone = '" . $this->db->escape($data['telephone']) . "', 
			fax = '" . $this->db->escape($data['fax']) . "', 
			salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', 
			password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', 
			newsletter = '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 0) . "', 
			member_group_id = '" . (int)$member_group_id . "', 
			ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', 
			status = '1', 
			approved = '" . (int)!$member_group_info['approval'] . "', 
			date_added = NOW()");

		$member_id = $this->db->getLastId();

		$this->db->query("INSERT INTO " . DB_PREFIX . "address 
			SET member_id = '" . (int)$member_id . "', 
			firstname = '" . $this->db->escape($data['firstname']) . "', 
			lastname = '" . $this->db->escape($data['lastname']) . "', 
			company = '" . $this->db->escape($data['company']) . "', 
			address_1 = '" . $this->db->escape($data['address_1']) . "', 
			address_2 = '" . $this->db->escape($data['address_2']) . "', 
			city = '" . $this->db->escape($data['city']) . "', 
			zipcode = '" . $this->db->escape($data['zipcode']) . "', 
			country_id = '" . (int)$data['country_id'] . "', 
			zone_id = '" . (int)$data['zone_id'] . "'");

		$address_id = $this->db->getLastId();

		$this->db->query("UPDATE " . DB_PREFIX . "member 
			SET address_id = '" . (int)$address_id . "' 
			WHERE member_id = '" . (int)$member_id . "'");

		$this->language->load('mail/member');

		$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));

		$message = sprintf($this->language->get('text_welcome'), $this->config->get('config_name')) . "\n\n";

		if (!$member_group_info['approval']) {
			$message .= $this->language->get('text_login') . "\n";
		} else {
			$message .= $this->language->get('text_approval') . "\n";
		}

		$message .= $this->url->link('account/login', '', 'SSL') . "\n\n";
		$message .= $this->language->get('text_services') . "\n\n";
		$message .= $this->language->get('text_thanks') . "\n";
		$message .= $this->config->get('config_name');

		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->hostname = $this->config->get('config_smtp_host');
		$mail->username = $this->config->get('config_smtp_username');
		$mail->password = $this->config->get('config_smtp_password');
		$mail->port = $this->config->get('config_smtp_port');
		$mail->timeout = $this->config->get('config_smtp_timeout');				
		$mail->setTo($data['email']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
		$mail->send();

		// Send to main admin email if new account email is enabled
		if ($this->config->get('config_account_mail')) {
			$message  = $this->language->get('text_signup') . "\n\n";
			$message .= $this->language->get('text_website') . ' ' . $this->config->get('config_name') . "\n";
			$message .= $this->language->get('text_firstname') . ' ' . $data['firstname'] . "\n";
			$message .= $this->language->get('text_lastname') . ' ' . $data['lastname'] . "\n";
			$message .= $this->language->get('text_member_group') . ' ' . $member_group_info['name'] . "\n";

			if ($data['company']) {
				$message .= $this->language->get('text_company') . ' '  . $data['company'] . "\n";
			}

			$message .= $this->language->get('text_email') . ' '  .  $data['email'] . "\n";
			$message .= $this->language->get('text_telephone') . ' ' . $data['telephone'] . "\n";

			$mail->setTo($this->config->get('config_email'));
			$mail->setSubject(html_entity_decode($this->language->get('text_new_member'), ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();

			// Send to additional alert emails if new account email is enabled
			$emails = explode(',', $this->config->get('config_alert_emails'));

			foreach ($emails as $email) {
				if (strlen($email) > 0 && preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
		}
	}

	public function editMember($data) {
		$this->db->query("UPDATE " . DB_PREFIX . "member 
			SET firstname = '" . $this->db->escape($data['firstname']) . "', 
			lastname = '" . $this->db->escape($data['lastname']) . "', 
			email = '" . $this->db->escape($data['email']) . "', 
			telephone = '" . $this->db->escape($data['telephone']) . "', 
			fax = '" . $this->db->escape($data['fax']) . "' 
			WHERE member_id = '" . (int)$this->member->getId() . "'");
	}

	public function editPassword($email, $password) {
		$this->db->query("UPDATE " . DB_PREFIX . "member 
			SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', 
			password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "' 
			WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
	}

	public function editNewsletter($newsletter) {
		$this->db->query("UPDATE " . DB_PREFIX . "member 
			SET newsletter = '" . (int)$newsletter . "' 
			WHERE member_id = '" . (int)$this->member->getId() . "'");
	}

	public function getMember($member_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "member WHERE member_id = '" . (int)$member_id . "'");

		return $query->row;
	}

	public function getMemberByEmail($email) {
		$query = $this->db->query("SELECT * 
			FROM " . DB_PREFIX . "member 
			WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}

	public function getMemberByToken($token) {
		$query = $this->db->query("SELECT * 
			FROM " . DB_PREFIX . "member 
			WHERE token = '" . $this->db->escape($token) . "' 
			AND token != ''");

		$this->db->query("UPDATE " . DB_PREFIX . "member SET token = ''");

		return $query->row;
	}

	public function getMembers($data = array()) {
		$sql = "SELECT *, 
		CONCAT(c.firstname, ' ', c.lastname) AS name, 
		cg.name AS member_group 
		FROM " . DB_PREFIX . "member c 
		LEFT JOIN " . DB_PREFIX . "member_group cg ON (c.member_group_id = cg.member_group_id) ";

		$implode = array();

		if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
			$implode[] = "LCASE(CONCAT(c.firstname, ' ', c.lastname)) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		}

		if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
			$implode[] = "LCASE(c.email) = '" . $this->db->escape(utf8_strtolower($data['filter_email'])) . "'";
		}

		if (isset($data['filter_member_group_id']) && !is_null($data['filter_member_group_id'])) {
			$implode[] = "cg.member_group_id = '" . $this->db->escape($data['filter_member_group_id']) . "'";
		}	

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "c.status = '" . (int)$data['filter_status'] . "'";
		}	

		if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
			$implode[] = "c.approved = '" . (int)$data['filter_approved'] . "'";
		}	

		if (isset($data['filter_ip']) && !is_null($data['filter_ip'])) {
			$implode[] = "c.member_id IN (SELECT member_id FROM " . DB_PREFIX . "member_ip WHERE ip = '" . $this->db->escape($data['filter_ip']) . "')";
		}	

		if (isset($data['filter_date_added']) && !is_null($data['filter_date_added'])) {
			$implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'name',
			'c.email',
			'member_group',
			'c.status',
			'c.ip',
			'c.date_added'
		);	

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY name";	
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

	public function getTotalMembersByEmail($email) {
		$query = $this->db->query("SELECT COUNT(*) AS total 
			FROM " . DB_PREFIX . "member 
			WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row['total'];
	}

	public function getIps($member_id) {
		$query = $this->db->query("SELECT * 
			FROM `" . DB_PREFIX . "member_ip` 
			WHERE member_id = '" . (int)$member_id . "'");

		return $query->rows;
	}	

	public function isBanIp($ip) {
		$query = $this->db->query("SELECT * 
			FROM `" . DB_PREFIX . "member_ban_ip` 
			WHERE ip = '" . $this->db->escape($ip) . "'");

		return $query->num_rows;
	}	
}
?>
