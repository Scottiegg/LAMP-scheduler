<?php
function getAddrStr($info) {
    $display_block = "";
    $addr = [];
    $addr[0] = stripslashes($info['addr_street']);
    $addr[1] = stripslashes($info['addr_street2']);
    $addr[2] = stripslashes($info['addr_city']);
    $addr[3] = stripslashes($info['addr_zipcode']);
    $addr[4] = stripslashes($info['addr_province']);

    $rowcount = count(array_filter($addr));
  
    if ($rowcount > 0) {
        $display_block .= "<tr><th rowspan=\"".($rowcount + 1)."\">Address</th>";
        $first = true;
        foreach ($addr as $value) {
            if (!empty($value)) {
                if ($first) {
                    $display_block .= "<tr>";
                    $first = false;
                }
                $display_block .= "<td>" .$value. "</td></tr>";
            }
        }
    }
    return $display_block;
}

function getNameStr($name, $relation) {
    $string = "";
    
    if (empty($relation)) {
    $string .= "<tr><th>Name</th>"
        . "<td>$name</td></tr>";
    } else {
        $string .= "<tr><th>Name and Relation</th>"
            . "<td>$name, $relation</td></tr>";
    }
    
    return $string;
}

function getNamesStr($names) {
    $string = "";
    $string .= "<tr><th>Name and Relation</th><td>";
    foreach ($names as $value) {
        $name = stripslashes($value['name']);
        $relation = stripslashes($value['relation']);
        if (empty($relation)) {
            $string .= $name . " ";
        } else {
            $string .= $name ." (" . $relation . ") ";
        }
    }
    $string .= "</tr>";
    return $string;
}

function addAppt($info, $id, $names) {
    $display_block = "";
    $date = date("g:i a\, m/d/Y", strtotime(stripslashes($info['date_time'])));
    $place = stripslashes($info['place']);

    $display_block .= "<table>"
        . "<tr><th>Time</th>"
            . "<td>$date</td></tr>";
    
    if (empty($names)) {
        $name = stripslashes($info['name']);
        $relation = stripslashes($info['relation']);
        $display_block .= getNameStr($name, $relation);
    } else {
        $display_block .= getNamesStr($names);
    }
    
    $display_block .= "<tr><th>Place</th>"
            . "<td>$place</td></tr>";

    $display_block .= getAddrStr($info);

    $phone = stripslashes($info['phone']);
    if (!empty($phone)) {
        $display_block .= "<tr><th>Place</th><td>$phone</td></tr>";
    }
    $display_block .= "</table>";
    $display_block .= "<a class=\"link\" href=\"deleteappt.php?id=$id\">"
            . "Delete Appointment</a>";
    return $display_block;
}

function getCountAppID($rows) {
    $appIDs = [];
    foreach ($rows as $value) {
        $appIDs[] = $value['id'];
    }
    return array_count_values($appIDs);
}

function addMultiPeople($info, $rowid) {
    $mysqli = mysqli_connect("localhost", "cs213user", "letmein", "scheduler");
    $sql = "SELECT pa.app_id, per.name name, per.relation "
        . "FROM person_appts pa, persons per "
        . "WHERE per.name = pa.name AND per.login_email = pa.login_email "
            . "AND app_id = $rowid AND per.login_email = '" .$_SESSION['email']. "'";
    $sqlresult = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
    $names = [];
     while ($r = mysqli_fetch_array($sqlresult)) {
        $names[] = $r;
    }
    mysqli_close($mysqli);
    
    return addAppt($info, $rowid, $names);
}

function buildTableString($rows) {
    $string = "";
    $countsOfAppId = getCountAppID($rows);
    
    foreach ($rows as $info) {
        $id = stripslashes($info['id']);
        if ($countsOfAppId[$id] == 1) {
            $string .= addAppt($info, $id, null);
        } else if ($countsOfAppId[$id] > 1) {
            $string .= addMultiPeople($info, $id);
            $countsOfAppId[$id] = 0;
        }
    }
    
    return $string;
}