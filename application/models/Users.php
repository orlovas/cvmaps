<?php

class Users extends CI_Model {
    public function __construct()
    {
        $this->load->database();
    }

    public function prelogin($email)
    {
        $this->db->select("id, password, group_id");
        $this->db->from('users');
        $this->db->where('email',$email);

        $query = $this->db->get();
        return $query->result_array();
    }

    public function register($email,$password,$group_id,$email_subscription,$partners_subscription)
    {

        if($this->existEmail($email)){
            return false;
        }

        /**
         * Encrypt password
         */

        $options = [
            'cost' => 11
        ];

        $password = password_hash($password, PASSWORD_BCRYPT, $options);

        $data = array(
            'email' => $email,
            'password' => $password,
            'group_id' => $group_id,
            'email_subscription' => $email_subscription,
            'partners_subscription' => $partners_subscription
        );

        $this->db->insert('users', $data);



        return $this->db->insert_id();

    }

    private function existEmail($email)
    {
        $this->db->select("email");
        $this->db->from("users");
        $this->db->where("email",$email);
        return ($this->db->count_all_results() ? 1 : 0);
    }

    public function writeToken($token,$user_id,$expires)
    {
        $data = array(
            'token' => $token,
            'user_id' => $user_id,
            'expires' => $expires
        );

        $this->db->insert('user_tokens', $data);
    }

    public function deleteToken($token)
    {
        $this->db->delete('user_tokens', array('token' => $token));
    }

    public function loginFromCookies($token)
    {
        $this->db->select("user_id,token,expires");
        $this->db->from("user_tokens");
        $this->db->where("token",$token);
        $this->db->where("expires > UNIX_TIMESTAMP()");
        $query = $this->db->get();
        $row = $query->row();
        if(isset($row)){
            return $row->user_id;
        } else {
            return false;
        }
    }

    public function confirm_user($data,$user_id)
    {
        $query = $this->db->select("id")->from($data["type"])->where(array("id"=>$data["id"],"user_id"=>$user_id))->get()->row();
        if($query) return true;
    }

}