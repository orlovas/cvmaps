<?php

class Users extends CI_Model {
    /**
     * Users constructor.
     */
    public function __construct()
    {
        $this->load->database();
    }

    /**
     * Metodas patikrina vartotojo egzistavimą pagal e-mail
     * @param $email
     * @return array
     */
    public function prelogin($email)
    {
        $this->db->select("id, password, group_id");
        $this->db->from('users');
        $this->db->where('email',$email);

        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Metodas į vartotojų lentėlę prideda naują įrašą apie naują vartotoją
     * @param $email
     * @param $password
     * @param $group_id
     * @param $email_subscription
     * @param $partners_subscription
     * @return bool
     */
    public function register($email, $password, $group_id, $email_subscription, $partners_subscription)
    {
        // Tikriname ar jau yra naudojamas toks e-mail sistemoje
        if($this->existEmail($email)){
            return false;
        }

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

    /**
     * Metodas ieško el. pašto sutapimus vartotojų lentėlyje
     * @param $email
     * @return int
     */
    private function existEmail($email)
    {
        $this->db->select("email");
        $this->db->from("users");
        $this->db->where("email",$email);
        return ($this->db->count_all_results() ? 1 : 0);
    }

    /**
     * Metodas įrašo į vartotojų lentėlę duomenis apie slapuką
     * @param $token
     * @param $user_id
     * @param $expires
     */
    public function writeToken($token, $user_id, $expires)
    {
        $data = array(
            'token' => $token,
            'user_id' => $user_id,
            'expires' => $expires
        );

        $this->db->insert('user_tokens', $data);
    }

    /**
     * Slapuko pašalinimas iš duombazes
     * @param $token
     */
    public function deleteToken($token)
    {
        $this->db->delete('user_tokens', array('token' => $token));
    }

    /**
     * Vartotojo prisijungimas iš slapukų
     * @param $token
     * @return bool
     */
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

    /**
     * Tikrinimi vartotojo teisės
     * @param $data
     * @param $user_id
     * @return bool
     */
    public function confirm_user($data, $user_id)
    {
        $query = $this->db->select("id")->from($data["type"])->where(array("id"=>$data["id"],"user_id"=>$user_id))->get()->row();
        return $query ? true : false;
    }

}