<?php
class Queries extends CI_Model {

    /**
     * Queries constructor.
     */
    public function __construct()
    {
        $this->load->database();
    }

    /**
     * Skelbimų paieškas pagal nurodytus parametrus
     * @param $page
     * @param $order_by
     * @param $city_id
     * @param $category_id
     * @param $edu_id
     * @param $salary
     * @param $work_time_id
     * @param int $user_id
     * @return array
     */
    public function get_jobs($page, $order_by, $city_id, $category_id, $edu_id, $salary, $work_time_id, $user_id=0)
    {
        $offset = ($page - 1) * 30;
        $this->db->select('jobs.id,company_id,IF(updated > created, updated, created) AS updated,url_id AS url,title,salary_from,salary_to,companies.name AS company,companies.logo AS logo,companies.average_salary, companies.high_credit_rating');
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
            $this->db->where("salary_from >=", $salary);
        }

        if($user_id != 0){
            $this->db->where('jobs.user_id',$user_id);
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

        $this->db->offset($offset);

        $query = $this->db->get();
        return $query->result_array();

    }

    /**
     * Metodas ieško skelbimą pagal nurodytą id
     * @param $id
     * @param int $category_id
     * @param int $city_id
     * @return mixed
     */
    public function get_job_by_id($id, $category_id = 0, $city_id = 0)
    {
        $this->db->select('jobs.id,company_id,title,salary_from,salary_to,companies.name AS company,companies.logo AS logo');
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

    /**
     * Metodas ieško skelbimus pagal nurodytus id
     * @param $ids
     * @param int $category_id
     * @param int $city_id
     * @return bool
     */
    public function get_jobs_by_ids($ids, $category_id = 0, $city_id = 0)
    {
        $query = $this->db->select('jobs.id,IF(company_hidden > 0,0,company_id) AS company_id,title,salary_from,salary_to,companies.name AS company,companies.logo AS logo, jobs.url_id AS u')->from('jobs')->join('companies','companies.id = jobs.company_id')->where_in('jobs.id',$ids)->get();
        return $query ? $query->result_array() : false;
    }

    /**
     * Metodas ieško vartotojo darbus pagal nurodytus id
     * @param $user_id
     * @return bool
     */
    public function get_user_jobs_ids($user_id)
    {
        $query = $this->db->select('id')->from('jobs')->where('user_id',$user_id)->get();
        return $query ? $query->result_array() : false;
    }

    /**
     * Metodas gauna žymes iš duombazės pagal nurodytus parametrus
     * @param $category_id
     * @param $city_id
     * @param $edu_id
     * @param $salary
     * @param $work_time_id
     * @param int $user_id
     * @return mixed
     */
    public function get_markers($category_id, $city_id, $edu_id, $salary, $work_time_id, $user_id=0)
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

        if($user_id != 0){
            $this->db->where('jobs.user_id',$user_id);
        }

        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Miestų sąrašo gavimas
     * @return array
     */
    public function get_cities()
    {
        $this->db->select("city_id, city_name")
            ->from("cities");
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Darbo kategorijų gavimas
     * @return mixed
     */
    public function get_categories()
    {
        $this->db->select("category_id, category_name")
            ->from("categories");
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Išsilavinimo tipų gavimas
     * @return array
     */
    public function get_educations()
    {
        $this->db->select("id, name")
            ->from("educations");
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Metodas ieško skelbimą pagal nurodytą pareigos pavadinimą
     * @param int $title
     * @return array
     */
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

    /**
     * Metodas skaičiuoja kiek yra skelbimų pagal nurodytus parametrus
     * @param $city_id
     * @param $category_id
     * @param $edu_id
     * @param $salary
     * @param $work_time_id
     * @param int $user_id
     * @return int
     */
    public function init_param($city_id, $category_id, $edu_id, $salary, $work_time_id, $user_id=0)
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

        if($user_id != 0){
            $this->db->where('jobs.user_id',$user_id);
        }

        if(!empty($salary)){
            $this->db->where("salary_from >=", $salary);
        }

        $query = $this->db->get();
        return $query->row();
    }

    /**
     * Metodas ieško URL pagal nurodyta id
     * @param $url_id
     * @return mixed
     */
    public function get_url_by_id($id)
    {
        $this->db->select("url")->from("urls")->where("id",$id);
        $query = $this->db->get();
        return $query->row();
    }

    public function categories_crossing($category)
    {
        $this->db->select("categories_crossing.category_id,categories.category_name")->from("categories_crossing")
            ->join('categories','categories.category_id = categories_crossing.category_id')
            ->where("name",$category);
        $query = $this->db->get();
        return $query->row();
    }

    public function find_city_id_by_name($city)
    {
        return $this->db->select("city_id")->from("cities")->where("city_name",$city)->get()->row();
    }
}