<?php
ob_start();
function sanitize($dirty){
  return htmlentities($dirty, ENT_QUOTES, "UTF-8"); 
}
function display_errors($errors){
	$display = '<ul style="text-align:center; font-weight:bolder" class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
	foreach ($errors as $error) {
		$display .= '<li style="list-style:none;">'.$error.'</li>';
	}
	$display .='</ul>';
	return $display;
}

function displayErrors($errors){
    $display = '';
    foreach($errors as $error){
        $display .= '<span class="error">'.$error.'&nbsp&nbsp</span>';
    }

    return $display;
}

function login($userId){
	session_start();
  $_SESSION['user'] = $userId;
    global $conn;
	date_default_timezone_set('Africa/Lagos');
	$date = date("Y-m-d H:i:s");
	$conn->query("UPDATE user SET last_login = '$date' WHERE users_id = '$userId'");
	$_SESSION['success_flash'] = 'Welcome you are now login';
	header("Location: index.php");
}
function is_logged_in(){
	if (isset($_SESSION['user']) && $_SESSION['user'] > 0) {
		return true;
	}
	else{
		return false;
	}
}

function login_error_redirect($url = 'login.php'){
	$_SESSION['error_flash'] = 'You must login.';
	header('Location: '.$url);
}
function pretty_date($date){
	return date("F d, Y, h:i A",strtotime($date));
}
ob_flush();
?>