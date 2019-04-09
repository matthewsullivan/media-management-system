<?php

class User
{
    private $relativeDBPath = "../media/users";
    private $relativeTransferPath = "/media/users";

    private $conn = null;
    
    public function __construct() {
        require_once($_SERVER['DOCUMENT_ROOT'] . "/admin/inc/config.php");
        
        $database = new DatabaseConnection(); 
        $this->conn = $database->connectDB();
    }
    
    public function selectUser($userID){
        try {
            $stmt = $this->conn->prepare('SELECT id, username, name, profile_img FROM users WHERE id = :id');
            $stmt->bindParam(":id", $userID);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $user = $stmt->fetchAll();
            
            return $user;
        
        } catch (PDOException $e) {
          return $e->getMessage();
            
          $this->conn = null;
        }
    }
        
    public function numberUserUploadTracks($userID){
        try {
            $numberOfRecords = $this->conn->prepare('SELECT count(*) FROM tracks WHERE userID = :id ');
            $numberOfRecords->bindParam(":id", $userID);
            $numberOfRecords->execute();
            
            $userTrackNumber = $numberOfRecords->fetchColumn(0);
            
            return $userTrackNumber;

        } catch (PDOException $e) {
          return $e->getMessage();
            
          $this->conn = null;
        }
    }
        
    public function profilePicture($userID, $imageUpload){
        $uploadpath = ROOT_DIR . $this->relativeTransferPath;
        $max_size = 40000;

        $allowtype = array('jpg', 'jpeg', 'png', 'gif');
        
        if ($imageUpload && strlen($imageUpload['name']) > 1) { 
            $randomStamp = md5(time());
            $path = $imageUpload['name'];
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $newFileName  = strtolower($randomStamp.'.'.$type); 
            
            if (!in_array($type, $allowtype)){
                $err .= 'The file: <b>'. $imageUpload['name']. '</b> contains an unaccepted filetype.';

                
                return $err;
            }
            
            $directoryName = count(glob("$uploadpath/$userID" . '/*' , GLOB_ONLYDIR));
             
            if (!is_dir("$uploadpath/$userID")){
                mkdir("$uploadpath/$userID", 0777);
                mkdir("$uploadpath/$userID/$directoryName", 0777);
            } else {
                mkdir("$uploadpath/$userID/$directoryName", 0777);
            }
            
            $uploadpath = "$uploadpath/$userID/$directoryName";
        
            $err = "";
            
            if ($err == "") {
                if (move_uploaded_file($imageUpload['tmp_name'], "$uploadpath/$newFileName")) {
                    $image_path = "$this->relativeDBPath/$userID/$directoryName/$newFileName";

                    try{
                        $stmt = $this->conn->prepare("UPDATE users
                                   SET profile_img = :profileImg
                                          WHERE id = :id");
                        
                        $stmt->bindParam(':profileImg', $image_path);
                        $stmt->bindParam(':id',  $userID);

                        $stmt->execute();

                        $success_msg = "Upload Successful";

                        $error_msg = "";
                    } catch (PDOException $e) {
                        $success_msg = "";
                        $error_msg = "Grand messup with the DB happened.";
                    }
                }else {
                    $error_msg = "Unable to upload the file.";
                    $success_msg = "";
                }
            } else {
                $error_msg = "Not Successful";
                $success_msg = "";
            }

            return $success_msg . $error_msg . $err;
        } else {
            return "No File";
        }
    }
    
    public function removeProfilePicture($userID){    
        try{
            $stmt = $this->conn->prepare("UPDATE users
                           SET profile_img = null
                                  WHERE id = :id");
            
            $stmt->bindParam(':id', $userID);

            $stmt->execute();
            
            $this->conn = null;
            
            return "Deleted";
        } catch (PDOException $e) {
            return $e->getMessage();
            
            $this->conn = null;
        }
    }
}

?>