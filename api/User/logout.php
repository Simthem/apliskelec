<?php
session_start();  
session_destroy();
echo "Vous allez être déconnecté(é)";
header("Location: logout.php");
?>