<?php
/**
 * Created by PhpStorm.
 * User: gregor
 * Date: 14.09.15
 * Time: 19:04
 */

namespace siestaphp\runtime\impl;

use siestaphp\runtime\DateTime;

/**
 * Class DateTimeImpl
 * @package siestaphp\runtime\impl
 */
class DateTimeImpl implements DateTime
{

    /**
     * @var int
     */
    protected $timestamp;


    /**
     *
     */
    public function __construct()
    {
        $this->timestamp = time();
    }

    /**
     * @return string
     */
    public function getSQLDateTime()
    {
        return date("Y-m-d H:i:s", $this->timestamp);
    }

    /**
     * @return string
     */
    public function getSQLDate()
    {
        return date("Y-m-d", $this->timestamp);
    }

    /**
     * @return string
     */
    public function getSQLTime()
    {
        return date("H:i:s", $this->timestamp);
    }

    /**
     * @param string $dateString
     */
    public function stringToTime($dateString)
    {

        $this->timestamp = strtotime($dateString);

        echo $this->timestamp;
    }

    /**
     * @return int
     */
    public function getTime() {
        return $this->timestamp;
    }

    /**
     * @param DateTime $dateTime
     * @return bool
     */
    public function equals( $dateTime) {
        if (!$dateTime) {
            return false;
        }
        return $this->timestamp === $dateTime->getTime();
    }



}