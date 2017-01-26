<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class my404 extends CI_Controller 
{
	public function __construct() {
		parent::__construct(); 
	} 

	public function index() { 
		$this->load->view('404');//loading in my template 
	} 
} 
?> 