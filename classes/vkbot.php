<?php
class vkbot
{
    private $access_tocken = "998ed027151d61480455cbbf0f1beaa0c5be5c313bcd00c81f52c1f892793dd80fa133c5fa60a05c9fcdd";
    private $version_kick = "5.81";
    private $version_msg_send = "5.87";
    //kick peoples
    public function kick($params)
    {
        $request_params = array(
            'chat_id' => $params->chat_id,
            'member_id' => $params->kick_id,
            'access_token' => $this->access_tocken,
            'v' => $this->version_kick
        );
        $get_params = http_build_query($request_params);
        $log1 = file_get_contents('https://api.vk.com/method/messages.removeChatUser?' . $get_params);
        $log = json_decode($log1, true);
        $retJSON = json_decode('{}');
        $retJSON->status = 'OK';
        $retJSON->data = $log;
        return $retJSON;
    }
    public function vkmsgsend($params)
    {
        $peer_id = $params->peer_id;
        $text = $params->text;
        $request_params = array(
            'message' => $text,
            'peer_id' => $peer_id,
            'access_token' => $this->access_tocken,
            'v' => $this->version_msg_send
        );
        $get_params = http_build_query($request_params);
        $log1 = file_get_contents('https://api.vk.com/method/messages.send?' . $get_params);
        $log = json_decode($log1, true);
        $retJSON = json_decode('{}');
        $retJSON->status = 'OK';
        $retJSON->data = $log;
        return $retJSON;
    }
    public function vkgetname($params)
    {
        $request_params = array(
            'user_ids' => $params->id,
            'access_token' => $this->access_tocken,
            'v' => $this->version_msg_send
        );
        $get_params = http_build_query($request_params);
        $log1 = file_get_contents('https://api.vk.com/method/users.get?' . $get_params);
        $log2 = json_decode($log1, true);
        return $log2['response'][0]['first_name'];
    }
}