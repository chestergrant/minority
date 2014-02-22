<?php

class game_manager{
    private $conn;
    private $start_over;
    function __construct($db){
        $this->conn = $db;
        $this->start_over = 12;
    }
    function getState(){
        $sql = "SELECT state FROM minority_states WHERE status = 'A'"; 
        
        if($stmt = $this->conn->prepare($sql)){                
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($state);
                if($stmt->num_rows > 0){
                    $stmt->fetch();
                    if($state == 12){
                        return 1;
                    }
                    return $state + 100;
                }
        }
        return 1;
    }
    
    function getGame(){
        $sql = "SELECT id FROM minority_games WHERE status = 'A'"; 
        
        if($stmt = $this->conn->prepare($sql)){                
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($id);
                if($stmt->num_rows > 0){
                    $stmt->fetch();
                    return $id;
                }
        }
        return 0;
    }
    
    function activeGames(){
        $sql = "SELECT id FROM minority_games WHERE status = 'A'"; 
        
        if($stmt = $this->conn->prepare($sql)){                
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($id);
                if($stmt->num_rows > 0){
                    return true;
                }
        }
        return false;
    }
    
    function getTimeToState3($game_id){
        $sql = "SELECT end_time,status FROM minority_states WHERE game_id = ? and state = 12"; 
        $output = 300;
        if($stmt = $this->conn->prepare($sql)){  
                $stmt-> bind_param("i", $game_id);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($end_time,$status);
                if($stmt->num_rows > 0){
                    $stmt->fetch();                    
                    $output = $end_time - time();
                    if(($output <= 0)&&($status == 'A')){
                        $output = 10;
                    }
                }
        }
        echo $output."<br>";
        flush();
        return $output;
    }
    
    function createState($game_id,$state,$length){
        $this->blanketTable("minority_states");
        $start_time = time();
        $end_time = $start_time + $length;
        $sql = "INSERT INTO minority_states(game_id,state,start_time,end_time,status)VALUES(".$game_id.",".$state.",".$start_time.",".$end_time.",'A')";
        echo $sql;
        //exit();
        $this->conn->query($sql);
        
    }
    function getCurrentGameId(){
        $sql = "SELECT id FROM minority_games WHERE status = 'A' ";
        $output = 0;
        if($stmt = $this->conn->prepare($sql)){                
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($id);
                if($stmt->num_rows > 0){
                    $stmt->fetch();                    
                    $output = $id;
                }
        }
        return $output;
        
    }
    function blanketTable($table){
        $sql = "UPDATE ".$table." SET status = 'I' WHERE status = 'A'";
        $this->conn->query($sql);
        
    }
    
    function getStateLength($state){
        $output = 60;
        if($state == 12){
            $output = 180;            
        }else if($state == 34){
            $output = 420;            
        }
        return $output;
    }
    function nextState($current_state){
        $output = 12;
        if($current_state == 12){
            return 34;            
        }
        if($current_state == 34){
            return 12;            
        }
        
        return $output;
    }
    
    function getCurrentState(){
        $sql = "SELECT state FROM minority_states WHERE status = 'A' ";
        $output = 0;
        if($stmt = $this->conn->prepare($sql)){                
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($state);
                if($stmt->num_rows > 0){
                    $stmt->fetch();                    
                    $output = $state;
                }
        }
        return $output;
    }
    
    function createNextState(){
        echo "Been in here";
        flush();
        $game_id = $this->getCurrentGameId();
        $current_state  = $this->getCurrentState();
        $next_state = $this->nextState($current_state);
        $length = $this->getStateLength($next_state);
        if($next_state == $this->start_over){
            if($next_state == $current_state){
                echo "Next stat is current state<br>";
            }
            echo "Been in here2";
            echo $this->start_over;
            flush();
            $this->createGame();
        }else{
            echo "What the hell man";
            echo "What the hell";
            flush();
            $this->createState($game_id, $next_state, $length);
        }
        flush();
    }
    
    function createGame(){
        echo "I was called...";
        flush();
        $this->blanketTable("minority_games");
        $sql = "INSERT INTO minority_games(status)VALUES('A')";
        $this->conn->query($sql);
        $game_id = $this->getCurrentGameId();
        $this->createState($game_id,12,$this->getStateLength(12));        
    }
    function getTimeLeftInState(){
        $sql = "SELECT end_time FROM minority_states WHERE status = 'A'"; 
        $output = 300;
        if($stmt = $this->conn->prepare($sql)){                
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($end_time);
                if($stmt->num_rows > 0){
                    $stmt->fetch();                    
                    $output = $end_time - time();
                }
        }
        echo $output."<br>";
        flush();
        return $output;
    }
    
    function storeUser($handle,$tweet){        
         $sql = "SELECT handle FROM minority_users WHERE handle = ? AND status = 'A'";
         if($stmt2 = $this->conn-> prepare($sql)){
            $stmt2 -> bind_param("s", $handle);
            $stmt2 -> execute();
            $stmt2->store_result();
            $stmt2->bind_result($output);
            if($stmt2->num_rows > 0){
                return false;
            }
         }
         $sql = "INSERT INTO minority_users ( handle, tweet, status,entry_time)VALUES(?,?,?,?)";
         if($stmt = $this->conn->prepare($sql)){
                $status = 'A';
                $stmt->bind_param("sssi", $handle, $tweet,$status,time());
                $stmt->execute();
         }
         return true;
    }
}
?>
