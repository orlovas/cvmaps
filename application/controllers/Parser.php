<?php

include_once('parser/simple_html_dom.php');

class Parser extends CI_Controller
{
    public $url;
    public $website;
    public $title;
    public $expire;
    public $city;
    public $category;
    public $salary;
    private $page;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('url', 'URL', 'trim|required|valid_url');
        if ($this->form_validation->run() == FALSE) {
            return;
        }

        $url = $this->input->post('url', TRUE);
        $this->load->model('queries');
        $this->url = $url;
        $this->website = $this->confirmWebsite($url);

        if (!$this->website['confirmed']) {
            return;
        }

        $this->page = $this->extractPage();

        if ($this->page == "") {
            return;
        }

        $this->title = $this->extractTitle();
        $this->salary = $this->extractSalary();
        $this->city = $this->extractCity();
        $this->category = $this->extractCategory();
        $this->expire = $this->extractExpire();
    }

    public function get()
    {
        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode([
            'website' => $this->website['host'],
            'title' => $this->title,
            'expire' => $this->expire,
            'city' => $this->city,
            'city_id' => $this->getCityId(),
            'category' =>
                ['original' => $this->category,
                 'cvm' => $this->categoryCrossing()
                ],
            'salary' => $this->salary,
            "csrfHash" => $this->security->get_csrf_hash()
        ]));
    }

    private function confirmWebsite($url)
    {
        $confirmed_list = ["cvbankas.lt","cv.lt","cvonline.lt","cvmarket.lt","dirba.lt","darbai24.lt","vilnius.cvzona.lt","kaunas.cvzona.lt","klaipeda.cvzona.lt"];

        $host = $this->detectWebsite($url);

        if(in_array($host,$confirmed_list)){
            $confirmed = true;
        } else {
            $confirmed = false;
        }

        return [
            'host' => $host,
            'confirmed' => $confirmed
        ];
    }

    private function detectWebsite($url)
    {
        return str_ireplace('www.', '', parse_url($url, PHP_URL_HOST));
    }

    private function extractPage()
    {
        return @file_get_html($this->url);
    }

    private function extractTitle()
    {
        if(!$this->page->find('h1', 0)){
            return null;
        }
        return $this->security->xss_clean($this->page->find('h1', 0)->plaintext);
    }

    private function extractExpire()
    {
        switch($this->website["host"]){
            case 'cvbankas.lt':
                $expire = $this->page->find('#jobad_expiration', 0)->innertext;
                $expire = preg_replace('/\s+/', '', $expire);
                $expire = substr($expire, 20);
                $expire = DateTime::createFromFormat('Y.m.d', $expire)->format('Y-m-d');
                break;
            case 'cvmarket.lt':
                /*$expire = $this->page->find('#jobTxtRTable > tbody > tr', 2)->find('td',1)->innertext;
                $expire = DateTime::createFromFormat('Y.m.d', $expire)->format('Y-m-d');*/
                $expire = null;
                break;
            case 'darbai24.lt':
                $expire = $this->page->find('#cont',-2)->find('h3',0)->plaintext;
                $expire = substr($expire, -10);
                $expire = DateTime::createFromFormat('Y-m-d', $expire)->format('Y-m-d');
                break;
            case 'vilnius.cvzona.lt':
            case 'kaunas.cvzona.lt':
            case 'klaipeda.cvzona.lt':
                $expire = $this->page->find('#tabs-1 > table > tr', 2)->find('td.value',0)->innertext;
                if(!$this->verifyDate($expire)){
                    $expire = $this->page->find('#tabs-1 > table > tr', 3)->find('td.value',0)->innertext;
                    if(!$this->verifyDate($expire)){
                        $expire = $this->page->find('#tabs-1 > table > tr', 1)->find('td.value',0)->innertext;
                    }
                }
                $expire = DateTime::createFromFormat('Y-m-d', $expire)->format('Y-m-d');
                break;
            case 'cvonline.lt':
            case 'dirba.lt':
            case 'cv.lt':
            default:
                return null;
        }

        return $this->security->xss_clean($expire);
    }

    private function extractCategory()
    {
        switch($this->website["host"]){
            case 'cvbankas.lt':
                $category = $this->page->find('a[data-category]', 2)->innertext;
                break;
            case 'cv.lt':
                if(!$this->page->find('#jobTxtRight > span', 0)){
                    $category = null;
                    break;
                }
                $category = $this->page->find('#jobTxtRight > span', 0)->innertext;
                break;
            case 'dirba.lt':
                $category = $this->page->find('.breadcrumb > ol',0)->find('li',1)->plaintext;
                break;
            case 'cvonline.lt':
            case 'cvmarket.lt':
            case 'vilnius.cvzona.lt':
            case 'kaunas.cvzona.lt':
            case 'klaipeda.cvzona.lt':
            case 'darbai24.lt':
            default:
                return null;
        }

        return $this->security->xss_clean($category);
    }

    private function extractCity()
    {
        switch($this->website["host"]){
            case 'cvbankas.lt':
                $city = $this->page->find('a[data-category]', 1)->innertext;
                break;
            case 'cv.lt':
                if(!$this->page->find('#jobCont01 > span > a', 1)){
                    $city = null;
                    break;
                }
                $city = $this->page->find('#jobCont01 > span > a', 1)->innertext;
                break;
            case 'dirba.lt':
                $city = $this->page->find('.breadcrumb > ol',0)->find('li',-1)->plaintext;
                break;
            case 'cvonline.lt':
                $city = $this->page->find('span[itemprop=jobLocation]', 0)->plaintext;
                $city = mb_ereg_replace("\(žemėlapis\)","",$city);
                $city = trim($city);
                break;
            case 'cvmarket.lt':
                $city = $this->page->find('.jobdetails', 0)->find('.jobdetails_value',0)->innertext;
                $city = preg_replace('/\s+/', '', $city);
                break;
            case 'darbai24.lt':
                if(!$this->page->find('#cont',2)->find('h3',0)){
                    $city = $this->page->find('#cont',1)->find('h3',0)->plaintext;
                    break;
                }
                $city = $this->page->find('#cont',2)->find('h3',0)->plaintext;
                break;
            case 'vilnius.cvzona.lt':
                $city = "Vilnius";
                break;
            case 'kaunas.cvzona.lt':
                $city = "Kaunas";
                break;
            case 'klaipeda.cvzona.lt':
                $city = "Klaipėda";
                break;
            default:
                return null;
        }

        return $this->security->xss_clean($city);
    }

    private function extractSalary()
    {
        switch($this->website["host"]){
            case 'cvbankas.lt':
                if(!$this->page->find('section', 4)->find('.jobad_txt',0)){
                    $salary = null;
                    break;
                }
                $salary = $this->page->find('section', 4)->find('.jobad_txt',0)->plaintext;
                $salary = preg_replace('/\s+/', '', $salary);
                if(intval($salary) != 0){
                    $salary = intval($salary);
                } else {
                    $salary = intval(substr($salary,3));
                }
                break;
            case 'cv.lt':
                if(!$this->page->find('#jobTxtRTable > tbody:nth-child(1) > tr:nth-child(1)',0)){
                    $salary = null;
                    break;
                }
                $salary = $this->page->find('#jobTxtRTable > tbody:nth-child(1) > tr:nth-child(1)',0)->find('td',1)->plaintext;
                $salary = preg_replace('/\s+/', '', $salary);
                $salary = preg_replace('/\./', '', $salary);
                if(intval($salary[0]) == 0){
                    $salary = substr($salary, 3);
                }
                if(!$this->verifySalary($salary)){
                    $salary = null;
                }
                $salary = intval($salary);
                break;
            case 'cvonline.lt':
                $salary = $this->page->find('span[itemprop=baseSalary]',0)->plaintext;
                $salary = preg_replace('/\s+/', '', $salary);
                $salary = intval(substr($salary, 18));
                break;
            case 'cvmarket.lt':
                if(!$this->page->find('.jobdetails', -1)){
                    return 0;
                }
                $salary = $this->page->find('.jobdetails', -1)->find('.jobdetails_value',0)->plaintext;
                $salary = preg_replace('/\s+/', '', $salary);
                if(substr($salary,0,1) == "i"){
                    $salary = null;
                } else {
                    $salary = intval(substr($salary,3));
                }

                if(!$this->verifySalary($salary)){
                    $salary = null;
                }
                break;
            case 'darbai24.lt':
                $salary = $this->page->find('#cont',-3)->find('h3',0)->plaintext;
                $salary = preg_replace('/\s+/', '', $salary);
                $salary = intval($salary);
                break;
            case 'vilnius.cvzona.lt':
            case 'kaunas.cvzona.lt':
            case 'klaipeda.cvzona.lt':
                if($this->page->find('#tabs-1 > table > tr', 1)->find('td.value > span',0)) {
                    $salary = $this->page->find('#tabs-1 > table > tr', 1)->find('td.value > span', 0)->innertext;
                    $salary = preg_replace('/\s+/', '', $salary);
                } else if($this->page->find('#tabs-1 > table > tr', 2)->find('td.value > span',0)) {

                    $salary = $this->page->find('#tabs-1 > table > tr', 2)->find('td.value > span', 0)->innertext;
                    $salary = preg_replace('/\s+/', '', $salary);

                    if (!$this->verifySalary($salary)) {
                        $salary = null;
                    }
                } else {
                    $salary = null;
                }
                $salary = intval($salary);
                break;
            case 'dirba.lt':
                $salary = $this->page->find('.iR > .s14', 2)->plaintext;
                if(!$this->verifySalary($salary)){
                    $salary = $this->page->find('.iR > .s14', 1)->plaintext;
                }
                $salary = intval($salary);
                break;
            default:
                return null;
        }

        return $this->security->xss_clean($salary);
    }

    private function categoryCrossing()
    {
        return $this->queries->categories_crossing($this->category);
    }

    private function getCityId()
    {
        return $this->queries->find_city_id_by_name($this->city);
    }

    static public function verifyDate($date, $strict = true)
    {
        $dateTime = DateTime::createFromFormat('Y-m-d', $date);
        if ($strict) {
            $errors = DateTime::getLastErrors();
            if (!empty($errors['warning_count'])) {
                return false;
            }
        }
        return $dateTime !== false;
    }

    static public function verifySalary($salary)
    {
        // v cvmarket ili cv.lt eto gde-to nuzhno. Ubral iz-za konfilkta s cvzona
        if($salary < 50 || $salary > 2016){
            return false;
        }
        if(intval($salary)){
            return true;
        }
        if(!empty($salary)){
            return true;
        }

        return false;
    }
}