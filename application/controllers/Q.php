<?php

class Q extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('queries');
        $this->load->helper('url_helper');
    }

    public function m()
    {
        $category_id = $this->input->get('category_id', TRUE);
		$city_id = $this->input->get('city_id', TRUE);
		$edu_id = $this->input->get('edu_id', TRUE);
		$work_time_id = $this->input->get('work_time_id', TRUE);
		$salary = $this->input->get('salary', TRUE);

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($this->queries->get_markers(
            $category_id,$city_id,$edu_id,$salary,$work_time_id
        )));
    }

    public function j()
    {
        $page = $this->input->get('page', TRUE);
		if(!isset($page)){
			$page = 1;
		}
		$category_id = $this->input->get('category_id', TRUE);
		$city_id = $this->input->get('city_id', TRUE);
		$edu_id = $this->input->get('edu_id', TRUE);
		$work_time_id = $this->input->get('work_time_id', TRUE);
		$order_by = $this->input->get('order_by', TRUE);
		$salary = $this->input->get('salary', TRUE);


        if(isset($page)
            && isset($order_by)
            && isset($city_id)
            && isset($category_id)
            && isset($edu_id)
            && isset($salary)) {
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode($this->queries->get_jobs(
                $page,$order_by,$city_id,$category_id,$edu_id,$salary,$work_time_id
            )));
        }

    }

    /*public function get_job($id)
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
    }*/

    public function get_jobs()
    {
        $ids = $this->input->get('ids', TRUE);
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($this->queries->get_jobs_by_ids($ids)));
    }

    /*public function find_job($query_text = "")
    {
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($this->queries->find_job_by_title($query_text)));
    }*/

    public function init_param()
    {
        $category_id = $this->input->get('category_id', TRUE);
		$city_id = $this->input->get('city_id', TRUE);
		$edu_id = $this->input->get('edu_id', TRUE);
		$work_time_id = $this->input->get('work_time_id', TRUE);
		$salary = $this->input->get('salary', TRUE);

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($this->queries->init_param(
            $city_id,$category_id,$edu_id,$salary,$work_time_id
        )));
    }

    public function redirect(){
        $id = $this->input->get('u',TRUE);
        $url = $this->queries->get_url_by_id($id);
        redirect($url->url);
    }
}