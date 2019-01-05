<?php
session_start();
unset($_SESSION['products']);
$_SESSION['products'] = [];
http_response_code(200);
