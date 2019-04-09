<?php

class Tracks
{
    protected $relativeDBPath = "../media/tracks/";
    protected $relativeTransferPath = "/media/tracks";

    private $conn = null;
    
    public function __construct() {
        require_once($_SERVER['DOCUMENT_ROOT'] . "/admin/inc/config.php");
        
        $database = new DatabaseConnection(); 
        $this->conn = $database->connectDB();
    }
        
    public function fetchAllTracks(){
        try {
            require_once(ROOT_DIR ."/model/user.class.php");

            $users = new User();
        
            $numberOfRecords = $this->conn->query('SELECT count(*) FROM tracks')->fetchColumn();

            if ($numberOfRecords > 0){
                $stmt = $this->conn->prepare('SELECT id, title, upload_date, userID FROM tracks WHERE active = :active ORDER BY id DESC');
                
                $active = 1;
                $stmt->bindParam(':active', $active, PDO::PARAM_INT);
                 
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                
                $stmt->execute();
                
                $data = $stmt->fetchAll();
                
                $tracks = array();
                
                foreach($data as $row){
                    $userID = $row['userID'];                
                    $user = $users->selectUser($userID);
                    
                    $track = array();
            
                    $track['info'] = $row;
                    $track['author'] = $user[0];
                                        
                    array_push($tracks,$track);  
                }
                
                $this->conn = null;

                return $tracks;

            } else { 
                $this->conn = null; return null; 
            }
        } catch (PDOException $e) {
          return $e->getMessage();
            
          $this->conn = null;
        }
    }
    
    
    public function fetchTrack($trackID){
        try {
            $stmt = $this->conn->prepare('SELECT * FROM tracks WHERE id = :id');

            $stmt->bindParam(':id', $trackID, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $track = $stmt->fetchAll();
            
            $this->conn = null;

            return $track;

        } catch (PDOException $e) {
            return $e->getMessage();
            
            $this->conn = null;
        }
    } 
    
    public function updateTrack($trackID, $title, $lyrics){
        try{
            $stmt = $this->conn->prepare("UPDATE tracks
                                   SET title = :title,
                                      lyrics = :lyrics
                                    WHERE id = :id");

                
            $stmt->bindParam(':title',  $title);
            $stmt->bindParam(':lyrics', $lyrics);
            $stmt->bindParam(':id', $trackID);

            $stmt->execute();
            
            $this->conn = null;
        } catch (PDOException $e) {
            return $e->getMessage();
            
            $this->conn = null;
        }
    }
    
    public function deleteTrack($trackID){
        try{           
            $stmt = $this->conn->prepare("UPDATE tracks SET active = :active WHERE id = :id");

            $stmt->bindParam(':id', $trackID);
            
            $active = 0;
            $stmt->bindParam(':active', $active, PDO::PARAM_INT);

            $stmt->execute();
            
            $this->conn = null;

        } catch (PDOException $e) {
            return $e->getMessage();
            
            $this->conn = null;
        }
    }
    
    public function insertTrack($trackUpload, $title, $lyrics, $userID){
        $uploadpath = ROOT_DIR . $this->relativeTransferPath;
        $max_size = 40000;
        $allowtype = array('wav', 'mp3', 'mp4', 'm4a');
        
        if ($trackUpload && strlen($trackUpload['name']) > 1) {
            $randomStamp = md5(time());
            $path = $trackUpload['name'];
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
                if (move_uploaded_file($trackUpload['tmp_name'], "$uploadpath/$newFileName")) {
                    $audio_path = "$this->relativeDBPath/$userID/$directoryName/$newFileName";
                    
                    try{
                        $details = \Fr\LS::getUser();
                        $userID = $details["id"];

                        $stmt = $this->conn->prepare("INSERT INTO tracks (upload_date, title, userID, lyrics, audio, active) VALUES (:uploadDate, :title, :userID, :lyrics, :audio, :active)");
                        
                        $datetime = date_create()->format('Y-m-d H:i:s');
                        
                        $stmt->bindParam(':uploadDate',  $datetime);
                        $stmt->bindParam(':title',  $title);
                        $stmt->bindParam(':userID',  $userID);
                        $stmt->bindParam(':lyrics', $lyrics);
                        $stmt->bindParam(':audio',  $audio_path);
                        $active = 1;
                        $stmt->bindParam(':active',  $active, PDO::PARAM_INT);

                        $stmt->execute();

                        $success_msg = "Upload Successful";

                        $error_msg = "";
                    } catch (PDOException $e) {
                        $success_msg = "";
                        $error_msg = "Grand messup with the DB happened.";
                    }
                } else {
                    $error_msg = "Unable to upload the file.";
                    $success_msg = "";
                }
            } else {
                $error_msg = "Not Successful";
                $success_msg = "";
            }

            return $success_msg . $error_msg;
        } else {
            return "No File";
        }
    }
}

?>