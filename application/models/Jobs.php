<?php
// JA NEOCHEN UMNYJ.
class Jobs extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getUserJobs($user_id, $json = false)
    {
        if($json){
            $this->db->select('markers.id AS mid, markers.lat, markers.lng, jobs.id AS jid,
        companies.average_salary AS avg_sal, companies.high_credit_rating AS credit, jobs.url_id AS u');
            $this->db->from('markers');
            $this->db->join('jobs', 'jobs.marker_id = markers.id');
            $this->db->join('companies','companies.id = jobs.company_id');
            $this->db->where('jobs.user_id',$user_id);

        } else {
            $this->db->select("id, title, marker_id, address");
            $this->db->from('jobs');
            $this->db->where('user_id',$user_id);

        }

        $query = $this->db->get();
        return $query->result_array();
    }

    public function getUserJobsInit($user_id)
    {
        $this->db->select("COUNT(id) AS jobs")->from("jobs")->where('user_id',$user_id);
        $query = $this->db->get();
        return $query->row();
    }

    public function add_job(
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
    ){
        $data = array(
            'company_id' => $company_id,
            'user_id' => $user_id,
            'category_id' => $category_id,
            'title' => $title,
            'address' => $address,
            'city_id' => $city_id,
            'marker_id' => $marker_id,
            'work_time_id' => $work_time_id,
            'salary_from' => ($salary_from == 0 ? NULL : $salary_from),
            'salary_to' => ($salary_to == 0 ? NULL : $salary_to),
            'edu_id'=> $edu_id,
            'url_id' => $url_id
        );

        $this->db->insert('jobs', $data);

        return true;
    }

    public function edit_job(
        $id,
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
    ){
        $data = array(
            'company_id' => $company_id,
            'user_id' => $user_id,
            'category_id' => $category_id,
            'title' => $title,
            'address' => $address,
            'city_id' => $city_id,
            'marker_id' => $marker_id,
            'work_time_id' => $work_time_id,
            'salary_from' => ($salary_from == 0 ? NULL : $salary_from),
            'salary_to' => ($salary_to == 0 ? NULL : $salary_to),
            'edu_id'=> $edu_id,
            'url_id' => $url_id
        );

        $this->db->where('id', $id);
        $this->db->update('jobs', $data);
        return true;
    }

    public function delete_job($id)
    {
        $job = $this->get_job_by_id($id);

        if(!$this->delete_marker($job->marker_id)) return false;
        if(!$this->delete_url($job->url_id)) return false;

        if(!$this->db->where('id', $id)->delete('jobs')) return false;

        return true;
    }

    public function delete_marker($marker_id)
    {
        if($this->db->where('id', $marker_id)->delete('markers')){
            return true;
        } else {
            return false;
        }
    }

    public function delete_url($url_id)
    {
        if($this->db->where('id', $url_id)->delete('urls')){
            return true;
        } else {
            return false;
        }

    }

    public function add_url($url){
        $data = array(
            'url' => $url
        );
        $this->db->insert('urls',$data);
        return $this->db->insert_id();
    }

    public function add_marker($coordinates){
        $data = array(
            'lat' => $coordinates[0],
            'lng' => $coordinates[1]
        );
        $this->db->insert('markers',$data);
        return $this->db->insert_id();
    }

    public function get_job_by_id($id){
        $this->db->select("jobs.id, user_id, title, address, category_id, work_time_id, salary_from, salary_to, edu_id, url_id, marker_id, urls.url AS url, markers.lat AS lat, markers.lng AS lng");
        $this->db->from('jobs');
        $this->db->join('urls', 'urls.id = jobs.url_id');
        $this->db->join('markers', 'markers.id = jobs.marker_id');
        $this->db->where('jobs.id',$id);

        if($query = $this->db->get()){
            return $query->row();
        } else {
            return false;
        }

    }

    public function get_url_by_id($url_id){
        return $this->db->select("url")->from('urls')->where("id",$url_id)->get()->row();
    }

    public function get_job_address($id){
        return $this->db->select("address")->from('jobs')->where("id",$id)->get()->row();
    }
}