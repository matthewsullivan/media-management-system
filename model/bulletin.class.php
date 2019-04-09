<?php

class Bulletin
{
    protected $relativeDBPath = "../media/bulletin";
    protected $relativeTransferPath = "/media/bulletin";

    private $conn = null;
    
    public function __construct() {
        require_once($_SERVER['DOCUMENT_ROOT'] . "/admin/inc/config.php");
        
        $database = new DatabaseConnection(); 
        $this->conn = $database->connectDB();
    }
    
    public function fetchAllBulletinItems(){
        try {
            require_once(ROOT_DIR . "/model/user.class.php");

            $users = new User();
        
            $numberOfRecords = $this->conn->query('SELECT count(*) FROM tracks')->fetchColumn();

            if ($numberOfRecords > 0){
                $stmt = $this->conn->prepare('SELECT id, author, name, content, media, creation_date FROM bulletin_board WHERE active = :active ORDER BY id DESC');
                
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $active = 1;
                $stmt->bindParam("active", $active, PDO::PARAM_INT);
                $stmt->execute();
                
                $data = $stmt->fetchAll();
                
                $this->conn = null;

                return $data;
            } else { 
                $this->conn = null; return null; 
            }
        } catch (PDOException $e) {
          return $e->getMessage();
            
          $this->conn = null;
        }
    }
           
    public function newBulletinItem($userID, $imageUpload, $name, $content){
        $uploadpath = ROOT_DIR . $this->relativeTransferPath;
        $max_size = 40000;

        $allowtype = array('jpg', 'jpeg', 'png', 'gif');
        
        if ($imageUpload && strlen($imageUpload['name']) > 1) {
            $randomStamp = md5(time());
            $path = $imageUpload['name'];
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $newFileName  = strtolower($randomStamp.'.'.$type); 
            
            if (!in_array($type, $allowtype)){
                $err .= 'The file: <b>'. $imageUpload['name']. '</b> is an unaccepted filetype.';
                
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

                    try {
                        $stmt = $this->conn->prepare("INSERT INTO bulletin_board (author, name, content, media, creation_date, active) VALUES (:author, :name, :content, :media, :creation_date, :active)");
                        
                        $datetime = date_create()->format('Y-m-d H:i:s');
  
                        $active = 1;
                        $stmt->bindParam(':author',  $userID);
                        $stmt->bindParam(':name',  $name);
                        $stmt->bindParam(':content',  $content);
                        $stmt->bindParam(':media', $image_path);
                        $stmt->bindParam(':creation_date',  $datetime);
                        $stmt->bindParam(':active', $active, PDO::PARAM_INT);
                    
                        $stmt->execute();

                        $success_msg = "Upload Successful";

                        $error_msg = "";

                    } catch (PDOException $e) {
                        $success_msg = "";            
                        $error_msg = $e->getMessage();
                    }
                } else {
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
    
    public function fetchBulletin($bulletinID){
        try {
            require_once(ROOT_DIR ."/model/user.class.php");

            $users = new User();
            
            $stmt = $this->conn->prepare('SELECT * FROM bulletin_board WHERE id = :id');
            $stmt->bindParam(':id', $bulletinID, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $bulletinItem = $stmt->fetchAll();
            $bulletins = array();
                
            foreach($bulletinItem as $row){
                $userID = $row['author'];                
                $user = $users->selectUser($userID);
                
                $bulletin = array();
                $bulletin['info'] = $row;
                $bulletin['author'] = $user[0];
                $bulletin['comments'] = $this->fetchComments($bulletinID);

                array_push($bulletins,$bulletin);
            }
            
            $this->conn = null;

            return $bulletins;

        } catch (PDOException $e) {
            return $e->getMessage();
            
            $this->conn = null;
        }
    }
    
    public function fetchComments($bulletinID){
        try {
            require_once(ROOT_DIR ."/model/user.class.php");

            $users = new User();
            
            $stmt = $this->conn->prepare('SELECT * FROM bulletin_comment 
                                          WHERE itemID = :id
                                          AND active = :active');
            
            $stmt->bindParam(':id', $bulletinID, PDO::PARAM_INT);
            $active = 1;
            $stmt->bindParam(':active', $active, PDO::PARAM_INT);
            
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $bulletinComment = $stmt->fetchAll();
            
            $comments = array();
                
            foreach($bulletinComment as $row){
                $userID = $row['userID'];                
                $user = $users->selectUser($userID);
                
                $comment = array();
                $comment['comment'] = $row;
                $comment['user'] = $user[0];

                array_push($comments,$comment);
            }
            
            $this->conn = null;

            return $comments;

        } catch (PDOException $e) {
            return $e->getMessage();
            
            $this->conn = null;
        }
    }
    
    public function insertComment($itemID, $userID, $comment){
        try{
            $stmt = $this->conn->prepare("INSERT INTO bulletin_comment (itemID, userID, comment_date, comment, active) VALUES (:itemID, :userID, :comment_date, :comment, :active)");
                        
            $datetime = date_create()->format('Y-m-d H:i:s');
            
            $stmt->bindParam(':itemID', $itemID, PDO::PARAM_INT);
            $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
            $stmt->bindParam(':comment', $comment);
            $stmt->bindParam(':comment_date', $datetime);
            $active = 1;
            $stmt->bindParam(':active', $active, PDO::PARAM_INT);
            
            $stmt->execute();
        } catch (PDOException $e){
            return $e->getMessage();
            
            $this->conn = null;
        }
    }
}

?>