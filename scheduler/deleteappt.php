<!DOCTYPE html>

<?php
session_start();

$display_block = "";
$completeflag = false;

if ($_SESSION['auth'] == '1') {
    //create display string
    $heading = "<h1>Welcome ".$_SESSION['username']."!</h1>";
} else {
    //redirect back to login form if not authorized
    header("Location: userlogin.html");
    exit;
}

$id = filter_input(INPUT_POST, 'id');

if (filter_input(INPUT_POST, 'submit')) {
    if (!empty(filter_input(INPUT_POST, 'id'))) {
        $mysqli = mysqli_connect("localhost", "cs213user", "letmein", "scheduler");
        
        //delete from person_appt table
        $sqlDelPerApp = "DELETE FROM person_appts WHERE app_id='$id' AND login_email='"
            .$_SESSION['email']."'";
        $result1 = mysqli_query($mysqli, $sqlDelPerApp) or die(mysqli_error($mysqli));
        
        //delete from appointments table
        $sqlDelPerson = "DELETE FROM appointments WHERE id='$id'";
        
        $result2 = mysqli_query($mysqli, $sqlDelPerson) or die(mysqli_error($mysqli));
        
        mysqli_close($mysqli);
    
        $display_block .= "<p><strong>The appointment has been deleted.</strong></p>";
        $completeflag = true;
    } else {
        $display_block .= "<p><strong>Deletion failed.</strong></p>";
        $completeflag = true;
    }
}
?>

<html>
    <head>
        <meta charset="UTF-8">
        <link href="default.css" type="text/css" rel="stylesheet">
        <title>Delete Appointment</title>
    </head>
    <body>
        <p><a class="link" href="home.php">Back to Home</a></p>
        <?php
            echo $display_block;
            if ($completeflag == false) {
        ?>
        <p><strong>Are you sure you want to delete?</strong></p>
        <form action="deleteappt.php" method="post">
            <input type="hidden" name="id" value="<?php echo filter_input(INPUT_GET, 'id'); ?>">
            <input type="submit" value="Confirm Deletion" name="submit">
        </form>
        <?php } ?>
    </body>
</html>
