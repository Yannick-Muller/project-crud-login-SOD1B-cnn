<?php
session_start();
session_destroy();
header("Location: beh-menu.php");
exit;
