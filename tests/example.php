<?php


use CouchDB_Client\src\CounchDBClient;

spl_autoload_register(function ($class_name){
    $class_name = str_replace("\\",DIRECTORY_SEPARATOR,$class_name);
    $path = dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR.$class_name.".php";
    require_once $path;
});
//try{
    $client = new CounchDBClient("127.0.0.1","5984",'yuwen','123');
    var_dump($client);
    $resutl = $client->createDB("testdbdd");
    var_dump($resutl);
    $resutl = $client->getAllDBs();
    var_dump($resutl);
//}catch (\CouchDB_Client\src\Exceptions\ParamException $e){
//    var_dump($e->getMessage());
//}

