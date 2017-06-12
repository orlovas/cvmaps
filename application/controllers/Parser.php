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
        $url = $this->input->get('url', TRUE);
        $this->url = $url;
        $this->website = $this->confirmWebsite($url);

        if (!$this->website['confirmed']) {
            die("Can't parse this website");
        }

        $this->page = $this->extractPage();

        if ($this->page == "") {
            die("Can't parse this website");
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
            'url' => $this->url,
            'website' => $this->website,
            'title' => $this->title,
            'expire' => $this->expire,
            'city' => $this->city,
            'category' => $this->category,
            'salary' => $this->salary
        ]));
    }

    private function confirmWebsite($url)
    {
        $confirmed = true;

        $host = $this->detectWebsite($url);

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
        // cvbankas
        return $this->page->find('h1', 0)->innertext;
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
                $expire = $this->page->find('#jobTxtRTable > tbody > tr', 2)->find('td',1)->innertext;
                $expire = DateTime::createFromFormat('Y.m.d', $expire)->format('Y-m-d');
                break;
            case 'dirba.lt':
                $expire = $this->page->find('#cont',-2)->find('h3',0)->plaintext;
                $expire = substr($expire, -10);
                $expire = DateTime::createFromFormat('Y-m-d', $expire)->format('Y-m-d');
                break;
            case 'vilnius.cvzona.lt':
            case 'kaunas.cvzona.lt':
            case 'klaipeda.cvzona.lt':
                $expire = $this->page->find('#tabs-1 > table > tr', 2)->find('td.value',0)->innertext;
                if(!$this->verifyDate($expire)){
                    $expire = $this->page->find('#tabs-1 > table > tr', 1)->find('td.value',0)->innertext;
                }
                $expire = DateTime::createFromFormat('Y-m-d', $expire)->format('Y-m-d');
                break;
            case 'cvonline.lt':
            case 'darbai24.lt':
            case 'cv.lt':
            default:
                return null;
        }

        return $expire;
    }

    private function extractCategory()
    {
        switch($this->website["host"]){
            case 'cvbankas.lt':
                $category = $this->page->find('a[data-category]', 2)->innertext;
                break;
            case 'cv.lt':
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

        return $category;
    }

    private function extractCity()
    {
        switch($this->website["host"]){
            case 'cvbankas.lt':
                $city = $this->page->find('a[data-category]', 1)->innertext;
                break;
            case 'cv.lt':
                $city = $this->page->find('#jobCont01 > span > a', 1)->innertext;
                break;
            case 'dirba.lt':
                $city = $this->page->find('.breadcrumb > ol',0)->find('li',-1)->plaintext;
                break;
            case 'cvonline.lt':
                $city = $this->page->find('span[itemprop=jobLocation]', 0)->innertext;
                break;
            case 'cvmarket.lt':
                $city = $this->page->find('.jobdetails', 0)->find('.jobdetails_value',0)->plaintext;
                break;
            case 'darbai24.lt':
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

        return $city;
    }

    private function extractSalary()
    {
        switch($this->website["host"]){
            case 'cvbankas.lt':
                $salary = $this->page->find('section', 4)->find('.jobad_txt',0)->plaintext;
                $salary = preg_replace('/\s+/', '', $salary);
                if(intval($salary) != 0){
                    $salary = intval($salary);
                } else {
                    $salary = intval(substr($salary,3));
                }
                break;
            case 'cv.lt':
                $salary = $this->page->find('#jobTxtRTable > tbody:nth-child(1) > tr:nth-child(1) > td:nth-child(2)',0)->plaintext;
                $salary = preg_replace('/\s+/', '', $salary);
                $salary = intval(substr($salary, 24));
                break;
            case 'cvonline.lt':
                $salary = $this->page->find('span[itemprop=baseSalary]',0)->plaintext;
                $salary = preg_replace('/\s+/', '', $salary);
                $salary = intval(substr($salary, 18));
                break;
            case 'cvmarket.lt':
                $salary = $this->page->find('.jobdetails', 4)->find('.jobdetails_value',0)->plaintext;
                $salary = preg_replace('/\s+/', '', $salary);
                if(substr($salary,0,2) === "iki"){
                    $salary = null;
                } else {
                    $salary = intval(substr($salary,3));
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
                if(!$this->page->find('#tabs-1 > table > tr', 1)->find('td.value > span',0)){
                    return 0;
                }

                $salary = $this->page->find('#tabs-1 > table > tr', 1)->find('td.value > span',0)->innertext;
                $salary = preg_replace('/\s+/', '', $salary);

                if(!$this->verifySalary($salary)){
                    $salary = $this->page->find('#tabs-1 > table > tr', 2)->find('td.value > span',0)->innertext;
                    $salary = preg_replace('/\s+/', '', $salary);

                    if(!$this->verifySalary($salary)){
                        $salary = 0;
                    }
                }
                $salary = intval($salary);
                break;
            case 'dirba.lt':
            default:
                return null;
        }

        return $salary;
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
        if(intval($salary)){
            return true;
        }
        if(!empty($salary)){
            return true;
        }

        return false;
    }
}