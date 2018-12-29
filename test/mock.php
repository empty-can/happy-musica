<?php
require_once ("../lib/init.php");
include '../lib/parts/header.php';
?>
<title>萌えアル for PC</title>
<style type="text/css">
<!--
body {
    padding: 1px;
}

div {
    padding: 0px;
    margin: 0px;
    text-align: center;
    font-size: xx-large;
}

.content {
    background-color: lightseagreen;
    width: 100%;
    height: 95vh;
    padding-top: 1%;
}

.left_menu {
    background-color: gold;
    width: 15%;
    height: 100%;
    float: left;
}

.main {
    color: white;
    background-color: black;
    width: 75%;
    height: calc(100% - 96px);
    float: left;
}

.right_menu {
    position: static;
    background-color: cornflowerblue;
    width: 10%;
    height: 100%;
    float: left;
}

.menu {
    width: 70%;
    height: 96vh;
    margin-top: 2vh;
    margin-bottom: 2vh;
    margin-left: auto;
    margin-right: auto;
}

.block_img {
    width: 100%;
    display: block;
}

.ads {
    background-color: white;
    width: 8%;
    position: absolute;
    bottom: 2%;
    margin-left: auto;
    margin-right: auto;
    text-align: center;
}

.error {
    background-color:red;
}

.clear {
    clear: both;
}
-->
</style>
</head>
<body>
	<div class="content">
	<div class="left_menu"> 左メニューバー </div>
	<div class="main"> 本体 </div>
	<div class="right_menu">
		<div class="menu">
        	<a href="/osaisen/" target="_self"><img class="block_img" src="./imgs/home.png" alt="トップページへ" /></a><br />
        	<a href="./" target="_self"></a><img class="block_img" src="./imgs/up.png" alt="ひとつ上のツイートへ" /><br />
        	<a href="./" target="_self"></a><img class="block_img" src="./imgs/down.png" alt="ひとつ下のツイートへ" /><br />
        	<div class="ads">広告枠</div>
    	</div>
	</div>
	<div class="clear"></div>
	</div>
</body>
</html>