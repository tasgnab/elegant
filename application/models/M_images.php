<?php 

class M_images extends CI_Model{
	function insertImages($data = array()){
		$insert = $this->db->insert_batch('images',$data);
		return $this->db->insert_id();
	}

	function insertImageMaps($imageMapsData){
		$this->db->insert_batch('image_maps',$imageMapsData);
	}

	function getImageMapByProductID($id){
		$sql = "SELECT i.filename FROM image_maps im JOIN images i ON im.image=i.id AND im.disable=0 AND i.disable=0 WHERE im.product=? ORDER BY im.image asc";
		$query = $this->db->query($sql, array($id));
		return $query->result();
	}
	function deleteImageMapByProductID($id){
		$data=array(
			'disable'=>1
		);
		$this->db->where('product',$id);
		$this->db->update('image_maps',$data);
	}

}

?>