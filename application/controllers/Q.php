<?php

class Q extends CI_Controller {
    /**
     * Q constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('queries'); // bendra klasė, visų pirma skirta duomenų tvarkimui iš žemėlapio valdanti kodo
        $this->load->helper('url_helper');
    }

    /**
     * Atvaizduoja skelbimų žymes JSON pavidalu
     */
    public function m()
    {
        // Parametrų skaitymas iš URL
        $category_id = $this->input->get('category_id', TRUE);
		$city_id = $this->input->get('city_id', TRUE);
		$edu_id = $this->input->get('edu_id', TRUE);
		$work_time_id = $this->input->get('work_time_id', TRUE);
		$salary = $this->input->get('salary', TRUE);
		$user_id = $this->input->get('user_id', TRUE);

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($this->queries->get_markers(
            $category_id,$city_id,$edu_id,$salary,$work_time_id,$user_id
        )));
    }

    /*
     * Atvaizduoja skelbimus JSON pavidalu. Viename puslapyje rodoma tik 30 skelbimų, todėl užklausoje reikia nurodyti
     * kurį puslapį užkrauti
     */
    public function j()
    {
        $page = $this->input->get('page', TRUE);

		if(!isset($page)){
			$page = 1;
		}

        // Parametrų skaitymas iš URL
		$category_id = $this->input->get('category_id', TRUE);
		$city_id = $this->input->get('city_id', TRUE);
		$edu_id = $this->input->get('edu_id', TRUE);
		$work_time_id = $this->input->get('work_time_id', TRUE);
		$order_by = $this->input->get('order_by', TRUE);
		$salary = $this->input->get('salary', TRUE);
        $user_id = $this->input->get('user_id', TRUE);

        if(isset($page)
            && isset($order_by)
            && isset($city_id)
            && isset($category_id)
            && isset($edu_id)
            && isset($salary)
        ) {
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode($this->queries->get_jobs(
                $page,$order_by,$city_id,$category_id,$edu_id,$salary,$work_time_id,$user_id
            )));
        }

    }

    /*
     * Skelbimų atvaizdavimas pagal 'id' JSON pavidalu
     */
    public function get_jobs()
    {
        $ids = $this->input->get('ids', TRUE);
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($this->queries->get_jobs_by_ids($ids)));
    }


    /*
     * Funkcija grąžina skelbimų skaičių JSON pavidalu pagal nurodytus parametrus
     */
    public function init_param()
    {
        // Parametrų skaitymas iš URL
        $category_id = $this->input->get('category_id', TRUE);
		$city_id = $this->input->get('city_id', TRUE);
		$edu_id = $this->input->get('edu_id', TRUE);
		$work_time_id = $this->input->get('work_time_id', TRUE);
		$salary = $this->input->get('salary', TRUE);
        $user_id = $this->input->get('user_id', TRUE);

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($this->queries->init_param(
            $city_id,$category_id,$edu_id,$salary,$work_time_id,$user_id
        )));
    }

    /*
     * Trumpos nuorodos konvertavimas ir nukreipimas į originalą
     */
    public function redirect()
    {
        $id = $this->input->get('u',TRUE);
        $url = $this->queries->get_url_by_id($id);
        redirect($url->url);
    }
}