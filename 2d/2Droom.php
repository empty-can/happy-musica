<?php
require_once ("../lib/init.php");
?><!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<title>二次絵絶対拡散するルーム</title>
<!--[if IE]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>

<![endif]-->
<style>
</style>
</head>
<body>
<?php
$filename = pathinfo(__FILE__, PATHINFO_FILENAME);
include './2Dheader.php';

printErrorMessages("color:red;font-weight:bold;");
?>
</body>
</html>