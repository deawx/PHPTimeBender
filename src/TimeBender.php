<?php
/**
 * TimeBender.php
 *
 * This file is part of PHPTimeBender.
 *
 * @author     Muhammet ŞAFAK <info@muhammetsafak.com.tr>
 * @copyright  Copyright © 2022 PHPTimeBender
 * @license    https://github.com/muhametsafak/PHPTimeBender/blob/main/LICENSE  MIT
 * @version    0.2
 * @link       https://www.muhammetsafak.com.tr
 */

declare(strict_types=1);

namespace PHPTimeBender;

/**
 * @method string format(string $format = '')
 * @method \DateTime|false modify(string $modifier = '')
 * @method \DateTime add(\DateInterval $interval)
 * @method \DateTime createFromImmutable(\DateTimeImmutable $object)
 * @method \DateTime sub(\DateInterval $interval)
 * @method \DateTimeZone|false getTimezone()
 * @method \DateTime setTimezone(string $timezone = '')
 * @method int getOffset()
 * @method \DateTime setTime(int $hour, int $minute, int $second = 0, int $microsecond = 0)
 * @method \DateTime setDate(int $year, int $month, int $day)
 * @method \DateTime setISODate(int $year, int $week, int $dayOfWeek = 1)
 * @method \DateTime setTimestamp(int $timestamp)
 * @method int getTimestamp()
 * @method \DateInterval diff(\DateTimeInterface $targetObject, bool $absolute = false)
 * @method static \DateTime|false createFromFormat(string $format, string $datetime, \DateTimeZone|null $timezone = null)
 * @method static array|false getLastErrors()
 * @method static string version()
 * @method static void setLocale(string $lang)
 * @method static PHPTimeBender now()
 * @method static PHPTimeBender parse(string $string)
 * @method static PHPTimeBender date(int $year, int $month = 1, int $day = 1)
 * @method static PHPTimeBender time(int $hour, int $minute = 0, int $second = 0)
 * @method static bool isDay(string $day)
 * @method static bool isMonth(string $month)
 * @method static bool isYear(int|string|array $year)
 * @method static bool isWeek(int|string|array $week)
 * @method static bool isYearDay(int|string|array $day)
 * @method static bool isJanuary()
 * @method static bool isFebruary()
 * @method static bool isMarch()
 * @method static bool isApril()
 * @method static bool isMay()
 * @method static bool isJune()
 * @method static bool isJuly()
 * @method static bool isAugust()
 * @method static bool isSeptember()
 * @method static bool isOctober()
 * @method static bool isNovember()
 * @method static bool isDecember()
 * @method static bool isMonday()
 * @method static bool isTuesday()
 * @method static bool isWednesday()
 * @method static bool isThursday()
 * @method static bool isFriday()
 * @method static bool isSaturday()
 * @method static bool isSunday()
 * @method static bool isCurrentYear()
 * @method static bool isNextYear()
 * @method static bool isPrevYear()
 * @method static bool isCurrentWeek()
 * @method static bool isNextWeek()
 * @method static bool isPrevWeek()
 * @method static bool isCurrentDay()
 * @method static bool isNextDay()
 * @method static bool isPrevDay()
 * @method static PHPTimeBender addSecond(int $second)
 * @method static PHPTimeBender subSecond(int $second)
 * @method static PHPTimeBender addMinute(int $minute)
 * @method static PHPTimeBender subMinute(int $minute)
 * @method static PHPTimeBender addHour(int $hour)
 * @method static PHPTimeBender subHour(int $hour)
 * @method static PHPTimeBender addDay(int $day)
 * @method static PHPTimeBender subDay(int $day)
 * @method static PHPTimeBender addMonth(int $month)
 * @method static PHPTimeBender subMonth(int $month)
 * @method static PHPTimeBender addYear(int $year)
 * @method static PHPTimeBender subYear(int $year)
 * @method static PHPTimeBender addInterval(string $string)
 * @method static PHPTimeBender timezone(null|string|\DateTimeZone $timezone)
 * @method static PHPTimeBender timestamp(int $timestamp)
 * @method static \DatePeriod between(PHPTimeBender $start, PHPTimeBender $stop, string $step = '1 day')
 * @method static string[] betweenFormat(PHPTimeBender $start, PHPTimeBender $stop, string $step = '1 day', string $format = 'd/m/Y')
 * @method static string diffForHumans()
 * @method static string diffForHumansDetail(string $detail = 'ymwdhis')
 */
class TimeBender
{
    protected static ?\PHPTimeBender\PHPTimeBender $instance = null;

    protected static ?string $timezone = null;

    protected static function getInstance(): \PHPTimeBender\PHPTimeBender
    {
        if(self::$instance === null){
            if(self::$timezone === null){
                self::$timezone = \date_default_timezone_get();
            }
            self::$instance = new PHPTimeBender('now', new \DateTimeZone(self::$timezone));
        }
        return self::$instance;
    }

    public function __call($name, $arguments)
    {
        return self::getInstance()->{$name}(...$arguments);
    }

    public static function __callStatic($name, $arguments)
    {
        return self::getInstance()->{$name}(...$arguments);
    }

    public static function timezoneConf(?string $timezone = null): void
    {
        static::$timezone = $timezone;
    }

}
