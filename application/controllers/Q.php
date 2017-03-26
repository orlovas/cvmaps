<?php

class Q extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('queries');
        $this->load->helper('url_helper');
    }

    public function m($qt = 0, $category_id = 0, $city_id = 0,$edu_id = NULL,$salary = NULL,$new = NULL,$premium = NULL, $work_time = NULL, $worker_type_id = NULL, $is_student = 0, $is_school = 0, $is_pensioneer = 0, $is_disabled = 0, $is_shift = 0, $no_exp = 0)
    {
        /*$page = $this->input->get('page', TRUE);
		if(!isset($page)){
			$page = 1;
		}
		$category_id = $this->input->get('category_id', TRUE);
		$city_id = $this->input->get('city_id', TRUE);
		$edu_id = $this->input->get('edu_id', TRUE);
		$order_by = $this->input->get('order_by', TRUE);
		$salary = $this->input->get('salary', TRUE);
		$premium = $this->input->get('premium', TRUE);
		$new = $this->input->get('new', TRUE);*/
        if(strpos($salary,",")){
            $salary = explode(",",$salary);
        }

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($this->queries->get_markers(
            $qt,$category_id,$city_id,$edu_id,$salary,$new,$premium,$work_time,
            $worker_type_id,$is_student,$is_school,$is_pensioneer,$is_disabled,$is_shift,$no_exp
        )));
    }

    public function j($page = NULL,$query_text = NULL,$order_by = NULL,$city_id = NULL,$category_id = NULL,$edu_id = NULL,$salary = NULL,$new = NULL,$premium = NULL, $work_time = NULL, $worker_type_id = NULL, $is_student = 0, $is_school = 0, $is_pensioneer = 0, $is_disabled = 0, $is_shift = 0, $no_exp = 0)
    {
        /*$page = $this->input->get('page', TRUE);
		if(!isset($page)){
			$page = 1;
		}
		$category_id = $this->input->get('category_id', TRUE);
		$city_id = $this->input->get('city_id', TRUE);
		$edu_id = $this->input->get('edu_id', TRUE);
		$order_by = $this->input->get('order_by', TRUE);
		$salary = $this->input->get('salary', TRUE);
		$premium = $this->input->get('premium', TRUE);
		$new = $this->input->get('new', TRUE);*/
        if(strpos($salary,",")){
            $salary = explode(",",$salary);
        }
        if(isset($page)
            && isset($query_text)
            && isset($order_by)
            && isset($city_id)
            && isset($category_id)
            && isset($edu_id)
            && isset($salary)
            && isset($new)
            && isset($premium)) {
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode($this->queries->get_jobs(
                $page,$query_text,$order_by,$city_id,$category_id,$edu_id,$salary,$new,$premium,$work_time,
                $worker_type_id,$is_student,$is_pensioneer,$is_disabled,$is_shift,$no_exp
            )));
        }

    }

    public function get_job($id)
    {
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($this->queries->get_job_by_id($id)));
        if(isset($_POST['username']) && isset($_POST['password'])){
            $username=$_POST['username'];
            $password=$_POST['password'];

            $query=mysql_query("SELECT * FROM users WHERE username='$username'");

            if(mysql_num_rows($query)>0){
                echo 'Užimta';
            } else {
                mysql_query("INSERT INTO users (username, password) VALUES ('$username','$password')");
                header("location:index.php");
            }
        }
    }

    public function get_jobs($ids)
    {
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($this->queries->get_jobs_by_ids($ids)));
    }

    public function find_job($query_text = "")
    {
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($this->queries->find_job_by_title($query_text)));
    }

    public function init_param($page = NULL,$query_text = NULL,$order_by = NULL,$city_id = NULL,$category_id = NULL,$edu_id = NULL,$salary = NULL,$new = NULL,$premium = NULL, $work_time = NULL, $worker_type_id = NULL, $is_student = 0, $is_school = 0, $is_pensioneer = 0, $is_disabled = 0, $is_shift = 0, $no_exp = 0)
    {
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($this->queries->init_param(
            $page,$query_text,$order_by,$city_id,$category_id,$edu_id,$salary,$new,$premium,$work_time,
            $worker_type_id,$is_student,$is_school,$is_pensioneer,$is_disabled,$is_shift,$no_exp
        )));
    }
}