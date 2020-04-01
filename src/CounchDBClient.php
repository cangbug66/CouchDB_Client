<?php
namespace CouchDB_Client\src;

use CouchDB_Client\src\Contracts\CounchDBClientContract;
use CouchDB_Client\src\Exceptions\ParamException;


class CounchDBClient extends Client implements CounchDBClientContract {
    protected $host;
    protected $auth;
    public function __construct($host,$port,$username,$passwd,$scheme="http")
    {
        if(empty($host) || empty($port)) throw new ParamException("host或port不能为空");
        if(empty($username) || empty($passwd)) throw new ParamException("用户名或密码不能为空");
        $this->host = sprintf("%s://%s:%s",$scheme,$host,$port);
        $this->auth = sprintf("%s:%s",$username,$passwd);
    }

    protected function all_url($dbname,$id='',$rev=''){
        $url = sprintf("%s/%s",$this->host,strtolower(trim($dbname)));
        if (!empty($id)) {
            $url = sprintf("%s/%s",$url,$id);
        }
        if (!empty($rev)) {
            $url = sprintf("%s?rev=%s",$url,$rev);
        }
        return $url;
    }

    public function createDB($dbname)
    {
        if(empty($dbname)) throw new ParamException("数据库名不能为空");
        $url = $this->all_url($dbname);
        return $this->put($url);
    }

    public function deleteDB($dbname)
    {
        if(empty($dbname)) throw new ParamException("数据库名不能为空");
        $url = $this->all_url($dbname);
        return $this->delete($url);
    }

    public function isExistDb($dbname){
        $all_dbs = $this->getAllDBs();
        return in_array($dbname,$all_dbs);
    }

    public function getAllDBs()
    {
        $url = $this->all_url("_all_dbs");
        return $this->get($url);
    }

    public function createDoc($dbname,$data=[])
    {
        $dbname = strtolower($dbname);
        if(!$this->isExistDb($dbname)) throw new ParamException("{$dbname}数据库不存在");
        if(!is_array($data)) $data[] = $data;
        $data = json_encode($data);
        $id = md5(uniqid().microtime());
        $url = $this->all_url($dbname,$id);
        return $this->put($url,$data);
    }

    public function selectDoc($dbname,$id)
    {
        if(empty($dbname)) throw new ParamException("数据库名不能为空");
        if(empty($id)) throw new ParamException("id不能为空");
        $url = $this->all_url($dbname,$id);
        return $this->get($url);
    }

    public function updateDoc($dbname, $id,$data=[])
    {
        if(empty($dbname)) throw new ParamException("数据库名不能为空");
        if(empty($id)) throw new ParamException("id不能为空");
        if(!is_array($data)) $data[] = $data;
        $docInfo = $this->selectDoc($dbname,$id);
        if(!empty($docInfo) && isset($docInfo["error"])){
            return $docInfo;
        }
        $data["_rev"] = $docInfo["_rev"];
        $url = $this->all_url($dbname,$id);
        return $this->put($url,json_encode($data));
    }

    public function deleteDoc($dbname, $id)
    {
        if(empty($dbname)) throw new ParamException("数据库名不能为空");
        if(empty($id)) throw new ParamException("id不能为空");
        $docInfo = $this->selectDoc($dbname,$id);
        if(!empty($docInfo) && isset($docInfo["error"])){
            return $docInfo;
        }
        $_rev = $docInfo["_rev"];
        $url = $this->all_url($dbname,$id,$_rev);
        return $this->delete($url);
    }


}
