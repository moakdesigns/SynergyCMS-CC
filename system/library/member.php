<?php
class Member {
	private $member_id;
	private $firstname;
	private $lastname;
	private $email;
	private $telephone;
	private $fax;
	private $newsletter;
	private $member_group_id;
	private $address_id;
	
  	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->db = $registry->get('db');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');
				
		if (isset($this->session->data['member_id'])) { 
			$member_query = $this->db->query("SELECT * 
				FROM " . DB_PREFIX . "member 
				WHERE member_id = '" . (int)$this->session->data['member_id'] . "' 
				AND status = '1'");
			
			if ($member_query->num_rows) {
				$this->member_id = $member_query->row['member_id'];
				$this->firstname = $member_query->row['firstname'];
				$this->lastname = $member_query->row['lastname'];
				$this->email = $member_query->row['email'];
				$this->telephone = $member_query->row['telephone'];
				$this->fax = $member_query->row['fax'];
				$this->newsletter = $member_query->row['newsletter'];
				$this->member_group_id = $member_query->row['member_group_id'];
				$this->address_id = $member_query->row['address_id'];
							
      			$this->db->query("UPDATE " . DB_PREFIX . "member 
      				SET cart = '" . $this->db->escape(isset($this->session->data['cart']) ? serialize($this->session->data['cart']) : '') . "', 
      				ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' 
      				WHERE member_id = '" . (int)$this->member_id . "'");
			
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "member_ip 
					WHERE member_id = '" . (int)$this->session->data['member_id'] . "' 
					AND ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'");
				
				if (!$query->num_rows) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "member_ip 
						SET member_id = '" . (int)$this->session->data['member_id'] . "', 
						ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', 
						date_added = NOW()");
				}
			} else {
				$this->logout();
			}
  		}
	}
		
  	public function login($email, $password, $override = false) {
		if ($override) {
			$member_query = $this->db->query("SELECT * 
				FROM " . DB_PREFIX . "member 
				where LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "' 
				AND status = '1'");
		} else {
			$member_query = $this->db->query("SELECT * 
				FROM " . DB_PREFIX . "member 
				WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "' 
				AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($password) . "'))))) 
					OR password = '" . $this->db->escape(md5($password)) . "') 
				AND status = '1' 
				AND approved = '1'");
		}
		
		if ($member_query->num_rows) {
			$this->session->data['member_id'] = $member_query->row['member_id'];	
		    
			if ($member_query->row['cart'] && is_string($member_query->row['cart'])) {
				$cart = unserialize($member_query->row['cart']);
				
				foreach ($cart as $key => $value) {
					if (!array_key_exists($key, $this->session->data['cart'])) {
						$this->session->data['cart'][$key] = $value;
					} else {
						$this->session->data['cart'][$key] += $value;
					}
				}			
			}

			// if ($member_query->row['wishlist'] && is_string($member_query->row['wishlist'])) {
			// 	if (!isset($this->session->data['wishlist'])) {
			// 		$this->session->data['wishlist'] = array();
			// 	}
								
			// 	$wishlist = unserialize($member_query->row['wishlist']);
			
			// 	foreach ($wishlist as $product_id) {
			// 		if (!in_array($product_id, $this->session->data['wishlist'])) {
			// 			$this->session->data['wishlist'][] = $product_id;
			// 		}
			// 	}			
			// }
									
			$this->member_id = $member_query->row['member_id'];
			$this->firstname = $member_query->row['firstname'];
			$this->lastname = $member_query->row['lastname'];
			$this->email = $member_query->row['email'];
			$this->telephone = $member_query->row['telephone'];
			$this->fax = $member_query->row['fax'];
			$this->newsletter = $member_query->row['newsletter'];
			$this->member_group_id = $member_query->row['member_group_id'];
			$this->address_id = $member_query->row['address_id'];
          	
			$this->db->query("UPDATE " . DB_PREFIX . "member 
				SET ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' 
				WHERE member_id = '" . (int)$this->member_id . "'");
			
	  		return true;
    	} else {
      		return false;
    	}
  	}
  	
	public function logout() {
		$this->db->query("UPDATE " . DB_PREFIX . "member 
			SET cart = '" . $this->db->escape(isset($this->session->data['cart']) ? serialize($this->session->data['cart']) : '') . "',  
			WHERE member_id = '" . (int)$this->member_id . "'");
		
		unset($this->session->data['member_id']);

		$this->member_id = '';
		$this->firstname = '';
		$this->lastname = '';
		$this->email = '';
		$this->telephone = '';
		$this->fax = '';
		$this->newsletter = '';
		$this->member_group_id = '';
		$this->address_id = '';
  	}
  
  	public function isLogged() {
    	return $this->member_id;
  	}

  	public function getId() {
    	return $this->member_id;
  	}
      
  	public function getFirstName() {
		return $this->firstname;
  	}
  
  	public function getLastName() {
		return $this->lastname;
  	}
  
  	public function getEmail() {
		return $this->email;
  	}
  
  	public function getTelephone() {
		return $this->telephone;
  	}
  
  	public function getFax() {
		return $this->fax;
  	}
	
  	public function getNewsletter() {
		return $this->newsletter;	
  	}

  	public function getMemberGroupId() {
		return $this->member_group_id;	
  	}
	
  	public function getAddressId() {
		return $this->address_id;	
  	}
	
  // 	public function getBalance() {
		// $query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "member_transaction WHERE member_id = '" . (int)$this->member_id . "'");
	
		// return $query->row['total'];
  // 	}	
		
  // 	public function getRewardPoints() {
		// $query = $this->db->query("SELECT SUM(points) AS total FROM " . DB_PREFIX . "member_reward WHERE member_id = '" . (int)$this->member_id . "'");
	
		// return $query->row['total'];	
  // 	}	
}
?>