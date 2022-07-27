<?php
include 'header.php';
/*if (!is_logged_in()) {
    header("Location: login.php"); 
}*/
// displaying the img
$sql= $conn->query("SELECT * FROM `gallery` ORDER BY gallery_id ASC ");

$photo = ((isset($_POST['photo']) && $_POST['photo'] != '')?sanitize($_POST['photo']):'');
$errors = array();
$display = '';
$dbpath = '';
$success = '';

//delete
if(isset($_GET['delete'])){
    $id= (int)$_GET['delete'];
    $conn->query("DELETE FROM `gallery` WHERE gallery_id = '$id'");
    $_SESSION['error_flash'] = 'Deleted';
    header("Location: gallery.php");
}
   //validate to the form
if(isset($_POST['submit'])){
    if($_FILES['photo']['name'] == ''){
        $errors[] = 'Image is empty.';	
    }
    if($_FILES['photo']['name'] != ''){
        $photo = $_FILES['photo'];
        $name = $photo['name'];
        $image = $photo["type"];
        $nameArray = explode('.',$name);
        $fileName = $nameArray[0];
        $fileExt = $nameArray[1];
        $mime = explode('/',$image);
        $mimeType = $mime[0];
        $mimeExt = $mime[1];
        $tmpLoc = $photo['tmp_name'];
        $fileSize = $photo['size'];
        $allowed = array('png','jpg','jpeg','gif','JPG','PNG','JPEG');
        $uploadName = md5(microtime()).'.'.$fileExt;
        $uploadPath = BASEURL.'img/gallery/'.$uploadName;
        $dbpath = 'img/gallery/'.$uploadName;
        if($mimeType != 'image'){
            $errors = 'The file must be an image baby.';
        }
        
        if(!in_array($fileExt, $allowed)){
            $errors[] = 'The image extension must be a png, jpg, jpeg, or gif.';
        }
        if($fileSize > 8000000){
            $errors[] = 'The image size must be below 8MB.';
        }
        if($fileExt != $mimeExt && ($mimeExt == 'jpeg' && $fileExt != 'jpg')){
            $errors[]= 'Image extension does not match the file.';
        }
    }
    if(!empty($errors)){
       echo display_errors($errors);
    }
    else{
        //upload and insert into database
        if(!empty($_FILES)){
            move_uploaded_file($tmpLoc,$uploadPath);
        }
        $insertSql = "INSERT INTO `gallery`(`images`) VALUES ('$dbpath')";
        $conn->query($insertSql);
        $_SESSION['success_flash'] = "Successful";

        header("location: gallery.php");
    }
}
?>

<!-- Begin Page Content -->
<div class="container-fluid">
     <!-- Page Heading -->
     <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <a href="../index.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i>Website</a>
    </div>
    <!-- Content Row -->
    <div class="row">
        <div class="col-md-6 offset-3">
            <?=$display;?>
            <form action="gallery.php" method="post" enctype="multipart/form-data">
                <div class=" form-inline form-group">
                    <input type="file" id="photo" name="photo" class="form-control">&nbsp;
                    <input class="btn btn-md btn-primary g-btn" type="submit" name="submit" value="Post" >
                </div>
            </form>
        </div>
        <div class="col-md-12">
            <?php while ($result = mysqli_fetch_assoc($sql)) : ?> 
                <div class="image">
                    <img src="/bss/<?=$result['images'];?>"/>
                    <a class="remove-image" href="gallery.php?delete=<?=$result['gallery_id'];?>" style="display: inline;">&#215;</a>
                </div>
            <?php endwhile; ?>   
        </div>
    </div>
</div> 
<?php include 'footer.php'?>