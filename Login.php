<?php 
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
  <section class="vh-100 d-flex justify-content-center p-5" style="background-color: #eee; ">
    <div class="row mt-5">
   <div class="col-lg-6 col-md-12">
    <h1 class="mb-2 text-success">Log in</h1>
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
       
         <div class="form-group row mb-5">
             <label for="password" class="col-sm-2 col-form-label fw-bold">Password:</label>
             <div class="col-sm-3">
                 <input type="password" class="form-control" name="password" id="password" value="<?php echo isset($password) ? $password :''?>">
             </div>
             <div class="col-sm-3 text-danger">
               <?php echo isset($loginSubmit)? $loginError: "" ?>  
             </div> 
        </div>
      
         <div class="d-flex flex-row flex-wrap justify-content-between">
             <div col-3>
        <input class="btn btn-success" name="loginSubmit" type="submit" value="Submit" >
        
        <input class="btn btn-success" name="clear" type="submit" value="Clear" >
               
        </div>
             </div>
        
        </form>
    </div>
        
        <div class="col-lg-6 col-md-12 ">
            <img src="Common/img/undraw_secure_login_pdn4.svg" class="img-fluid p-3" alt="Sample image">
            </div>
         </div>
      </section>
        </div>
<?php include('./common/Footer.php'); ?>

