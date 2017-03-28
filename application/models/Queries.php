<?php
// TODO: nemnogo otlichajutsia rezultaty get_jobs i get_markers
class Queries extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function get_jobs($page,$order_by,$city_id,$category_id,$edu_id,$salary,$work_time_id)
    {
        $offset = ($page - 1) * 30;
        $this->db->select('jobs.id,IF(company_hidden > 0,0,company_id) AS company_id,url_id AS url,title,salary_from,salary_to,companies.name AS company,companies.logo AS logo,companies.average_salary, companies.high_credit_rating');
        $this->db->from('jobs');
        $this->db->join('companies','companies.id = jobs.company_id');

        if($category_id != 0){
            $this->db->where('jobs.category_id',$category_id);
        }

        if($city_id != 0){
            $this->db->where('jobs.city_id',$city_id);
        }

        if($edu_id != 0){
            $this->db->where('jobs.edu_id',$edu_id);
        }

        if($work_time_id != 0){
            $this->db->where('jobs.work_time_id',$work_time_id);
        }

        if(!empty($salary)){
            $this->db->where("salary_from >=", $salary); // 500
        }

        if(!empty($order_by)){
            switch($order_by){
                case "date_desc":
                    $this->db->order_by("updated DESC", "created DESC");
                    break;
                case "date_asc":
                    $this->db->order_by("updated ASC", "created ASC");
                    break;
                default:
                    $this->db->order_by("updated DESC", "created DESC");
            }
        }

        //$this->db->limit(35);
        $this->db->offset($offset);

        $query = $this->db->get();
        return $query->result_array();

    }

    public function get_job_by_id($id,$category_id = 0,$city_id = 0)
    {
        $this->db->select('jobs.id,IF(company_hidden > 0,0,company_id) AS company_id,title,salary_from,salary_to,companies.name AS company,companies.logo AS logo');
        $this->db->from('jobs');
        $this->db->join('companies','companies.id = jobs.company_id');

        if($category_id != 0){
            $this->db->where('jobs.category_id',$category_id);
        }

        if($city_id != 0){
            $this->db->where('jobs.city_id',$city_id);
        }

        $this->db->where("id",$id);

        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_jobs_by_ids($ids,$category_id = 0,$city_id = 0)
    {
        $this->db->select('jobs.id,IF(company_hidden > 0,0,company_id) AS company_id,title,salary_from,salary_to,companies.name AS company,companies.logo AS logo, jobs.url_id AS u');
        $this->db->from('jobs');
        $this->db->join('companies','companies.id = jobs.company_id');

        if($category_id != 0){
            $this->db->where('jobs.category_id',$category_id);
        }

        if($city_id != 0){
            $this->db->where('jobs.city_id',$city_id);
        }


        $this->db->where_in("jobs.id",$ids);

        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_markers($category_id,$city_id,$edu_id,$salary,$work_time_id)
    {
        $this->db->select('markers.id AS mid, markers.lat, markers.lng, jobs.id AS jid,
        companies.average_salary AS avg_sal, companies.high_credit_rating AS credit, jobs.url_id AS u');
        $this->db->from('markers');
        $this->db->join('jobs', 'jobs.marker_id = markers.id');
        $this->db->join('companies','companies.id = jobs.company_id');

        if(!empty($category_id)){
            $this->db->where('jobs.category_id',$category_id);
        }

        if(!empty($city_id)){
            $this->db->where('jobs.city_id',$city_id);
        }

        if(!empty($edu_id)){
            $this->db->where('jobs.edu_id',$edu_id);
        }

        if($work_time_id != 0){
            $this->db->where('jobs.work_time_id',$work_time_id);
        }

        if(!empty($salary)){
            $this->db->where("salary_from >=", $salary);
        }

        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_cities()
    {
        $this->db->select("city_id, city_name")
            ->from("cities");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_categories()
    {
        $this->db->select("category_id, category_name")
            ->from("categories");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_worker_types()
    {
        $this->db->select("id, type")
            ->from("worker_types");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function find_job_by_title($title = 0)
    {
        $this->db->select("id,IF(company_hidden > 0,0,company_id) AS company_id,title,salary_from,salary_to")
            ->from("jobs");

        if(!empty($title)){
            $this->db->like("title",$title);
        }

        $query = $this->db->get();
        return $query->result_array();
    }

    public function init_param($city_id,$category_id,$edu_id,$salary,$work_time_id)

    {
        $this->db->select("COUNT(id) AS jobs")->from("jobs");

        if($category_id != 0){
            $this->db->where('jobs.category_id',$category_id);
        }

        if($city_id != 0){
            $this->db->where('jobs.city_id',$city_id);
        }

        if($work_time_id != 0){
            $this->db->where('jobs.work_time_id',$work_time_id);
        }

        if($edu_id != 0){
            $this->db->where('jobs.edu_id',$edu_id);
        }

        if(!empty($salary)){
            $this->db->where("salary_from >=", $salary);
        }

        $query = $this->db->get();
        return $query->row();
    }

    public function get_url_by_id($id){
        $this->db->select("url")->from("urls")->where("id",$id);
        $query = $this->db->get();
        return $query->row();
    }
}