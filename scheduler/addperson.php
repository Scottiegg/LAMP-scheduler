<?php
session_start();

if (!($_SESSION['auth'] == '1')) {
    //redirect back to login form if not authorized
    header("Location: userlogin.html");
    exit;
}

$display_block = "";
$completeflag = false;
$targetname = filter_input(INPUT_POST, 'name');

//check for required fields from the form
if (filter_input(INPUT_POST, 'name')) {
    $mysqli = mysqli_connect("localhost", "cs213user", "letmein", "scheduler");
    
    $sql = "SELECT name, login_email FROM persons WHERE name = '".$targetname."'";
    $result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
        
    //get the number of rows in the result set; should be 1 if a match
    if (mysqli_num_rows($result) != 0) {
        //create display string
        $display_block .= "<p><strong>The name ".$targetname.
                " is already in use, please use a different one.</strong></p>";
    } else {
        $sqlInsert = "INSERT persons VALUES('"
                .$targetname."', '"
                .filter_input(INPUT_POST, 'relation')."', '"
                .$_SESSION['email']."')";
                
        $result = mysqli_query($mysqli, $sqlInsert) or die(mysqli_error($mysqli));
        
        $display_block .= "<p><strong>The name ".$targetname." has been created.</strong></p>"
                . "<p><a class=\"link\" href=\"".filter_input(INPUT_SERVER, 'PHP_SELF')."\">"
                . "Add another?</a></p>";
        
        $completeflag = true;
    }
    
    mysqli_close($mysqli);
}
?>

<html>
    <head>
        <title>Add a Person</title>
        <link href="default.css" type="text/css" rel="stylesheet">
    </head>
    <body>
        <p><a class="link" href="home.php">Back to Home</a></p>"
        <?php
        echo "$display_block";
        if (!$completeflag) {
        ?>
        <form method="post" action="addperson.php">
            <fieldset><legend><strong>Individual Information</strong></legend>
                <p><strong>Name:</strong><br/>
                    <input type="text" name="name"/></p>
                <p><strong>Relationship:</strong><br/>
                    <input type="text" name="relation"/></p>
                <p><input type="submit" name="submit" value="Create Person"/></p>
            </fieldset>
        </form>
        <?php } ?>
    </body>
</html>