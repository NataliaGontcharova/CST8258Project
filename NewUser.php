<?php 
    include('./common/Header.php');
include_once "Functions.php";

$userExists = false;

extract($_POST);    
    
    if(isset($regSubmit) && ValidateUserID($userId) === '' && ValidateName($name) === '' &&
             ValidatePhone($phone) === '' && ValidatePassword($password,$passwordAgain) === '')
    {
        try{
        if(!checkIfUserExists($userId)) {
            addNewUser($userId, $name, $phone, $password);
            header("Location: Login.php");           
        }
        else {
            $userExists = true;
        }
        }
        catch(Exception $e)
        {
            die("Please try again later");
        }
    }
    else if(isset($regClear)){
        $userId = '';
        $name = '';
        $password = '';    
        $phone = '';
        $passwordAgain = '';
    }
    ?>

<div class="container">
    <section class="vh-100 d-flex  p-5" style="background-color: #eee; ">
       
     <div class="row mt-5">
     <div class="col-lg-7 col-md-12">
    <h2 class="mb-2 text-success">Sign up</h2>
    <br/>
   <p> All fields are required</p>
    <br/>
    
    <form action="newUser.php" method="post">
        <div class="form-group row">
             <label for="userId" class="col-sm-3 col-form-label fw-bold">User ID:</label>
             <div class="col-sm-4">
             <input type="text" class="form-control" name="userId" id="studentID" value="<?php echo isset($userId)? $userId : '' ?>" >
             </div>
             <div class="col-sm-3 text-danger">
                 <?php 
                    if($userExists) {
                        echo 'A user with this ID already signed up';
                    }
                    else {
                        echo (isset($regSubmit))? ValidateUserID($userId): '';
                    }
                 ?>
             </div> 
        </div>
        
        <div class="form-group row">
             <label for="name" class="col-sm-3 col-form-label fw-bold">Name:</label>
             <div class="col-sm-4">
             <input type="text" class="form-control" name="name" id="name" value="<?php echo isset($name)? $name :'' ?>">
             </div>
             <div class="col-sm-3 text-danger">
               <?php echo (isset($regSubmit))? ValidateName($name):'' ?>
             </div> 
        </div>
                  
                 
        <div class="form-group row">
            <label for="phone" class="col-sm-3 col-form-label fw-bold">Phone number:</br>
            <small class="text-muted">(nnn-nnn-nnnn)</small></label> 
            </label>
            <div class="col-sm-4">
            <input type="text" class="form-control" name="phone" id="phone" value="<?php echo isset($phone) ? $phone:'' ?>" >
            </div>
            <div class="col-sm-3 text-danger">
            <?php echo (isset($regSubmit))? ValidatePhone($phone): ''?>
            </div> 
         </div>
        
         <div class="form-group row">
             <label for="password" class="col-sm-3 col-form-label fw-bold">Password:</label>
             <div class="col-sm-4">
                 <input type="password" class="form-control" name="password" id="password" value="<?php echo isset($password)? $password:'' ?>">
             </div>
             <div class="col-sm-3 text-danger">
               <?php echo (isset($regSubmit))? ValidatePassword($password,$passwordAgain): '' ?>  
             </div> 
        </div>
        
        <div class="form-group row">
             <label for="passwordAgain" class="col-sm-3 col-form-label fw-bold">Password Again:</label>
             <div class="col-sm-4 mb-4">
                 <input type="password" class="form-control" name="passwordAgain" id="passwordAgain" value="<?php echo isset($passwordAgain)? $passwordAgain:'' ?>">
             </div>
             <div class="col-sm-3 text-danger">
             <?php echo (isset($regSubmit))? ValidatePassword($password,$passwordAgain): '' ?>  
 
             </div> 
        </div>
        
         
         <div class="col-lg-6 col-md-12">
             
             </div>
         
        <div class="d-flex flex-row flex-wrap justify-content-between">
             <div col-3>
           
        
        <input class="btn btn btn-success" name="regSubmit" type="submit" value="Submit" >
          
        <input class="btn btn btn-success" name="regClear" type="submit" value="Clear" >
          
        </div>
            </div>
        
        </form>
    </div>
     <div class="col-lg-5 col-md-12 ">
         
         <img src="Common/img/undraw_dev_focus_re_6iwt.svg" class="img-fluid p-1 mt-5" alt="Sample image">
            </div>
         </div>
    
    </div>
</section></div>
<?php 
    include('./common/Footer.php'); ?>

