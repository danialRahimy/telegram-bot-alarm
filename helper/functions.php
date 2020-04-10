<?php

function getJson($url)
{
    return json_decode(file_get_contents($url), true);
}

function method_add($index, $alarmClass, $content)
{
    $adds = explode("add\n", $content[$index]["message"]["text"]);
    if (is_array($adds) and count($adds) > 1) {
        if (strpos($adds[1], ",")) {
            $times = explode(",", $adds[1]);
            $alarmClass->addAlarm($times, $content[$index]["message"]["chat"]["username"]);
        } else {
            $alarmClass->addAlarm(array($adds[1]), $content[$index]["message"]["chat"]["username"]);
        }
    }
}

function method_channel($index, $alarmClass, $content)
{
    $adds = explode("channel\n", $content[$index]["message"]["text"]);
    if (is_array($adds) and count($adds) > 1) {
        $alarmClass->channel($adds[1], $content[$index]["message"]["chat"]["username"]);
    }
}

function method_repeat($index, $alarmClass, $content)
{
    $adds = explode("repeat\n", $content[$index]["message"]["text"]);
    if (is_array($adds) and count($adds) > 1) {
        $alarmClass->repeatDuring($adds[1], $content[$index]["message"]["chat"]["username"]);
    }
}

function method_remove($index, $alarmClass, $content)
{
    $adds = explode("remove\n", $content[$index]["message"]["text"]);
    if (is_array($adds) and count($adds) > 1) {
        if (strpos($adds[1], ",")) {
            $times = explode(",", $adds[1]);
            $alarmClass->removeAlarm($times, $content[$index]["message"]["chat"]["username"]);
        } else {
            $alarmClass->removeAlarm(array($adds[1]), $content[$index]["message"]["chat"]["username"]);
        }
    }
}

function method_list($robotClass, $alarms)
{
    $robotClass->sendMassage($alarms["alarms"], $alarms["channel"]);
}

function method_help($robotClass, $commands, $user)
{
    $robotClass->sendMassage($commands, $user);
}