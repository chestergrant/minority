<?php

class minoritygames{
    private $conn;
    function __construct($db){
        $this->conn = $db;
    }
    function getId($ip){
        $sql = "SELECT minority_ip.link_id AS link_id FROM minority_ip, minority_data WHERE minority_ip.ip = ? AND minority_data.children_id = minority_ip.link_id AND minority_data.end_time > ".time();
        $sql .= " ORDER BY minority_data.end_time DESC";
        if($stmt = $this->conn->prepare($sql)){  
                $stmt-> bind_param("s", $ip);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($link_id);
                if($stmt->num_rows > 0){
                    $stmt->fetch();
                    return $link_id;
                }
        }
        return "";
    }
    //Works
    function hasID($ip){
        $sql = "SELECT minority_ip.link_id AS link_id FROM minority_ip, minority_data WHERE minority_ip.ip = ? AND minority_data.children_id = minority_ip.link_id AND minority_data.end_time > ".time();
        $sql .= " ORDER BY minority_data.end_time DESC";
        if($stmt = $this->conn->prepare($sql)){  
                $stmt-> bind_param("s", $ip);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($link_id);
                
                if($stmt->num_rows >= 100){                    
                    return true;
                }
        }
       
        return false;
    }
    //Works
    function validLink($link_id){
       $sql = "SELECT children_id FROM minority_data WHERE children_id = ?";
       if($stmt = $this->conn->prepare($sql)){  
                $stmt-> bind_param("s", $link_id);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($children_id);
                if($stmt->num_rows > 0){                    
                    return true;
                }
        }
        
        return false;
    }
    //Works
    function failed($link_id){
        $sql = "SELECT children_id FROM minority_data WHERE children_id = ? AND end_time < ".time()." AND children_id NOT IN (SELECT link_id FROM minority_data WHERE link_id = ?)";
        if($stmt = $this->conn->prepare($sql)){  
                $stmt-> bind_param("ss", $link_id,$link_id);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($children_id);
                if($stmt->num_rows > 0){                    
                    return true;
                }
        }
        return false;
    }
    //works
    function makeId(){
        $alphanum = "abcdefghijklmnopqrstvuwxyz0123456789";
        $output= "";
        $count = 0;
        do{
            $output ="";
            for($i =0;$i< 10; $i++){
                $char = $alphanum[rand(0,strlen($alphanum)-1)];
                $output .= $char;
            }
            $count++;
        }while((!$this->newId($output))&&($count <1000));
        
        return $output;        
    }
    //Works
    function newId($link_id){        
        $sql = "SELECT link_id FROM minority_ip WHERE link_id ='".$link_id."'";
        $result = $this->conn->query($sql);
        if($result != false){
            if($result->num_rows > 0){                
                return false;                
            }
        }
        $sql = "SELECT children_id FROM minority_data WHERE children_id ='".$link_id."'";
        $result = $this->conn->query($sql);
        if($result != false){
            if($result->num_rows > 0){                
                return false;                
            }
        }
        return true;
    }
    //works
    function createId($ip){
        $sql = "INSERT INTO minority_ip(ip,link_id)VALUES(?,?)";
        $link_id = $this->makeId();
        if($stmt2 = $this->conn-> prepare($sql)){
            $stmt2 -> bind_param("ss", $ip,$link_id);
            $stmt2 -> execute();
        }
        return $link_id;
    }
    //Works
    function reach($link_id){
        if($link_id == ""){
            return 0;
        }
        $sql = "SELECT reach FROM minority_data WHERE children_id = ?";
        $output = 0;
        if($stmt = $this->conn->prepare($sql)){  
                $stmt-> bind_param("s", $link_id);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($reach);
                if($stmt->num_rows > 0){
                    $stmt->fetch();
                    return $reach;
                }
        }
        
        return $output;
    }
    //Works
    function childid($link_id,$number){
        $output = "";
        $sql = "SELECT children_id FROM minority_data WHERE link_id = ? AND number =".$number;       
        if($stmt = $this->conn->prepare($sql)){  
                $stmt-> bind_param("s", $link_id);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($children_id);
                if($stmt->num_rows > 0){
                    $stmt->fetch();
                    return $children_id;
                }
        }
        return $output;
    }
    //Works
    function getUrl($link_id,$ip,$number){
        $id = $this->childid($link_id,$number);
        if($id == ""){     
            $id = $this->createChild($link_id, $ip, $number);            
        }
        return "http://www.minoritygames.com/index.php?link_id=".$id;
    }
    //Works
    function createChild($link_id,$ip,$number){
      $inip = $this->isInIPTable($link_id);
      if(!$inip){
          $this->storeInIP($link_id,$ip);          
      }
      if(!$this->childOfSomeone($link_id)){
          $this->makeChildOfSomeone($link_id,"",1);
      }
      $id = $this->makeId();
      $this->makeChildOfSomeone($id,$link_id,$number);
      return $id;
      
    }
    //Works
    function childOfSomeone($link_id){
        $sql = "SELECT link_id FROM minority_data WHERE children_id = ? ";
        if($stmt = $this->conn->prepare($sql)){  
                $stmt-> bind_param("s", $link_id);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($link_id);
                if($stmt->num_rows > 0){
                    return true;
                }
        }
        return false;
    }
    //Works
    function makeChildOfSomeone($child,$parent,$number){
        $start_time = time();
        $end_time = $start_time + 24*60*60;
        $reach = 0;
        $sql = "INSERT INTO minority_data(link_id,children_id,number,start_time,end_time,reach)VALUES(?,?,?,?,?,?)";
        if($stmt2 = $this->conn-> prepare($sql)){
            $stmt2 -> bind_param("ssiiii", $parent,$child,$number,$start_time,$end_time,$reach);
            $stmt2 -> execute();
        }
        $count = 0;
        while($parent != ""){
            $this->update_endtime($parent,$end_time);
            $new_parent = $this->getParent($parent);
            if(($count == 0)){
                //do nothing
            }else{
                if($number == 1){
                    $this->update_reach($parent);
                }
            } 
            $parent = $new_parent;
            $count++;
        }
    }
    //Works
    function update_reach($parent){
        $sql = "UPDATE minority_data SET reach = reach + 1 WHERE children_id = ?";
        if($stmt2 = $this->conn-> prepare($sql)){
            $stmt2 -> bind_param("s", $parent);
            $stmt2 -> execute();
        }
    }
    //Works
    function update_endtime($parent,$end_time){
        $sql = "UPDATE minority_data SET end_time = ? WHERE children_id = ?";
        if($stmt2 = $this->conn-> prepare($sql)){
            $stmt2 -> bind_param("is", $end_time,$parent);
            $stmt2 -> execute();
        }
    }
    //Works
    function getParent($parent){
        $output = "";
        $sql = "SELECT link_id FROM minority_data WHERE children_id = ? ";
        if($stmt = $this->conn->prepare($sql)){  
                $stmt-> bind_param("s", $parent);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($link_id);
                if($stmt->num_rows > 0){
                    $stmt->fetch();
                    $output =$link_id;
                }
        }
        return $output;
    }
    //Works
    function storeInIP($link_id,$ip){
        $sql = "INSERT INTO minority_ip(ip,link_id)VALUES(?,?)";
        
        if($stmt2 = $this->conn-> prepare($sql)){
            $stmt2 -> bind_param("ss", $ip,$link_id);
            $stmt2 -> execute();
        }
    }
    //Works
    function isInIPTable($link_id){
        $sql = "SELECT link_id FROM minority_ip WHERE link_id = ? ";
        if($stmt = $this->conn->prepare($sql)){  
                $stmt-> bind_param("s", $link_id);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($link_id);
                if($stmt->num_rows > 0){
                    return true;
                }
        }
        return false;
    }
}