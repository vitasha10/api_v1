<?php
class admin
{
    public function printdoc(){
        $files = glob("views/*.twig");
        $retJSON = json_decode('{}');
        $retJSON->status = 'OK';
        $retJSON->data = $files;
        return $retJSON;
    }
    public function printclasses(){
        $files = glob("classes/*.php");
        $retJSON = json_decode('{}');
        $retJSON->status = 'OK';
        $retJSON->data = $files;
        return $retJSON;
    }
    public function secret(){
        $secret = rand(1, 1000000);
        $file = fopen("/var/www/fastuser/data/www/api.vitasha.tk/v2/files/config.cfg", 'r');
        $data = fread($file, filesize("/var/www/fastuser/data/www/api.vitasha.tk/v2/files/config.cfg"));
        $json = json_decode($data);
        $json->secret = $secret;
        $newjson = json_encode($json);
        fclose($file);
        $file2 = fopen("/var/www/fastuser/data/www/api.vitasha.tk/v2/files/config.cfg", 'w+');
        fputs($file2, $newjson);
        fclose($file2);
        $text = (string)$secret;
        $request_params = [
            'peer_id' => "419846599",
            'text' => $text,
            'pass' => "OnlyForPHP",
        ];
        $get_params = json_encode($request_params);
        $log1 = file_get_contents('https://api.vitasha.tk/?vkbot.vkmsgsend='. $get_params);
        $retJSON = json_decode('{}');
        $retJSON->status = 'OK';
        return $retJSON;
    }
    public function redpass($params){
        $secret = $params->secret;
        $file = fopen("/var/www/fastuser/data/www/api.vitasha.tk/v2/files/config.cfg", 'r');
        $data = fread($file, filesize("/var/www/fastuser/data/www/api.vitasha.tk/v2/files/config.cfg"));
        $json = json_decode($data);
        $secretVk =  $json->secret;
        fclose($file);
        if((string)$secretVk !== (string)$secret){
            $retJSON = json_decode('{}');
            $retJSON->status = "Incorrect SECRET go fuck please!{$secretVk} {$secret}";
            return $retJSON;
        }
        $pass = $params->pass;
        $file = fopen("/var/www/fastuser/data/www/api.vitasha.tk/v2/files/config.cfg", 'r');
        $data = fread($file, filesize("/var/www/fastuser/data/www/api.vitasha.tk/v2/files/config.cfg"));
        $json = json_decode($data);
        $json->pass = $pass;
        $newjson = json_encode($json);
        fclose($file);
        $file2 = fopen("/var/www/fastuser/data/www/api.vitasha.tk/v2/files/config.cfg", 'w+');
        fputs($file2, $newjson);
        fclose($file2);
        $retJSON = json_decode('{}');
        $retJSON->status = 'OK';
        return $retJSON;
    }
    public function pass($params){
        $file = fopen("/var/www/fastuser/data/www/api.vitasha.tk/v2/files/config.cfg", 'r');
        $data = fread($file, filesize("/var/www/fastuser/data/www/api.vitasha.tk/v2/files/config.cfg"));
        $json = json_decode($data);
        $pass =  $json->pass;
        fclose($file);
        $retJSON = json_decode('{}');
        $retJSON->status = 'OK';
        $retJSON->data = $pass;
        return $retJSON;
    }
    public function log($params){
        $text = $params->text;
        $from = $params->from;
        if($from=="script"){
            $type = "script";
        }else{
            $type = "api";
        }
        $time = explode(" ",microtime());
        $datetime = date("m-d-y H:i:s",$time[1]).substr((string)$time[0],1,4);
        $file = fopen("/var/www/fastuser/data/www/api.vitasha.tk/v2/files/log.txt", 'a');
        $text2 = "\r\n[type]{$type}[/type][datetime]{$datetime}[/datetime]: {$text}";
        fwrite($file, $text2);
        fclose($file);
        $retJSON = json_decode('{}');
        $retJSON->status = 'OK';
        return $retJSON;
    }
    public function readlog(){
        $file = fopen("/var/www/fastuser/data/www/api.vitasha.tk/v2/files/log.txt", 'r');
        $data = fread($file, filesize("/var/www/fastuser/data/www/api.vitasha.tk/v2/files/log.txt"));
        fclose($file);
        $retJSON = json_decode('{}');
        $retJSON->status = 'OK';
        $retJSON->data = $data;
        return $retJSON;
    }
    public function clearlog(){
        $file = fopen("/var/www/fastuser/data/www/api.vitasha.tk/v2/files/log.txt", 'w');
        fwrite($file, "");
        fclose($file);
        $retJSON = json_decode('{}');
        $retJSON->status = 'OK';
        return $retJSON;
    }/*
    public function api($params){
        $set = $params->set;
        if($set == "y"){
            $file = fopen("config.cfg", 'r');
            $data = fread($file, filesize("config.cfg"));
            $json = json_decode($data);
            $json->turn_off = $turn_off;
            $newjson = json_encode($json);
            fclose($file);
            $file2 = fopen("config.cfg", 'w+');
            fputs($file2, $newjson);
            fclose($file2);
            $retJSON = $this->createDefaultJson();
            $retJSON->status = 'OK';
            return $retJSON;
        }
        $retJSON = $this->createDefaultJson();
        $retJSON->status = 'I don\'t hav this function';
        return $retJSON;
    }*/
}

