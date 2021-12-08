<?php 
    include('./common/Header.php'); 
    
    if(!isset($_SESSION['user']) || $_SESSION['user'] === '') {
         header("Location: Index.php");       
    } else {
        $userId =$_SESSION['user'];
    } 
    if(isset($_SESSION['AccessOptions']))
    {
        $accessOption = $_SESSION['AccessOptions'];
    }
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
$sqlAlbum= "SELECT Album_Id, Title, Description, Date_Updated, Accessibility_Code FROM Album  WHERE Owner_Id = '{$userId}'";
$queryAlbum = $conn->prepare($sqlAlbum);
$queryAlbum->execute();
$resultAlbum = $queryAlbum->fetchAll();  
//Array ( [Album_Id] => 20 [Title] => t [Description] => tt [Date_Updated] => 2021-12-05 [Accessibility_Code] => private )
//print_r($resultAlbum[4]["Title"]);
$num = count($resultAlbum);
  
if(isset($_POST["delete"]))
{
    if(isset($_POST["deleteID"]))
    {
        //delete
        $sqldeletee="DELETE FROM album WHERE Album_Id='{$_POST["deleteID"]}'&& Owner_Id='{$userId}' ";
        $queryDele = $conn->prepare($sqldeletee);
        $result=$queryDele->execute();
    }     
}
if(isset($_POST['save']))
{
    print_r($_POST['access']);
}

?>


<div class="container">  
    <h3>My Albums</h3>
    <h4>Welcome  <?php print_r($userName); ?> (not you? change user <a href="Login.php">here</a>)</h4>
    <p><a href="AddAlbum.php">Create a New Album</a></p>
    
    
    <form action="MyAlbums.php" method="post">
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date Update</th>
                    <th>Number of Pictures</th>
                    <th>Accessibility</th>
                    <th> </th>
                </tr>
            </thead>
            <tbody>                    
                <?php              
                  for ($i=0; $i<$num;$i++) {
                      
                     echo "<tr>";
                     echo "<td><a href='MyPictures.php?albumId=".$resultAlbum[$i]["Album_Id"]."'>" . $resultAlbum[$i]["Album_Id"] ." - ".$resultAlbum[$i]["Title"] . "</a></td>";
;
                     echo "<td>" . $resultAlbum[$i]["Date_Updated"] . "</td>";
                     
                     //show picnum
                     $sqlPic = "SELECT Picture_Id FROM Picture WHERE Album_Id = {$resultAlbum[$i]["Album_Id"]}";
                     $queryPic = $conn->prepare($sqlPic);
                    $queryPic->execute();
                    $resultPic = $queryPic->fetchAll();  
                     $numPic = count($resultPic);
                     echo "<td>" .$numPic."</td>";                     
                     
                     echo "<td> <select name='access'>";

                    foreach ($conn->query('SELECT * FROM accessibility ;') as $row)
                    {
                        echo '<option value="'.$row[0].'"'.($row[0]== $resultAlbum[$i]["Accessibility_Code"]? "selected":'').' >'.$row[1].'</option>';
                    }
                     
                        echo '</select></td>';
                       echo "<td><a onclick='return deleteClicked()' id='btndelete'>delete</a>"
                                . "<input type='text' name='deleteID' value='".$resultAlbum[$i]["Album_Id"]."'></td>";
                       
                     echo "</tr>";
                 }

                ?>
            </tbody>
        </table>
         <input class="btn btn-primary" value="Delete" type="submit" name="delete" id="submitBtn">
          <input class="btn btn-primary" value="Save Change" type="submit" name="save" >
    </form>
    <script type="text/javascript">
        function deleteClicked(){
            return confirm("Please confirm to delete this albumn? ");
        }
        //tie confirm delete 
        document.querySelector("#btndelete").addEventListener("click",function(){
            alert("trigger submit")
            document.getElementById("submitBtn").click();
        },false)
    </script>   
  
</div>
<?php     include('./common/Footer.php'); ?>
