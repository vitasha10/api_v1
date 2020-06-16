<?php
require __DIR__.'/vendor/autoload.php';
require_once( __DIR__.'/const.php');
$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => display_error_details,
    ]    
]);
$container = $app->getContainer();
function mylog($log){
    error_reporting(E_ALL);
    ini_set('error_log', path_to_error_log);
    error_log($log, 0);
}
function callApiFunction($class, $method, $params) {
    $resultFunctionCall = json_decode('{}');//Создаем JSON  ответа
    if (file_exists(classes_folder."/".$class.'.php')) {
        require_once classes_folder."/".$class.'.php';
        $apiClass = new $class();
        $apiReflection = new ReflectionClass($class);//Через рефлексию получем информацию о классе объекта
        try {
            $apiReflection->getMethod($method);//Провераем наличие метода
            $response = response_world;
            $resultFunctionCall->$response = $apiClass->$method($params);//Вызыаем метод в API который вернет JSON обект
        } catch (Exception $ex) {
            $resultFunctionCall->error = $ex->getMessage();
        }
    } else {
        //Если запрашиваемый API не найден
        $resultFunctionCall->error = file_not_found_error;
    }
    return json_encode($resultFunctionCall,JSON_UNESCAPED_UNICODE);
}
function resize_photo($file1, $quality = default_photo_quality){
    $path = path_for_new_photos;
    $filename = new_photos_name.".jpg";
    if($file1['type'] == 'image/jpeg'){
        $source = imagecreatefromjpeg($file1['tmp_name']);
    }else if($file1['type'] == 'image/png'){
        $source = imagecreatefrompng($file1['tmp_name']);
    }else if($file1['type'] == 'image/gif'){
        $source = imagecreatefromgif($file1['tmp_name']);
    }else{
        return error_not_allowed_type."{$file1['type']}";
    }
    imagejpeg($source, $path.$filename, $quality); //Сохраняем созданное изображение по указанному пути в формате jpg
    imagedestroy($source);
    return $filename;
}
/*
function pass($pass, $class, $method){
    if(($class == "admin" and ($method == "secret" or $method == "redpass")) or $class == "apitest" or ($class = "vkbot" and $method == "vkgetname")){
        return 1;
    }
    $file = fopen("files/config.cfg", 'r');
    $data = fread($file, filesize("files/config.cfg"));
    $json = json_decode($data);
    $system_pass =  $json->pass;
    fclose($file);
    if(($pass !== $system_pass) and ($pass !== "OnlyForPHP")){
        return 0;
    }else{
        return 1;
    }
}*/
function pass($user, $pass, $class, $method){
    /*if(($class == "admin" and ($method == "secret" or $method == "redpass")) or $class == "apitest" or ($class = "vkbot" and $method == "vkgetname")){
        return 1;
    }
    $file = fopen("files/config.cfg", 'r');
    $data = fread($file, filesize("files/config.cfg"));
    $json = json_decode($data);
    $system_pass =  $json->pass;
    fclose($file);
    if(($pass !== $system_pass) and ($pass !== "OnlyForPHP")){
        return 0;
    }else{
        return 1;
    }*/
    $link = mysqli_connect(mysql_server, mysql_user, mysql_pass, mysql_db);    
    $sql = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM `users` WHERE `login`='{$user}' AND `pass`='{$pass}' AND (`class`='{$class}' OR `class`='any') AND (`method`='{$method}' OR `method`='any')"));
    if($sql['login']==$user){
        return 1;
    }else{
        return 0;
    }
}
function token($token, $class, $method){
    $link = mysqli_connect(mysql_server, mysql_user, mysql_pass, mysql_db);
    $sql = mysqli_fetch_array(mysqli_query($link, "SELECT * FROM `tokens` WHERE `token`={$token} AND (`class`='{$class}' OR `class`='any') AND (`method`='{$method}' OR `method`='any')"));
    if($sql['token']==$token){
        return 1;
    }else{
        return 0;
    }
}/*
function callApiFunction($class, $method, $params) {
    $resultFunctionCall = json_decode('{}');//Создаем JSON  ответа
    if (file_exists('classes/'.$class.'.php')) {
        require_once 'classes/'.$class.'.php';
        $apiClass = new $class();
        $apiReflection = new ReflectionClass($class);//Через рефлексию получем информацию о классе объекта
        try {
            $apiReflection->getMethod($method);//Провераем наличие метода
            $response = response_world;
            //if ($jsonParams) {
                //if (isset($jsonParams->responseBinary)){//Для возможности возврата не JSON, а бинарных данных таких как zip, png и др. контетнта 
                  //  $data =  $apiClass->$functionName($jsonParams);
                  //  return $data;//Вызываем метод в API
                //}else{
            $resultFunctionCall->$response = $apiClass->$method($params);//Вызыаем метод в API который вернет JSON обект
               //}
            //} else {
                //Если ошибка декодирования JSON параметров запроса
             //   $resultFunctionCall->error = 'Error given params';
           // }
        } catch (Exception $ex) {
            //Непредвиденное исключение
            $resultFunctionCall->error = $ex->getMessage();
        }
    } else {
        //Если запрашиваемый API не найден
        $resultFunctionCall->error = 'File not found';
    }
    return json_encode($resultFunctionCall,JSON_UNESCAPED_UNICODE);
}

$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/views', [
        'cache' => false
    ]);

    // Instantiate and add Slim specific extension
    $router = $container->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));

    return $view;
};
*/


$app->get('/', function ($request, $response) {
    //return $response->redirect(root_api_page , 303);
    //header('Content-Type: application/json'); 
    return "Home";
    //return $this->php->render($response, "home.html");
});
$app->get('/api/{user}/{pass}/{class}/{method}/{params}', function ($request, $response, $args) {
    //header('Content-Type: application/json'); 
    //return $this->view->render($response, "home.html");
    $response = $response->withHeader('Content-type', 'application/json; charset=utf-8');
    if(pass($args['user'],$args['pass'],$args['class'],$args['method']) == 0){
        $resultFunctionCall = json_decode('{}');//Создаем JSON  ответа  
        $resultFunctionCall->error = error_incorect_pass;
        return $response->write(json_encode($resultFunctionCall));
    }else{
        $data = callApiFunction($args['class'],$args['method'],json_decode($args['params']));
        return $response->write($data);
    }
});
$app->post('/{token}/new_photo/{quality}', function ($request, $response, $args){
    if(token($args['token'],'new_photo','new_photo') == 0){
        $resultFunctionCall = json_decode('{}');//Создаем JSON  ответа  
        $resultFunctionCall->error = error_incorect_token;
        return $response->write(json_encode($resultFunctionCall));
    }else{
        return $response->write(resize_photo($_FILES['file']),$args['quality']);
    }
});
$app->get('/{token}/photo/{name}', function ($request, $response, $args){
    if(token($args['token'],'photo', $args['name']) == 0){
        $resultFunctionCall = json_decode('{}');//Создаем JSON  ответа  
        $resultFunctionCall->error = error_incorect_token;
        return $response->write(json_encode($resultFunctionCall));
    }else{
        $response->write(path_for_new_photos."/".$args['name']);
        return $response->withHeader('Content-Type', FILEINFO_MIME_TYPE);
    }
});
/*
$app->get('/api/{class}/{function}/{pass}/{params}', function ($request, $response, $args) {
    $response = $response->withHeader('Content-type', 'application/json; charset=utf-8');
    if(pass($args['pass'],$args['class'],$args['function']) == 0){
        $resultFunctionCall = json_decode('{}');//Создаем JSON  ответа  
        $resultFunctionCall->error = 'Password incorect!';
        return $response->write(json_encode($resultFunctionCall));
    }else{
        $data = callApiFunction($args['class'],$args['function'],json_decode($args['params']));
        //if(is_json($data) == true){
        return $response->write($data);
        //}else if((exif_imagetype($data) == "IMAGETYPE_JPEG") or (exif_imagetype($data) == "IMAGETYPE_PNG")){
          //  ob_start();
            //imagepng($data);
            //$data = ob_get_contents();
            //ob_end_clean();
            //$response->write($data);
            //$response = $response->withHeader('Content-type', 'image/png');
            //return $response;
        ///}else{
           // echo "none";
       // }
    }
});
$app->get('/documentation/{class}/{function}', function ($request, $response, $args) {
    return $this->view->render($response, "{$args['class']}_{$args['function']}.twig");
    //$data = documentation($args['class'],$args['function']);
    //return $response->write("{$data}");
});
$app->get('/links/{link_id}', function ($request, $response, $args) {
    $data = go_to_link($args['link_id']);
    return $response->redirect($data, 303);
});
$app->get('/about', function ($request, $response) {
    return $this->view->render($response, "about.html");
});
*/
$app->run();