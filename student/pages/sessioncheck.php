<?php

session_start();
ob_start();

include("../../config.php");
include("../../mail/mail.php");

if (!isset($_SESSION['VALID_ACADEMY_STUDENT'])) 
{
  header("location:logout.php");
}



?>