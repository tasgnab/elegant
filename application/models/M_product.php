<?php 

class M_product extends CI_Model{
	function insertProduct($data){
		$this->db->insert('products',$data);
		return $this->db->insert_id();
	}
	function insertStock($stockData){
		$this->db->insert_batch('stock',$stockData);
	}
	function getProductNewestList($limit){
		$this->db->select('products.id, products.name, products.description, products.price, products.brand, images.filename');
		$this->db->from('products');
		$this->db->join('images','products.image = images.id AND products.disable=0 AND images.disable=0');
		$this->db->limit($limit);
		$this->db->order_by('products.create_datetime', 'DESC');

		return $this->db->get()->result();
	}
	function getProductByID($id){
		$this->db->select('products.id, products.name, products.description, products.price, brand.brand, images.filename, products.rating, products.create_datetime');
		$this->db->from('products');
		$this->db->join('images', 'products.image = images.id AND products.disable=0 AND images.disable=0');
		$this->db->join('brand', 'products.brand=brand.id AND brand.disable=0');
		$this->db->where(array('products.id' => $id));
		
		return $this->db->get()->row();
	}
	function deleteProductByID($id){
		$data=array(
			'disable'=>1
		);
		$this->db->where('id',$id);
		$this->db->update('product',$data);
	}
	function getStockByID($id){
		$this->db->select('size,stock');
		$this->db->from('stock');
		$this->db->where(array('product' => $id, 'disable' => 0));
		return $this->db->get()->result();
	}
}

?>