<?php
// Todo: ne derzhit sessiju;
class Backoffice extends CI_Controller
{
    private $user_id;
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

    public function index(){
        if (isset($_COOKIE["token"])) {
            $token = $_COOKIE["token"];

            $user_id = $this->users->loginFromCookies($token);
            if (!isset($user_id)) {
                $this->load->view('auth');
            }
            $data["jobs"] = $this->jobs->getUserJobs($user_id);
            $data["markers"] = $this->jobs->getUserJobs($user_id,true);
            $data["company_id"] = $this->company_id;
            $this->load->view('backoffice', $data);

        } else {
            $this->load->view('auth');
        }

    }

    public function create_company()
    {
        $this->form_validation->set_rules('name', 'name', 'trim|required');
        $this->form_validation->set_rules('average_salary', 'average salary', 'trim|required|numeric');
        $this->form_validation->set_rules('high_credit_rating', 'high credit rating', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('createcompany');
        } else {
            $name = $this->input->post('name');
            $average_salary = $this->input->post('average_salary');
            $high_credit_rating = $this->input->post('high_credit_rating');
            $logo = $this->input->post('logo');
            if($logo !== "default.png"){
                $logo = $this->upload_logo('logo');
            }

            if($this->companies->create_company($this->user_id,$name,$average_salary,$high_credit_rating,$logo)){
                redirect("index.php?c=backoffice");
            } else {
                var_dump($this->db->error());
            }

        }
    }

    public function edit_company()
    {
        $this->form_validation->set_rules('name', 'name', 'trim|required');
        $this->form_validation->set_rules('average_salary', 'average salary', 'trim|required|numeric');
        $this->form_validation->set_rules('high_credit_rating', 'high credit rating', 'trim|required');

        $company_id = $this->input->get("id");

        if(!$this->users->confirm_user(["type"=>"companies","id"=>$company_id],$this->user_id)) return false;
        $data = $this->companies->get_company($company_id);

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('editcompany', $data);
        } else {
            $name = $this->input->post('name');
            $average_salary = $this->input->post('average_salary');
            $high_credit_rating = $this->input->post('high_credit_rating');
            $logo = $this->input->post('logo');
            if(!$logo){
                $this->delete_old_logo($data->logo);
                $logo = $this->upload_logo('logo');
            }

            if($this->companies->edit_company($company_id,$name,$average_salary,$high_credit_rating,$logo)){
                $data = $this->companies->get_company($company_id);
                $this->load->view('editcompany', $data);
            } else {
                var_dump($this->db->error());
            }

        }
    }

    private function upload_logo($logo){
        $config['upload_path']          = 'static/images/l/';
        $config['allowed_types']        = 'gif|jpg|png|jpeg|tiff';
        $config['max_size']             = 2048;
        $config['max_width']            = 2048;
        $config['max_height']           = 2048;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($logo)) {
            $error = array('error' => $this->upload->display_errors());
            var_dump($error);
            return false;
        } else {
            $data = array('upload_data' => $this->upload->data());

            $config['image_library'] = 'gd2';
            $config['source_image'] = $this->upload->data('full_path');
            $config['maintain_ratio'] = TRUE;
            $config['width']         = 74;
            $config['height']       = 74;

            $this->load->library('image_lib', $config);

            if(!$this->image_lib->resize()){
                echo $this->image_lib->display_errors();
                return false;
            }

            return $this->upload->data('file_name');
        }
    }

    private function delete_old_logo($logo){
        unlink("static/images/l/".$logo);
    }

    public function add_job()
    {
        // required
        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('address', 'Address', 'trim|required');
        $this->form_validation->set_rules('category_id', 'Darbo sritis', 'trim|required');
        $this->form_validation->set_rules('city_id', 'Miestas', 'trim|required');
        $this->form_validation->set_rules('salary_from', 'Atlyginimas nuo', 'trim|numeric');
        $this->form_validation->set_rules('salary_to', 'Atlyginimas iki', 'trim|numeric');
        $this->form_validation->set_rules('url', 'URL', 'trim|required|valid_url');

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
            $edu_id = $this->input->post("edu_id");
            $work_time_id = $this->input->post("work_time_id");
            $salary_from = $this->input->post("salary_from");
            $salary_to = $this->input->post("salary_to");
            $url = $this->input->post('url');
            $company_id = $this->company_id;
            $user_id = $this->user_id;
            $url_id = $this->jobs->add_url($url);
            $marker_id = $this->jobs->add_marker($this->getCoordinates($address));
            if($this->jobs->add_job(
                $company_id,
                $user_id,
                $title,
                $city_id,
                $address,
                $category_id,
                $salary_from,
                $salary_to,
                $work_time_id,
                $edu_id,
                $url_id,
                $marker_id
            )){
                echo 'job added';

            } else {
                var_dump($this->db->error());
            }
        }
    }

    public function edit_job(){
        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('address', 'Address', 'trim|required');
        $this->form_validation->set_rules('category_id', 'Darbo sritis', 'trim|required');
        $this->form_validation->set_rules('city_id', 'Miestas', 'trim|required');
        $this->form_validation->set_rules('salary_from', 'Atlyginimas nuo', 'trim|numeric');
        $this->form_validation->set_rules('salary_to', 'Atlyginimas iki', 'trim|numeric');
        $this->form_validation->set_rules('url', 'URL', 'trim|required|valid_url');

        $id = $this->input->get("id");

        if(!$this->users->confirm_user(["type"=>"jobs","id"=>$id],$this->user_id)) return false;

        $data = $this->jobs->get_job_by_id($id);
        if(!$data){
            echo "job not found";
            return false;
        }
        $data->categories = $this->queries->get_categories();
        $data->cities = $this->queries->get_cities();

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('editjob', $data);
        } else {
            $title = $this->input->post('title');
            $city_id = $this->input->post('city_id');
            $category_id = $this->input->post('category_id');
            $edu_id = $this->input->post("edu_id");
            $work_time_id = $this->input->post("work_time_id");
            $salary_from = $this->input->post("salary_from");
            $salary_to = $this->input->post("salary_to");
            $company_id = 1;
            $user_id = $this->user_id;
            $url = $this->input->post('url');
            $url_id = ($this->compareUrls($url,$data->url_id) ? $data->url_id : $this->jobs->add_url($url));
            $address = $this->input->post('address');
            $marker_id = ($this->compareAddresses($address,$data->id,$data->marker_id) ? $data->marker_id : $this->jobs->add_marker($this->getCoordinates($address)));
            if($this->jobs->edit_job(
                $data->id,
                $company_id,
                $user_id,
                $title,
                $city_id,
                $address,
                $category_id,
                $salary_from,
                $salary_to,
                $work_time_id,
                $edu_id,
                $url_id,
                $marker_id
            )){
                $id = $this->input->get("id");
                $data = $this->jobs->get_job_by_id($id);
                $data->categories = $this->queries->get_categories();
                $data->cities = $this->queries->get_cities();
                $this->load->view('editjob', $data);

            } else {
                var_dump($this->db->error());
            }
        }
    }

    public function delete_job(){
        $id = $this->input->get("id");

        if(!$this->users->confirm_user(["type"=>"jobs","id"=>$id],$this->user_id)) return false;

        if($this->jobs->delete_job($id)){
            $this->index();
        } else {
            echo "error";
        }
    }

    private function compareUrls($url,$url_id){
        $url2 = $this->jobs->get_url_by_id($url_id);
        if(strcmp($url,$url2->url) === 0){
            return true;
        } else {
            $this->jobs->delete_url($url_id);
            return false;
        }
    }

    private function compareAddresses($address,$id,$marker_id){
        $address2 = $this->jobs->get_job_address($id);
        if(strcmp($address,$address2->address) === 0){
            return true;
        } else {
            $this->jobs->delete_marker($marker_id);
            return false;
        }
    }

    private function getCoordinates($address){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($address)."&key=AIzaSyAJhXhTIxa5iUsy3FQA5bERrbbxdEZ7Cls");
        $result = curl_exec($ch);
        curl_close($ch);

        $adr = json_decode($result);
        if($adr->status == "OK"){
            $lat = $adr->results[0]->geometry->location->lat;
            $lng = $adr->results[0]->geometry->location->lng;
            return [$lat,$lng];
        } else {
            return false;
        }
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