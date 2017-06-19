<?php

include_once('parser/simple_html_dom.php');

class Company extends CI_Controller
{
    private $data_url = "http://sodra.is.lt/Failai/Vidurkiai.zip";
    private $data_zip = "Vidurkiai.zip";
    private $data_filename = "VIDURKIAI.CSV";

    public function __construct()
    {
        parent::__construct();
    }

    public function searchByNumber()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('number', 'number', 'required');
        if ($this->form_validation->run() == FALSE) {
            return;
        }

        $number = $this->input->post('number', TRUE);

        $page = @file_get_html("http://imones.lrytas.lt/paieska/p?cc=".$number);

        if($page->find('h2',0)){
            $name = $page->find('h2',0)->plaintext;
            $salary = $this->getAverageSalary($number);
        } else {
            $name = null;
            $salary = null;
        }

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode([
            'name' => $name,
            'salary' => $salary,
            "csrfHash" => $this->security->get_csrf_hash()
        ]));
    }

    /*public function searchByName()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('company', 'company', 'required');
        if ($this->form_validation->run() == FALSE) {
            return;
        }

        $company = $this->input->post('company', TRUE);

        $company = str_replace(" ", "+",$company);
        $page = @file_get_html("http://118.15min.lt/imones/paieskos-rezultatai?actionId=7&name=".$company."&act=&rgn=&det=0");
        $data = $page->find('.result h2');

        $variants = [];

        foreach($data as $d){
            array_push($variants,$d->plaintext);
        }

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode([
            'variants' => $variants,
            "csrfHash" => $this->security->get_csrf_hash()
        ]));

        $number = "";
        if(sizeof($variants) == 1){
            $url = $variants[0]->href;
            $page = @file_get_html("http://118.15min.lt/imones/".$url);
            $number = trim($page->find('#phMainContent_CompanyDetailedInfo_codeRow',0)->find('td',0)->plaintext);
            echo $number;
            echo "<br>";
        }

        echo $this->getAverageSalary($number);
    }*/
    
    public function getAverageSalary($number)
    {
        $file = fopen($this->data_filename, "r");
        if($file !== FALSE) {
            while(! feof($file)) {
                $data = fgetcsv($file, 1000, ";");
                if($data[0] == $number){
                    fclose($file);
                    return trim($data[2]);
                }
            }
        }
        return null;
    }

    public function downloadData()
    {
        file_put_contents($this->data_zip, fopen($this->data_url, 'r'));
    }

    private function unzipData()
    {
        $path = pathinfo(realpath($this->data_zip), PATHINFO_DIRNAME);
        $zip = new ZipArchive;
        $res = $zip->open($this->data_zip);
        if ($res === TRUE) {
            $zip->extractTo($path);
            $zip->close();
            return true;
        } else {
            return false;
        }
    }
}