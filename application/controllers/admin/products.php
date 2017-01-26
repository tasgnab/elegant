<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class products extends my_controller {

	function  __construct() {
		parent::__construct();
		$this->load->model('m_images');
		$this->load->model('m_category');
		$this->load->model('m_brand');
		$this->load->model('m_product');
		$this->load->library('upload');
	}

	function product_new(){
		if ($this->isAdmin()){
			$data=$this->session->flashdata('data');
			$data['productList'] = $this->m_product->getProductNewestList(6);
			$data['allCategory'] = $this->m_category->getCategoryTree();
			$data['allBrand'] = $this->m_brand->getAllBrand();
			$this->load->view('admin/product_new',$data);
		} else {
			redirect(base_url());
		}
	}

	function product_view($id){
		if ($this->isAdmin()){
			$product = $this->m_product->getProductByID($id);
			if (count($product)===0){
				$this->load->view('404');
			}
			$data['product'] = $product;
			$data['imageList'] = $this->m_images->getImageMapByProductID($id);
			$data['categoryList'] = $this->m_category->getCategoryMapByID($id);
			$data['stockList'] = $this->m_product->getStockByID($id);
			$data['allCategory'] = $this->m_category->getCategoryTree();
			$data['allBrand'] = $this->m_brand->getAllBrand();
			$this->load->view('admin/product_view',$data);
		} else {
			redirect(base_url());
		}
	}

	function update_category(){
		if ($this->isAdmin()){
			$idProduct=$this->input->post('product');
			$category = explode("#", $this->input->post('category'));
			for ($i=0; $i<count($category)-1; $i++){
				$categoryMapsData[$i]['category'] =  $category[$i];
				$categoryMapsData[$i]['product'] = $idProduct;
				$categoryMapsData[$i]['update_by'] = 'sysadmin';
			}

			$this->db->trans_start();
			$this->m_category->disableAllCategoryByID($categoryMapsData[0]);
			$categoryMapsDataInsert=array();
			foreach ($categoryMapsData as $categoryMap) {
				if($this->m_category->checkCategoryMaps($categoryMap)){
					$this->m_category->enableCategoryMaps($categoryMap);
				} else {
					array_push($categoryMapsDataInsert,$categoryMap);
				}
			}
			if(count($categoryMapsDataInsert)>0){
				$this->m_category->insertCategoryMaps($categoryMapsDataInsert);
			}
			$this->db->trans_complete();
			if ($this->db->trans_status() === FALSE){
				$message = "Failed to Save Category.";
				$result = 0;
			} else {
				$message = "Category saved";
				$result = 1;
			}
			$data = array(
				'message' => $message,
				'result' => $result
			);

			echo json_encode($data);
		} else {
			redirect(base_url());
		}
	}

	function doInputProduct(){
		if ($this->isAdmin()){
			log_message('info', "==#	start doInputProduct	#==");
			$control = $this->session->userdata('control');
			$timestamp = $this->input->post('name');
			$result = 1;
			if ($control['timestamp']!==$timestamp){
				//start db transaction
				$this->db->trans_start();
				//processing image files
				$uploadStatus = true;
				$files = $_FILES;
				$cpt = count($files['images']['name']);
				for($i=0; $i<$cpt; $i++){
					$_FILES['images']['name']= $files['images']['name'][$i];
					$_FILES['images']['type']= $files['images']['type'][$i];
					$_FILES['images']['tmp_name']= $files['images']['tmp_name'][$i];
					$_FILES['images']['error']= $files['images']['error'][$i];
					$_FILES['images']['size']= $files['images']['size'][$i];
					$this->upload->initialize(
						array(
							'upload_path' 	=> 'images/product/',
							'allowed_types' => 'jpeg|jpg|png',
							'overwrite' => FALSE,
							'encrypt_name' => TRUE,
							'max_size' => 100
							// 'max_width' => 768,
							// 'max_height' => 1024
						)
					);
					if ($this->upload->do_upload('images')){
						$fileData = $this->upload->data();
						$imageData[$i]['filename'] = $fileData['file_name'];
						$imageData[$i]['update_by'] = 'sysadmin';
					} else {
						$result = 2;
						$uploadStatus = false;
						$message = $this->upload->display_errors();
					}
				}
				log_message('info', 'upload status :'.$uploadStatus);
				if(isset($imageData)){
					$idImage = $this->m_images->insertImages($imageData);
					//processing brand
					$brand = $this->input->post('brand');
					$idBrand = $this->m_brand->checkBrand(array('brand' => $brand));
					if (!$idBrand){
						$idBrand = $this->m_brand->insertBrand(array('brand' => $brand, 'update_by' => 'sysadmin'));
					}
					//rest of the field
					$name = $this->input->post('name');
					$description = $this->input->post('description');
					$price = intval(str_replace(",","",$this->input->post('price')));
					//insert product
					$product = array(
						'name' => $name,
						'description' => $description,
						'price' => $price,
						'brand' => $idBrand,
						'image' => $idImage,
						'update_by' => 'sysadmin'
						);
					$idProduct = $this->m_product->insertProduct($product);
					//insert category_map
					$category = explode("#", $this->input->post('category'));
					for ($i=0; $i<count($category)-1; $i++){
						$categoryMapsData[$i]['category'] =  $category[$i];
						$categoryMapsData[$i]['product'] = $idProduct;
						$categoryMapsData[$i]['update_by'] = 'sysadmin';
					}
					$this->m_category->insertCategoryMaps($categoryMapsData);
					//insert image map
					for ($i=(int)$idImage; $i<(int)$idImage+count($imageData); $i++){
						$imageMapsData[$i]['image'] =  $i;
						$imageMapsData[$i]['product'] = $idProduct;
						$imageMapsData[$i]['update_by'] = 'sysadmin';
					}
					$this->m_images->insertImageMaps($imageMapsData);
					//insert stock
					$size = explode(",", $this->input->post('size'));
					for ($i=0; $i<count($size); $i++) {
						if (trim($size[$i])!==""){
							$stockData[$i]['product'] = $idProduct;
							$stockData[$i]['size'] = trim($size[$i]);
							$stockData[$i]['stock'] = 10;
							$stockData[$i]['update_by'] = 'sysadmin';
						}
					}
					$this->m_product->insertStock($stockData);
					//db transaction complete
					$this->db->trans_complete();
					if ($this->db->trans_status() === FALSE){
						$message = "Failed to Insert Record.";
						$result = 0;
					} else {
						if($result === 1){
							$message = "Product Saved.";
						} else if($result === 2){
							$message = "Some images failed to upload. Error: ".$message;
						}
					}
					$control['timestamp']=$timestamp;
					$this->session->set_userdata('control',$control);	
				} else {
					$message = "Failed to Insert Image.";
					$result = 0;
				}
				
			} else {
				$message = "Failed to Insert Record. Resubmission detected.";
				$result = 0;
			}
			
			$data['productList'] = $this->m_product->getProductNewestList(6);
			$data['allCategory'] = $this->m_category->getCategoryTree();
			$data['allBrand'] = $this->m_brand->getAllBrand();
			$data['message'] = $message;
			$data['result'] = $result;
			log_message('info', "==#	end doInputProduct	#==");
			$this->session->set_flashdata('data',$data);
			redirect('/admin/product/new');
		} else {
			redirect(base_url());
		}
	}

	function doInsertImages(){
		if ($this->isAdmin()){
			$files = $_FILES;
			$cpt = count($files['images']['name']);
			for($i=0; $i<$cpt; $i++){
				$_FILES['images']['name']= $files['images']['name'][$i];
				$_FILES['images']['type']= $files['images']['type'][$i];
				$_FILES['images']['tmp_name']= $files['images']['tmp_name'][$i];
				$_FILES['images']['error']= $files['images']['error'][$i];
				$_FILES['images']['size']= $files['images']['size'][$i];
				$this->upload->initialize(
					array(
						'upload_path' 	=> 'images/product/',
						'allowed_types' => 'jpeg|jpg|png',
						'overwrite' => FALSE,
						'encrypt_name' => TRUE,
						'max_size' => 100
						// 'max_width' => 768,
						// 'max_height' => 1024
					)
				);
				if ($this->upload->do_upload('images')){
					$fileData = $this->upload->data();
					$imageData[$i]['filename'] = $fileData['file_name'];
					$imageData[$i]['update_by'] = 'sysadmin';
				} else {
					$result = 2;
					$uploadStatus = false;
					$message = $this->upload->display_errors();
				}
			}
		}
				
	}

	function doSaveProduct(){
		if ($this->isAdmin()){
			$idProduct = $this->input->post('idProduct');
			$name = $this->input->post('name');
			$description = $this->input->post('description');
		} else {
			redirect(base_url());
		}		
	}
}

?>
			   