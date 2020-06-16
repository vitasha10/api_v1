<?php

class apitest
{
    //http://www.example.com/api/?apitest.helloAPI={}
    function helloAPI() {
        $retJSON = json_decode('{}');
        $retJSON->hello = 'Hello world!!!';
        return $retJSON;
    }

    //http://www.example.com/api/?apitest.helloAPIWithParams={"TestParamOne":"Text of first parameter"}
    function helloAPIWithParams($apiMethodParams) {
        $retJSON = json_decode('{}');
        $retJSON->params=$apiMethodParams;
        return $retJSON;
    }
    /*
    //http://www.example.com/api/?apitest.helloAPIResponseBinary={"responseBinary":1}
    function helloAPIResponseBinary(){
        header('Content-type: image/png');
        echo file_get_contents("http://habrahabr.ru/i/error-404-monster.jpg");
    }
    */
}

?>