<?php include 'header.php';

/*if (!is_logged_in()) {
    header("Location: login.php"); 
}*/
//delete pic
if(isset($_GET['photo_delete'])){
    $id= (int)$_GET['photo_delete'];
    $conn->query("UPDATE `blog` SET `photo`= ' ' WHERE `blog_id` = '$id'");
    $_SESSION['error_flash'] = 'Deleted';
    header("Location: blog.php");
}

//delete from database
//delete
if(isset($_GET['delete'])){
    $id= (int)$_GET['delete'];
    $conn->query("DELETE FROM `blog` WHERE blog_id = '$id'");
    $_SESSION['error_flash'] = 'Deleted';
    header("Location: blog.php");
}
$photo = ((isset($_POST['photo']) && $_POST['photo'] != '')?sanitize($_POST['photo']):'');
$title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):'');
$title = strtoupper($title);
$note = ((isset($_POST['note']) && $_POST['note'] != '')?sanitize($_POST['note']): '');
$errors = array();
$display = '';
$dbpath = '';
$success = '';
$date = date("F d, Y, h:i A");
echo($date);

// to edit
if(isset($_GET['edit'])){
    $edit = $_GET['edit'];
    $edit_id = (int)$edit;
    $sql= $conn->query("SELECT * FROM `blog` WHERE `blog_id` = '$edit_id'");
    $get = mysqli_fetch_assoc($sql);
    $photo = ((isset($_POST['photo']) && $_POST['photo'] != '')?sanitize($_POST['photo']):'');
    $title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):$get['title']);
    $title = strtoupper($title);
    $note = ((isset($_POST['note']) && $_POST['note'] != '')?sanitize($_POST['note']):$get['note']);
}
//to sumbit
if(isset($_POST['submit'])){

    //if($_FILES['photo']['name'] == ''){
       // $errors[] = 'Image is empty.';	
    //}
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
        $uploadPath = BASEURL.'img/blog/'.$uploadName;
        $dbpath = 'img/blog/'.$uploadName;
        if($mimeType != 'image'){
            $errors = 'The file must be an image.';
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
        $sql3 = "INSERT INTO `blog`(`title`, `note`, `photo`) VALUES ('$title','$note','$dbpath')";
        //update into database.
        if(isset($_GET['edit'])){
            $date = ("Y-m-d H:i:s");
            if(!empty($dbpath)){
                $sql3="UPDATE `blog` SET `title`= '$title', `note`= '$note', `photo`= '$dbpath', `date`='$date' WHERE `blog_id` = '$edit_id'";
            }else{
                $sql3="UPDATE `blog` SET `title`= '$title', `note`= '$note', `date`='$date' WHERE `blog_id` = '$edit_id'";
            }
           
        }
        $conn->query($sql3);
        $_SESSION['success_flash'] = "Successful";
        header("location: blog.php");
    }
}
//get data to table
$sql2= $conn->query("SELECT * FROM `blog` ORDER BY blog_id ASC ");
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
        <div class="col-md-5 offset-3">
            <form action="blog.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:'');?>" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="text" id="title" name="title" class="form-control" value="<?=$title;?>" placeholder="Your Title..." required><br>
                    <textarea type="text" id="note" name="note" class="form-control" placeholder="Your Note..." row="10" required><?=$note;?></textarea><br>
                    <input type="file" id="photo" name="photo" class="form-control"><br>
                    <button type="submit" name="submit" class="btn btn-md btn-primary form-control">Post</button>
                </div>
            </form>
        </div>
    </div>
        <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Title</th>
                            <th>Note</th>
                            <th>picture</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>Title</th>
                            <th>Note</th>
                            <th>picture</th>
                            <th></th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php while ($result = mysqli_fetch_assoc($sql2)) : ?>
                        <tr>
                            <td><a href="blog.php?edit=<?=$result['blog_id'];?>" class="btn btn-xs btn-default edit"><span class="glyphicon glyphicon-pencil"></span>edit</a></td>
                            <td><?=$result['title'];?></td>
                            <td><?=$result['note'];?></td>
                            <td><img src="/bss/<?=$result['photo'];?>" alt="BSS" srcset=""><a class="remove-image" href="blog.php?photo_delete=<?=$result['blog_id'];?>" style="display: inline;">&#215;</a></td>
                            <td><a href="blog.php?delete=<?=$result['blog_id'];?>" class="btn btn-xs btn-default delete"><span class="glyphicon glyphicon-remove-sign"></span>delete</a></td>
                           
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<?php include 'footer.php'?>