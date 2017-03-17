<?php

class Backoffice extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('queries');
        $this->load->model('users');
        $this->load->model('jobs');
        $this->load->helper('url_helper');
        $this->load->library('session');
        $this->load->helper('cookie');
        $this->load->library('form_validation');
    }

    public function index(){
        if (isset($_COOKIE["token"])) {
			$token = $_COOKIE["token"];

			$user_id = $this->users->loginFromCookies($token);
			if (isset($user_id)) {
				echo $user_id . ' <a href="http://[::1]/cvm/index.php/user/logout">logout</a>';
			} else {
				$this->load->view('auth');
			}

            $data["jobs"] = $this->jobs->getUserJobs($user_id);
		    $this->load->view('backoffice', $data);

		} else {
			$this->load->view('auth');
		}

    }

    public function add_job()
    {
        // required
        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('address', 'Address', 'trim|required');
        $this->form_validation->set_rules('category_id', 'Darbo sritis', 'trim|required');
        $this->form_validation->set_rules('city_id', 'Miestas', 'trim|required');
        $this->form_validation->set_rules('description', 'Darbo aprašymas', 'trim|required');
        $this->form_validation->set_rules('worker_type_id', 'Darbojoto tipas', 'trim|required');
        $this->form_validation->set_rules('phone', 'Telefonas', 'trim|required|min_length[4]|max_length[12]');

        // other
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
        $this->form_validation->set_rules('website', 'Website', 'trim|valid_url');
        $this->form_validation->set_rules('requirements', 'Reikalavimai', 'trim');
        $this->form_validation->set_rules('offer', 'Imone siulo', 'trim');
        $this->form_validation->set_rules('salary_from', 'Atlyginimas nuo', 'trim|numeric');
        $this->form_validation->set_rules('salary_to', 'Atlyginimas iki', 'trim|numeric');

        if ($this->form_validation->run() == FALSE) {
            $data['categories'] = $this->queries->get_categories();
            $data['cities'] = $this->queries->get_cities();
            $data['worker_types'] = $this->queries->get_worker_types();
            $this->load->view('addjob',$data);
        } else {

            $title = $this->input->post('title');
            $city_id = $this->input->post('city_id');
            $address = $this->input->post('address');
            $category_id = $this->input->post('category_id');
            $description = $this->input->post('description');
            $requirements = $this->input->post('requirements');
            $offer = $this->input->post('offer');
            $salary_from = $this->input->post('salary_from');
            $salary_to = $this->input->post('salary_to');
            $work_time_id = $this->input->post('work_time_id');
            $worker_type_id = $this->input->post('worker_type_id');
            $phone = $this->input->post('phone');
            $email = $this->input->post('email');
            $website = $this->input->post('website');
            $is_student = $this->input->post('is_student');
            $is_school = $this->input->post('is_school');
            $is_pensioneer = $this->input->post('is_pensioneer');
            $is_disabled = $this->input->post('is_disabled');
            $is_shift = $this->input->post('is_shift');
            $no_exp = $this->input->post('no_exp');



            $company_id = 1;
            $user_id = $this->users->loginFromCookies($_COOKIE["token"]);

            if($this->jobs->add_job(
                $company_id,
                $user_id,
                $title,
                $city_id,
                $address,
                $category_id,
                $description,
                $requirements,
                $offer,
                $salary_from,
                $salary_to,
                $work_time_id,
                $worker_type_id,
                $phone,
                $email,
                $website,
                $is_student,
                $is_school,
                $is_pensioneer,
                $is_disabled,
                $is_shift,
                $no_exp
            )){
                echo 'job added';

            } else {
                var_dump($this->db->error());
            }
        }


    }
}