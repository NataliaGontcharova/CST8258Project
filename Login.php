<?php 
    include('./common/Header.php'); 
   
include_once "Functions.php";

$loginError = '';

extract($_POST); 
if(isset($regSubmit)){
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
    if(isset($_SESSION['user']) && $_SESSION['user'] !== '') {
        $_SESSION['user']  = '';
        header("Location: index.php");
        exit();
    }
    else if(isset($regClear)){
        $userID = '';
        $password = '';    
    }
}

 
    
    ?>




             <div class="col-sm-3 text-danger">
                 <?php echo $loginError ?>
             </div> 

<section class="vh-100" style="background-color: #eee;">
  <div class="container v-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-lg-12 col-xl-11">
        <div class="card text-black" style="border-radius: 25px;">
          <div class="card-body p-md-5 vh-100">
            <div class="row justify-content-center">
              <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">

                <p class="text-center h1 fw-bold mb-3 mx-1 mx-md-4 mt-3">Log in</p>
 <p class="text-center h6  mb-3 mx-1 mx-md-4 mt-3">You need to sign up if you are new user</p>
                <form class="mx-1 mx-md-4" method="post">
                   <div class="d-flex flex-row align-items-center mb-2">
                   
                    <div class="form-outline flex-fill mb-0">
                      <input type="text" name="userId" class="form-control" />
                      <label class="form-label" for="userId">User Id </label>
                    </div>
                  </div>                
              
                  <div class="d-flex flex-row align-items-center mb-2">                   
                    <div class="form-outline flex-fill mb-0">
                      <input type="password" name="password" class="form-control" />
                      <label class="form-label" for="password">Password</label>
                    </div>
                  </div>                             

                  <div class="d-flex justify-content-start  mb-3 mb-lg-4">
                    <input class="btn btn-success mr-3" name="regSubmit" type="submit" value="Submit" >
                    <input class="btn btn-success" name="regClear" type="submit" value="Clear" >
                  </div>

                </form>

              </div>
              <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">

                  <img src="Common/img/undraw_secure_login_pdn4.svg" class="img-fluid" alt="Sample image">

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<?php include('./common/Footer.php'); ?>


