<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Home extends CI_Controller {
    /**
     * Home constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('queries'); // bendra klasė, visų pirma skirta duomenų tvarkimui iš žemėlapio valdanti kodo
        $this->load->model('users'); // klasė darbui su vartotojų duombaze
        $this->load->model('jobs'); // klasė darbui su skelbimų duombaze
        $this->load->model('companies'); // klasė darbui su kompanijų duombaze
        $this->load->helper('url_helper');
        $this->load->library('session');
        $this->load->helper('cookie');
        $this->load->library('form_validation');
        $this->user_id = $this->getUserId();
        $this->company_id = $this->getUserCompanyId();
    }


    /**
     * Metodas, kuris paleidžiamas tik užėjęs į puslapį. Iš jo gaunami darbo kategorijos, miestų sąrašas, išsilavinimo
     * rūšių sąrašas.
     */
    public function index()
	{
        $data = [
            "company" => new stdClass()
        ];

        $data["company"]->id = NULL;
        $data['categories'] = $this->queries->get_categories();
        $data['cities'] = $this->queries->get_cities();
        $data['educations'] = $this->queries->get_educations();

        // Jeigu užėjo prisijungęs vartotojas, tuometu užkrauname jo info
        if(isset($_COOKIE["token"])){
            $data["company"] = $this->companies->get_company($this->company_id);
            $data['user_id'] = $this->user_id;
            $data['user_jobs_ids'] = json_encode($this->queries->get_user_jobs_ids($this->user_id));
        }

        $this->load->view('index',$data);
	}

    /**
     * Skelbimo atvaizdavimas iš nurodyto 'id'
     */
    public function get_job_by_id(){
        // Parametrų skaitymas iš URL
        $id = $this->input->get("id");

        $data = $this->jobs->get_job_by_id($id);

        if(!$data){
            echo "KLAIDA: darbas nerastas";
            return false;
        }

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($data));
    }

    /**
     * Sužinoti vartotojo 'id' iš slapukų
     * @return int
     */
    private function getUserId(){
        $id = 0;
        if(isset($_COOKIE["token"]) && !empty($_COOKIE["token"])){
            $id = $this->users->loginFromCookies($_COOKIE["token"]);
        }
        return $id;
    }

    /**
     * Gauti vartotojo kompanijos 'id'
     * @return null
     */
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
