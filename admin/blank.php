<?php 
    include 'header.php';
    if (!is_logged_in()) {
		header("Location: login"); 
	}

    //$u = password_hash('12345', PASSWORD_DEFAULT);
   // echo($u);
    //$query = $db_handle->runQuery("SELECT * FROM tbl_image ");
    $sql= $conn->query("SELECT * FROM `gallery` ORDER BY gallery_id ASC ");
?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Blank Page</h1>

</div>
<!-- End of Main Content -->

<?php include 'footer.php' ?>