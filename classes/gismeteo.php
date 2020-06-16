<?php
class gismeteo
{
    public function perm()
    {
        $log1 = file_get_contents('https://api.openweathermap.org/data/2.5/weather?q=Perm&appid=57c5def75a9d9dbc18fb0363276b807a&units=metric');
        /*$request_params = array(
            'chat_id' => $params->chat_id,
            '' => $params->kick_id,
            'access_token' => $this->access_tocken,
            'v' => $this->version_kick
        );
        $get_params = http_build_query($request_params);
        $log1 = file_get_contents('https://api.gismeteo.net/v2/weather/current/4368/' . $get_params);*/
        $log = json_decode($log1, true);
        $retJSON = json_decode('{}');
        $retJSON->status = 'OK';
        $retJSON->data = $log;
        return $retJSON;
    }
}