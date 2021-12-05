<?php 
session_start();
    include('./common/Header.php'); 
   
include_once "Functions.php";

$loginError = '';

extract($_POST); 
if(isset($loginSubmit)){
    try
    {
        $user = getUserByIdAndPassword($userId, $password);
    }
    catch(Exeption $e){
        die("Please try again later");
    }
    if ($user==null){
        $loginError = "Incorrect User Id and Password Combination";
    }
    else {
        $_SESSION['user']  = $userId;
        header("Location: MyAlbums.php");
        exit();
      }
 }
 else {
    if(isset($regClear)){
        $userI = '';
        $password = '';    
    }
}
   
    ?>

<div class="container">
    <h2>Log in</h2>
    <br/>
   <p> You need to <a href="NewUser.php">sign up</a> if you are a new user</p>
    <br/>
    
    <form action="Login.php" method="post">
        <div class="form-group row">
             <label for="StudentId" class="col-sm-2 col-form-label fw-bold">User ID:</label>
             <div class="col-sm-3">
             <input type="text" class="form-control" name="userId" id="userId" value="<?php echo isset($userId)? $userId : '' ?>" >
             </div>
             <div class="col-sm-3 text-danger">
                 <?php echo (isset($loginSubmit))? ValidateUserID($userId): '' ?>
             </div> 
        </div>
       
         <div class="form-group row">
             <label for="password" class="col-sm-2 col-form-label fw-bold">Password:</label>
             <div class="col-sm-3">
                 <input type="password" class="form-control" name="password" id="password" value="<?php echo isset($password) ? $password :''?>">
             </div>
             <div class="col-sm-3 text-danger">
               <?php echo isset($loginSubmit)? $loginError: "" ?>  
             </div> 
        </div>
      
         <div class="form-group row">
           
        <div class="col-md-2">
        <input class="btn btn-primary" name="loginSubmit" type="submit" value="Submit" >
        </div>
          <div class="col-md-1">   
        <input class="btn btn-primary" name="clear" type="submit" value="Clear" >
           </div>     
        </div>
        
        </form>

<?php include('./common/Footer.php'); ?>

