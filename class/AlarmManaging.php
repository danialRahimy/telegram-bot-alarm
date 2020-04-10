<?php


class AlarmManaging
{

    private $url;
    private $content;
    private $newMember;

    /**
     * AlarmManaging constructor.
     * @param string $url
     * @param array $content
     */
    public function __construct(string $url, array $content)
    {
        $this->url = $url;
        $this->content = $content;
        $this->addNewMember();
    }

    /**
     * @param array $times
     * @param string $userName
     */
    public function addAlarm(array $times, string $userName)
    {
        $index = $this->findIndexUsername($userName);

        if (!is_null($index)) {
            $index = intval($index);
            foreach ($times as $time) {
                $this->content["alarms"][$index]["alarms"][] = $time;
            }
        } else {
            $array = array("alarms" => $times, "userName" => $userName);
            $this->content["alarms"][] = array_merge($this->newMember, $array);
        }
    }

    /**
     * @param string $date
     */
    public function lastUpdate(string $date)
    {
        $this->content["lastUpdate"] = $date;
    }

    /**
     * @param string $channelName
     * @param string $userName
     */
    public function channel(string $channelName, string $userName)
    {
        $index = $this->findIndexUsername($userName);

        if (!is_null($index)) {
            $index = intval($index);
            $this->content["alarms"][$index]["channel"] = $channelName;
        } else {
            $array = array("channel" => $channelName, "userName" => $userName);
            $this->content["alarms"][] = array_merge($this->newMember, $array);
        }
    }

    /**
     * @param string $repeat
     * @param string $userName
     */
    public function repeatDuring(string $repeat, string $userName)
    {
        $index = $this->findIndexUsername($userName);

        if (!is_null($index)) {
            $index = intval($index);
            $this->content["alarms"][$index]["repeat"] = $repeat;
        } else {
            $array = array("repeat" => $repeat, "userName" => $userName);
            $this->content["alarms"][] = array_merge($this->newMember, $array);
        }
    }

    /**
     * @param array $times
     * @param string $userName
     */
    public function removeAlarm(array $times, string $userName)
    {
        $index = $this->findIndexUsername($userName);

        if (!is_null($index)) {
            $index = intval($index);
            for ($i = 0; $i < count($this->content["alarms"][$index]["alarms"]); $i++) {
                for ($j = 0; $j < count($times); $j++) {
                    if ($this->content["alarms"][$index]["alarms"][$i] == $times[$j]) {
                        array_splice($this->content["alarms"][$index]["alarms"], $i, 1);
                    }
                }
            }
        }
    }

    /**
     *
     */
    public function done()
    {
        file_put_contents($this->url, json_encode($this->content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_OBJECT_AS_ARRAY));
    }

    /**
     * @param string $username
     * @return int|null
     */
    private function findIndexUsername(string $username)
    {
        $index = null;

        for ($i = 0; $i < count($this->content["alarms"]); $i++) {
            if ($this->content["alarms"][$i]["userName"] === $username) {
                $index = $i;
                break;
            }
        }

        return $index;
    }

    /**
     *
     */
    private function addNewMember()
    {
        $this->newMember = array("userName" => "", "alarms" => array(), "channel" => "", "repeat" => "3");
    }

}