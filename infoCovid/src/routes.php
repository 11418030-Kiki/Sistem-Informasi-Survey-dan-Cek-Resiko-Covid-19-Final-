<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();

    $app->get('/[{name}]', function (Request $request, Response $response, array $args) use ($container) {
        // Sample log message
        $container->get('logger')->info("Slim-Skeleton '/' route");

        // Render index view
        return $container->get('renderer')->render($response, 'index.phtml', $args);
    });
    $app->post('/register', function (Request $request, Response $response, array $args) {
        $input = $request->getParsedBody();
        $Username=trim(strip_tags($input['Username']));
        $Password=trim(strip_tags($input['Password']));
        $Nama=trim(strip_tags($input['Nama']));
        $JenisKelamin = trim(strip_tags($input['JenisKelamin']));
        $sql = "INSERT INTO users(username, password, nama, jenis_kelamin) 
            VALUES(:username, :password, :nama, :jenis_kelamin)";
        $sth = $this->db->prepare($sql);
        $sth->bindParam("username", $Username);
        $sth->bindParam("password", $Password);
        $sth->bindParam("jenis_kelamin", $JenisKelamin);
        $sth->bindParam("nama", $Nama);
        $StatusInsert=$sth->execute();
        if($StatusInsert){
            $responseJson["error"] = false;
            $responseJson["message"] = "Berhasil Menambah Ke DataBase";
            $responseJson["success"] = "1";
            echo json_encode($responseJson);
        } else {
            return $this->response->withJson(['status' => 'error','data'=>'error insert produk.'],200); 
        }
    });

    $app->post('/login', function (Request $request, Response $response, array $args) {
        $input = $request->getParsedBody();
        $Username=trim(strip_tags($input['Username']));
        $Password=trim(strip_tags($input['Password']));
        $sql = "SELECT * FROM users";
        $sth = $this->db->prepare($sql);
        $sth->bindParam("username", $Username);
        $sth->bindParam("password", $Password);
        $sth->execute();
        $user = $sth->fetchAll();       
        if(!$user) {
            return $this->response->withJson(['status' => 'error', 'message' => 'These credentials do not match our records username.']);  
        }
        $settings = $this->get('settings');       
    
  
        return $response->withJson(["status" => "success", "data" => $user], 200);
    });
};
