<?php 
    include('./common/Header.php'); 
    if(!isset($_SESSION['user']) || $_SESSION['user'] === '') {
         header("Location: Index.php");
    }
    
    extract($_SESSION); 
    extract($_POST); 
    
    include('./Functions.php'); 
    $pdo = getPDO();

    if(isset($AcceptFriendship)) {       
         $q_friend_requests = $pdo->prepare(
           "update Friendship set `Status`= 'accepted' " 
           ."WHERE BINARY `Friend_RequesteeId` = :uid and `Friend_RequesterId` = :fid and "
                . "`Status` = 'request'");

          foreach ($friendrequest as $v => $FriendId) {
              $q_friend_requests->execute( [':uid' => $user, ':fid' => $FriendId] );    
          }          
      }
        
    
    if(isset($DenyFriendship)) {       
         $q_friend_requests = $pdo->prepare(
           "delete from Friendship  " 
           ."WHERE BINARY `Friend_RequesteeId` = :uid and `Friend_RequesterId` = :fid");

          foreach ($friendrequest as $v => $FriendId) {
              $q_friend_requests->execute( [':uid' => $user, ':fid' => $FriendId] );    
          }          
      }
        
    
    
    
    
    
    
    $q_friends = $pdo->prepare(
       "SELECT `Name`,`UserId`, count(`Album_Id`) as `Albums` " 
       ."FROM User left outer join Album on `User`.`UserId` = `Album`.`Owner_Id` " 
       ."WHERE `UserId` in "
            . "(Select `Friend_RequesterId` from Friendship "
             . "where BINARY `Friend_RequesteeId` = :uid and `Status` = 'accepted') or "
       ."`UserId` in "
            . "(Select `Friend_RequesteeId` from Friendship "
             . "where BINARY `Friend_RequesterId` = :uid and `Status` = 'accepted') " 
       . "group by 1, 2 "
       . "order by 1");

    $q_friends->execute( [':uid' => $user] );

    $q_friend_requests = $pdo->prepare(
       "SELECT `Name`, `UserId` " 
       ."FROM User " 
       ."WHERE `UserId` in "
            . "(Select `Friend_RequesterId` from Friendship "
             . "where BINARY `Friend_RequesteeId` = :uid and `Status` = 'request')  " 
       . "order by 1");

    $q_friend_requests->execute( [':uid' => $user] );
 
           
?>

<div class="container">
    <h3> My Friends page</h3>
  
    <div class="container">
    <h2 class="text-center"> Course Selection </h2>
    <p>Welcome <?php echo $_SESSION['user'] ?>! (not you? change user <a href="login.php">  here.</a>)</p>
    <p>Please note that the courses you have registered will not be displayed in the list. </p></br>
  
    <div>
    
    <div> 
        Friends :
        <a href="AddFriend.php"> Add friends <a/>
    </div>
    
    <form action="MyFriends.php" method="post">        
         <table class="table">
                <thead>
                <tr>
                <th>Name</th>
                <th>Shared Albums</th>
                <th>Defriend</th>
                </tr>
                </thead>
                <tbody>
                    
           <?php 
            
             while($q_friend_row = $q_friends->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . $q_friend_row['Name'] . "</td>";
                echo "<td>" . $q_friend_row['Albums'] . "</td>";
                echo "<td><input name='friendrequest[]' type='checkbox' value='". $q_friend_row['UserId'] ."'/></td>";
                echo "</tr>";
            }
            
           ?>
                </tbody>
          </table>
         
          <input class="btn btn-primary" value="Defriend Selected" type="submit" name="DenyFriendship" >        
        
        </form>

        <div>
            Friend requests:
            
        </div>
        
        <form action="MyFriends.php" method="post"> 
         <table class="table">
                <thead>
                <tr>
                <th>Name</th>
                <th>Accept or deny</th>
                </tr>
                </thead>
                <tbody>
                    
           <?php 
           
             while($q_request_row = $q_friend_requests->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . $q_request_row['Name'] . "</td>";
                echo "<td><input name='friendrequest[]' type='checkbox' value='". $q_request_row['UserId'] ."'/></td>";
                echo "</tr>";
            }
           
                    
            ?>
                </tbody>
          </table>
         
          <input class="btn btn-primary" value="Accept Selected" type="submit" name="AcceptFriendship" >        
          <input class="btn btn-primary" value="Deny Selected" type="submit" name="DenyFriendship" >        
        
        </form>

        
</div>
    </div>
<?php 
    include('./common/Footer.php'); ?>


