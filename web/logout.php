<?php

require_once "../include/config.php";

session_unset();

header("Location: {$config['url']}");

mysqli_close($db);

?>