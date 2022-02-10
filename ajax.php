<?php

$action = $_REQUEST['action'];

if(!empty('action')){
    require_once 'includes/Data.php';
    $obj = new Data();
}

if($action == 'adduser' && !empty($_POST)){
     $name = $_POST['username'];
     $procedency = $_POST['procedency'];
     $age = $_POST['age'];
     $photo = $_FILES['photo'];
     $id = (!empty($_POST['userid'])) ? $_POST['userid'] : '';

     // ahora validamos la descarga del archivo de la foto

     $imagenphoto = '';
     if(!empty($photo['name'])){
         $imagenphoto = $obj->getPhoto($photo);
         $dataDat = [
             'name' => $name,
             'procedency' => $procedency,
             'age' => $age,
             'photo' => $imagenphoto,
         ];
     } else {
          $dataDat = [
             'name' => $name,
             'procedency' => $procedency,
             'age' => $age,
         ];
     }

    if($id){
        $obj->upgrade($dataDat, $id);
    } else {
       $dataId =  $obj->add($dataDat);
    }
   


    if(!empty($dataId)){
        $data = $obj->getRow('id', $dataId);
        echo json_encode($data);
        exit();
    }
}


if($action == "getusers" ){
    $page = (!empty($_GET['page'])) ? $_GET['page'] : 1;
    $limit = 4;
    $start = ($page - 1) * $limit;

    $users = $obj->getRows($start, $limit);
    if(!empty($users)){
        $list = $users;
    } else {
        $list = [];
    }
     $total = $obj->getCount();
     $userArr = ['counter'=> $total, 'users' => $list ];
     
    echo json_encode($userArr);
    exit();
}

if($action == "getuser"){
    $userId = (!empty($_GET['id'])) ? $_GET['id'] : '';

    if(!empty($userId)) {
        $users = $obj->getRow( 'id', $userId);
        echo json_encode($users);
        exit();
    }
}


if($action == "deleteuser"){
    $userId = (!empty($_GET['id'])) ? $_GET['id'] : '';

    if(!empty($userId)) {
        $isDelete = $obj->deleteRows($userId);
        if($isDelete){
           $message = ['user delete' => 1];
        } else {
           $message = ['user delete' => 0];
        }
        echo json_encode($message);
        exit();    }
}

if($action == "searchuser"){
    $query = (!empty($_GET['searchstring'])) ? trim($_GET['searchstring']) : '';
    $resultado =  $obj->searchUser($query);
    echo json_encode($resultado);
    exit();
}


?>