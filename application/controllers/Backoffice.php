<?php

class Backoffice extends CI_Controller
{
    private $user_id;

    /**
     * Backoffice constructor.
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

    public function add_company()
    {
        $data = [];
        $this->load->view('forms/add_company',$data);
    }

    /**
     * Firmos duomenų redagavimas
     * @return bool
     */
    public function edit_company()
    {
        // Formos įvesti duomenys tikrinami pagal nurodytas taisykles
        $this->form_validation->set_rules('name', 'name', 'trim|required');
        $this->form_validation->set_rules('average_salary', 'average salary', 'trim|required|numeric');
        $this->form_validation->set_rules('high_credit_rating', 'high credit rating', 'trim|required');

        // Jeigu neįmanoma identifikuoti vartotoją, neleidžiama toliau vykdyti kodą
        if(!$this->users->confirm_user(["type"=>"companies","id"=>$this->company_id],$this->user_id)) return false;

        // Gaunami kompanijos duomenys
        $data = $this->companies->get_company($this->company_id);

        // Tikrinima, ar visi formos laukai teisingai užpildyti, neleidžiama toliau vykdyti kodą ir rodoma klaidą
        if ($this->form_validation->run() == FALSE) {
            redirect("?error=bad_company_edit");
        } else {
            // Duomenų iš formos skaitymas
            $name = $this->input->post('name');
            $average_salary = $this->input->post('average_salary');
            $high_credit_rating = $this->input->post('high_credit_rating');
            $logo = $this->input->post('logo');

            if(!$logo){
                $this->delete_old_logo($data->logo);
                $logo = $this->upload_logo('logo');
            }

            if($this->companies->edit_company($this->company_id,$name,$average_salary,$high_credit_rating,$logo)){
                redirect("?success");
            } else {
                redirect("?error=bad_company_edit");
            }

        }
    }

    /**
     * Logotipo užkrovimas į serverį
     * @param $logo
     * @return bool
     */
    private function upload_logo($logo)
    {
        // Logotipo parametrų apribojimai
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

            // Nuotraukos apdorojimas
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

    /**
     * Logotipo pašalinimas
     * @param $logo
     */
    private function delete_old_logo($logo)
    {
        unlink("static/images/l/".$logo);
    }

    /**
     * Naujo skelbimo įkėlimas
     */
    public function add_job()
    {
        // Formos įvesti duomenys tikrinami pagal nurodytas taisykles
        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('address', 'Address', 'trim|required');
        $this->form_validation->set_rules('category_id', 'Darbo sritis', 'trim|required');
        $this->form_validation->set_rules('city_id', 'Miestas', 'trim|required');
        $this->form_validation->set_rules('edu_id', 'Issilavinimas', 'trim|required');
        $this->form_validation->set_rules('salary_from', 'Atlyginimas nuo', 'trim|numeric|required');
        $this->form_validation->set_rules('salary_to', 'Atlyginimas iki', 'trim|numeric');
        $this->form_validation->set_rules('url', 'URL', 'trim|required|valid_url');

        // Tikrinima, ar visi formos laukai teisingai užpildyti, neleidžiama toliau vykdyti kodą ir rodoma klaidą
        if ($this->form_validation->run() == FALSE) {
            $data["categories"] = $this->queries->get_categories();
            $data["cities"] = $this->queries->get_cities();
            $this->load->view('forms/add_job',$data);
        } else {
            // Duomenų iš formos skaitymas
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
                $marker_id)
            ){

            } else {

            }
        }
    }

    /**
     * Skelbimo redagavimas
     * @return bool
     */
    public function edit_job()
    {
        // Formos įvesti duomenys tikrinami pagal nurodytas taisykles
        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('address', 'Address', 'trim|required');
        $this->form_validation->set_rules('category_id', 'Darbo sritis', 'trim|required');
        $this->form_validation->set_rules('city_id', 'Miestas', 'trim|required');
        $this->form_validation->set_rules('salary_from', 'Atlyginimas nuo', 'trim|numeric');
        $this->form_validation->set_rules('salary_to', 'Atlyginimas iki', 'trim|numeric');
        $this->form_validation->set_rules('url', 'URL', 'trim|required|valid_url');

        $id = $this->input->get("id");

        // Jeigu neįmanoma identifikuoti vartotoją, neleidžiama toliau vykdyti kodą
        if(!$this->users->confirm_user(["type"=>"jobs","id"=>$id],$this->user_id)) return false;

        $data = $this->jobs->get_job_by_id($id);

        if(!$data){
            echo "KLAIDA: darbas nerastas.";
            return false;
        }

        // Užkraunami miestai ir darbo kategorijos, kad vėliau atvaizduoti redagavimo formoje
        $data->categories = $this->queries->get_categories();
        $data->cities = $this->queries->get_cities();

        // Tikrinima, ar visi formos laukai teisingai užpildyti, neleidžiama toliau vykdyti kodą ir rodoma klaidą
        if ($this->form_validation->run() == FALSE) {
            redirect("?error=bad_edit_job");
        } else {
            // Duomenų iš formos skaitymas
            $title = $this->input->post('title');
            $city_id = $this->input->post('city_id');
            $category_id = $this->input->post('category_id');
            $edu_id = $this->input->post("edu_id");
            $work_time_id = $this->input->post("work_time_id");
            $salary_from = $this->input->post("salary_from");
            $salary_to = $this->input->post("salary_to");
            $company_id = $this->company_id;
            $user_id = $this->user_id;
            $url = $this->input->post('url');

            // Naujo URL lyginimas su senu. Išsamiau apie tai parašyta prie compareUrls funkcijos
            $url_id = ($this->compareUrls($url,$data->url_id) ? $data->url_id : $this->jobs->add_url($url));

            $address = $this->input->post('address');

            // Naujo adreso lyginimas su senu. Išsamiau apie tai parašyta prie compareAddresses funkcijos
            $marker_id = (
                $this->compareAddresses($address,$data->id,$data->marker_id)
                    ? $data->marker_id
                    : $this->jobs->add_marker($this->getCoordinates($address))
            );

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
                $marker_id)
            ){
                redirect("?success");
            } else {
                redirect("?error=bad_edit_job");
            }
        }
    }

    /**
     * Skelbimo pašalinimas
     * @return bool
     */
    public function delete_job(){
        $id = $this->input->get("id");

        // Jeigu neįmanoma identifikuoti vartotoją, neleidžiama toliau vykdyti kodą
        if(!$this->users->confirm_user(["type"=>"jobs","id"=>$id],$this->user_id)) return false;

        if($this->jobs->delete_job($id)){
            redirect("?success");
        } else {
            redirect("?error=bad_delete_job");
        }
    }

    /**
     * URL adresų palyginimas. Kadangi originalių skelbimų URL saugomi atskiroje lentelėje,
     * kad vėliau gauti "sutrumpintą nuorodą", po skelbimo atnaujinimo reikia tikrinti ar pasikeitė tas URL,
     * jeigu taip - šalinam senąjį, ne - grąžinam 'true'.
     * @param $url
     * @param $url_id
     * @return bool
     */
    private function compareUrls($url, $url_id){
        $url2 = $this->jobs->get_url_by_id($url_id);
        if(strcmp($url,$url2->url) === 0){
            return true;
        } else {
            $this->jobs->delete_url($url_id);
            return false;
        }
    }

    /**
     * Skelbimo adresų palyginimas. Kadangi skelbimo koordinatės ir žymės saugomi atskiroje lentoje,
     * po skelbimo atnaujinimo reikia tikrinti ar pasikeitė skelbimo adresas,
     * jeigu taip - šalinam senas koordinates ir žymės, ne - grąžinam 'true'.
     * @param $address
     * @param $id
     * @param $marker_id
     * @return bool
     */
    private function compareAddresses($address, $id, $marker_id){
        $address2 = $this->jobs->get_job_address($id);
        if(strcmp($address,$address2->address) === 0){
            return true;
        } else {
            $this->jobs->delete_marker($marker_id);
            return false;
        }
    }

    /**
     * Adreso konvertavimas į koordinates
     * @param $address
     * @return array|bool
     */
    private function getCoordinates($address){
        // Jungimas prie Google Maps serverių per cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($address)."&key=AIzaSyAJhXhTIxa5iUsy3FQA5bERrbbxdEZ7Cls");
        $result = curl_exec($ch);
        curl_close($ch);

        // Atsakymo apdorojimas
        $adr = json_decode($result);
        if($adr->status == "OK"){
            $lat = $adr->results[0]->geometry->location->lat;
            $lng = $adr->results[0]->geometry->location->lng;
            return [$lat,$lng];
        } else {
            return false;
        }
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