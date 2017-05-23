<?php
session_start();

//check for required fields from the form
if ((!filter_input(INPUT_POST, 'email'))
        || (!filter_input(INPUT_POST, 'password'))) {
//if ((!isset($_POST["username"])) || (!isset($_POST["password"]))) {
    header("Location: userlogin.html");
    exit;
}

//connect to server and select database
$mysqli = mysqli_connect("localhost", "cs213user", "letmein", "scheduler");
/* For more info about mysqli functions, go to the site below:
   http://www.w3schools.com/php/php_ref_mysqli.asp */

//create and issue the query
$targetname = filter_input(INPUT_POST, 'email');
$targetpasswd = filter_input(INPUT_POST, 'password');
$sql = "SELECT username FROM logins WHERE email = '".$targetname.
        "' AND password = PASSWORD('".$targetpasswd."')";

$result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));

//get the number of rows in the result set; should be 1 if a match
if (mysqli_num_rows($result) == 1) {
    //if authorized, get the values of f_name l_name
    while ($info = mysqli_fetch_array($result)) {
        $username = stripslashes($info['username']);
    }
    
    $_SESSION['auth'] = '1';
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $targetname;
    
    //redirect to Services Page
    header("Location: home.php");

   
} else {
    //redirect back to login form if not authorized
    header("Location: userlogin.html");
    exit;
}

mysqli_close($mysqli);
?>

