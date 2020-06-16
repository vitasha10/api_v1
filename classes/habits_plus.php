<?php

class habits_plus
{
    public function get_user_data($params){
        $params = json_encode($params);
        $link = mysqli_connect('localhost', 'api_vitasha', 'Hack8908', 'api');
        $sql = mysqli_query($link, "INSERT INTO `jsons` (`json`) VALUES ('{$params}')");
        return $sql;
    }
}