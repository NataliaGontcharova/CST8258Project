<?php 
    session_start();
    if(!isset($_SESSION['user']) || $_SESSION['user'] === '') {
         header("Location: Index.php");
        
    } else {
        $userId =$_SESSION['user'];
    } 

    include('./common/Header.php'); 
    ?>
<?php 
$dbConnection = parse_ini_file("Project.ini");
extract($dbConnection);
$conn = new PDO($dsn, $user, $password);
//GET NAME
$sqlName = "SELECT name FROM user where userId='{$userId}'";
$queryCourse = $conn->prepare($sqlName);
$queryCourse->execute();
$courseInfo = $queryCourse->fetch(PDO::FETCH_ASSOC);
$userName = $courseInfo["name"];
$_SESSION['userName']= $userName;
// Load Access Authority options

    
?>


<div class="container">
    <h3> My Albums</h3>
    <h4>Welcome  <?php print_r($userName); ?> (not you? change user <a href="Login.php">here</a>)</h4>
    <p><a href="AddAlbum.php">Create a New Album</a></p>
    
  
</div>
<?php include('./common/Footer.php'); ?>
