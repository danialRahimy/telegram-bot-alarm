<?php

require_once "./class/TelegramRobotClass.php";
require_once "./class/AlarmManaging.php";
require_once "./helper/functions.php";

date_default_timezone_set("Asia/Tehran");
$date = date("Hi");

$config = getJson("./data/configs.json");
$apiKey = $config["robotDetails"]["apiKey"];

$alarms = getJson("./data/alarms.json");
$theAlarms = $alarms["alarms"];

$robotClass = new TelegramRobotClass($apiKey);
$alarmClass = new AlarmManaging("./data/alarms.json",$alarms);

for ($i = 0; $i < count($theAlarms); $i++) {
    if (!array_key_exists("channel", $theAlarms[$i])) {
        continue;
    }
    $alarmList = $alarms["alarms"][$i]["alarms"];
    $repeat = $alarms["alarms"][$i]["repeat"];
    for ($j = 0; $j < count($alarmList); $j++) {
        if ($date > str_replace(":", "", $alarmList[$j]) and ($date - str_replace(":", "", $alarmList[$j])) <= $repeat) {
            $robotClass->sendTextMassage(array("the time is {$alarmList[$j]}"), $alarms["alarms"][$i]["channel"]);
            if (($date - str_replace(":", "", $alarmList[$j])) == $repeat){
                $alarmClass->removeAlarm(array($alarmList[$j]),$alarms["alarms"][$i]["userName"]);
            }
        }elseif (($date - str_replace(":", "", $alarmList[$j])) <= ($repeat + 3)){
            $alarmClass->removeAlarm(array($alarmList[$j]),$alarms["alarms"][$i]["userName"]);
        }
    }
}