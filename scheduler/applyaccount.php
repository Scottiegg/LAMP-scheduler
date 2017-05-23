<?php
$display_block = "";
$completeflag = false;
$targetemail = filter_input(INPUT_POST, 'email');

//check for required fields from the form
if ((filter_input(INPUT_POST, 'email'))
        && (filter_input(INPUT_POST, 'password'))
        && (filter_input(INPUT_POST, 'username'))) {
    $mysqli = mysqli_connect("localhost", "cs213user", "letmein", "scheduler");
    
    $sql = "SELECT email FROM logins WHERE email = '".$targetemail."'";
    $result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
        
    //get the number of rows in the result set; should be 1 if a match
    if (mysqli_num_rows($result) != 0) {
        //create display string
        $display_block .= "<p><strong>The email address ".$targetemail.
                " has already been used, please use a different one.</strong></p>";
    } else {
        $sqlInsertLogins = "INSERT logins VALUES('"
                .filter_input(INPUT_POST, 'username')."', '"
                .$targetemail."', "
                ."PASSWORD('".filter_input(INPUT_POST, 'password')."'))";
                
        $result = mysqli_query($mysqli, $sqlInsertLogins) or die(mysqli_error($mysqli));
        
        $sqlInsertPersons = "INSERT persons VALUES(
            'Myself', null, '".$targetemail."') ";
                
        $result = mysqli_query($mysqli, $sqlInsertPersons) or die(mysqli_error($mysqli));
        
        $display_block .= "<p><strong>Your new account has been created."
                . " Thank you for joining us!</strong></p>"
                . "<p><a href=\"userlogin.html\">Back to login</a></p>";
        
        $completeflag = true;
    }
    
    mysqli_close($mysqli);
}
?>
    
<html>
    <head>
        <title>New User Form</title>
        <link href="default.css" type="text/css" rel="stylesheet">
    </head>
    <body>
        <?php
        echo "$display_block";
        if (!$completeflag) {
        ?>
        <form method="post" action="applyaccount.php">
            <fieldset> <legend><strong>User Information</strong></legend>
                <p><strong>Username:</strong><br/>
                    <input type="text" name="username"/></p>
                <p><strong>Email:</strong><br/>
                    <input type="email" name="email"/></p>
                <p><strong>Password:</strong><br/>
                    <input type="password" name="password"/></p>
                <p><input type="submit" name="submit" value="Create Account"/></p>
            </fieldset>
        </form>
        <?php } ?>
    </body>
</html>