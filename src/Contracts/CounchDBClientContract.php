<?php
namespace CouchDB_Client\src\Contracts;

interface CounchDBClientContract{
    /**
     * 创建文档
     * @param $dbname
     * @return mixed
     */
    public function createDB($dbname);

    public function deleteDB($dbname);

    public function isExistDb($dbname);

    public function getAllDBs();

    public function createDoc($dbname,$data=[]);

    public function selectDoc($dbname,$id);

    public function updateDoc($dbname, $id,$data=[]);

    public function deleteDoc($dbname, $id);
}