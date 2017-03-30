<?php

class Companies extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_company($id)
    {
        return $this->db->select("id,user_id,name,average_salary,high_credit_rating,logo")->from("companies")->where("id",$id)->get()->row();
    }

    public function create_company($user_id,$name,$average_salary,$high_credit_rating,$logo)
    {
        $data = array(
            'user_id' => $user_id,
            'name' => $name,
            'average_salary' => $average_salary,
            'high_credit_rating' => $high_credit_rating,
            'logo' => $logo
        );
        $query = $this->db->insert('companies', $data);
        if(!$query) return false;
        return true;
    }

    public function edit_company($id,$name,$average_salary,$high_credit_rating,$logo)
    {
        $data = array(
            'name' => $name,
            'average_salary' => $average_salary,
            'high_credit_rating' => $high_credit_rating,
            'logo' => $logo
        );

        $this->db->where('id', $id);
        $this->db->update('companies', $data);
        return true;
    }

    public function get_company_id($user_id)
    {
        $query = $this->db->select("id")->from("companies")->where("user_id",$user_id)->get();
        if($query){
            return $query->row();
        } else {
            return 0;
        }

    }

}