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

if (filter_input(INPUT_POST, 'sortby')) {
    $sortby = filter_input(INPUT_POST, 'sortby');
    $showPast = false;
    if ((filter_input(INPUT_POST, 'past')) == 'past') {
        $showPast = true;    
    }
    
    $mysqli = mysqli_connect("localhost", "cs213user", "letmein", "scheduler");
    $sql = "SELECT app.id, app.date_time, per.name name, per.relation, app.place, "
                . "app.addr_street, app.addr_street2, "
                . "app.addr_city, app.addr_zipcode, app.addr_province, app.phone "
                . "FROM persons per, person_appts pa, appointments app "
                . "WHERE per.name = pa.name AND per.login_email = pa.login_email "
                . "AND pa.app_id = app.id AND per.login_email = '" .$_SESSION['email']. "'";

    if (!$showPast) {
        $sql .= " AND app.date_time > now()";
    }
    
    switch ($sortby) {
        case "date":
            $sql .= " ORDER BY app.date_time";
            break;
        case "person";
            $sql .= " ORDER BY per.name DESC, app.date_time DESC";
            break;
        default:
            //invalid input
            header("Location: home.php");
            exit;
    }
    $result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
    
    $rows = [];
    while ($r = mysqli_fetch_array($result)) {
        $rows[] = $r;
    }
    mysqli_close($mysqli);
    
    include 'apptbuilder.php';
    $display_block .= "<h1>List of selected Appointments</h1>";
    $display_block .= buildTableString($rows);
    
    $completeflag = true;
}
?>

<html>
    <head>
        <title>User Login</title>
        <link href="default.css" type="text/css" rel="stylesheet">
    </head>
    <body>
        <?php echo "$heading"; ?>
        <nav>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="addperson.php">Add a Person</a></li>
                <li><a href="addappt.php">Create an Appointment</a></li>
                <li><a href="deleteperson.php">Remove a Person</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
        <?php 
            echo $display_block;
            if ($completeflag == FALSE) {

        ?>
        <form action="home.php" method="POST">
            <fieldset><legend><strong>Select Appointments to View</strong></legend>
                <p><strong>View by Date: </strong>
                    <input type="radio" name="sortby" value="date" checked="checked"/>
                    <strong>View by Person: </strong>
                    <input type="radio" name="sortby" value="person"/></p>
                <p><strong>View Past Appointments? </strong>
                    <input type="checkbox" name="past" value="past"/></p>
                <p><input type="submit" name="submit" value="View Appointments"/></p>
            </fieldset>
        </form>
        <?php  }  ?>
    </body>
</html>