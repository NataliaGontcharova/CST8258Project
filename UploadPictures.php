<?php
include './Common/Header.php';
include_once "Functions.php";

# get list of album
$AlbumList = getALbumList($_SESSION['user']);

# get list of album
$errors = [];
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['album'])) {
        $errors['album'] = 'Missing album';
    }
    if (empty($_POST['title'])) {
        $errors['title'] = 'Missing title of picture(s)';
    }
    //Count # of uploaded files in array
    $total = count($_FILES['picture']['name'] ?? []);
    if (0 === $total) {
        $errors['title'] = 'Missing picture(s)';
    }
    if (0 == count($errors)) {
        $album = $_POST['album'];
        $title = trim($_POST['title']);
        $description = $_POST['description'] ?? null;
        $path = 'Common/uploadFiles';
        if (!is_dir($path)) {
            mkdir($path);
        }
        $pdo = getPDO();
        $prepared = $pdo->prepare("INSERT INTO `picture` (`Album_Id`, `File_Name`, `Title`, `Description`, `Date_Added`) VALUES (:Album_Id, :File_Name, :Title, :Description, :Date_Added)");
        // Loop through each file
        for ($i = 0; $i < $total; $i++) {
            //Get the temp file path
            $tmpFilePath = $_FILES['picture']['tmp_name'][$i];
            //Make sure we have a file path
            if ("" != $tmpFilePath) {
                //Setup our new file path
                $newFilePath = $path . "/" . $_FILES['picture']['name'][$i];
                if (file_exists($newFilePath)) {
                    $infos = pathinfo($newFilePath);
                    $l = 1;
                    do {
                        $newFilePath = $infos['dirname'] . '/' . $infos['filename'] . $l++ . $infos['extension'];
                    } while (file_exists($newFilePath));
                }
                //Upload the file into the temp dir
                if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                    $params = [
                        'Album_Id' => $album,
                        'File_Name' => $newFilePath,
                        'Title' => $title,
                        'Description' => $description,
                        'Date_Added' => date('Y-m-d H:i:s'),
                    ];
                    $prepared->execute($params);
                }
            }
        }
        $success = $total . ' Picture(s) inserted';
    }
}
?>

<div class="container">
    <h3>Upload Pictures</h3
    <p>Accepted picture type: JPG(JPEG), GIF and PNG.</p>
    <p>You can upload multiple pictures at a time by pressing the shift key while selecting pictures.</p>
    <p>When uploading multiple pictures, the title and description fields will be applied to all pictures.</p>
    <?php if (0 < count($errors)): ?>
        <div class="alert alert-danger"><?= implode('<br />', $errors) ?></div>
    <?php endif; ?>
    <?php if (0 < strlen($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group row">
            <label for="selectAlbum" class="col-sm-2">Upload to Album</label>
            <div class="col-sm-10">
                <select type="text" name="album" class="form-control" id="selectAlbum">
                    <?php foreach ($AlbumList as $album): ?>
                        <option value="<?= $album['Album_Id'] ?>">
                            <?= $album['Title'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="inputFile" class="col-sm-2 col-form-label">File to upload</label>
            <div class="col-sm-10">
                <input type="file" name="picture[]" id="inputFile" multiple>
            </div>
        </div>
        <div class="form-group row">
            <label for="inputTitle" class="col-sm-2 col-form-label">Title</label>
            <div class="col-sm-10">
                <input type="text" name="title" class="form-control" id="inputTitle" placeholder="">
            </div>
        </div>
        <div class="form-group row">
            <label for="inputDescription" class="col-sm-2 col-form-label">Description</label>
            <div class="col-sm-10">
                <textarea name="description" class="form-control" id="inputDescription" placeholder="" rows="6"></textarea>
            </div>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="reset" class="btn btn-primary">Clear</button>
        </div>
    </form>
    <?php if (empty($AlbumList)) : ?>
        <div class="alert alert-danger">You must first <a href="AddAlbum.php">create an album here</a></div>
    <?php else : ?>
    <?php endif; ?>
</div>
<?php include './Common/Footer.php'; ?>


