<?php include 'header.php';
	/*if (!is_logged_in()) {
		header("Location: login.php"); 
	}*/

//delete from database
//delete
if(isset($_GET['delete'])){
    $id= (int)$_GET['delete'];
    $conn->query("DELETE FROM `com` WHERE com_id = '$id'");
    $_SESSION['error_flash'] = 'Deleted';
    header("Location: comments.php");
}

$title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):'');
$title = ucwords($title);
$name = ((isset($_POST['name']) && $_POST['name'] != '')?sanitize($_POST['name']): '');
$name = ucwords($name);
$note = ((isset($_POST['note']) && $_POST['note'] != '')?sanitize($_POST['note']): '');
$note = ucwords($note);
$errors = array();
$display = '';
$success = '';

// to edit
if(isset($_GET['edit'])){
    $edit = $_GET['edit'];
    $edit_id = (int)$edit;
    $sql= $conn->query("SELECT * FROM `com` WHERE `com_id` = '$edit_id'");
    $get = mysqli_fetch_assoc($sql);
    $title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):$get['title']);
    $title = ucwords($title);
    $name = ((isset($_POST['name']) && $_POST['name'] != '')?sanitize($_POST['name']):$get['name']);
    $name = ucwords($name);
    $note = ((isset($_POST['note']) && $_POST['note'] != '')?sanitize($_POST['note']):$get['note']);
    $note = ucwords($note);
}   

if(isset($_POST['submit'])){
    if($name == ''){
        $errors[] = "is empty";
    }
    if(!empty($errors)){
        echo display_errors($errors);
    }
    else{
        $sql1="INSERT INTO `com`(`name`, `title`, `note`) VALUES ('$name','$title','$note')";
        if(isset($_GET['edit'])){   
            $sql1="UPDATE `com` SET `name`= '$name', `title`= '$title', `note`= '$note' WHERE `com_id` = '$edit_id'"; 
        }
        $conn->query($sql1);
        $_SESSION['success_flash'] = "Successful";
        header("location: comments.php");
    }
}

//get data to table
$sql2= $conn->query("SELECT * FROM `com` ORDER BY com_id ASC ");
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
            <form action="comments.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:'');?>" method="post">
                <div class="form-group">
                    <input type="text" id="name" name="name" class="form-control" value="<?=$name;?>" placeholder="Name..." required><br>
                    <input type="text" id="title" name="title" class="form-control" value="<?=$title;?>" placeholder="Title..." required><br>
                    <textarea type="text" id="note" name="note" class="form-control" placeholder="Your Note..." row="10" required><?=$note;?></textarea><br>
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
                            <th>Name</th>
                            <th>Title</th>
                            <th>Note</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Title</th>
                            <th>Note</th>
                            <th></th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php while ($result = mysqli_fetch_assoc($sql2)) : ?>
                        <tr>
                            <td><a href="comments.php?edit=<?=$result['com_id'];?>" class="btn btn-xs btn-default edit"><span class="glyphicon glyphicon-pencil"></span>edit</a></td>
                            <td><?=$result['name'];?></td>
                            <td><?=$result['title'];?></td>
                            <td><?=$result['note'];?></td>
                            <td><a href="comments.php?delete=<?=$result['com_id'];?>" class="btn btn-xs btn-default delete"><span class="glyphicon glyphicon-remove-sign"></span>delete</a></td>
                           
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div> 


<?php include 'footer.php'?>