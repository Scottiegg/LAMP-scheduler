<?php
session_start();
    
if (!($_SESSION['auth'] == '1')) {
    //redirect back to login form if not authorized
    header("Location: userlogin.html");
    exit;
}
    
$display_block = "";
$completeflag = false;

date_default_timezone_set("America/Vancouver");
  
//check for required fields from the form
if (filter_input(INPUT_POST, 'place') && !empty($_POST['names']) &&
        !empty(filter_input(INPUT_POST, 'date_time'))) { 
    $place = filter_input(INPUT_POST, 'place');
    $date = filter_input(INPUT_POST, 'date_time');
    $mysqldate = date("Y-m-d H:i:s", strtotime(str_replace('-', '/', $date)));
    $phone = filter_input(INPUT_POST, 'phone');
    $addr_1 = filter_input(INPUT_POST, 'addr_line_1');
    $addr_2 = filter_input(INPUT_POST, 'addr_line_2');
    $city = filter_input(INPUT_POST, 'city');
    $zip = filter_input(INPUT_POST, 'zip');
    $prov = filter_input(INPUT_POST, 'province');
        
    $mysqli = mysqli_connect("localhost", "cs213user", "letmein", "scheduler");
    
    $sqlInsertAppts = "INSERT appointments VALUES(
            '$mysqldate', '$place', '$phone', "
            . "'$addr_1', '$addr_2', '$city', '$zip', '$prov', null)";
                
    $result = mysqli_query($mysqli, $sqlInsertAppts) or die(mysqli_error($mysqli));
        
    $insertIntoAppts = mysqli_insert_id($mysqli);
    $namesStr = "";
    
    if(!empty($_POST['names'])) {
        foreach($_POST['names'] as $name) {
            $sql = "INSERT person_appts VALUES('$name', '"
            .$_SESSION['email']. "', $insertIntoAppts)";
            $result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
            $namesStr .= $name . " ";
        }
    }
    
    $completeflag = true;
    $display_block .= "<p><strong>The appointment: </strong><br/>"
            .$namesStr."<br/><br/>"
            .$place."<br/><br/>"
            .$mysqldate."<br/><br/>"
            .$phone."<br/><br/>"
            .$addr_1."<br/>"
            .$addr_2."<br/>"
            .$city." ".$zip." ".$prov."<br/>"
            ." <strong>has been created.</strong></p>"
            . "<p><a class=\"link\" href=\"".filter_input(INPUT_SERVER, 'PHP_SELF')."\">"
            . "Add another?</a></p>";
                
    mysqli_close($mysqli);
} //else {
//    $display_block .= "<p><strong>Missing or incorrectly entered fields.</strong></p>";
//}
?>
    
<html>
    <head>
        <title>Add a Person</title>
        <link href="default.css" type="text/css" rel="stylesheet">
    </head>
    <body>
        <p><a class="link" href="home.php">Back to Home</a></p>
        <?php
        echo "$display_block";
        if (!$completeflag) {
            
        ?>
            
        <form method="post" action="addappt.php" name="form">
            <fieldset><legend><strong>Appointment Information</strong></legend>
                <p><strong>Person(s) Attending (at least one):</strong><br/>
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
                        echo " <input type=\"checkbox\" name=\"names[]\" value=\"$name\"> ";
   
                        if (empty($person['relation'])) {
                            echo $name;
                        } else {
                            echo $name. ", " . $person['relation'];
                        }
                        
                        if ($i == 5) { echo "<br/>"; }
                        $i++;
                    }

                    mysqli_close($mysqli);
                ?>
                </p>
                <p><strong>Appointment Place (required):</strong><br/>
                    <input type="text" name="place"/></p>
                <p><strong>Time and Date (required):</strong><br/>
                    <input type="datetime-local" name="date_time" value="<?php echo date("Y-m-d\TH:i") ?>"/></p>
                <p><strong>Phone:</strong><br/>
                    <input type="tel" name="phone"/></p>
                <p><strong>Address Line 1:</strong><br/>
                    <input type="text" name="addr_line_1"/></p>
                <p><strong>Address Line 2:</strong><br/>
                    <input type="text" name="addr_line_2"/></p>
                <p><strong>City:</strong><br/>
                    <input type="text" name="city"/>
                <p><strong>Zip Code:</strong><br/>
                    <input type="text" name="zip"/></p>
                <p><strong>Province:</strong><br/>
                    <input type="text" name="province"/></p>
                <p><input type="submit" name="submit" value="Create Appointment"/></p>
            </fieldset>
        </form>
        <?php } ?>
    </body>
</html>