<?php 

class M_brand extends CI_Model{
	function insertBrand($data){
		$this->db->insert('brand',$data);
		return $this->db->insert_id();
	}

	function checkBrand($where){
		$where['disable'] = 0;
		$result = $this->db->get_where('brand',$where);
		if ($result->num_rows() > 0){
			return $result->row()->id;
		}
		return false;
	}

	function getAllBrand(){
		$this->db->select('brand');
		$this->db->from('brand');
		$this->db->where('disable', 0);
		$this->db->order_by('brand','asc');
        $query = $this->db->get();
		return $query->result();
	}

}

?>