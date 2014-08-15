<?php
class ModelMembersMember extends Model {
	public function addMember($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "member SET 
			firstname = '" . $this->db->escape($data['firstname']) . "', 
			lastname = '" . $this->db->escape($data['lastname']) . "', 
			email = '" . $this->db->escape($data['email']) . "', 
			telephone = '" . $this->db->escape($data['telephone']) . "', 
			fax = '" . $this->db->escape($data['fax']) . "', 
			newsletter = '" . (int)$data['newsletter'] . "', 
			member_group_id = '" . (int)$data['member_group_id'] . "', 
			salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', 
			password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', 
			status = '" . (int)$data['status'] . "', 
			date_added = NOW()");

		$member_id = $this->db->getLastId();

		if (isset($data['address'])) {
			foreach ($data['address'] as $address) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "address SET 
					member_id = '" . (int)$member_id . "', 
					firstname = '" . $this->db->escape($address['firstname']) . "', 
					lastname = '" . $this->db->escape($address['lastname']) . "', 
					company = '" . $this->db->escape($address['company']) . "',  
					address_1 = '" . $this->db->escape($address['address_1']) . "', 
					address_2 = '" . $this->db->escape($address['address_2']) . "', 
					city = '" . $this->db->escape($address['city']) . "', 
					zipcode = '" . $this->db->escape($address['zipcode']) . "', 
					country_id = '" . (int)$address['country_id'] . "', 
					zone_id = '" . (int)$address['zone_id'] . "'");

				if (isset($address['default'])) {
					$address_id = $this->db->getLastId();

					$this->db->query("UPDATE " . DB_PREFIX . "member SET address_id = '" . $address_id . "' WHERE member_id = '" . (int)$member_id . "'");
				}
			}
		}
	}

	public function editMember($member_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "member SET 
			firstname = '" . $this->db->escape($data['firstname']) . "', 
			lastname = '" . $this->db->escape($data['lastname']) . "', 
			email = '" . $this->db->escape($data['email']) . "', 
			telephone = '" . $this->db->escape($data['telephone']) . "', 
			fax = '" . $this->db->escape($data['fax']) . "', 
			newsletter = '" . (int)$data['newsletter'] . "', 
			member_group_id = '" . (int)$data['member_group_id'] . "', 
			status = '" . (int)$data['status'] . "' 
			WHERE member_id = '" . (int)$member_id . "'");

		if ($data['password']) {
			$this->db->query("UPDATE " . DB_PREFIX . "member SET 
				salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', 
				password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "' 
				WHERE member_id = '" . (int)$member_id . "'");
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE member_id = '" . (int)$member_id . "'");

		if (isset($data['address'])) {
			foreach ($data['address'] as $address) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "address SET 
					address_id = '" . (int)$address['address_id'] . "', 
					member_id = '" . (int)$member_id . "', 
					firstname = '" . $this->db->escape($address['firstname']) . "', 
					lastname = '" . $this->db->escape($address['lastname']) . "', 
					company = '" . $this->db->escape($address['company']) . "', 
					address_1 = '" . $this->db->escape($address['address_1']) . "', 
					address_2 = '" . $this->db->escape($address['address_2']) . "', 
					city = '" . $this->db->escape($address['city']) . "', 
					zipcode = '" . $this->db->escape($address['zipcode']) . "', 
					country_id = '" . (int)$address['country_id'] . "', 
					zone_id = '" . (int)$address['zone_id'] . "'");

				if (isset($address['default'])) {
					$address_id = $this->db->getLastId();

					$this->db->query("UPDATE " . DB_PREFIX . "member SET address_id = '" . (int)$address_id . "' WHERE member_id = '" . (int)$member_id . "'");
				}
			}
		}
	}

	public function editToken($member_id, $token) {
		$this->db->query("UPDATE " . DB_PREFIX . "member SET token = '" . $this->db->escape($token) . "' WHERE member_id = '" . (int)$member_id . "'");
	}

	public function deleteMember($member_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "member WHERE member_id = '" . (int)$member_id . "'");
		// $this->db->query("DELETE FROM " . DB_PREFIX . "member_reward WHERE member_id = '" . (int)$member_id . "'");
		// $this->db->query("DELETE FROM " . DB_PREFIX . "member_transaction WHERE member_id = '" . (int)$member_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "member_ip WHERE member_id = '" . (int)$member_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE member_id = '" . (int)$member_id . "'");
	}

	public function getMember($member_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "member WHERE member_id = '" . (int)$member_id . "'");

		return $query->row;
	}

	public function getMemberByEmail($email) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "member WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}

	public function getMembers($data = array()) {
		$sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, cgd.name AS member_group FROM " . DB_PREFIX . "member c LEFT JOIN " . DB_PREFIX . "member_group_description cgd ON (c.member_group_id = cgd.member_group_id) WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (isset($data['filter_newsletter']) && !is_null($data['filter_newsletter'])) {
			$implode[] = "c.newsletter = '" . (int)$data['filter_newsletter'] . "'";
		}	

		if (!empty($data['filter_member_group_id'])) {
			$implode[] = "c.member_group_id = '" . (int)$data['filter_member_group_id'] . "'";
		}	

		if (!empty($data['filter_ip'])) {
			$implode[] = "c.member_id IN (SELECT member_id FROM " . DB_PREFIX . "member_ip WHERE ip = '" . $this->db->escape($data['filter_ip']) . "')";
		}	

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "c.status = '" . (int)$data['filter_status'] . "'";
		}	

		if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
			$implode[] = "c.approved = '" . (int)$data['filter_approved'] . "'";
		}	

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'name',
			'c.email',
			'member_group',
			'c.status',
			'c.approved',
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

	public function approve($member_id) {
		$member_info = $this->getMember($member_id);

		if ($member_info) {
			$this->db->query("UPDATE " . DB_PREFIX . "member SET approved = '1' WHERE member_id = '" . (int)$member_id . "'");

			$this->language->load('mail/member');

			$this->load->model('setting/site');

			$site_info = $this->model_setting_site->getSite($member_info['site_id']);

			if ($site_info) {
				$site_name = $site_info['name'];
				$site_url = $site_info['url'] . 'index.php?route=account/login';
			} else {
				$site_name = $this->config->get('config_name');
				$site_url = HTTP_CATALOG . 'index.php?route=account/login';
			}

			$message  = sprintf($this->language->get('text_approve_welcome'), $site_name) . "\n\n";
			$message .= $this->language->get('text_approve_login') . "\n";
			$message .= $site_url . "\n\n";
			$message .= $this->language->get('text_approve_services') . "\n\n";
			$message .= $this->language->get('text_approve_thanks') . "\n";
			$message .= $site_name;

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');							
			$mail->setTo($member_info['email']);
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender($site_name);
			$mail->setSubject(html_entity_decode(sprintf($this->language->get('text_approve_subject'), $site_name), ENT_QUOTES, 'UTF-8'));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
		}		
	}

	public function getAddress($address_id) {
		$address_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$address_id . "'");

		if ($address_query->num_rows) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$address_query->row['country_id'] . "'");

			if ($country_query->num_rows) {
				$country = $country_query->row['name'];
				$iso_code_2 = $country_query->row['iso_code_2'];
				$iso_code_3 = $country_query->row['iso_code_3'];
				$address_format = $country_query->row['address_format'];
			} else {
				$country = '';
				$iso_code_2 = '';
				$iso_code_3 = '';	
				$address_format = '';
			}

			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$address_query->row['zone_id'] . "'");

			if ($zone_query->num_rows) {
				$zone = $zone_query->row['name'];
				$zone_code = $zone_query->row['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}		

			return array(
				'address_id'     => $address_query->row['address_id'],
				'member_id'    => $address_query->row['member_id'],
				'firstname'      => $address_query->row['firstname'],
				'lastname'       => $address_query->row['lastname'],
				'company'        => $address_query->row['company'],
				'address_1'      => $address_query->row['address_1'],
				'address_2'      => $address_query->row['address_2'],
				'zipcode'       => $address_query->row['zipcode'],
				'city'           => $address_query->row['city'],
				'zone_id'        => $address_query->row['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => $address_query->row['country_id'],
				'country'        => $country,	
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format
			);
		}
	}

	public function getAddresses($member_id) {
		$address_data = array();

		$query = $this->db->query("SELECT address_id FROM " . DB_PREFIX . "address WHERE member_id = '" . (int)$member_id . "'");

		foreach ($query->rows as $result) {
			$address_info = $this->getAddress($result['address_id']);

			if ($address_info) {
				$address_data[$result['address_id']] = $address_info;
			}
		}		

		return $address_data;
	}	

	public function getTotalMembers($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "member";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_email'])) {
			$implode[] = "email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
		}

		if (isset($data['filter_newsletter']) && !is_null($data['filter_newsletter'])) {
			$implode[] = "newsletter = '" . (int)$data['filter_newsletter'] . "'";
		}

		if (!empty($data['filter_member_group_id'])) {
			$implode[] = "member_group_id = '" . (int)$data['filter_member_group_id'] . "'";
		}	

		if (!empty($data['filter_ip'])) {
			$implode[] = "member_id IN (SELECT member_id FROM " . DB_PREFIX . "member_ip WHERE ip = '" . $this->db->escape($data['filter_ip']) . "')";
		}	

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$implode[] = "status = '" . (int)$data['filter_status'] . "'";
		}			

		if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
			$implode[] = "approved = '" . (int)$data['filter_approved'] . "'";
		}		

		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getTotalMembersAwaitingApproval() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "member WHERE status = '0' OR approved = '0'");

		return $query->row['total'];
	}

	public function getTotalAddressesByMemberId($member_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE member_id = '" . (int)$member_id . "'");

		return $query->row['total'];
	}

	public function getTotalAddressesByCountryId($country_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE country_id = '" . (int)$country_id . "'");

		return $query->row['total'];
	}	

	public function getTotalAddressesByZoneId($zone_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "address WHERE zone_id = '" . (int)$zone_id . "'");

		return $query->row['total'];
	}

	public function getTotalMembersByMemberGroupId($member_group_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "member WHERE member_group_id = '" . (int)$member_group_id . "'");

		return $query->row['total'];
	}

	// public function addHistory($member_id, $comment) {
	// 	$this->db->query("INSERT INTO " . DB_PREFIX . "member_history SET member_id = '" . (int)$member_id . "', comment = '" . $this->db->escape(strip_tags($comment)) . "', date_added = NOW()");
	// }	

	// public function getHistories($member_id, $start = 0, $limit = 10) { 
	// 	if ($start < 0) {
	// 		$start = 0;
	// 	}

	// 	if ($limit < 1) {
	// 		$limit = 10;
	// 	}	

	// 	$query = $this->db->query("SELECT comment, date_added FROM " . DB_PREFIX . "member_history WHERE member_id = '" . (int)$member_id . "' ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

	// 	return $query->rows;
	// }	

	// public function getTotalHistories($member_id) {
	// 	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "member_history WHERE member_id = '" . (int)$member_id . "'");

	// 	return $query->row['total'];
	// }	

	// public function addTransaction($member_id, $description = '', $amount = '', $order_id = 0) {
	// 	$member_info = $this->getMember($member_id);

	// 	if ($member_info) { 
	// 		$this->db->query("INSERT INTO " . DB_PREFIX . "member_transaction SET member_id = '" . (int)$member_id . "', order_id = '" . (int)$order_id . "', description = '" . $this->db->escape($description) . "', amount = '" . (float)$amount . "', date_added = NOW()");

	// 		$this->language->load('mail/member');

	// 		if ($member_info['site_id']) {
	// 			$this->load->model('setting/site');

	// 			$site_info = $this->model_setting_site->getSite($member_info['site_id']);

	// 			if ($site_info) {
	// 				$site_name = $site_info['name'];
	// 			} else {
	// 				$site_name = $this->config->get('config_name');
	// 			}	
	// 		} else {
	// 			$site_name = $this->config->get('config_name');
	// 		}

	// 		$message  = sprintf($this->language->get('text_transaction_received'), $this->currency->format($amount, $this->config->get('config_currency'))) . "\n\n";
	// 		$message .= sprintf($this->language->get('text_transaction_total'), $this->currency->format($this->getTransactionTotal($member_id)));

	// 		$mail = new Mail();
	// 		$mail->protocol = $this->config->get('config_mail_protocol');
	// 		$mail->parameter = $this->config->get('config_mail_parameter');
	// 		$mail->hostname = $this->config->get('config_smtp_host');
	// 		$mail->username = $this->config->get('config_smtp_username');
	// 		$mail->password = $this->config->get('config_smtp_password');
	// 		$mail->port = $this->config->get('config_smtp_port');
	// 		$mail->timeout = $this->config->get('config_smtp_timeout');
	// 		$mail->setTo($member_info['email']);
	// 		$mail->setFrom($this->config->get('config_email'));
	// 		$mail->setSender($site_name);
	// 		$mail->setSubject(html_entity_decode(sprintf($this->language->get('text_transaction_subject'), $this->config->get('config_name')), ENT_QUOTES, 'UTF-8'));
	// 		$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
	// 		$mail->send();
	// 	}
	// }

	// public function deleteTransaction($order_id) {
	// 	$this->db->query("DELETE FROM " . DB_PREFIX . "member_transaction WHERE order_id = '" . (int)$order_id . "'");
	// }

	// public function getTransactions($member_id, $start = 0, $limit = 10) {
	// 	if ($start < 0) {
	// 		$start = 0;
	// 	}

	// 	if ($limit < 1) {
	// 		$limit = 10;
	// 	}	

	// 	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "member_transaction WHERE member_id = '" . (int)$member_id . "' ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

	// 	return $query->rows;
	// }

	// public function getTotalTransactions($member_id) {
	// 	$query = $this->db->query("SELECT COUNT(*) AS total  FROM " . DB_PREFIX . "member_transaction WHERE member_id = '" . (int)$member_id . "'");

	// 	return $query->row['total'];
	// }

	// public function getTransactionTotal($member_id) {
	// 	$query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "member_transaction WHERE member_id = '" . (int)$member_id . "'");

	// 	return $query->row['total'];
	// }

	// public function getTotalTransactionsByOrderId($order_id) {
	// 	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "member_transaction WHERE order_id = '" . (int)$order_id . "'");

	// 	return $query->row['total'];
	// }	

	// public function addReward($member_id, $description = '', $points = '', $order_id = 0) {
	// 	$member_info = $this->getMember($member_id);

	// 	if ($member_info) { 
	// 		$this->db->query("INSERT INTO " . DB_PREFIX . "member_reward SET member_id = '" . (int)$member_id . "', order_id = '" . (int)$order_id . "', points = '" . (int)$points . "', description = '" . $this->db->escape($description) . "', date_added = NOW()");

	// 		$this->language->load('mail/member');

	// 		if ($order_id) {
	// 			$this->load->model('members/order');

	// 			$order_info = $this->model_members_order->getOrder($order_id);

	// 			if ($order_info) {
	// 				$site_name = $order_info['site_name'];
	// 			} else {
	// 				$site_name = $this->config->get('config_name');
	// 			}	
	// 		} else {
	// 			$site_name = $this->config->get('config_name');
	// 		}		

	// 		$message  = sprintf($this->language->get('text_reward_received'), $points) . "\n\n";
	// 		$message .= sprintf($this->language->get('text_reward_total'), $this->getRewardTotal($member_id));

	// 		$mail = new Mail();
	// 		$mail->protocol = $this->config->get('config_mail_protocol');
	// 		$mail->parameter = $this->config->get('config_mail_parameter');
	// 		$mail->hostname = $this->config->get('config_smtp_host');
	// 		$mail->username = $this->config->get('config_smtp_username');
	// 		$mail->password = $this->config->get('config_smtp_password');
	// 		$mail->port = $this->config->get('config_smtp_port');
	// 		$mail->timeout = $this->config->get('config_smtp_timeout');
	// 		$mail->setTo($member_info['email']);
	// 		$mail->setFrom($this->config->get('config_email'));
	// 		$mail->setSender($site_name);
	// 		$mail->setSubject(html_entity_decode(sprintf($this->language->get('text_reward_subject'), $site_name), ENT_QUOTES, 'UTF-8'));
	// 		$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
	// 		$mail->send();
	// 	}
	// }

	// public function deleteReward($order_id) {
	// 	$this->db->query("DELETE FROM " . DB_PREFIX . "member_reward WHERE order_id = '" . (int)$order_id . "'");
	// }

	// public function getRewards($member_id, $start = 0, $limit = 10) {
	// 	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "member_reward WHERE member_id = '" . (int)$member_id . "' ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);

	// 	return $query->rows;
	// }

	// public function getTotalRewards($member_id) {
	// 	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "member_reward WHERE member_id = '" . (int)$member_id . "'");

	// 	return $query->row['total'];
	// }

	// public function getRewardTotal($member_id) {
	// 	$query = $this->db->query("SELECT SUM(points) AS total FROM " . DB_PREFIX . "member_reward WHERE member_id = '" . (int)$member_id . "'");

	// 	return $query->row['total'];
	// }		

	// public function getTotalMemberRewardsByOrderId($order_id) {
	// 	$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "member_reward WHERE order_id = '" . (int)$order_id . "'");

	// 	return $query->row['total'];
	// }

	public function getIpsByMemberId($member_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "member_ip WHERE member_id = '" . (int)$member_id . "'");

		return $query->rows;
	}	

	public function getTotalMembersByIp($ip) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "member_ip WHERE ip = '" . $this->db->escape($ip) . "'");

		return $query->row['total'];
	}

	public function addBanIp($ip) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "member_ban_ip` SET `ip` = '" . $this->db->escape($ip) . "'");
	}

	public function removeBanIp($ip) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "member_ban_ip` WHERE `ip` = '" . $this->db->escape($ip) . "'");
	}

	public function getTotalBanIpsByIp($ip) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "member_ban_ip` WHERE `ip` = '" . $this->db->escape($ip) . "'");

		return $query->row['total'];
	}	
}
?>