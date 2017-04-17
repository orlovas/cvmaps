<?php

class Companies extends CI_Model
{
    /**
     * Companies constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Įmonės paieška pagal nurodytą id
     * @param $id
     * @return mixed
     */
    public function get_company($id)
    {
        return $this->db->select("id,user_id,name,average_salary,high_credit_rating,logo")->from("companies")->where("id",$id)->get()->row();
    }

    /**
     * Metodas sukuria įmonių lentėlyje naują įrašą - naują kompaniją
     * @param $user_id
     * @param $name
     * @param $average_salary
     * @param $high_credit_rating
     * @param $logo
     * @return bool
     */
    public function create_company($user_id, $name, $average_salary, $high_credit_rating, $logo)
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

    /**
     * Išsaugoti firmos pakeitimus duombazėje
     * @param $id
     * @param $name
     * @param $average_salary
     * @param $high_credit_rating
     * @param $logo
     * @return bool
     */
    public function edit_company($id, $name, $average_salary, $high_credit_rating, $logo)
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

    /**
     * Surasti įmonę pagal id
     * @param $user_id
     * @return int
     */
    public function get_company_id($user_id)
    {
        $query = $this->db->select("id")->from("companies")->where("user_id",$user_id)->get();
        return $query ? $query->row() : 0;

    }

}