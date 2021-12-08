<?php 
    include("./common/header.php"); 
    if($_SESSION['userName'])
    {
        $userName=$_SESSION['userName'];
    }
    if(isset($_SESSION['user'])) 
    {         
        $userId =$_SESSION['user'];
    }
?>
<?php
$arrAccess=[];
$dbConnection = parse_ini_file("Project.ini");
extract($dbConnection);
$conn = new PDO($dsn, $user, $password);

//load acceaa authority
$sqlAcc='SELECT * FROM accessibility ;';
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pStmt = $conn->prepare($sqlAcc);
$pStmt->execute();      
$row =$pStmt->fetchAll();  //  t/f 。fetch(PDO::FETCH_ASSOC);  只做一個      
if($row)
{
//    echo 'line 18';
    foreach ($row as $eachRow)
    {
//        echo 'check '.$eachRow[1]; //[0] code [1] descript
        array_push($arrAccess, $eachRow);
    }
}

//btn
if(isset($_POST['submit']))
{
    $time = (new DateTime())->format('Y-m-d H:i:s');
    extract($_POST);
    try
    {
        $dbConnection = parse_ini_file("Project.ini");
        extract($dbConnection);
        $conn = new PDO($dsn, $user, $password);
        $sqlSave = "INSERT INTO Album (`Title`, `Description`, `Owner_Id`, `Date_Updated`, `Accessibility_Code`) VALUES( :title, :description, :userId, :dateUpdated, :accessibilityCode)";
        $queryCourse = $conn->prepare($sqlSave);
        $result=$queryCourse->execute([':title' => $title, ':description' => $description, ':userId' => $userId, ':dateUpdated' => $time, ':accessibilityCode' => $access]);

/*        if($result)
        {
            echo 'success';
        } else {
            echo 'no save';
        } */
        
    }catch(Exeption $e){
        die("Please try again later");
    }
}

?>
<link rel="stylesheet" href="./Common/css/albumn.css">   
<div class="container">
    <h1 class="text-success mt-3">Create New Album</h1>
    <h4 class="mb-2">Welcome  <?php print_r($userName); ?> (not you? change user <a href="Login.php">here</a>)</h4>
    <form action="AddAlbum.php" method="post" class="row">
        <p class="col-md-3">Title</p>
        <p class="col-md-9">
            <input type="text" name="title" value="">
        </p>
        
        <p class="col-md-3">Accessibillity</p>
        <p class="col-md-9">
            <select name="access" id="access" class="selectAlbum">
            <option value="-1">Select ...</option>
            <?php
                try{
                    foreach($arrAccess as $option) 
                    {
                        echo '<option value="'.$option[0].'">'.$option[1].'</option>';
                    }
                }catch(PDOException $e) {
                               echo '60 '.$e->getMessage();
                }  
            ?>
            </select>
        </p>
        
        <p class="col-md-12">
            <p class="col-md-3">Description</p>
            <p class="col-md-9">
                <textarea name="description" style="width:50%; height: 150px"></textarea>
            </p>
        </p>
        <p class="col-md-12">
            <input type="submit" name="submit" value="Submit" class="btn-success btn" style="margin-right: 1.5rem ">
             <input type="submit" name="reset" value="Reset" class="btn-success btn" >
        </p>
        
    </form>
</div>

<?php include('./common/footer.php'); ?>
