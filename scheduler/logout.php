<?php

session_start();
unset($_SESSION['username']);
unset($_SESSION['auth']);
unset($_SESSION['email']);

echo 'You have logged out.';
header('Refresh: 1; URL = userlogin.html');

?>