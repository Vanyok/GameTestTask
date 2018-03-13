<?php
/**
 * Created by PhpStorm.
 * User: ivan
 * Date: 13.03.18
 * Time: 15:18
 */

namespace php\models;


class FileDataModel
{

    function __construct($params=[])
    {
        foreach ($params as $param => $value){
            $this->$param = $value;
        }
    }

    public static function fileName()
    {
        return '';
    }

    function exportToJson() {

        mysql_connect("localhost", "root", "");
        mysql_select_db("krasimir_benchmark");

        $res = mysql_query("SELECT * FROM users ORDER BY id");
        $records = array();
        while($obj = mysql_fetch_object($res)) {
            $records []= $obj;
        }
        file_put_contents("data.json", json_encode($records));

    }

    function find($where,$one = 1){
        
    }

}