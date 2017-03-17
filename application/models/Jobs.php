<?php

class Jobs extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getUserJobs($user_id)
    {
        $this->db->select("id, title, created, updated, expires, address, active");
        $this->db->from('jobs');
        $this->db->where('user_id',$user_id);

        $query = $this->db->get();
        return $query->result_array();
    }

    public function add_job(
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
                $no_exp)
    {
        $data = array(
            'company_id' => $company_id,
            'user_id' => $user_id,
            'category_id' => $category_id,
            'title' => $title,
            'description' => $description,
            'requirements' => $requirements,
            'company_offer' => $offer,
            'address' => $address,
            'city_id' => $city_id,
            'marker_id' => '0',
            'work_time_id' => $work_time_id,
            'worker_type_id' => $worker_type_id,
            'salary_from' => $salary_from,
            'salary_to' => $salary_to,
            'phone' => $phone,
            'email' => $email,
            'website' => $website,
            'is_student' => $is_student,
            'is_school' => $is_school,
            'is_pensioneer' => $is_pensioneer,
            'is_disabled' => $is_disabled,
            'is_shift' => $is_shift,
            'no_exp' => $no_exp
        );

        $this->db->insert('jobs', $data);

        return true;
    }
}