<?php
class ModelAccountAddress extends Model {
	public function addAddress($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "address 
			SET member_id = '" . (int)$this->member->getId() . "', 
			firstname = '" . $this->db->escape($data['firstname']) . "', 
			lastname = '" . $this->db->escape($data['lastname']) . "', 
			company = '" . $this->db->escape($data['company']) . "', 
			address_1 = '" . $this->db->escape($data['address_1']) . "', 
			address_2 = '" . $this->db->escape($data['address_2']) . "', 
			zipcode = '" . $this->db->escape($data['zipcode']) . "', 
			city = '" . $this->db->escape($data['city']) . "', 
			zone_id = '" . (int)$data['zone_id'] . "', 
			country_id = '" . (int)$data['country_id'] . "'");
		
		$address_id = $this->db->getLastId();
		
		if (!empty($data['default'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "member 
				SET address_id = '" . (int)$address_id . "' 
				WHERE member_id = '" . (int)$this->member->getId() . "'");
		}
		
		return $address_id;
	}
	
	public function editAddress($address_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "address 
			SET firstname = '" . $this->db->escape($data['firstname']) . "', 
			lastname = '" . $this->db->escape($data['lastname']) . "', 
			company = '" . $this->db->escape($data['company']) . "', 
			address_1 = '" . $this->db->escape($data['address_1']) . "', 
			address_2 = '" . $this->db->escape($data['address_2']) . "', 
			zipcode = '" . $this->db->escape($data['zipcode']) . "', 
			city = '" . $this->db->escape($data['city']) . "', 
			zone_id = '" . (int)$data['zone_id'] . "', 
			country_id = '" . (int)$data['country_id'] . "' 
			WHERE address_id  = '" . (int)$address_id . "' 
			AND member_id = '" . (int)$this->member->getId() . "'");
	
		if (!empty($data['default'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "member 
				SET address_id = '" . (int)$address_id . "' 
				WHERE member_id = '" . (int)$this->member->getId() . "'");
		}
	}
	
	public function deleteAddress($address_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "address 
			WHERE address_id = '" . (int)$address_id . "' 
			AND member_id = '" . (int)$this->member->getId() . "'");
	}	
	
	public function getAddress($address_id) {
		$address_query = $this->db->query("SELECT DISTINCT * 
			FROM " . DB_PREFIX . "address 
			WHERE address_id = '" . (int)$address_id . "' 
			AND member_id = '" . (int)$this->member->getId() . "'");
		
		if ($address_query->num_rows) {
			$country_query = $this->db->query("SELECT * 
				FROM `" . DB_PREFIX . "country` 
				WHERE country_id = '" . (int)$address_query->row['country_id'] . "'");
			
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
			
			$zone_query = $this->db->query("SELECT * 
				FROM `" . DB_PREFIX . "zone` 
				WHERE zone_id = '" . (int)$address_query->row['zone_id'] . "'");
			
			if ($zone_query->num_rows) {
				$zone = $zone_query->row['name'];
				$zone_code = $zone_query->row['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}		
			
			$address_data = array(
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
			
			return $address_data;
		} else {
			return false;	
		}
	}
	
	public function getAddresses() {
		$address_data = array();
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "address 
			WHERE member_id = '" . (int)$this->member->getId() . "'");
	
		foreach ($query->rows as $result) {
			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` 
				WHERE country_id = '" . (int)$result['country_id'] . "'");
			
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
			
			$zone_query = $this->db->query("SELECT * 
				FROM `" . DB_PREFIX . "zone` 
				WHERE zone_id = '" . (int)$result['zone_id'] . "'");
			
			if ($zone_query->num_rows) {
				$zone = $zone_query->row['name'];
				$zone_code = $zone_query->row['code'];
			} else {
				$zone = '';
				$zone_code = '';
			}		
		
			$address_data[$result['address_id']] = array(
				'address_id'     => $result['address_id'],
				'firstname'      => $result['firstname'],
				'lastname'       => $result['lastname'],
				'company'        => $result['company'],
				'address_1'      => $result['address_1'],
				'address_2'      => $result['address_2'],
				'zipcode'       => $result['zipcode'],
				'city'           => $result['city'],
				'zone_id'        => $result['zone_id'],
				'zone'           => $zone,
				'zone_code'      => $zone_code,
				'country_id'     => $result['country_id'],
				'country'        => $country,	
				'iso_code_2'     => $iso_code_2,
				'iso_code_3'     => $iso_code_3,
				'address_format' => $address_format
			);
		}		
		
		return $address_data;
	}	
	
	public function getTotalAddresses() {
		$query = $this->db->query("SELECT COUNT(*) AS total 
			FROM " . DB_PREFIX . "address 
			WHERE member_id = '" . (int)$this->member->getId() . "'");
	
		return $query->row['total'];
	}
}
?>