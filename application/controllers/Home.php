<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Home extends CI_Controller {
	public function __construct()
    {
        parent::__construct();
        $this->load->model('queries');
        $this->load->model('users');
        $this->load->model('jobs');
        $this->load->model('companies');
        $this->load->helper('url_helper');
        $this->load->library('session');
        $this->load->helper('cookie');
        $this->load->library('form_validation');
        $this->user_id = $this->getUserId();
        $this->company_id = $this->getUserCompanyId();
    }


	public function index()
	{
        $data = [
            "company" => new stdClass()
        ];
        $data["company"]->id = NULL;
        if(isset($_COOKIE["token"])){
            $token = $_COOKIE["token"];

            $user_id = $this->users->loginFromCookies($token);
            /*$data["company_init"] = $this->jobs->getUserJobsInit($user_id);
            $data["jobs"] = $this->jobs->getUserJobs($user_id);
            $data["markers"] = $this->jobs->getUserJobs($user_id,true);*/
            $data["company"] = $this->companies->get_company($this->company_id);
            $data['categories'] = $this->queries->get_categories();
            $data['cities'] = $this->queries->get_cities();
            $data['educations'] = $this->queries->get_educations();
        }

        $this->load->view('index',$data);
	}

    public function get_job_by_id(){
        $id = $this->input->get("id");
        $data = $this->jobs->get_job_by_id($id);
        if(!$data){
            echo "job not found";
            return false;
        }
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($data));
    }

    private function getUserId(){
        $id = 0;
        if(isset($_COOKIE["token"]) && !empty($_COOKIE["token"])){
            $id = $this->users->loginFromCookies($_COOKIE["token"]);
        }
        return $id;
    }

    private function getUserCompanyId(){
        $id = NULL;

        if(isset($this->user_id) && !empty($this->user_id)){
            $query = $this->companies->get_company_id($this->user_id);
            if(!is_null($query)){
                $id = $query->id;
            }
        }
        return $id;
    }

}
