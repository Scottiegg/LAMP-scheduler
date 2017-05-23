<!DOCTYPE html>
<?php
session_start();

if (!($_SESSION['auth'] == '1')) {
    //redirect back to login form if not authorized
    header("Location: userlogin.html");
    exit;
}

$display_block = "";
$completeflag = false;

if (filter_input(INPUT_POST, "submit") && filter_input(INPUT_POST, "name")) {
    $name = filter_input(INPUT_POST, "name");
    $mysqli = mysqli_connect("localhost", "cs213user", "letmein", "scheduler");
    
    //delete from person_appt table
    $sqlDelPerApp = "DELETE FROM person_appts WHERE name='$name' AND login_email='"
            .$_SESSION['email']."'";
    $result1 = mysqli_query($mysqli, $sqlDelPerApp) or die(mysqli_error($mysqli));
    
    //delete from person table
    $sqlDelPerson = "DELETE FROM persons WHERE name='$name' AND login_email='"
            .$_SESSION['email']."'";
    $result2 = mysqli_query($mysqli, $sqlDelPerson) or die(mysqli_error($mysqli));
    
    //delete orphaned appointments with no people for them
    $sqlDelApptsRem = "DELETE FROM appointments WHERE ID NOT IN (SELECT app_id FROM person_appts)";
    $result3 = mysqli_query($mysqli, $sqlDelApptsRem) or die(mysqli_error($mysqli));
    
    mysqli_close($mysqli);
    
    $display_block .= "<p><strong>The name ".$name." has been deleted.</strong></p>"
            . "<p><a class=\"link\" href=\"".filter_input(INPUT_SERVER, 'PHP_SELF')."\">"
            . "Delete another?</a></p>";
    
    $completeflag = true;
}
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Remove a Person</title>
        <link href="default.css" type="text/css" rel="stylesheet">
    </head>
    <body>
        <p><a class="link" href="home.php">Back to Home</a></p>
        <?php
            echo $display_block;
            if (!$completeflag) {
        ?>
        <form method="post" action="deleteperson.php" name="form">
            <fieldset><legend><strong>Delete a Person</strong></legend>
                <p><strong>Person to delete:</strong><br/>
                <?php
                    $mysqli = mysqli_connect("localhost", "cs213user", "letmein", "scheduler");
                    $sql = "SELECT name, relation FROM persons WHERE login_email = '".$_SESSION['email']."'";
                    $result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
                    $persons = [];
                    while ($info = mysqli_fetch_array($result)) {
                        $persons[] = $info;
                    }

                    $i = 1;
                    foreach ($persons as $person) {
                        $name = $person['name'];
                        if ($name != 'Myself') {
                            echo " <input type=\"radio\" name=\"name\" value=\"$name\"> ";

                            if (empty($person['relation'])) {
                                echo $name;
                            } else {
                                echo $name. ", " . $person['relation'];
                            }

                            if ($i == 5) { echo "<br/>"; }
                            $i++;
                        }
                    }

                    mysqli_close($mysqli);
                ?>
                </p>
                <p><input type="submit" name="submit" value="Delete"/></p>
            </fieldset>
        </form>
        <?php } ?>
    </body>
</html>
