<?php 
    session_start();
    if(!isset($_SESSION['user']) || $_SESSION['user'] === '') {
         header("Location: Index.php");
        
    } else {
        $userId =$_SESSION['user'];
    } 
    if(isset($_SESSION['AccessOptions']))
    {
        $accessOption = $_SESSION['AccessOptions'];
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
$sqlAlbum= "SELECT Album_Id, Title, Description, Date_Updated, Accessibility_Code FROM Album  WHERE Owner_Id = '{$userId}'";
$queryAlbum = $conn->prepare($sqlAlbum);
$queryAlbum->execute();
$resultAlbum = $queryAlbum->fetchAll();  
//Array ( [Album_Id] => 20 [Title] => t [Description] => tt [Date_Updated] => 2021-12-05 [Accessibility_Code] => private )
//print_r($resultAlbum[4]["Title"]);
$num = count($resultAlbum);
  
if(isset($_POST["delect"]))
{
//    echo 'submit done';
    if(isset($_POST["deleteID"]))
    {
//        print_r ($_POST["deleteID"]);
        //delect
        $sqlDelecte="DELETE FROM album WHERE Album_Id='{$_POST["deleteID"]}'&& Owner_Id='{$userId}' ";
        $queryDele = $conn->prepare($sqlDelecte);
        $result=$queryDele->execute();
        if($result)
        {
            echo 'succ';            
        } else {
            echo 'false';    
        }        
    }     
}
if($_POST['save'])
{
    print_r($_POST['access']);
}

?>


<div class="container">
    <p>數量43<?php print_r($num); ?></p>
    
    <h3> My Albums</h3>
    <h4>Welcome  <?php print_r($userName); ?> (not you? change user <a href="Login.php">here</a>)</h4>
    <p><a href="AddAlbum.php">Create a New Album</a></p>
    
    
    <form action="MyAlbums.php" method="post">
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date Update</th>
                    <th>Number of Picture</th>
                    <th>Accessibility</th>
                    <th> </th>
                </tr>
            </thead>
            <tbody>                    
                <?php              
                  for ($i=0; $i<$num;$i++) {
                      
                     echo "<tr>";
                     echo "<td>" . $resultAlbum[$i]["Album_Id"] .$resultAlbum[$i]["Title"] . "</td>";
                     echo "<td>" . $resultAlbum[$i]["Date_Updated"] . "</td>";
                     
                     //show picnum
                     $sqlPic = "SELECT Picture_Id FROM Picture WHERE Album_Id = {$resultAlbum[$i]["Album_Id"]}";
                     $queryPic = $conn->prepare($sqlPic);
                    $queryPic->execute();
                    $resultPic = $queryPic->fetchAll();  
                     $numPic = count($resultPic);
                     echo "<td>" .$numPic."</td>";
                     
                     //確認權限 停留在那
                     
                     echo "<td> <select name='access'>";
//                        foreach ($option as $accessOption)
//                        {
//                        echo "<option value='".$option."'".($resultAlbum[$i]["Accessibility_Code"]== $option? "selected":'')." >".$option."</option>". ""; 
//                        }
                    foreach ($conn->query('SELECT * FROM accessibility ;') as $row)
                    {
                        echo '<option value="'.$row[0].'"'.($row[0]== $resultAlbum[$i]["Accessibility_Code"]? "selected":'').' >'.$row[1].'</option>';
                    }
                     
                        echo '</select></td>';
//                     echo "<td><a onclick='return delectClicked('".$resultAlbum[$i]["Title"] ."')'>Delect</a></td>";
                       echo "<td><a onclick='return delectClicked()' id='btnDelect'>Delect</a>"
                                . "<input type='text' name='deleteID' value='".$resultAlbum[$i]["Album_Id"]."'></td>";
                       
                     echo "</tr>";
                 }

                ?>
            </tbody>
        </table>
         <input class="btn btn-primary" value="delect" type="submit" name="delect" id="submitBtn">
          <input class="btn btn-primary" value="Save Change" type="submit" name="save" >
    </form>
    <script type="text/javascript">
        function delectClicked(){
            return confirm("Please confirm to delect this albumn? ");
        }
        //tie confirm delect 
        document.querySelector("#btnDelect").addEventListener("click",function(){
            alert("trigger submit")
            document.getElementById("submitBtn").click();
        },false)
    </script>   
  
</div>
<?php     include('./common/Footer.php'); ?>
