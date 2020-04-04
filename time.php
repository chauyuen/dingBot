<?php
define('IN_SYS', TRUE);
session_start();
if(!isset($_SESSION['Loginuid'])||!isset($_SESSION['Loginusername']))
{
    header("Location: index.php?r=illgal");
}
require_once "config.php";
$timestamp=time();
$random=rand();
$token= md5($sysname.$pass.$timestamp.$random);
?>

<html>
<head>
<meta charset="UTF-8">
<title><?php echo $sitename;?>--定时器管理</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit">
<link rel="stylesheet" href="assets/css/amazeui.min.css">
<link rel="stylesheet" href="assets/css/amazeui.datetimepicker-se.min.css"/>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/amazeui.min.js"></script>
<script src="assets/js/moment-with-locales.min.js"></script>
<script src="assets/js/amazeui.datetimepicker-se.min.js"></script>
<script>
 $(function() {
    $('#datetp1').datetimepicker({
      widgetParent:$(document.body)
    });
  });

  $(document).ready(function(){
  $("input#add").click(function(){addSpot(this);
  });
});

function addSpot(obj) {
$('div#spots').append(
    '<div class="am-input-group">' +
    '<input type="text" name="atMobile[]" class="am-form-field"/>' +
    '<span class="am-input-group-btn">'+
    '<input type="button" id="remove" name="remove" class="remove am-btn am-btn-primary am-btn-sm" value="删除本行"></input>'+
    '</span></div>').find("input.remove").click(function(){
    $(this).parent().parent().remove();
    $('input#add').show();
  });
};
</script>

<style>
.header {
	text-align: center;
}
	.header h1 {
		font-size: 150%;
		color: #333;
		margin-top: 15px;
	}
		.header p {
			font-size: 14px;
		}
</style>
</head>

<div class="am-g">
<div class="am-u-lg-6 am-u-md-8 am-u-sm-centered">

 <ul class="am-nav am-nav-tabs am-nav-justify">
  <li><a href="sent.php">发送信息</a></li>
  <li class="am-active"><a href="time.php">定时发送</a></li>
  <li><a href="token.php">凭据管理</a></li>
  <li><a href="log.php">日志查看</a></li>
  <li><a href="index.php">安全退出</a></li>
</ul>

<div class="header">
<div class="am-g">
<h1><?php echo $sitename;?>--定时器管理</h1>
<p>请根据页面提示选择您需要进行的操作。</p>
</div>
<hr />
</div>

<?php
echo $welcome;
echo $_SESSION['Loginusername'];
echo "<br><br>";
echo "现有的定时设定：";
$cid=null;
?>
<hr />
    
<div class="am-panel-group" id="accordion">
  <div class="am-panel am-panel-default">
    <div class="am-panel-hd">
      <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#listng-groupco-1'}">
        等待发送的列表
      </h4>
    </div>
    <div id="listng-groupco-1" class="am-panel-collapse am-collapse am-scrollable-horizontal">
      <div class="am-panel-bd">
      <table class="am-table am-table-bordered am-table-striped am-table-compact">
    <thead>
        <tr>
            <th>cid</th>
            <th>token</th>
            <th>settime</th>
            <th>creatimes</th>
            <th>messages</th>
        </tr>
    </thead>
    <tbody>
        <?php
$data1 = $database->select("crontab",["[>]token" => "token"], ["crontab.cid","token.name","token.tid","crontab.settime","crontab.creatimes"], ["AND" => ["crontab.uid" => $_SESSION['Loginuid'],"crontab.status" => "1"]]);
foreach($data1 as $data)
      {
        if($cid != $data["cid"]){
        $phptime=date('Y-m-d H:i:s',$data["settime"]);
        echo"<tr>";
        echo "<td>".$data["cid"] ."</td><td>".$data["tid"]."-".$data["name"]."</td><td>".$phptime."</td><td>".$data["creatimes"]."</td>";?>
        <td><a href=# onClick="javascript:window.open('timevlookup.php?cid=<?php echo $data["cid"]; ?>','','toolbar=no, status=no, menubar=no, resizable=yes, scrollbars=yes');return false;">点我查看</a></td></tr>
        <?php
        $cid = $data["cid"];
        }
      }
?>

    </tbody>
</table>
      </div>
    </div></div>
     <div class="am-panel am-panel-default">
    <div class="am-panel-hd">
      <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#listng-groupco-2'}">
        已发送的列表
      </h4>
    </div>
  <div id="listng-groupco-2" class="am-panel-collapse am-collapse">
      <div class="am-panel-bd">
      <table class="am-table am-table-bordered am-table-striped am-table-compact">
    <thead>
        <tr>
            <th>cid</th>
            <th>token</th>
            <th>settime</th>
            <th>creatimes</th>
                        <th>messages</th>
        </tr>
    </thead>
    <tbody>
        <?php
$datas = $database->select("crontab",["[>]token" => "token"], ["crontab.cid","token.name","token.tid","crontab.settime","crontab.creatimes"], ["AND" => ["crontab.uid" => $_SESSION['Loginuid'],"crontab.status" => "-1"]]);
foreach($datas as $data)
      {
        if($cid != $data["cid"]){
        $phptime=date('Y-m-d H:i:s',$data["settime"]);
        echo"<tr>";
        echo "<td>".$data["cid"] ."</td><td>".$data["tid"]."-".$data["name"]."</td><td>".$phptime."</td><td>".$data["creatimes"]."</td>";?>
        <td><a href=# onClick="javascript:window.open('timevlookup.php?cid=<?php echo $data["cid"]; ?>','','toolbar=no, status=no, menubar=no, resizable=yes, scrollbars=yes');return false;">点我查看</a></td></tr>
        <?php
        $cid = $data["cid"];
        }
      }
?>

    </tbody>
</table>
      </div>
    </div>
    </div>



     <div class="am-panel am-panel-default">
    <div class="am-panel-hd">
      <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#listng-groupco-3'}">
        已停用的列表
      </h4>
    </div>
   <div id="listng-groupco-3" class="am-panel-collapse am-collapse">
      <div class="am-panel-bd">
      <table class="am-table am-table-bordered am-table-striped am-table-compact">
    <thead>
        <tr>
            <th>cid</th>
            <th>token</th>
            <th>settime</th>
            <th>creatimes</th>
            <th>messages</th>
        </tr>
    </thead>
    <tbody>
        <?php
$datas = $database->select("crontab",["[>]token" => "token"], ["crontab.cid","token.name","token.tid","crontab.settime","crontab.creatimes"], ["AND" => ["crontab.uid" => $_SESSION['Loginuid'],"crontab.status" => "2"]]);
foreach($datas as $data)
      {
        if($cid != $data["cid"]){
        $phptime=date('Y-m-d H:i:s',$data["settime"]);
        echo"<tr>";
        echo "<td>".$data["cid"] ."</td><td>".$data["tid"]."-".$data["name"]."</td><td>".$phptime."</td><td>".$data["creatimes"]."</td>";?>
        <td><a href=# onClick="javascript:window.open('timevlookup.php?cid=<?php echo $data["cid"]; ?>','','toolbar=no, status=no, menubar=no, resizable=yes, scrollbars=yes');return false;">点我查看</a></td></tr>
        <?php
        $cid = $data["cid"];
        }
      }
?>

    </tbody>
</table>
      </div>
    </div>
  </div>
  </div>


<br />
添加新的定时器：
<hr />
<form method="post" class="am-form" action="_time.php?method=add&t=<?php echo $timestamp; ?>&r=<?php echo $random; ?>&token=<?php echo $token; ?>">
      <label for="token[]">Token:</label>
     <select multiple data-am-selected="{searchBox: 1,btnWidth: '100%'}" name="token[]" id="token[]" required="true">
      <?php
$datas = $database->select("token", ["tid","name","token"], ["uid" => $_SESSION['Loginuid']]);
      foreach($datas as $data)
      {
        echo "<option value=". $data["tid"] .">".$data["tid"]."-".$data["name"]."</option>";
      }
      ?>
      </select>
      <br>
      <label for="messages">消息内容:</label>
      <textarea name="messages" id="messages" required="true" rows=5></textarea>
      <br>
      <label for="settime">发送时间:</label>
<div>
  <input value="<?php echo (date("Y-m-d H:i:s",time())); ?>" name="settime" class="am-form-field" id='datetp1'>
</div>

<br>
      <label for="ndays">重复天数:</label>
      <input type="text" name="ndays" id="ndays" required="true" value="1" />

      <br>

      <label for="spots">被@人的手机号：（没有请留空）</label>
      <div id="spots">
      <div class="am-input-group">
      <input type="text" name="atMobile[]" class="am-form-field"/>
      <span class="am-input-group-btn">
        <input type="button" id="add" name="add" class="am-btn am-btn-primary am-btn-sm" value="添加一行"></input>
      </span>
      </div>
    </div>
      <br>
     <label for="isAtAll">At全员？:</label><br/>
      <select name="isAtAll" id="isAtAll" data-am-selected>
      <option value="false">否</option>
      <option value="true">是</option>
      </select>
      </label>
      <br>
      <br />
      <div class="am-cf">
        <input type="submit" name="" value="添 加" class="am-btn am-btn-primary am-btn-sm am-fl">
      </div>
    </form>
  
<br />

停用一个定时器：(Del By cid)
<hr />
 <form method="post" class="am-form" action="_time.php?method=del&t=<?php echo $timestamp ?>&r=<?php echo $random ?>&token=<?php echo $token ?>">
       <select multiple data-am-selected="{searchBox: 1,btnWidth: '100%'}" name="urls[]" id="urls[]" required="true">
      <?php
      $cid=null;
      foreach($data1 as $data)
      {
        $phptime=date('Y-m-d H:i:s',$data["settime"]);
        if($cid != $data["cid"]){
        echo "<option value=". $data["cid"] .">".$data["cid"]."-".$phptime."</option>";
        $cid = $data["cid"];
        }
      }
      ?>
      </select>
      <br>
      <br>
      <div class="am-cf">
        <input type="submit" name="" value="删 除" class="am-btn am-btn-danger am-btn-sm am-fl">
      </div>
    </form>
<hr />
<p>©<?php echo date('Y'); ?> Design by Charlie.Pan|<?php echo $sitename;echo " V";echo $version;?>|仅供团队开发测试使用|<a href="http://beian.miit.gov.cn/" target="_blank"><?php echo $icp; ?></a></p>
</div>
</div>
</body>
</html>