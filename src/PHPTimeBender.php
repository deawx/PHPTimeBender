<?php
/**
 * PHPTimeBender.php
 *
 * This file is part of PHPTimeBender.
 *
 * @author     Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * @copyright  Copyright © 2022 PHPTimeBender
 * @license    https://github.com/muhametsafak/PHPTimeBender/blob/main/LICENSE  MIT
 * @version    0.1
 * @link       https://www.muhammetsafak.com.tr
 */

declare(strict_types=1);

namespace PHPTimeBender;

use PHPLanguagesSupport\Language;

class PHPTimeBender extends \DateTime
{

    protected const VERSION = '0.1';

    protected const DEFAULT_LANG = 'en_EN';

    protected const DEFAULT_FORMAT = 'Y-m-d H:i:s';

    protected static ?Language $language = null;

    public function __construct($datetime = 'now', ?\DateTimeZone $timezone = null)
    {
        parent::__construct($datetime, $timezone);
    }

    public function __toString()
    {
        return $this->format(self::DEFAULT_FORMAT);
    }

    public function version(): string
    {
        return self::VERSION;
    }

    public static function newInstance($datetime = 'now', ?\DateTimeZone $timezone = null): PHPTimeBender
    {
        return new PHPTimeBender($datetime, $timezone);
    }

    public function setLocale(string $lang): void
    {
        self::setLocaleStatic($lang);
    }

    public function parse(string $string): PHPTimeBender
    {
        if(($time = \strtotime($string)) === FALSE){
            throw new \RuntimeException('The string could not be parsed.');
        }
        $clone = clone $this;
        $clone->setTimestamp($time);
        return $clone;
    }

    public function now($timezone = null): PHPTimeBender
    {
        if(\is_string($timezone) && !empty($timezone)){
            $timezone = new \DateTimeZone($timezone);
        }
        return $now = self::newInstance('now', $timezone);
    }

    public function create(int $year, int $month, int $day, int $hour, int $minute, int $second, ?string $timezone = null): PHPTimeBender
    {
        $clone = clone $this;
        if($timezone !== null){
            $timezone = new \DateTimeZone($timezone);
            $clone->setTimezone($timezone);
        }
        $clone->setDate($year, $month, $day)->setTime($hour, $minute, $second);
        return $clone;
    }

    public function date(int $year, int $month = 1, int $day = 1): PHPTimeBender
    {
        $clone = clone $this;
        $clone->setDate($year, $month, $day);
        return $clone;
    }

    public function time(int $hour, int $minute = 0, int $second = 0): PHPTimeBender
    {
        $clone = clone $this;
        $clone->setTime($hour, $minute, $second);
        return $clone;
    }

    public function isDay(string $day): bool
    {
        $days = [
            'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'
        ];
        $dayIndex = $this->format('w');
        $isDays = \explode('|', \strtolower($day));
        if(\in_array($days[$dayIndex], $isDays)){
            return true;
        }
        return false;
    }

    public function isMonth(string $month): bool
    {
        $months = [
            'january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'
        ];
        $monthIndex = $this->format('n') - 1;
        $isMonth = \explode('|', \strtolower($month));
        if(\in_array($months[$monthIndex], $isMonth)){
            return true;
        }
        return false;
    }

    public function isYear($year): bool
    {
        $cYear = (int)$this->format('Y');
        if(\is_int($year)){
            return ($year === $cYear);
        }
        if(\is_string($year)){
            $year = \explode('|', $year);
        }
        if(\is_array($year)){
            return \in_array($cYear, $year);
        }
        return false;
    }

    public function isWeek($week): bool
    {
        $cWeek = (int)$this->format('W');
        if(\is_int($week)){
            return ($week === $cWeek);
        }
        if(\is_string($week)){
            $week = \explode('|', $week);
        }
        if(\is_array($week)){
            return \in_array($cWeek, $week);
        }
        return false;
    }

    public function isYearDay($day): bool
    {
        $cDay = (int)$this->format('z');
        if(\is_int($day)){
            return ($day === $cDay);
        }
        if(\is_string($day)){
            $day = \explode('|', $day);
        }
        if(\is_array($day)){
            return \in_array($cDay, $day);
        }
        return false;
    }

    public function isJanuary(): bool
    {
        return $this->isMonth('january');
    }

    public function isFebruary(): bool
    {
        return $this->isMonth('february');
    }

    public function isMarch(): bool
    {
        return $this->isMonth('march');
    }

    public function isMay(): bool
    {
        return $this->isMonth('may');
    }

    public function isJune(): bool
    {
        return $this->isMonth('june');
    }

    public function isJuly(): bool
    {
        return $this->isMonth('july');
    }

    public function isAugust(): bool
    {
        return $this->isMonth('august');
    }

    public function isSeptember(): bool
    {
        return $this->isMonth('september');
    }

    public function isOctober(): bool
    {
        return $this->isMonth('october');
    }

    public function isNovember(): bool
    {
        return $this->isMonth('november');
    }

    public function isDecember(): bool
    {
        return $this->isMonth('december');
    }

    public function isMonday(): bool
    {
        return $this->isDay('monday');
    }

    public function isTuesday(): bool
    {
        return $this->isDay('tuesday');
    }

    public function isWednesday(): bool
    {
        return $this->isDay('wednesday');
    }

    public function isThursday(): bool
    {
        return $this->isDay('thursday');
    }

    public function isFriday(): bool
    {
        return $this->isDay('friday');
    }

    public function isSaturday(): bool
    {
        return $this->isDay('saturday');
    }

    public function isSunday(): bool
    {
        return $this->isDay('sunday');
    }

    public function isCurrentYear(): bool
    {
        return $this->isYear((int)\date('Y'));
    }

    public function isNextYear(): bool
    {
        return $this->isYear(((int)\date('Y') + 1));
    }

    public function isPrevYear(): bool
    {
        return $this->isYear(((int)\date('Y') - 1));
    }

    public function isCurrentWeek(): bool
    {
        return $this->isWeek((int)\date('W'));
    }

    public function isNextWeek(): bool
    {
        $week = (int)\date('W') + 1;
        if($week > 52){
            $week = 1;
        }
        return $this->isWeek($week);
    }

    public function isPrevWeek(): bool
    {
        $week = (int)\date('W') - 1;
        if($week < 1){
            $week = 52;
        }
        return $this->isWeek($week);
    }

    public function isCurrentDay(): bool
    {
        $day = $this->format('z');
        return $this->isYearDay((int)$day);
    }

    public function isNextDay(): bool
    {
        $day = (int)$this->format('z') + 1;
        if($day > 365){
            $day = 0;
        }
        return $this->isYearDay($day);
    }

    public function isPrevDay(): bool
    {
        $day = (int)$this->format('z') - 1;
        if($day < 0){
            $day = 365;
        }
        return $this->isYearDay($day);
    }

    public function addSecond(int $second): PHPTimeBender
    {
        $clone = clone $this;
        $timestamp = $clone->getTimestamp() + $second;
        $clone->setTimestamp($timestamp);
        return $clone;
    }

    public function subSecond(int $second): PHPTimeBender
    {
        $clone = clone $this;
        $timestamp = $clone->getTimestamp() - $second;
        $clone->setTimestamp($timestamp);
        return $clone;
    }

    public function addMinute(int $minute): PHPTimeBender
    {
        $clone = clone $this;
        $timestamp = $clone->getTimestamp() + ($minute * 60);
        $clone->setTimestamp($timestamp);
        return $clone;
    }

    public function subMinute(int $minute): PHPTimeBender
    {
        $clone = clone $this;
        $timestamp = $clone->getTimestamp() - ($minute * 60);
        $clone->setTimestamp($timestamp);
        return $clone;
    }

    public function addHour(int $hour): PHPTimeBender
    {
        $clone = clone $this;
        $timestamp = $clone->getTimestamp() + ($hour * 3600);
        $clone->setTimestamp($timestamp);
        return $clone;
    }

    public function subHour(int $hour): PHPTimeBender
    {
        $clone = clone $this;
        $timestamp = $clone->getTimestamp() - ($hour * 3600);
        $clone->setTimestamp($timestamp);
        return $clone;
    }

    public function addDay(int $day): PHPTimeBender
    {
        $clone = clone $this;
        $timestamp = $clone->getTimestamp() + ($day * 86400);
        $clone->setTimestamp($timestamp);
        return $clone;
    }

    public function subDay(int $day): PHPTimeBender
    {
        $clone = clone $this;
        $timestamp = $clone->getTimestamp() - ($day * 86400);
        $clone->setTimestamp($timestamp);
        return $clone;
    }

    public function addMonth(int $month): PHPTimeBender
    {
        return $this->addInterval($month . ' month');
    }

    public function subMonth(int $month): PHPTimeBender
    {
        if($month < 0){
            $month = \abs($month);
        }
        return $this->addInterval('-' . $month . ' month');
    }

    public function addYear(int $year): PHPTimeBender
    {
        return $this->addInterval($year . ' year');
    }

    public function subYear(int $year): PHPTimeBender
    {
        if($year < 0){
            $year = \abs($year);
        }
        return $this->addInterval('-' . $year . ' year');
    }

    public function addInterval(string $string): PHPTimeBender
    {
        $clone = clone $this;
        if(($interval = \DateInterval::createFromDateString($string)) === FALSE){
            throw new \RuntimeException('Could not understand the string to use for Internal : "'.$string.'"');
        }
        $datetime = $clone->add($interval);
        $clone->timestamp = $clone->getTimestamp();
        return $clone;
    }

    public function period(PHPTimeBender $start, PHPTimeBender $stop, string $step = '1 day'): \DatePeriod
    {
        $results = [];
        $interval = \DateInterval::createFromDateString($step);
        return new \DatePeriod($start, $interval, $stop);
    }

    public function periodFormat(PHPTimeBender $start, PHPTimeBender $stop, string $step = '1 day', string $format = 'd/m/Y'): array
    {
        $res = array();
        $periods = $this->period($start, $stop, $step);
        foreach ($periods as $period) {
            $res[] = $period->format($format);
        }
        return $res;
    }

    public function diffForHumans(): string
    {
        $now = \time();
        $timestamp = $this->getTimestamp();

        $ago = (int)($now - $timestamp);
        $key = 'ago';
        if($ago < 0){
            $key = 'later';
            $ago = \abs($ago);
        }
        return self::getLanguage($key, ['diff' => $this->diffHumansCalc($ago)]);
    }

    protected function diffHumansCalc(int $ago): string
    {
        if($ago >= 31556926){
            $agoYear = \round(($ago / 31556926));
            $expression = $agoYear . ' ';
            if($agoYear > 1){
                $expression .= self::getLanguage('years');
            }else{
                $expression .= self::getLanguage('year');
            }
        }elseif($ago >= 2592000){
            $agoMonth = \round(($ago / 2592000));
            $expression = $agoMonth . ' ';
            if($agoMonth > 1){
                $expression .= self::getLanguage('months');
            }else{
                $expression .= self::getLanguage('month');
            }
        }elseif($ago >= 604800){
            $agoWeek = \round(($ago / 604800));
            $expression = $agoWeek . ' ';
            if($agoWeek > 1){
                $expression .= self::getLanguage('weeks');
            }else{
                $expression .= self::getLanguage('week');
            }
        }elseif($ago >= 86400){
            $agoDay = \round(($ago / 86400));
            $expression = $agoDay . ' ';
            if($agoDay > 1){
                $expression .= self::getLanguage('days');
            }else{
                $expression .= self::getLanguage('day');
            }
        }elseif($ago >= 3600){
            $agoHour = \round(($ago / 3600));
            $expression = $agoHour . ' ';
            if($agoHour > 1){
                $expression .= self::getLanguage('hours');
            }else{
                $expression .= self::getLanguage('hour');
            }
        }elseif($ago >= 60){
            $agoMinute = \round(($ago / 60));
            $expression = $agoMinute . ' ';
            if($agoMinute > 1){
                $expression .= self::getLanguage('minutes');
            }else{
                $expression .= self::getLanguage('minute');
            }
        }else{
            $expression = $ago . ' ';
            if($ago > 1){
                $expression .= self::getLanguage('seconds');
            }else{
                $expression .= self::getLanguage('second');
            }
        }
        return $expression;
    }


    protected static function setLocaleStatic(string $lang): void
    {
        self::$language = new Language();
        self::$language->setConfig([
            'path'      => __DIR__ . \DIRECTORY_SEPARATOR . 'Languages/',
            'base'      => 'Main',
        ]);
        self::$language->set($lang);
    }

    protected static function getLanguage(string $key, array $context = []): string
    {
        if(self::$language === null){
            self::setLocaleStatic(self::DEFAULT_LANG);
        }
        return self::$language->r($key, $key, $context);
    }

}
