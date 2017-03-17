<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Welcome extends CI_Controller {
	public function __construct()
    {
        parent::__construct();
       /* $this->load->model('queries');
		$this->load->model('users');
        $this->load->helper('url_helper');
		$this->load->library('session');
        $this->load->helper('cookie');
		$this->load->library('form_validation');*/
    }


	public function index($page = NULL,$query_text = NULL,$order_by = NULL,$city_id = NULL,$category_id = NULL,$edu_id = NULL,$salary = NULL,$new = NULL,$premium = NULL, $work_time = NULL, $worker_type_id = NULL, $is_student = 0, $is_school = 0, $is_pensioneer = 0, $is_disabled = 0, $is_shift = 0, $no_exp = 0)
	{
		/*$page = $this->input->get('page', TRUE);
		if (!isset($page)) {
			$page = 1;
		}
		$category_id = $this->input->get('category_id', TRUE);
		$city_id = $this->input->get('city_id', TRUE);

		$jobs = $this->queries->get_jobs(
				$page, $query_text, $order_by, $city_id, $category_id, $edu_id, $salary, $new, $premium, $work_time,
				$worker_type_id, $is_student, $is_pensioneer, $is_disabled, $is_shift, $no_exp
		);
		$data['jobs'] = $jobs;
		if (isset($_COOKIE["token"])) {
			$token = $_COOKIE["token"];

			$user_id = $this->users->loginFromCookies($token);
			if (isset($user_id)) {
				echo $user_id . ' <a href="http://[::1]/cvm/index.php/user/logout">logout</a>';
			} else {
				$this->load->view('auth');
			}

		} else {
			$this->load->view('auth');
		}
		$this->load->view('index', $data);*/
	}


	/*public function jobs($page = NULL,$query_text = NULL,$order_by = NULL,$city_id = NULL,$category_id = NULL,$edu_id = NULL,$salary = NULL,$new = NULL,$premium = NULL, $work_time = NULL, $worker_type_id = NULL, $is_student = 0, $is_school = 0, $is_pensioneer = 0, $is_disabled = 0, $is_shift = 0, $no_exp = 0)
	{
		$page = $this->input->get('page', TRUE);
		/*$order_by = substr($order_by,11);
		$order_by = ($order_by == "naujausi" ? 0 : $order_by);
		$city_id = substr($city_id,8);
		$category_id = substr($category_id,11);
		$edu_id = substr($edu_id,14);
		$salary = substr($salary,12);
		$new = substr($new,8);
		$premium = substr($premium,9);
		$page = substr($page,9);*/
		/*$jobs = $this->queries->get_jobs(
				$page,$query_text,$order_by,$city_id,$category_id,$edu_id,$salary,$new,$premium,$work_time,
                $worker_type_id,$is_student,$is_pensioneer,$is_disabled,$is_shift,$no_exp
		);
		$data['jobs'] = $jobs;
		$this->load->view('list',$data);
	}*/
}
