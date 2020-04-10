<?php

require_once "./class/TelegramRobotClass.php";
require_once "./class/AlarmManaging.php";
require_once "./helper/functions.php";

$config = getJson("./data/configs.json");
$apiKey = $config["robotDetails"]["apiKey"];

$alarms = getJson("./data/alarms.json");
$alarmsLastUpdate = $alarms["lastUpdate"];

$alarmManagingClass = new AlarmManaging("./data/alarms.json", $alarms);

$robotClass = new TelegramRobotClass($apiKey);
$newData = $robotClass->getUpdates();
$newData = array_reverse($newData["result"]);

if (!is_array($newData) and count($newData) < 1) {
    die();
} else {
    $newDataCount = count($newData);
    $newDataCount -= 1;
    $newDataLasUpdate = $newData[0]["message"]["date"];
    if ($alarmsLastUpdate == $newDataLasUpdate) {
        die();
    }
}

for ($i = 0; $i < count($newData); $i++) {
    if (!array_key_exists("message", $newData[$i])) {
        continue;
    }
    if ($newData[$i]["message"]["date"] == $alarmsLastUpdate) {
        break;
    }
    if (strpos($newData[$i]["message"]["text"], "\n")) {
        if (strpos($newData[$i]["message"]["text"], "dd")) { // add
            method_add($i, $alarmManagingClass, $newData);
        } elseif (strpos($newData[$i]["message"]["text"], "hannel")) { // channel
            method_channel($i, $alarmManagingClass, $newData);
        } elseif (strpos($newData[$i]["message"]["text"], "epeat")) { // repeat
            method_repeat($i, $alarmManagingClass, $newData);
        } elseif (strpos($newData[$i]["message"]["text"], "emove")) { // remove
            method_remove($i, $alarmManagingClass, $newData);
        }
    }else{
        if (strpos($newData[$i]["message"]["text"], "ist")) { // list
            foreach ($alarms["alarms"] as $alarm) {
                if ($alarm["userName"] === $newData[$i]["message"]["chat"]["username"]) {
                    method_list($robotClass, $alarm);
                }
            }
        }elseif (strpos($newData[$i]["message"]["text"], "elp")){ // help
            $channel = null;
            foreach ($alarms["alarms"] as $alarm){
                if ($alarm["userName"] === $newData[$i]["message"]["chat"]["username"]){
                    $channel = $alarm["channel"];
                }
            }
            if (!is_null($channel)){
                method_help($robotClass,$config["commands"],$channel);
            }
        }
    }
}

$alarmManagingClass->lastUpdate($newData[0]["message"]["date"]);
$alarmManagingClass->done();