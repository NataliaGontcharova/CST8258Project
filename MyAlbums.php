<?php 
    include('./common/Header.php'); 
    if(!isset($_SESSION['user']) || $_SESSION['user'] === '') {
         header("Location: Index.php");        
    } else {
        $userId =$_SESSION['user'];
    } 
    ?>
<?php 
    $dbConnection = parse_ini_file("Project.ini");
    extract($dbConnection);
    $conn = new PDO($dsn, $user, $password);
    //GET NAME
    $sqlName = "SELECT `Name` FROM user where `UserId`='{$userId}'";
    $queryCourse = $conn->prepare($sqlName);
    $queryCourse->execute();
    $courseInfo = $queryCourse->fetch(PDO::FETCH_ASSOC);
    $userName = $courseInfo["Name"];
    $_SESSION['userName']= $userName;
    // Load Access Authority options
    $sqlAlbum= "SELECT `Album_Id`, `Title`, `Description`, `Date_Updated`, `Accessibility_Code` FROM Album  WHERE `Owner_Id` = '{$userId}'";
    $queryAlbum = $conn->prepare($sqlAlbum);
    $queryAlbum->execute();
    $resultAlbum = $queryAlbum->fetchAll();  
    //Array ( [Album_Id] => 20 [Title] => t [Description] => tt [Date_Updated] => 2021-12-05 [Accessibility_Code] => private )
    $num = count($resultAlbum);

    if(isset($_POST["delect"]))
    {
        if(isset($_POST["deleteID"]))
        {
            //delect
            foreach($_POST["deleteID"] as $item)
            {
                $sqlDelecte="DELETE FROM comment WHERE `Picture_Id` in (select `Picture_Id` from picture where `Album_Id`='{$item}') ";
                $queryDele = $conn->prepare($sqlDelecte);
                $result=$queryDele->execute();

                
                $sqlDelecte="DELETE FROM picture WHERE `Album_Id`='{$item}' ";
                $queryDele = $conn->prepare($sqlDelecte);
                $result=$queryDele->execute();

                
                $sqlDelecte="DELETE FROM album WHERE `Album_Id`='{$item}' and `Owner_Id`='{$userId}' ";
                $queryDele = $conn->prepare($sqlDelecte);
                $result=$queryDele->execute();
            }              
        }     
    } else {
        if(isset($_POST["deleteID"]))
        {
            $sqlAlbum= "SELECT `Album_Id`, `Title`, `Description`, `Date_Updated`, `Accessibility_Code` FROM Album  WHERE `Owner_Id` = '{$userId}'";
            $queryAlbum = $conn->prepare($sqlAlbum);
            $queryAlbum->execute();
            $resultAlbum = $queryAlbum->fetchAll();  
            $num = count($resultAlbum);
        }
    }  //if(isset($_POST["delect"]))

    if(isset($_POST['save']))
    {
        if(isset($_POST["accID"])&& isset($_POST['access']))
        {
            $arrayAlbumId = $_POST["accID"];
            $arraySelect = $_POST['access'];        
            $numArrAlbumId = count($arrayAlbumId);
        //save acc
            for($i =0; $i< $numArrAlbumId;$i++)
            {
                $sqlAcc = "UPDATE Album SET `Accessibility_Code` = '{$arraySelect[$i]}' WHERE `Album_Id`='{$arrayAlbumId[$i]}' and `Owner_Id` = '{$userId}'";
                $queryACC = $conn->prepare($sqlAcc);
                $resultACC=$queryACC->execute();
            }
            if($resultACC)
            {
                //after save > reload data to diaplay
                $sqlAlbum= "SELECT Album_Id, Title, Description, Date_Updated, Accessibility_Code FROM Album  WHERE Owner_Id = '{$userId}'";
                $queryAlbum = $conn->prepare($sqlAlbum);
                $queryAlbum->execute();
                $resultAlbum = $queryAlbum->fetchAll();  
                $num = count($resultAlbum); //display for            
            }         
        }
    } else {
        //before save > reload displayed data
        $sqlAlbum= "SELECT Album_Id, Title, Description, Date_Updated, Accessibility_Code FROM Album  WHERE Owner_Id = '{$userId}'";
        $queryAlbum = $conn->prepare($sqlAlbum);
        $queryAlbum->execute();
        $resultAlbum = $queryAlbum->fetchAll();  
        $num = count($resultAlbum); //display for
    }
?>
<script type="text/javascript">
    var cheId;
    function delectClicked(id){
        //sned ID value to php  to delect
        cheId = id;
        return confirm("Please confirm to delect this albumn? ");
    }
 </script>

<div class="container">  
    <section class="vh-100">
    <h1 class="text-center text-success mt-3"> My Albums</h1>
    <p>Welcome<b>  <?php print_r($userName); ?> </b>(not you? change user <a href="Login.php">here</a>)</p>
    <p><b><a href="AddAlbum.php">Create a New Album</a></b></p>        
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
                        echo "<td><a href='MyPictures.php?album=".$resultAlbum[$i]["Album_Id"]."'>".$resultAlbum[$i]["Title"] . "</a></td>";
                        echo "<td>" . $resultAlbum[$i]["Date_Updated"] . "</td>";                     
                        //show picnum
                            $sqlPic = "SELECT Picture_Id FROM Picture WHERE Album_Id = {$resultAlbum[$i]["Album_Id"]}";
                            $queryPic = $conn->prepare($sqlPic);
                            $queryPic->execute();
                            $resultPic = $queryPic->fetchAll();  
                            $numPic = count($resultPic);
                        echo "<td style='padding-left: 3em;'>" .$numPic."</td>";     
                                        
                        echo "<td> <select name='access[]' id='select'>";   
                        foreach ($conn->query('SELECT * FROM accessibility ;') as $row)
                        {
                            echo '<option  value="'.$row[0].'"'.($row[0]== $resultAlbum[$i]["Accessibility_Code"]? "selected":'').' >'.$row[1].'</option>';
                        }
                        echo '</select><input style="display:none;" type="text" name="accID[]" value="'.$resultAlbum[$i]["Album_Id"].'"></td>';
                        echo "<td><a onclick='return delectClicked(".$resultAlbum[$i]['Album_Id'].")' id='btnDelet' class='btn btn-success' name='delect' value='delete'>Delete</a>"
                                . " <input style='display:none;'  type='checkbox' name='deleteID[]' value='".$resultAlbum[$i]["Album_Id"]."'id='".$resultAlbum[$i]["Album_Id"]."'></td>";                       
                         echo "</tr>";
                    }
                ?>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>
                        <input class="btn btn-primary" value="delect" type="submit" name="delect" id="submitBtn" style='display:none;' >
                        <input class="btn btn-success" value="Save Change" type="submit" name="save" >                        
                    </th>
                    <th></th>                    
                </tr>
                
            </tbody>
        </table>
    </form>
    <script type="text/javascript">        
        //tie confirm delect 
        document.querySelector('body').addEventListener('click', function (e) {
            if (e.target.id === 'btnDelet') {
                  console.log(" checed ? "+cheId);
                  document.getElementById(cheId).checked = true;
                  document.getElementById("submitBtn").click(); 
                  }
                })          
    </script> 
    </div>
</section>    


<?php include('./common/Footer.php'); ?>
