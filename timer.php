<?php
header("Content-Type: text/html; charset=UTF-8");
define('IN_SYS', TRUE);
session_start(); 
require_once 'config.php';
 //$i0 = $database->insert("logs", ["ttoken" => $ip,"type" => "100","status" => "1","uid" => "214748364"]);

$datas = $database->select("crontab", ["cid","token","settime","messages","uid"], ["status" => "1"]);
$result = "Result:";
foreach($datas as $data)
      {
          $timenow=time();
          if(($data["settime"]-$timenow)<=0 && ($data["settime"]-$timenow)>=-1800)
          {
          $url = $data["token"];
          $url1="https://oapi.dingtalk.com/robot/send?access_token=".$url;
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type: application/json; charset=utf-8'));
          curl_setopt($ch, CURLOPT_URL, "$url1");
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_POST, true);  
          curl_setopt($ch, CURLOPT_POSTFIELDS, $data["messages"]); 
          $retBase = curl_exec($ch);
          curl_close($ch);
          $result =$result."***".$retBase."***";
          $ret=json_decode($retBase,true);
          if ($ret["errmsg"]=="ok" && $ret["errcode"]=="0" )
          {
              $id = $database->update("crontab", ["status" => "-1"],["cid" => $data["cid"]]);
              $i2 = $database->insert("logs", ["tname" => $data["cid"],"ttoken" => $url,"tmessages" => $data["messages"],"type" => "9","status" => "1","uid" => $data["uid"]]);
          }else{
              $i2 = $database->insert("logs", ["tname" => $data["cid"],"ttoken" => $url,"tmessages" => $data["messages"],"error" => $retBase,"type" => "9","status" => "-1","uid" => $data["uid"]]);
          }
          }
        }
        if ($result=="Result:"){
        $result = "Not thing to do<br />";
        echo $result;
        }else{
        echo $result;
        }
?>