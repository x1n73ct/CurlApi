<?php
//Curl Api Request
class CurlApi
{

    private $ch;
    private $option = [];
    private $header = [];

    public function __construct()
    {
        $this->ch = curl_init();
        $this->header[] =  'Content-Type: application/json';
    }


    public function Get($url, $header = [])
    {
        $this->Urls($url);
        $this->Method("GET");
        $this->DefaultOpt();
        $this->SetHeader($header);
        $this->SetOpt();
        return $this->GetExec();
    }

    public function Post($url, $data, $header = [])
    {
        $this->Urls($url);
        $this->Method("POST");
        $this->DefaultOpt();
        $this->SetHeader($header);
        $this->option[CURLOPT_POSTFIELDS] = $this->CheckData($data);
        $this->SetOpt();
        return $this->GetExec();
    }

    public function Delete($url, $data, $header = [])
    {
        $this->Urls($url);
        $this->Method("DELETE");
        $this->DefaultOpt();
        $this->SetHeader($header);
        if (!empty($data)) {
            $this->option[CURLOPT_POSTFIELDS] = $this->CheckData($data);
        }
        $this->SetOpt();
        return $this->GetExec();
    }

    public function Put($url, $data, $header = [])
    {
        $this->Urls($url);
        $this->Method("PUT");
        $this->DefaultOpt();
        $this->SetHeader($header);
        $this->option[CURLOPT_POSTFIELDS] = $this->CheckData($data);
        $this->SetOpt();
        return $this->GetExec();
    }

    private function CheckData($data)
    {
        return (is_array($data) || $data instanceof stdClass) ? json_encode($data) : $data;
    }

    private function Urls($url)
    {
        $this->option[CURLOPT_URL] = $url;
    }

    private function Method($method)
    {
        $this->option[CURLOPT_CUSTOMREQUEST] = $method;
    }

    private function DefaultOpt()
    {
        $this->option[CURLOPT_RETURNTRANSFER] = true;
        $this->option[CURLOPT_FOLLOWLOCATION] = true;
        $this->option[CURLOPT_AUTOREFERER] = true;
        $this->option[CURLOPT_CONNECTTIMEOUT] = 10;
        $this->option[CURLOPT_SSL_VERIFYPEER] = false;
        $this->option[CURLOPT_USERAGENT] = 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:5.0) Gecko/20110619 Firefox/5.0';
        $this->option[CURLOPT_TIMEOUT] = 0;
    }

    private function SetHeader($header_array = [])
    {
        if (!empty($header_array)) {
            $header = $this->header;
            $this->header = array_merge($header, $header_array);
        }
        $this->option[CURLOPT_HTTPHEADER] = $this->header;
    }

    private function SetOpt()
    {
        curl_setopt_array($this->ch, $this->option);
    }

    public function GetExec()
    {
        $execute = curl_exec($this->ch);
        curl_close($this->ch);
        return $execute;
    }
}
