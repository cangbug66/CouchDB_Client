<?php
namespace CouchDB_Client\src;

use CouchDB_Client\src\Contracts\ClientContract;

class Client implements ClientContract{
    public function get($url,$data='')
    {
        return $this->request($url,"GET",$data);
    }
    public function put($url,$data='')
    {
        return $this->request($url,"PUT",$data);
    }
    public function delete($url,$data='')
    {
        return $this->request($url,"DELETE",$data);
    }

    public function request($url='',$method='GET',$data=""){
        $header[] = "Content-Type:application/json";
        $header[] = "Authorization: Basic ".base64_encode($this->auth);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        switch ($method){
            case "PUT":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"PUT");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
            case "DELETE":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"DELETE");
                break;
            case "POST":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
        }
        $output = curl_exec($ch);
        curl_close($ch);
        $output = json_decode($output,true);
        return $output;
    }
}
