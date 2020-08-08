<?php
//Curl Api Request
class CurlApi
{

    private $ch;
    private $url;
    private $data;
    private $method;
    private $request;
    private $cookies = "cookies/cookies.cks";
    private $option = [];
    private $header = [];

    public function __construct($req)
    {
        $this->ch = curl_init();
        $this->request = $req;
    }

    public function Get($url, $data, $header = [])
    {
        $this->url = $url;
        $this->data = $data;
        $this->method = "GET";

        $this->DefaultOpt();
        $this->SetHeader($header);
        $this->SetOpt();
        return $this->GetExec();
    }

    public function Post($url, $data, $header = [])
    {
        $this->url = $url;
        $this->data = $data;
        $this->method = "POST";

        $this->DefaultOpt();
        $this->SetHeader($header);
        $this->SetOpt();
        return $this->GetExec();
    }

    public function Delete($url, $data, $header = [])
    {
        $this->url = $url;
        $this->data = $data;
        $this->method = "DELETE";

        $this->DefaultOpt();
        $this->SetHeader($header);
        $this->SetOpt();
        return $this->GetExec();
    }

    public function Put($url, $data, $header = [])
    {
        $this->url = $url;
        $this->data = $data;
        $this->method = "PUT";

        $this->DefaultOpt();
        $this->SetHeader($header);
        $this->SetOpt();
        return $this->GetExec();
    } 

    private function DefaultOpt()
    {
        $this->GetMethod();
        $this->CheckData();
        $this->SetCookies();
        $this->SetSsl();
        $this->option[CURLOPT_RETURNTRANSFER] = true;
        $this->option[CURLOPT_FOLLOWLOCATION] = true;
        $this->option[CURLOPT_AUTOREFERER] = true;
        $this->option[CURLOPT_TIMEOUT] = 0;
        $this->option[CURLOPT_CONNECTTIMEOUT] = 10;
        $this->option[CURLOPT_USERAGENT] = 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:5.0) Gecko/20110619 Firefox/5.0';
    }

    private function SetSsl()
    {
        $parse = parse_url($this->url, PHP_URL_SCHEME);
        if ($parse == "https") {
            $this->option[CURLOPT_SSL_VERIFYHOST] = false;
            $this->option[CURLOPT_SSL_VERIFYPEER] = false;
        }
    }

    private function SetCookies()
    {
        if ($this->folder_exist("cookies") == false) {
            mkdir("cookies");
        }

        $this->option[CURLOPT_COOKIEJAR] = $this->cookies;
        $this->option[CURLOPT_COOKIEFILE] = $this->cookies;
    }

    private function GetMethod()
    {
        if ($this->method === "GET") {

            $this->option[CURLOPT_URL] = $this->url .(!empty($this->data)) ? "?" . http_build_query($this->data) :"";
        } else {

            $this->option[CURLOPT_URL] = $this->url;
            $this->option[CURLOPT_CUSTOMREQUEST] = $this->method;
        }
    }

    private function CheckData()
    {
        if ($this->method !== "GET") {
            if ($this->request === "isjson") {

                $this->header[] =  'Content-Type: application/json';

                if (!empty($this->data)) {
                    $this->option[CURLOPT_POSTFIELDS] = (is_array($this->data) || $this->data instanceof stdClass) ? json_encode($this->data) : $this->data;
                }
            } elseif ($this->request === "notjson") {

                $this->option[CURLOPT_POSTFIELDS] = $this->data;
            }
        }
    }

    private function folder_exist($folder)
    {
        $path = realpath($folder);
        return ($path !== false and is_dir($path)) ? $path : false;
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
