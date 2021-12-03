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

<section class="vh-90" style="background-color: #eee;">
  <div class="container h-80">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-lg-12 col-xl-11">
        <div class="card text-black" style="border-radius: 25px;">
          <div class="card-body p-md-5">
            <div class="row justify-content-center">
              <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">

                <p class="text-center h1 fw-bold mb-3 mx-1 mx-md-4 mt-3">Sign up</p>

                <form method="post" class="mx-1 mx-md-4">
                   <div class="d-flex flex-row align-items-center mb-2">
                   
                    <div class="form-outline flex-fill mb-0">
                      <input type="text" name="userId" class="form-control" />
                      <label class="form-label" for="userId">User Id </label>
                    </div>
                  </div>
                  <div class="d-flex flex-row align-items-center mb-2">
                    <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                    <div class="form-outline flex-fill mb-0">
                      <input type="text" name="name" class="form-control" />
                      <label class="form-label" for="">Name</label>
                    </div>
                  </div>

                  <div class="d-flex flex-row align-items-center mb-2">
                   
                    <div class="form-outline flex-fill mb-0">
                      <input type="text" name="phone" class="form-control" />
                      <label class="form-label" for="phone">Phone number <small class="text-muted">(nnn-nnn-nnnn)</small></label>
                    </div>
                  </div>

                  <div class="d-flex flex-row align-items-center mb-2">                   
                    <div class="form-outline flex-fill mb-0">
                      <input type="password" name="password" class="form-control" />
                      <label class="form-label" for="password">Password</label>
                    </div>
                  </div>

                  <div class="d-flex flex-row align-items-center mb-2">                   
                    <div class="form-outline flex-fill mb-0">
                      <input type="password" name="passwordAgain" class="form-control" />
                      <label class="form-label" for="passwordAgain">Repeat your password</label>
                    </div>
                  </div>

                  

                  <div class="d-flex justify-content-start  mb-3 mb-lg-4">
                    <input class="btn btn-success mr-3" name="regSubmit" type="submit" value="Submit" >
                    <input class="btn btn-success" name="regClear" type="submit" value="Clear" >

                  </div>

                </form>

              </div>
              <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">

                  <img src="Common/img/undraw_dev_focus_re_6iwt.svg" class="img-fluid" alt="Sample image">

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php 
    include('./common/Footer.php'); ?>

