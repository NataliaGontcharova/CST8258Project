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
           ."WHERE (BINARY `Friend_RequesteeId` = :uid and `Friend_RequesterId` = :fid) or "
                 . "(BINARY `Friend_RequesteeId` = :fid and `Friend_RequesterId` = :uid)");

          foreach ($friendrequest as $v => $FriendId) {
              $q_friend_requests->execute( [':uid' => $user, ':fid' => $FriendId] );    
          }          
      }
        
    
    
    
    
    
    
    $q_friends = $pdo->prepare(
       "SELECT `Name`,`UserId`, count(`Album_Id`) as `Albums` " 
       ."FROM User left outer join Album on `User`.`UserId` = `Album`.`Owner_Id` and `Accessibility_Code`='shared' " 
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
    <section class="vh-100">
    <h1 class="text-center text-success mt-3"> My Friends page</h1>
  
    <p>Welcome<b> <?php echo $_SESSION['userName'] ?>!</b> (not you? change user <a href="login.php">  here.</a>)</p>
   
    <div class="row mb-5">
    <div class="col-lg-6 col-md-12">
      <h4 class="text-success mt-3">Friends :</h4>
      
       <b> <a href="AddFriend.php"> Add friends <a/></b>
   
    
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
                echo "<td><a href='FriendPictures.php?friend=". $q_friend_row['UserId'] ."'>" . $q_friend_row['Name'] . "</a></td>";
                echo "<td>" . $q_friend_row['Albums'] . "</td>";
                echo "<td><input name='friendrequest[]' type='checkbox' value='". $q_friend_row['UserId'] ."'/></td>";
                echo "</tr>";
            }
            
           ?>
                </tbody>
          </table>
         
          <input class="btn btn-success" value="Defriend Selected" type="submit" name="DenyFriendship" >        
        
        </form>
            </div>
</div>
    <div class="row">
     <div class="col-lg-6 col-md-12">
       <h4 class="text-success mt-3">Friends requests:</h4>
        
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
         
          <input class="btn btn-success" value="Accept Selected" type="submit" name="AcceptFriendship" >        
          <input class="btn btn-success" value="Deny Selected" type="submit" name="DenyFriendship" >        
        
        </form>
</div>
        
</div>
    </section>
    </div>
<?php 
    include('./common/Footer.php'); ?>


