<?php 
    include('./common/Header.php'); 
    
    if(!isset($_SESSION['user']) || $_SESSION['user'] === '') {
         header("Location: Index.php");
    }
    
    
    extract($_SESSION); 
    extract($_POST); 

    $validationMessage = '';
    
    include('./Functions.php'); 
    $pdo = getPDO();

    if(isset($FriendId) && $FriendId === $user){
        $validationMessage = 'Cannot send request to yourself';
    }
    
    if(isset($SendRequest) && $validationMessage === '') {
            $q_friend_requests = $pdo->prepare(
               "Select `Name` from User " 
               ."WHERE BINARY `UserId` = :fid");

            $q_friend_requests->execute( [':fid' => $FriendId] );  

            if($q_request_row = $q_friend_requests->fetch(PDO::FETCH_ASSOC))                        
                $FriendName = $q_request_row['Name'];
            else 
                $validationMessage = 'User ' .$FriendId . ' doesn\'t exist.';            
                      
    }
    
    if(isset($SendRequest) && $validationMessage === '') {
        $q_friend_requests = $pdo->prepare(
           "Select 1 from Friendship "
                 . "where (BINARY `Friend_RequesteeId` = :uid and BINARY `Friend_RequesterId` = :fid) or "
                . " (BINARY `Friend_RequesterId` = :uid and BINARY `Friend_RequesteeId` = :fid) "
                . "and `Status` = 'accepted'");

        $q_friend_requests->execute( [':uid' => $user, ':fid' => $FriendId] );    
        
        if($q_friend_requests->fetch(PDO::FETCH_ASSOC)){
            $validationMessage = 'You and ' .$FriendId . ' are already friends';            
        }
        
        if($validationMessage === '') {
            $q_friend_requests = $pdo->prepare(
               "Select `Friend_RequesteeId` from Friendship " 
               ."WHERE BINARY `Friend_RequesteeId` = :uid and `Friend_RequesterId` = :fid and"
                    . "`Status` = 'request'");

            $q_friend_requests->execute( [':uid' => $user, ':fid' => $FriendId] );    
            
            if($q_friend_requests->fetch(PDO::FETCH_ASSOC)){
                $q_friend_requests = $pdo->prepare(
                   "update Friendship set `Status`= 'accepted' " 
                   ."WHERE BINARY `Friend_RequesteeId` = :uid and `Friend_RequesterId` = :fid and "
                        . "`Status` = 'request'");

                
                if (!$q_friend_requests) {
                    echo "\nPDO::errorInfo():\n";
                     print_r($dbh->errorInfo());
                }                
                
                
                $q_friend_requests->execute( [':uid' => $user, ':fid' => $FriendId] );    

                            
            }
            else {
                $q_friend_requests = $pdo->prepare(
                   "insert into Friendship(`Friend_RequesteeId`,`Friend_RequesterId`,`Status`) " 
                   ."values(:fid , :uid, 'request')");

                if (!$q_friend_requests) {
                    echo "\nPDO::errorInfo():\n";
                     print_r($dbh->errorInfo());
                    }                

                
                
                $q_friend_requests->execute( [':uid' => $user, ':fid' => $FriendId] );    

                if (!$q_friend_requests) {
                    echo "\nPDO::errorInfo():\n";
                     print_r($dbh->errorInfo());
                }                
            }

           $validationMessage = 
               'Your request has been sent to '. $FriendName . ' (ID: ' .$FriendId . ').' . 
               ' Once '.$FriendName. ' accepts your request, you and '.  $FriendName .
               ' will be friends and be able to view each other\'s shared albums';            
            
        }
    }
?>

<div class="container">
    <section class="vh-100"> 
   
    
  
    
    <h1 class="text-success mt-3"> Add friend</h1>
    
    <p>Welcome<b> <?php echo $_SESSION['user'] ?>!</b> (not you? change user <a href="login.php">  here.</a>)</p>
    <p class="mb-5">Enter the ID of the user you want to friend with </p>
  
 
    <div class="col-sm-3 text-danger">
        <?php echo $validationMessage ?>  
    </div> 

    
    <form action="AddFriend.php" method="post">        
    <div>    
    ID: 
    <input type="text" name="FriendId" />
    <input type="submit" name="SendRequest" value="Send Friend Request"/>
    </div>
    </form>

        
</div>
    </section>

<?php 
    include('./common/Footer.php'); ?>


