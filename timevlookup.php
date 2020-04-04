<?php 
define('IN_SYS', TRUE);
session_start(); 
require_once 'config.php';
if(!isset($_SESSION['Loginuid'])||!isset($_SESSION['Loginusername']))
{
    header("Location: index.php?r=illgal");
}
$cid=$_GET['cid'];
$datas = $database->select("crontab",["messages"], ["cid" => $cid]);
?>

<style>
    body, html {
        width: 100%;
        height: 100%;
        margin: ;
        padding: 0;
    }

    .box {
        float: left;
        width: 50%;
        height: 100%;
    }
</style>
<div class="box"></div>
<div class="dark-box"></div>
<link rel="stylesheet" href="assets/css/json-viewer.css"/>
<script src="assets/js/json-viewer.js"></script>
<script>
    var packageJSON = <?php echo $datas[0]["messages"]; ?>;

    var box = document.querySelector('.box');
    var jsonViewer = new JSONViewer({
        eventHandler: box,
        indentSize: 20,
        expand: 1,
        quoteKeys: true
    });
    box.innerHTML = jsonViewer.toJSON(packageJSON);
</script>