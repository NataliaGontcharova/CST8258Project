<?php
include './Common/Header.php';
include_once "Functions.php";

if(!isset($_SESSION['user']) || $_SESSION['user'] === '') {
     header("Location: Index.php");
}

$AlbumList = getALbumList($_SESSION['user']);
$view = 'default';
$errors = [];
if (isset($_GET['album'])) {
    $AlbumPictureList = getALbumPictureList($_GET['album']);
    $selectedALbum = $_GET['album'];
    $view = 'album';
}
if (isset($_GET['picture'])) {
    $view = 'picture';
    $picture = null;
    foreach ($AlbumPictureList as $item) {
        if ($item['Picture_Id'] == $_GET['picture']) {
            $picture = $item;
            break;
        }
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $comment = (string) trim($_POST['comment'] ?? '');
        if (0 == strlen($comment)) {
            $errors[] = 'comment field is empty';
        } else {
            $pdo = getPDO();
            $prepared = $pdo->prepare("INSERT INTO `comment` (`Author_Id`, `Picture_Id`, `Comment_Text`, `Date`) VALUES (:auth, :pic, :comm, :date)");
            $params = [
                'auth' => $_SESSION['user'],
                'pic' => $picture['Picture_Id'] ?? 1,
                'comm' => $comment,
                'date' => date('Y-m-d H:i:s'),
            ];
            if (!$prepared->execute($params)) {
                $errors[] = 'An error occured when saving comment';
            }
        }
    }
    $PictureCommentList = getPictureCommentsList($picture['Picture_Id']);
}
?>

<div class="container mt-5">
    <section class="vh-100">
    <h1 class="text-success">My pictures</h1>
    <div class="row">
        <div class="col-md-12">
            <select name="albumchoice" onchange="changeAlbum(this)" class="form-control">
                <option value="">--Select--</option>
                <?php foreach ($AlbumList as $row) : ?>
                    <option value="?album=<?= $row['Album_Id'] ?>" <?= (isset($selectedALbum) && $selectedALbum == $row['Album_Id']) ? ' selected' : '' ?>>
                        <?= $row['Title'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <?php if ('album' == $view): ?>
        <?php foreach ($AlbumPictureList as $picture) : ?>
            <a href="MyPictures.php?album=<?= $_GET['album'] ?>&picture=<?= $picture['Picture_Id'] ?>">
                <img src="<?= $picture['File_Name'] ?>" class="img-thumb" alt="<?= $picture['Title'] ?>" />
            </a>
        <?php endforeach; ?>
    <?php elseif ('picture' == $view): ?>
        <h1><?= $picture['Title'] ?></h1>
        <div class="row">
            <div class="col-md-8">
                <div>
                    <img src="<?= $picture['File_Name'] ?>" class="img-fluid" alt="<?= $picture['Title'] ?>" />
                </div>
                <div style="overflow-x: scroll">
                    <ul class="thumb-list">
                        <?php foreach ($AlbumPictureList as $pictureItem) : ?>
                            <?php $activ = $picture['Picture_Id'] == $pictureItem['Picture_Id'] ? ' active' : ''; ?>
                            <li>
                                <a href="MyPictures.php?album=<?= $_GET['album'] ?>&picture=<?= $pictureItem['Picture_Id'] ?>">
                                    <img src="<?= $pictureItem['File_Name'] ?>" class="img-thumb<?= $activ ?>" alt="<?= $pictureItem['Title'] ?>" />
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <h4>Description</h4>
                <?= $picture['Description'] ?? '' ?>
                <?php if (isset($PictureCommentList) && 0 < count($PictureCommentList)) : ?>
                    <div class="mb-5">
                        <h4>Comments</h4>
                        <?php foreach ($PictureCommentList as $comment) : ?>
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $comment['Name'] ?></h5>
                                    <h6 class="card-subtitle mb-2 text-muted"><?= $comment['Date'] ?></h6>
                                    <p class="card-text"><?= nl2br($comment['Comment_Text']) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <form action="" method="post">
                    <div class="form-group">
                        <textarea class="form-control" name="comment" placeholder="Leave Comment..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Add Comment</button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div> <!-- /container -->
</section>
<script>
    function changeAlbum(e) {
        window.location.href = 'MyPictures.php' + e.options[e.selectedIndex].value;
    }
</script>

<?php include './Common/Footer.php'; ?>


