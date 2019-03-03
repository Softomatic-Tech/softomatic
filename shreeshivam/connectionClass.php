<?php
class connectionClass extends mysqli{
    public $host="localhost",$dbname="cp5el_shreeshivam_test",$dbpass="cp5el_testshree",$dbuser="cp5el_testshree";
    public $con;
    
    public function __construct() {
        if($this->connect($this->host, $this->dbuser, $this->dbpass, $this->dbname)){}
        else
        {
            return "<h1>Error while connecting database</h1>";
        }
    }
}
