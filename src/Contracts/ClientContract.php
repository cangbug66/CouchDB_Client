<?php
namespace CouchDB_Client\src\Contracts;

interface ClientContract{
    public function get($url,$data='');

    public function put($url,$data='');

    public function delete($url,$data='');

    public function request($url='',$method='GET',$data="");
}