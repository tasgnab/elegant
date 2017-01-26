<?php 

class M_category extends CI_Model{	
	function getCategoryTree(){
		$allCategory = array();

		$this->db->select('id,category_name');
		$this->db->where(array('parent' => NULL, 'disable'=>0));
		$this->db->from('categories');
		$this->db->order_by('seq','asc');
		$query = $this->db->get();
		$parentCat = $query->result_array();

		if(!empty($parentCat)){
			foreach($parentCat as $parent){
				$this->db->select('id,category_name');
				$this->db->from('categories');
				$this->db->where(array('parent' => $parent['id'], 'disable'=>0));
				$this->db->order_by('seq','asc');
				$query = $this->db->get();
				$subCat = $query->result_array();

				$subCategory = array();
				if(!empty($subCat)){
					foreach($subCat as $sub){
						array_push($subCategory, $sub);
					}
				}
				$parent['subCat'] = $subCategory;
				array_push($allCategory, $parent);
			}
		}
		return $allCategory;
	}

	function insertCategoryMaps($categoryMapsData){
		$this->db->insert_batch('category_maps',$categoryMapsData);
	}

	function getCategoryMapByID($id){
		$this->db->select('categories.id, categories.category_name');
		$this->db->from('category_maps');
		$this->db->join('categories','category_maps.category=categories.id AND category_maps.disable=0 AND categories.disable=0');
		$this->db->where(array('category_maps.product'=> $id));
		$this->db->order_by('category_maps.category', 'ASC');
		return $this->db->get()->result();
	}

	function disableAllCategoryByID($categoryMaps){
		$data = array('disable'=>1,'update_by'=>$categoryMaps['update_by']);
		$this->db->where(array('product'=>$categoryMaps['product'], 'disable'=>0));
		$this->db->update('category_maps',$data);
	}

	function enableCategoryMaps($categoryMaps){
		$where = $categoryMaps;
		$where['disable'] = 1;
		unset($where['update_by']);

		$categoryMaps['disable']=0;
		$this->db->where($where);
		$this->db->update('category_maps',$categoryMaps);
	}

	function checkCategoryMaps($categoryMaps){
		$categoryMaps['disable']=1;
		unset($categoryMaps['update_by']);
		$this->db->where($categoryMaps);
		$this->db->from('category_maps');
		if($this->db->count_all_results()===0){
			return false;
		} else {
			return true;
		}
	}

	function deleteCategoryMapByID($id){
		$data=array(
			'disable'=>1
		);
		$this->db->where('product',$id);
		$this->db->update('category_maps',$data);
	}
}

?>