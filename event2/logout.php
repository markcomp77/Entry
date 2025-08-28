<?php
require_once __DIR__ . "/db_config.php";
session_destroy();
header("Location: index.php");
exit;
