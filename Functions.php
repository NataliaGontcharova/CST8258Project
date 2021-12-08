<?php
function getPDO()
{
    $dbConnection = parse_ini_file("Project.ini");
    extract($dbConnection);
    $myPdo = new PDO($dsn, $user, $password);
    return $myPdo;
}



class User {
  public $userId;
  public $name;
  public $phone;
}

function getUserByIdAndPassword($userId, $password)
{
    $pdo = getPDO();
    
    $prepared = $pdo->prepare("SELECT `UserId`, `Name`, `Phone`, `Password` FROM User WHERE BINARY `UserID` = :sid");
    
    $prepared->execute( [':sid' => $userId]);

    $row = $prepared->fetch(PDO::FETCH_ASSOC);
    if($row)
    {
        if(!password_verify($password, $row['Password']) ) 
        {    
            return false;
        } 

        return true;
    }
   
    return false;
}

function getUserNameById($userId)
{
    $pdo = getPDO();
    
    $prepared = $pdo->prepare("SELECT `Name` FROM User WHERE BINARY `UserID` = :sid");
    
    $prepared->execute( [':sid' => $userId]);

    $row = $prepared->fetch(PDO::FETCH_ASSOC);
    if($row)
    {
        return $row['Name'];
    }
   
    return '';   
}




function checkIfUserExists($userId)
{
    $pdo = getPDO();
    $prepared = $pdo->prepare("SELECT `UserId` FROM User WHERE BINARY `UserID` = :sid");
    $prepared->execute([':sid' => $userId]);
    if ($prepared)
    {
        $row = $prepared->fetch(PDO::FETCH_ASSOC);
        if($row)
        {
            return true;
        }
        else
        {
            return false;
        }
        
    }
    else{
        return false;
    }
    
    return false;
}




function addNewUser($userId,$name, $phone, $password )
{
    $pdo = getPDO();
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT );
    
    $sql = "INSERT INTO User(`UserId`,`Name`,`Phone`,`Password`) VALUES ('$userId', '$name', '$phone', '$hashed_password')";
    $pdoStm = $pdo->query($sql);
}

// validation functions
function ValidateUserID($userId)
    {
       return (!isset($userId) || $userId === "")? "Name is required" : "";             
    } 
function ValidateName($name)
    {
       return (!isset($name) || $name === "")? "Name is required" : "";             
    } 
  
function ValidatePhone($phone) 
        {
        if(!isset($phone) || $phone === ""){
            return "Phone number is required";
        }
        if(preg_match("/^[2-9][0-9]{2}-[2-9][0-9]{2}-[0-9]{4}$/", $phone) != 1) {
            return "Incorrect Phone Format";
        }
        return "";
        }
        
function ValidatePassword($password,$passwordAgain)
{
//    return "";
    
    if(!isset($password)&&!isset($passwordAgain))
        return"Password is required";
    
    if($password !== $passwordAgain) 
        return "Passwords don't match";
    
    if(strlen($password) < 6)
        return "Password must contain at least 6 charactors";
    
    if(!preg_match('/[A-Z]/', $password))
        return "Password must contain at least one uppercase charactor";

    if(!preg_match('/[a-z]/', $password))
        return "Password must contain at least one lowercase charactor";

    if(!preg_match('/[0-9]/', $password))
        return "Password must contain at least one digit";
    
    return "";
}



/**
 * 
 * 
 * 
 * MY ADDED FUNCTIONS
 * 
 * 
 */
function getALbumList($userId)
{
    $pdo = getPDO();
    $prepared = $pdo->prepare("SELECT `Album_Id`, `Title` FROM album WHERE `Owner_Id` = :sid");
    $prepared->execute(['sid' => $userId]);
    $list = $prepared->fetchAll(PDO::FETCH_ASSOC);
    if (!$list) {
        return [];
    }
    return $list;
}

function getFriendsAlbumList($userId)
{
    $pdo = getPDO();
    $prepared = $pdo->prepare("SELECT `Album_Id`, `Title` FROM album WHERE `Owner_Id` = :sid and `Accessibility_Code`='shared'");
    $prepared->execute(['sid' => $userId]);
    $list = $prepared->fetchAll(PDO::FETCH_ASSOC);
    if (!$list) {
        return [];
    }
    return $list;
}

function getALbumPictureList($Album_Id)
{
    $pdo = getPDO();
    $prepared = $pdo->prepare("SELECT * FROM `picture` WHERE `Album_Id` = :sid");
    $prepared->execute(['sid' => $Album_Id]);
    $list = $prepared->fetchAll(PDO::FETCH_ASSOC);
    if (!$list) {
        return [];
    }
    return $list;
}


function getPictureCommentsList($Picture_Id)
{
    $pdo = getPDO();
    $prepared = $pdo->prepare("SELECT c.*, u.Name FROM `comment` c, user u WHERE u.`UserId`=c.`Author_id` AND c.`Picture_Id`=:sid ORDER By c.`Date` DESC");
    $prepared->execute(['sid' => $Picture_Id]);
    $list = $prepared->fetchAll(PDO::FETCH_ASSOC);
    if (!$list) {
        return [];
    }
    return $list;
}
