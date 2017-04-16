<?php

class User extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('users');
        $this->load->model('companies');
        $this->load->helper('url_helper');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->helper('cookie');
    }

    public function index()
    {
        if(isset($_COOKIE["token"])){
            $token = $_COOKIE["token"];

            $user_id = $this->users->loginFromCookies($token);
            if(isset($user_id)){
                echo $user_id.' <a href="http://[::1]/cvm/index.php/user/logout">logout</a>';
            } else {
            $this->load->view('auth');
        }

        } else {
            $this->load->view('auth');
        }

    }

    public function login()
    {
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('auth');
        } else {

            $email = $this->input->post('email');
            $password = $this->input->post('password');

            $prelogin = $this->users->prelogin($email);

            if(!empty($prelogin)){
                /**
                 * Verify passwords
                */

                if(password_verify($password,$prelogin[0]['password'])){
                    /**
                     * Generate token for cookies
                     */

                    $token = $this->generateToken();

                    /**
                     * Token cookie settings
                     */

                    $expire = time()+2592000; // 1 month

                    $cookie = [
                        "name" => "token",
                        "value" => $token,
                        "expire" => $expire,
                        "path" => ini_get('session.cookie_path'),
                        "domain" => ini_get('session.cookie_domain'),
                        "secure" => false, // only for https
                        "httponly" => true
                    ];

                    /**
                     * Set cookie
                     */

                    setcookie(
                        $cookie['name'],
                        $cookie['value'],
                        $cookie['expire'],
                        $cookie['path'],
                        $cookie['domain'],
                        $cookie['secure'],
                        $cookie['httponly']
                    );

                    $this->users->writeToken($token,$prelogin[0]['id'],$expire);
                    redirect("?success=login");
                } else {
                    redirect("?error=bad_login");
                }
            } else {
                $this->output->set_output("Email not found");
            }
        }

    }

    public function register()
    {
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('password_repeat', 'Password Repeat', 'trim|required');
        $this->form_validation->set_rules('name', 'name', 'trim|required');
        $this->form_validation->set_rules('average_salary', 'average salary', 'trim|required|numeric');
        $this->form_validation->set_rules('high_credit_rating', 'high credit rating', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            redirect("?error=bad_registration");
        } else {
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $password_repeat = $this->input->post('password_repeat');

            /**
             * Compare passwords for equality
             */

           if(!$this->comparePass($password,$password_repeat)){
                echo 'error, not equal passwords';
                return false;
            }
            $register = $this->users->register($email,$password,1,1,1);
            if(!is_null($register)){
                $name = $this->input->post('name');
                $average_salary = $this->input->post('average_salary');
                $high_credit_rating = $this->input->post('high_credit_rating');
                $logo = $this->input->post('logo');
                if($logo !== "default.png"){
                    $logo = $this->upload_logo('logo');
                }

                if($this->companies->create_company($register,$name,$average_salary,$high_credit_rating,$logo)){
                    redirect("?success=registration");
                } else {
                    var_dump($this->db->error());
                }

            } else {
                echo 'error';
            }
        }
    }

    private function upload_logo($logo){
        $config['upload_path']          = 'static/images/l/';
        $config['allowed_types']        = 'gif|jpg|png|jpeg|tiff';
        $config['max_size']             = 2048;
        $config['max_width']            = 2048;
        $config['max_height']           = 2048;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($logo)) {
            $error = array('error' => $this->upload->display_errors());
            var_dump($error);
            return false;
        } else {
            $data = array('upload_data' => $this->upload->data());

            $config['image_library'] = 'gd2';
            $config['source_image'] = $this->upload->data('full_path');
            $config['maintain_ratio'] = TRUE;
            $config['width']         = 74;
            $config['height']       = 74;

            $this->load->library('image_lib', $config);

            if(!$this->image_lib->resize()){
                echo $this->image_lib->display_errors();
                return false;
            }

            return $this->upload->data('file_name');
        }
    }

    public function logout()
    {
        delete_cookie('token');
        $token = $_COOKIE["token"];

        // Unset all of the session variables.
        $_SESSION = array();

        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Finally, destroy the session.
        session_destroy();

        $this->users->deleteToken($token);
        redirect();
        return true;
    }

    private function comparePass($pass1,$pass2)
    {
        if($pass1 == $pass2){
            return true;
        } else {
            return false;
        }
    }

    private function generateToken($length = 16){
        $bytes = openssl_random_pseudo_bytes($length, $cstrong);
        return bin2hex($bytes);
    }
}