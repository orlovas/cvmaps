<?php
// TODO: nemnogo otlichajutsia rezultaty get_jobs i get_markers
class Queries extends CI_Model {

    public function __construct()
    {
        $this->load->database();
    }

    public function get_jobs($page,$query_text,$order_by,$city_id,$category_id,$edu_id,$salary,$new,$premium,$work_time,
                $worker_type_id,$is_student,$is_pensioneer,$is_disabled,$is_shift,$no_exp)
    {
        $offset = ($page - 1) * 30;
        $this->db->select('jobs.id,IF(company_hidden > 0,0,company_id) AS company_id,title,salary_from,salary_to,companies.name AS company,companies.logo AS logo,companies.average_salary, companies.high_credit_rating, premium, updated');
        $this->db->from('jobs');
        $this->db->join('companies','companies.id = jobs.company_id');

        if(!empty($query_text)){
            $this->db->like("title",$query_text);
        }

        if($category_id != 0){
            $this->db->where('jobs.category_id',$category_id);
        }

        if($city_id != 0){
            $this->db->where('jobs.city_id',$city_id);
        }

        if($worker_type_id != 0){
            $this->db->where('jobs.worker_type_id',$worker_type_id);
        }

        if($work_time != 0){
            $this->db->where('jobs.work_time_id',$work_time);
        }

        if(!empty($is_student)){
            $this->db->where('jobs.is_student',1);
        }

        if(!empty($is_school)){
            $this->db->where('jobs.is_school',1);
        }

        if(!empty($is_pensioneer)){
            $this->db->where('jobs.is_pensioneer',1);
        }

        if(!empty($is_disabled)){
            $this->db->where('jobs.is_disabled',1);
        }

        if(!empty($is_shift)){
            $this->db->where('jobs.is_shift',1);
        }

        if(!empty($no_exp)){
            $this->db->where('jobs.no_exp',1);
        }

        if(!empty($new)){
            $this->db->where('updated > DATE_SUB(NOW(), INTERVAL 1 DAY)');
        }

        if(!empty($premium)){
            $this->db->where('jobs.premium',1);
        }

        if(!empty($salary)){
            $this->db->where("salary_to >=", $salary); // 500
        }


        $this->db->order_by('premium DESC');

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

        //$this->db->limit(30);
        $this->db->offset($offset);

        $query = $this->db->get();
        return $query->result_array();

    }

    /*public function get_jobs_($order_by,$city_id,$category_id,$edu_id,$salary,$new,$premium,$page = 1,$query_text)
    {
        $offset = ($page - 1) * 30;
        $this->db->select('jobs.id,IF(company_hidden > 0,0,company_id) AS company_id,title,salary_from,salary_to,companies.name AS company,companies.logo AS logo, premium, updated');
        $this->db->from('jobs');
        $this->db->join('companies','companies.id = jobs.company_id');

        if(!empty($query_text)){
            $this->db->like("title",$query_text);
        }

        if($category_id != 0){
            $this->db->where('jobs.category_id',$category_id);
        }

        if($city_id != 0){
            $this->db->where('jobs.city_id',$city_id);
        }

        $this->db->limit(30);
        $this->db->offset($offset);
        $this->db->order_by('premium DESC');
        $this->db->order_by('updated DESC');
        $query = $this->db->get();
        return $query->result_array();

    }*/

    public function get_job_by_id($id,$query_text = 0,$category_id = 0,$city_id = 0)
    {
        $this->db->select('jobs.id,IF(company_hidden > 0,0,company_id) AS company_id,title,salary_from,salary_to,companies.name AS company,companies.logo AS logo, premium, updated');
        $this->db->from('jobs');
        $this->db->join('companies','companies.id = jobs.company_id');

        if(!empty($query_text)){
            $this->db->like("title",$query_text);
        }

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

    public function get_jobs_by_ids($ids,$query_text = 0,$category_id = 0,$city_id = 0)
    {
        $this->db->select('jobs.id,IF(company_hidden > 0,0,company_id) AS company_id,title,salary_from,salary_to,companies.name AS company,companies.logo AS logo, premium, updated');
        $this->db->from('jobs');
        $this->db->join('companies','companies.id = jobs.company_id');

        if(!empty($query_text)){
            $this->db->like("title",$query_text);
        }

        if($category_id != 0){
            $this->db->where('jobs.category_id',$category_id);
        }

        if($city_id != 0){
            $this->db->where('jobs.city_id',$city_id);
        }
        $ids = explode(",", $ids);

        $this->db->where_in("jobs.id",$ids);

        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_markers($qt,$category_id,$city_id,$edu_id,$salary,$new,$premium,$work_time,
            $worker_type_id,$is_student,$is_school,$is_pensioneer,$is_disabled,$is_shift,$no_exp)
    {
        $this->db->select('markers.id AS mid, markers.lat, markers.lng, jobs.id AS jid,
        companies.average_salary AS avg_sal, companies.high_credit_rating AS credit');
        $this->db->from('markers');
        $this->db->join('jobs', 'jobs.marker_id = markers.id');
        $this->db->join('companies','companies.id = jobs.company_id');

        if(!empty($qt)){
            $this->db->like('jobs.title',$qt);
        }

        if(!empty($category_id)){
            $this->db->where('jobs.category_id',$category_id);
        }

        if(!empty($city_id)){
            $this->db->where('jobs.city_id',$city_id);
        }

        if($work_time != 0){
            $this->db->where('jobs.work_time_id',$work_time);
        }

        if($worker_type_id != 0){
            $this->db->where('jobs.worker_type_id',$worker_type_id);
        }

        if(!empty($is_student)){
            $this->db->where('jobs.is_student',1);
        }

        if(!empty($is_school)){
            $this->db->where('jobs.is_school',1);
        }

        if(!empty($is_pensioneer)){
            $this->db->where('jobs.is_pensioneer',1);
        }

        if(!empty($is_disabled)){
            $this->db->where('jobs.is_disabled',1);
        }

        if(!empty($is_shift)){
            $this->db->where('jobs.is_shift',1);
        }

        if(!empty($no_exp)){
            $this->db->where('jobs.no_exp',1);
        }

        if(!empty($new)){
            $this->db->where('updated > DATE_SUB(NOW(), INTERVAL 1 DAY)');
        }

        if(!empty($premium)){
            $this->db->where('jobs.premium',1);
        }

        if(!empty($salary)){
            $this->db->where("salary_to >=", $salary); // 500
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

    public function init_param($page,$query_text,$order_by,$city_id,$category_id,$edu_id,$salary,$new,$premium,$work_time,
                $worker_type_id,$is_student,$is_school,$is_pensioneer,$is_disabled,$is_shift,$no_exp)

    {
        $this->db->select("COUNT(id) AS jobs")
            ->from("jobs");
        if(!empty($query_text)){
            $this->db->like("title",$query_text);
        }

        if($category_id != 0){
            $this->db->where('jobs.category_id',$category_id);
        }

        if($city_id != 0){
            $this->db->where('jobs.city_id',$city_id);
        }

        if($work_time != 0){
            $this->db->where('jobs.work_time_id',$work_time);
        }

        if($worker_type_id != 0){
            $this->db->where('jobs.worker_type_id',$worker_type_id);
        }

        if(!empty($is_student)){
            $this->db->where('jobs.is_student',1);
        }

        if(!empty($is_school)){
            $this->db->where('jobs.is_school',1);
        }

        if(!empty($is_pensioneer)){
            $this->db->where('jobs.is_pensioneer',1);
        }

        if(!empty($is_disabled)){
            $this->db->where('jobs.is_disabled',1);
        }

        if(!empty($is_shift)){
            $this->db->where('jobs.is_shift',1);
        }

        if(!empty($no_exp)){

            $this->db->where('jobs.no_exp',1);
        }

        if(!empty($new)){
            $this->db->where('updated > DATE_SUB(NOW(), INTERVAL 1 DAY)');
        }

        if(!empty($premium)){
            $this->db->where('jobs.premium',1);
        }

        if(!empty($salary)){
            $this->db->where("salary_to >=", $salary); // 500
        }

        /*SELECT IFNULL(salary_from,0) as salary_from, IFNULL(salary_to,0) as salary_to
FROM `jobs`
WHERE
	CASE WHEN salary_from = 0 THEN salary_from <= 500 AND salary_to >= 500
    	 WHEN salary_to = 0 THEN salary_from >= 380 AND salary_from <= 500
         ELSE salary_from >= 380 AND salary_from <= 500 AND salary_to >= 500
    END*/


        $query = $this->db->get();
        return $query->row();
    }
}