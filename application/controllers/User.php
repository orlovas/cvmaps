<?php
/*
 * Vartotojo valdymo klasė
 */
class User extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('users'); // klasė darbui su vartotojų duombaze
        $this->load->model('companies'); // klasė darbui su kompanijų duombaze
        $this->load->helper('url_helper');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->helper('cookie');
    }

    /**
     * Vartotojo prisijungimo metodas
     */
    public function login()
    {
        // Formos įvesti duomenys tikrinami pagal nurodytas taisykles
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');

        // Tikrinima, ar visi formos laukai teisingai užpildyti, neleidžiama toliau vykdyti kodą ir rodoma klaidą
        if ($this->form_validation->run() == FALSE) {
            redirect("?error=bad_login");
        } else {
            // Duomenų iš formos skaitymas
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            // Reikia patikrinti, ar egzistuoja toks vartotojas su tokiu e-mail
            $prelogin = $this->users->prelogin($email);

            if(!empty($prelogin)){
                // Tikrinami įvestas slaptažodžio šifras su šifru, kuris saugomas duombazėje
                if(password_verify($password,$prelogin[0]['password'])){
                    // Generuojamas access token
                    $token = $this->generateToken();

                    // Slapuko nustatymai
                    $expire = time()+2592000; // 1 mėn.
                    $cookie = [
                        "name" => "token",
                        "value" => $token,
                        "expire" => $expire,
                        "path" => ini_get('session.cookie_path'),
                        "domain" => ini_get('session.cookie_domain'),
                        "secure" => false,
                        "httponly" => true
                    ];

                    // Slapuko generavimas
                    setcookie(
                        $cookie['name'],
                        $cookie['value'],
                        $cookie['expire'],
                        $cookie['path'],
                        $cookie['domain'],
                        $cookie['secure'],
                        $cookie['httponly']
                    );

                    // Slapuko 'id' įrašymas į duombazę
                    $this->users->writeToken($token,$prelogin[0]['id'],$expire);
                    redirect("?success=login");
                } else {
                    redirect("?error=bad_login");
                }
            } else {
                redirect("?error=bad_login");
            }
        }
    }

    public function register()
    {
        // Formos įvesti duomenys tikrinami pagal nurodytas taisykles
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('password_repeat', 'Password Repeat', 'trim|required');
        $this->form_validation->set_rules('name', 'name', 'trim|required');
        $this->form_validation->set_rules('average_salary', 'average salary', 'trim|required|numeric');
        $this->form_validation->set_rules('high_credit_rating', 'high credit rating', 'trim|required');

        // Tikrinima, ar visi formos laukai teisingai užpildyti, neleidžiama toliau vykdyti kodą ir rodoma klaidą
        if ($this->form_validation->run() == FALSE) {
            redirect("?error=bad_registration");
        } else {
            // Duomenų iš formos skaitymas
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $password_repeat = $this->input->post('password_repeat');

            // Slaptažodžių patikrinimas
            if(!$this->comparePass($password,$password_repeat)){
                redirect("?error=bad_registration");
                return false;
            }

            // Slaptažodžio šifravimas
            $options = [
                'cost' => 11
            ];

            $password = password_hash($password, PASSWORD_BCRYPT, $options);

            $register = $this->users->register($email,$password,1,1,1);

            // Jeigu pavyko įregistruoti vartotoją, toliau reikia įrašyti firmos duomenys
            if(!is_null($register)){
                // Duomenų iš formos skaitymas
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
                    redirect("?error=bad_registration");
                }
            } else {
                redirect("?error=bad_registration");
            }
        }
    }

    /**
     * Logotipo užkrovimas į serverį
     * @param $logo
     * @return bool
     */
    private function upload_logo($logo)
    {
        // Logotipo parametrų apribojimai
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

            // Nuotraukos apdorojimas
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

    /**
     * Vartotojo atsijungimo metodas
     * @return bool
     */
    public function logout()
    {
        // Trinimas slapukas
        delete_cookie('token');
        $token = $_COOKIE["token"];

        // Šalinami sesijos duomenis
        $_SESSION = array();

        // Šalinami sesijos slapukai
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Kitų būdu šalinami sesiją
        session_destroy();

        // Slapuko 'id' šalinimas iš duombazės
        $this->users->deleteToken($token);
        redirect();
        return true;
    }

    /**
     * Slaptažodžio ir pakartato slaptažodžio palyginimas
     * @param $pass1
     * @param $pass2
     * @return bool
     */
    private function comparePass($pass1, $pass2)
    {
        return $pass1 == $pass2 ? true : false;
    }

    /**
     * Generuojama atsiktinę bitų seką
     * @param int $length
     * @return string
     */
    private function generateToken($length = 16){
        $bytes = openssl_random_pseudo_bytes($length, $cstrong);
        return bin2hex($bytes);
    }
}