<?php

 require_once 'Database.php';

  class Data extends Database {

      // aqui ponemos el nombre de la tabla
      private $datatable = 'user';
       
      /**
       * funcion es usada para agregar un registro
       * @param array $dates
       * @return int $lastInsertId
       */
      // se crea una funcion para agregar los datos a la tabla
      public function add($dates) {
           
          if(!empty($dates)){
              $fileds = $placeholders = [];
              foreach ($dates as $fields => $value) {
                   $fileds[] = $fields;
                   $placeholders[] = ":{$fields}";
              }
          }

          $sql = "INSERT INTO {$this->datatable} (". implode(',', $fileds) .") 
          VALUES(". implode(',', $placeholders). ")";
          $stmt = $this->conn->prepare($sql);
          try {
              $this->conn->beginTransaction();
              $stmt->execute($dates);
              $lastInserted = $this->conn->lastInsertId();
              $this->conn->commit();
             return $lastInserted;
          } catch (PDOException $e) {
              echo "ERROR:  " .  $e->getMessage();
              $this->conn->rollBack();
          }
      }
        
    public function upgrade($data, $id){
         if(!empty($data)){
              $fileds = '';
              $j = 1;
              $filedsDate = count($data);
              foreach ($data as $fields => $value) {
                   $fileds.="{$fields}=:{$fields}";
                   if($j < $filedsDate){
                       $fileds .= ", ";
                   }
                   $j++;
              }
          }

        $sql = "UPDATE {$this->datatable} SET {$fileds} WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
         try {
              $this->conn->beginTransaction();
              $data['id'] = $id;
              $stmt->execute($data);
              $this->conn->commit();
          } catch (PDOException $e) {
              echo "ERROR:  " .  $e->getMessage();
              $this->conn->rollBack();
          }

    }
      /**
       * funcion es usada para obtener los registros de datos
       * @param int @stmt
       * @param int @limit
       * @param array @result
       */
        
       public function getRows($start = 0, $limit = 4){
           $sql = "SELECT * FROM {$this->datatable} ORDER BY id DESC LIMIT {$start}, {$limit}";
           $stmt =  $this->conn->prepare($sql);
           $stmt->execute();
           if($stmt->rowCount() > 0){
               $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
           } else {
               $result = [];
           }
           return $result;
       }

         public function deleteRows($id){
          $sql = "DELETE  FROM {$this->datatable}  WHERE id=:id";
          $stmt = $this->conn->prepare($sql);     
           try {
               $stmt->execute([':id' => $id]);
                if($stmt->rowCount() > 0){
               return true;
           }
           } catch (PDOException $e) {
               echo "ERROR TO DELETE:  " .  $e->getMessage();
               return false;
           }
          
       }

         public function getCount(){
           $sql = "SELECT count(*) as counter FROM {$this->datatable}";
           $stmt =  $this->conn->prepare($sql);
           $stmt->execute();
           $result = $stmt->fetch(PDO::FETCH_ASSOC);
           return $result["counter"];
       }
     
      /**
       * funcion se usa para para obtener un registro simple basado en el valor de su columna 
       * @param string $fields
       * @param any $value
       * @param array $results
       */
      public function getRow($fields, $value) {
           

          $sql = "SELECT * FROM {$this->datatable}  WHERE {$fields}=:{$fields}";

           $stmt = $this->conn->prepare($sql);
           $stmt->execute([":{$fields}" => $value]);
           if($stmt->rowCount() > 0){
               $results = $stmt->fetch(PDO::FETCH_ASSOC);
           } else {

              $results = [];
           }
           return $results;
      }
     public function getAllRows() {
           

          $sql = "SELECT * FROM {$this->datatable}";

           $stmt = $this->conn->prepare($sql);
           $stmt->execute();
           if($stmt->rowCount() > 0){
               $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
           } else {

              $results = [];
           }
           return $results;
      } 

      public function searchUser($search, $start = 0, $limits = 4){
          $sql = "SELECT * FROM  {$this->datatable} WHERE name LIKE :search ORDER BY id DESC LIMIT {$start},
          {$limits}";
          $stmt = $this->conn->prepare($sql);
          $stmt->execute([':search' => "{$search}%"]);
           if($stmt->rowCount() > 0){
               $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
           } else {

              $results = [];
           }
           return $results;
      }
      /**
       * funcion se usa para descargar un archivo
       * @param string $filePath
       * @param return $fileNew
       */


      public function getPhoto($file) {

          if(!empty($file)){
              $filePath = $file['tmp_name'];
              $fileName = $file['name'];
              $fileSize = $file['size'];
              $fileType = $file['type'];
              $fileCamps = explode('.', $fileName);
              $fileExt = strtolower(end($fileCamps));
              $fileNew = md5(time() . $fileName) . ' . ' . $fileExt;
              $allowExt = ["jpg", "png", "git", "jpeg" ];
              if(in_array($fileExt, $allowExt)) {
                  $uploadFile = getcwd() . '/uploads/';
                  $destFile = $uploadFile . $fileNew;
                  if(move_uploaded_file($filePath, $destFile)) {
                      return $fileNew;
                  }
              }
          }
      }
  }

?>