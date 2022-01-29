<?php
/**
 * PHPTimeBender.php
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

use PHPLanguagesSupport\Language;

class PHPTimeBender extends \DateTime
{

    protected const VERSION = '0.2';

    protected const DEFAULT_LANG = 'en_EN';

    protected const DEFAULT_FORMAT = 'Y-m-d H:i:s';

    protected static ?Language $language = null;

    protected ?\DateTimeZone $timezone = null;

    public function __toString()
    {
        return $this->format(self::DEFAULT_FORMAT);
    }

    /**
     * PHPTimeBender kütüphanesinin sürümünü döndürür.
     *
     * @return string
     */
    public function version(): string
    {
        return self::VERSION;
    }

    /**
     * Yerelleştirmeyi sağlar.
     *
     * @param string $lang <p>src/Languages/ altında yer alan bir yerelleştirme dizin adı.</p>
     * @return void
     */
    public function setLocale(string $lang): void
    {
        self::setLocaleStatic($lang);
    }

    /**
     * String bir zaman ifadesini parçalayarak kütüphanenin zamanı değiştirilmiş bir örneğini döndürür.
     *
     * @param string $string
     * @return PHPTimeBender
     */
    public function parse(string $string): PHPTimeBender
    {
        $time = \strtotime($string);
        $clone = clone $this;
        $clone->setTimestamp($time);
        return $clone;
    }

    /**
     * O anki zaman ile kütüphanenin bir örneğini döndürür.
     *
     * @param null|\DateTimeZone|string $timezone
     * @return PHPTimeBender
     * @throws \Exception
     */
    public function now($timezone = null): PHPTimeBender
    {
        $clone = clone $this;
        $clone->setTimestamp(\time());
        if($timezone !== null){
            $clone->setTimezone($this->str2Timezone($timezone));
        }
        return $clone;
    }

    /**
     * Zaman aralığını değiştirerek kütüphanenin bir örneğini döndürür.
     *
     * @param $timezone
     * @return PHPTimeBender
     */
    public function timezone($timezone): PHPTimeBender
    {
        $clone = clone $this;
        $clone->setTimezone($this->str2Timezone($timezone));
        return $clone;
    }

    /**
     * Belirtilen parametreler zamanın değiştirildiği kütüphanenin bir örneğini döndürür.
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @param int $hour
     * @param int $minute
     * @param int $second
     * @param string|null $timezone
     * @return PHPTimeBender
     */
    public function create(int $year, int $month, int $day, int $hour, int $minute, int $second, ?string $timezone = null): PHPTimeBender
    {
        $clone = clone $this;
        $clone->setTimezone($this->str2Timezone($timezone));
        $clone->setDate($year, $month, $day)->setTime($hour, $minute, $second);
        return $clone;
    }

    /**
     * Kütüphanenin tarih bilgisi değiştirilmiş bir örneğini döndürür.
     *
     * @param int $year
     * @param int $month
     * @param int $day
     * @return PHPTimeBender
     */
    public function date(int $year, int $month = 1, int $day = 1): PHPTimeBender
    {
        $clone = clone $this;
        $clone->setDate($year, $month, $day);
        return $clone;
    }

    /**
     * Kütüphanenin saat bilgisi değiştirilmiş bir örneğini döndürür.
     *
     * @param int $hour
     * @param int $minute
     * @param int $second
     * @return PHPTimeBender
     */
    public function time(int $hour, int $minute = 0, int $second = 0): PHPTimeBender
    {
        $clone = clone $this;
        $clone->setTime($hour, $minute, $second);
        return $clone;
    }

    /**
     * Kütüphanenin zaman damgası değiştirilmiş bir örneğini döndürür.
     *
     * @param int $timestamp
     * @return PHPTimeBender
     */
    public function timestamp(int $timestamp): PHPTimeBender
    {
        $clone = clone $this;
        $clone->setTimestamp($timestamp);
        return $clone;
    }

    /**
     * Kütüphanenin tuttuğu zamanının gün bilgisini sınar.
     *
     * @param string $day <p>Günlerin ingilizce isimleri. Birden fazla gün için sınama yapılacaksa dik çizgi (|) kullanılarak birden fazla gün için sınama yapılabilir. Bu durumda her hangi birinin eşleşmesi yeterlidir.</p>
     * @return bool
     */
    public function isDay(string $day): bool
    {
        $days = [
            'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'
        ];
        $day = \mb_strtolower($day, 'UTF-8');

        foreach ($days as $row) {
            $day = \str_replace(\mb_strtolower(self::getLanguage($row), 'UTF-8'), $row, $day);
            $day = \str_replace(\mb_strtolower(self::getLanguage($row . '_short'), 'UTF-8'), $row, $day);
        }
        $dayIndex = $this->format('w');
        $isDays = \explode('|', $day);
        if(\in_array($days[$dayIndex], $isDays)){
            return true;
        }
        return false;
    }

    /**
     * Kütüphanenin tuttuğu zamanın ay bilgisini sınar.
     *
     * @param string $month <p>Ayların ingilizce isimleri. Birden fazla ay için sınama yapılacaksa dik çizgi (|) kullanılarak birden fazla ay için sınama yapılabilir. Bu durumda her hangi birini eşleşmesi yeterlidir.</p>
     * @return bool
     */
    public function isMonth(string $month): bool
    {
        $months = [
            'january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december'
        ];
        $month = \mb_strtolower($month, 'UTF-8');
        foreach ($months as $row) {
            $month = \str_replace(\mb_strtolower(self::getLanguage($row), 'UTF-8'), $row, $month);
            $month = \str_replace(\mb_strtolower(self::getLanguage($row . '_short'), 'UTF-8'), $row, $month);
        }
        $monthIndex = $this->format('n') - 1;
        $isMonth = \explode('|', $month);
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

    /**
     * Kütüphanenin tuttuğu zamanının yılın kaçıncı haftası olduğunu sınar.
     *
     * @param int|string|int[] $week
     * @return bool
     */
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

    /**
     * Kütüphanenin tuttuğu zamanının yılın kaçıncı günü olduğunu sınar.
     *
     * @param int|string|int[] $day
     * @return bool
     */
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

    /**
     * Kütüphanenın tuttuğu zaman bir Ocak ayı mı diye sınar.
     *
     * @return bool
     */
    public function isJanuary(): bool
    {
        return $this->isMonth('january');
    }

    /**
     * Kütüphanenın tuttuğu zaman bir Şubat ayı mı diye sınar.
     *
     * @return bool
     */
    public function isFebruary(): bool
    {
        return $this->isMonth('february');
    }

    /**
     * Kütüphanenın tuttuğu zaman bir Mart ayı mı diye sınar.
     *
     * @return bool
     */
    public function isMarch(): bool
    {
        return $this->isMonth('march');
    }

    /**
     * Kütüphanenın tuttuğu zaman bir Nisan ayı mı diye sınar.
     *
     * @return bool
     */
    public function isApril(): bool
    {
        return $this->isMonth('april');
    }

    /**
     * Kütüphanenın tuttuğu zaman bir Mayıs ayı mı diye sınar.
     *
     * @return bool
     */
    public function isMay(): bool
    {
        return $this->isMonth('may');
    }

    /**
     * Kütüphanenın tuttuğu zaman bir Haziran ayı mı diye sınar.
     *
     * @return bool
     */
    public function isJune(): bool
    {
        return $this->isMonth('june');
    }

    /**
     * Kütüphanenın tuttuğu zaman bir Temmuz ayı mı diye sınar.
     *
     * @return bool
     */
    public function isJuly(): bool
    {
        return $this->isMonth('july');
    }

    /**
     * Kütüphanenın tuttuğu zaman bir Ağustos ayı mı diye sınar.
     *
     * @return bool
     */
    public function isAugust(): bool
    {
        return $this->isMonth('august');
    }

    /**
     * Kütüphanenın tuttuğu zaman bir Eylül ayı mı diye sınar.
     *
     * @return bool
     */
    public function isSeptember(): bool
    {
        return $this->isMonth('september');
    }

    /**
     * Kütüphanenın tuttuğu zaman bir Ekim ayı mı diye sınar.
     *
     * @return bool
     */
    public function isOctober(): bool
    {
        return $this->isMonth('october');
    }

    /**
     * Kütüphanenın tuttuğu zaman bir Kasım ayı mı diye sınar.
     *
     * @return bool
     */
    public function isNovember(): bool
    {
        return $this->isMonth('november');
    }

    /**
     * Kütüphanenın tuttuğu zaman bir Aralık ayı mı diye sınar.
     *
     * @return bool
     */
    public function isDecember(): bool
    {
        return $this->isMonth('december');
    }

    /**
     * Kütüphanenın tuttuğu zaman bir Pazartesi günü mü diye sınar.
     *
     * @return bool
     */
    public function isMonday(): bool
    {
        return $this->isDay('monday');
    }

    /**
     * Kütüphanenın tuttuğu zaman bir Salı günü mü diye sınar.
     *
     * @return bool
     */
    public function isTuesday(): bool
    {
        return $this->isDay('tuesday');
    }

    /**
     * Kütüphanenın tuttuğu zaman bir Çarşamba günü mü diye sınar.
     *
     * @return bool
     */
    public function isWednesday(): bool
    {
        return $this->isDay('wednesday');
    }

    /**
     * Kütüphanenın tuttuğu zaman bir Perşembe günü mü diye sınar.
     *
     * @return bool
     */
    public function isThursday(): bool
    {
        return $this->isDay('thursday');
    }

    /**
     * Kütüphanenın tuttuğu zaman bir Cuma günü mü diye sınar.
     *
     * @return bool
     */
    public function isFriday(): bool
    {
        return $this->isDay('friday');
    }

    /**
     * Kütüphanenın tuttuğu zaman bir Cumartesi günü mü diye sınar.
     *
     * @return bool
     */
    public function isSaturday(): bool
    {
        return $this->isDay('saturday');
    }

    /**
     * Kütüphanenın tuttuğu zaman bir Pazar günü mü diye sınar.
     *
     * @return bool
     */
    public function isSunday(): bool
    {
        return $this->isDay('sunday');
    }

    /**
     * Kütüphanenın tuttuğu zaman günümüz yılınamı ait diye sınar.
     *
     * @return bool
     */
    public function isCurrentYear(): bool
    {
        return $this->isYear((int)\date('Y'));
    }

    /**
     * Kütüphanenın tuttuğu zaman gelecek yılamı ait diye sınar.
     *
     * @return bool
     */
    public function isNextYear(): bool
    {
        return $this->isYear(((int)\date('Y') + 1));
    }

    /**
     * Kütüphanenın tuttuğu zaman geçen yılamı ait diye sınar.
     *
     * @return bool
     */
    public function isPrevYear(): bool
    {
        return $this->isYear(((int)\date('Y') - 1));
    }

    /**
     * Kütüphanenın tuttuğu zamanın hafta numarası günümüz hafta numarası ile aynı mı diye sınar.
     *
     * @return bool
     */
    public function isCurrentWeek(): bool
    {
        return $this->isWeek((int)\date('W'));
    }

    /**
     * Kütüphanenın tuttuğu zamanın hafta numarası gelecek hafta numarası ile aynı mı diye sınar.
     *
     * @return bool
     */
    public function isNextWeek(): bool
    {
        $week = (int)\date('W') + 1;
        if($week > 52){
            $week = 1;
        }
        return $this->isWeek($week);
    }

    /**
     * Kütüphanenın tuttuğu zamanın hafta numarası geçen hafta numarası ile aynı mı diye sınar.
     *
     * @return bool
     */
    public function isPrevWeek(): bool
    {
        $week = (int)\date('W') - 1;
        if($week < 1){
            $week = 52;
        }
        return $this->isWeek($week);
    }

    /**
     * Kütüphanenın tuttuğu zamanın gün numarası günümüz gün numarası ile aynı mı diye sınar. Burada gün numarası yılın kaçıncı günü olduğudur.
     *
     * @return bool
     */
    public function isCurrentDay(): bool
    {
        $day = \date('z');
        return $this->isYearDay((int)$day);
    }

    /**
     * Kütüphanenın tuttuğu zamanın gün numarası yarının gün numarası ile aynı mı diye sınar. Burada gün numarası yılın kaçıncı günü olduğudur.
     *
     * @return bool
     */
    public function isNextDay(): bool
    {
        $day = (int)(\date('z')) + 1;
        if($day > 365){
            $day = 0;
        }
        return $this->isYearDay($day);
    }

    /**
     * Kütüphanenın tuttuğu zamanın gün numarası dünün gün numarası ile aynı mı diye sınar. Burada gün numarası yılın kaçıncı günü olduğudur.
     *
     * @return bool
     */
    public function isPrevDay(): bool
    {
        $day = (int)(\date('z')) - 1;
        if($day < 0){
            $day = 365;
        }
        return $this->isYearDay($day);
    }

    /**
     * Kütüphanenin tuttuğu zamanı belirtilen saniye kadar ileri alındığı bir örneğini döndürür.
     *
     * @param int $second
     * @return PHPTimeBender
     */
    public function addSecond(int $second): PHPTimeBender
    {
        $clone = clone $this;
        $timestamp = $clone->getTimestamp() + $second;
        $clone->setTimestamp($timestamp);
        return $clone;
    }

    /**
     * Kütüphanenin tuttuğu zamanı belirtilen saniye kadar geriye alındığı bir örneğini döndürür.
     *
     * @param int $second
     * @return PHPTimeBender
     */
    public function subSecond(int $second): PHPTimeBender
    {
        $clone = clone $this;
        $timestamp = $clone->getTimestamp() - $second;
        $clone->setTimestamp($timestamp);
        return $clone;
    }

    /**
     * Kütüphanenin tuttuğu zamanı belirtilen dakika kadar ileri alındığı bir örneğini döndürür.
     *
     * @param int $minute
     * @return PHPTimeBender
     */
    public function addMinute(int $minute): PHPTimeBender
    {
        $clone = clone $this;
        $timestamp = $clone->getTimestamp() + ($minute * 60);
        $clone->setTimestamp($timestamp);
        return $clone;
    }

    /**
     * Kütüphanenin tuttuğu zamanı belirtilen dakika kadar geriye alındığı bir örneğini döndürür.
     *
     * @param int $minute
     * @return PHPTimeBender
     */
    public function subMinute(int $minute): PHPTimeBender
    {
        $clone = clone $this;
        $timestamp = $clone->getTimestamp() - ($minute * 60);
        $clone->setTimestamp($timestamp);
        return $clone;
    }

    /**
     * Kütüphanenin tuttuğu zamanı belirtilen saat kadar ileri alındığı bir örneğini döndürür.
     *
     * @param int $hour
     * @return PHPTimeBender
     */
    public function addHour(int $hour): PHPTimeBender
    {
        $clone = clone $this;
        $timestamp = $clone->getTimestamp() + ($hour * 3600);
        $clone->setTimestamp($timestamp);
        return $clone;
    }

    /**
     * Kütüphanenin tuttuğu zamanı belirtilen saat kadar geriye alındığı bir örneğini döndürür.
     *
     * @param int $hour
     * @return PHPTimeBender
     */
    public function subHour(int $hour): PHPTimeBender
    {
        $clone = clone $this;
        $timestamp = $clone->getTimestamp() - ($hour * 3600);
        $clone->setTimestamp($timestamp);
        return $clone;
    }

    /**
     * Kütüphanenin tuttuğu zamanı belirtilen gün kadar ileri alındığı bir örneğini döndürür.
     *
     * @param int $day
     * @return PHPTimeBender
     */
    public function addDay(int $day): PHPTimeBender
    {
        $clone = clone $this;
        $timestamp = $clone->getTimestamp() + ($day * 86400);
        $clone->setTimestamp($timestamp);
        return $clone;
    }

    /**
     * Kütüphanenin tuttuğu zamanı belirtilen gün kadar geriye alındığı bir örneğini döndürür.
     *
     * @param int $day
     * @return PHPTimeBender
     */
    public function subDay(int $day): PHPTimeBender
    {
        $clone = clone $this;
        $timestamp = $clone->getTimestamp() - ($day * 86400);
        $clone->setTimestamp($timestamp);
        return $clone;
    }

    /**
     * Kütüphanenin tuttuğu zamanı belirtilen ay kadar ileri alındığı bir örneğini döndürür.
     *
     * @param int $month
     * @return PHPTimeBender
     */
    public function addMonth(int $month): PHPTimeBender
    {
        return $this->addInterval($month . ' month');
    }

    /**
     * Kütüphanenin tuttuğu zamanı belirtilen ay kadar geriye alındığı bir örneğini döndürür.
     *
     * @param int $month
     * @return PHPTimeBender
     */
    public function subMonth(int $month): PHPTimeBender
    {
        if($month < 0){
            $month = \abs($month);
        }
        return $this->addInterval('-' . $month . ' month');
    }

    /**
     * Kütüphanenin tuttuğu zamanı belirtilen yıl kadar ileri alındığı bir örneğini döndürür.
     *
     * @param int $year
     * @return PHPTimeBender
     */
    public function addYear(int $year): PHPTimeBender
    {
        return $this->addInterval($year . ' year');
    }

    /**
     * Kütüphanenin tuttuğu zamanı belirtilen yıl kadar geriye alındığı bir örneğini döndürür.
     *
     * @param int $year
     * @return PHPTimeBender
     */
    public function subYear(int $year): PHPTimeBender
    {
        if($year < 0){
            $year = \abs($year);
        }
        if(1970 > (\date("Y") - $year)){
            throw new \InvalidArgumentException('The Time Bender cannot be positioned to a year prior to 1970.');
        }
        return $this->addInterval('-' . $year . ' year');
    }

    /**
     * Bir dize ile kütüphanenin tuttuğu zamanın değiştirilmiş bir örneğini döndürür.
     *
     * @param string $string
     * @return PHPTimeBender
     */
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

    /**
     * İki zaman arasında kalan zamanı almak için kullanılır.
     *
     * @param PHPTimeBender $start
     * @param PHPTimeBender $stop
     * @param string $step
     * @return \DatePeriod
     */
    public function between(PHPTimeBender $start, PHPTimeBender $stop, string $step = '1 day'): \DatePeriod
    {
        $results = [];
        $interval = \DateInterval::createFromDateString($step);
        return new \DatePeriod($start, $interval, $stop);
    }

    /**
     * İki zaman arasında kalan zamanları belirtilen formatta formatlayarak bir dizi halinde döndürür.
     *
     * @param PHPTimeBender $start
     * @param PHPTimeBender $stop
     * @param string $step
     * @param string $format
     * @return array
     */
    public function betweenFormat(PHPTimeBender $start, PHPTimeBender $stop, string $step = '1 day', string $format = 'd/m/Y'): array
    {
        $res = array();
        $periods = $this->between($start, $stop, $step);
        foreach ($periods as $period) {
            $res[] = $period->format($format);
        }
        return $res;
    }

    /**
     * Kütüphane tarafından tutulan zamanın şuan ki zaman ile arasındaki farklı daha anlaşılır şekilde döndürür.
     *
     * @return string
     */
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

    /**
     * Kütüphane tarafından tutulan zamanın şuan ki zaman ile arasındaki farklı daha anlaşılır şekilde döndürür. diffForHumans() yönteminden farklı olarak hangi detayların bulunacağını belirtebilirsiniz.
     *
     * @param string $detail <p>Cümlede hangi detayların bulunacağını belirten karakterlerden oluşan dize. <code>Y</code> Year/Yıl, <code>M</code> Month/Ay, <code>W</code> Week/Hafta, <code>D</code> Day/Gün, <code>H</code> Hour/Saat, <code>I</code> Minute/Dakika, <code>S</code> Second/Saniye</p>
     * @return string
     */
    public function diffForHumansDetail(string $detail = 'ymwdhis'): string
    {
        $now = \time();
        $timestamp = $this->getTimestamp();

        $ago = (int)($now - $timestamp);
        $key = 'ago';
        if($ago < 0){
            $key = 'later';
            $ago = \abs($ago);
        }
        return self::getLanguage($key, ['diff' => $this->diffHumansCalc($ago, $detail)]);
    }

    protected function diffHumansCalc(int $ago, $format = ''): string
    {
        if($format != ''){
            $format = \strtolower(\str_replace('İ', 'i', $format));
        }
        $expression = '';

        if($ago >= 31556926 && ($format == '' || \stripos($format, 'y') !== FALSE)){
            if($format == ''){
                $agoYear = \round(($ago / 31556926));
            }else{
                $agoYear = \floor(($ago / 31556926));
            }

            $expression .= $agoYear . ' ';
            if($agoYear > 1){
                $expression .= self::getLanguage('years');
            }else{
                $expression .= self::getLanguage('year');
            }
            if($format == ''){
                return \trim($expression);
            }
            $ago = $ago - ($agoYear * 31556926);
        }

        if($ago >= 2592000 && ($format == '' || \stripos($format, 'm') !== FALSE)){
            if($format == ''){
                $agoMonth = \round(($ago / 2592000));
            }else{
                $agoMonth = \floor(($ago / 2592000));
            }
            $expression .= ' ' . $agoMonth . ' ';
            if($agoMonth > 1){
                $expression .= self::getLanguage('months');
            }else{
                $expression .= self::getLanguage('month');
            }
            if($format == ''){
                return \trim($expression);
            }
            $ago = $ago - ($agoMonth * 2592000);
        }

        if($ago >= 604800 && ($format == '' || \stripos($format, 'w') !== FALSE)){
            if($format == ''){
                $agoWeek = \round(($ago / 604800));
            }else{
                $agoWeek = \floor(($ago / 604800));
            }
            $expression .= ' ' . $agoWeek . ' ';
            if($agoWeek > 1){
                $expression .= self::getLanguage('weeks');
            }else{
                $expression .= self::getLanguage('week');
            }
            if($format == ''){
                return \trim($expression);
            }
            $ago = $ago - ($agoWeek * 604800);
        }

        if($ago >= 86400 && ($format == '' || \stripos($format, 'd') !== FALSE)){
            if($format == ''){
                $agoDay = \round(($ago / 86400));
            }else{
                $agoDay = \floor(($ago / 86400));
            }
            $expression .= ' ' . $agoDay . ' ';
            if($agoDay > 1){
                $expression .= self::getLanguage('days');
            }else{
                $expression .= self::getLanguage('day');
            }
            if($format == ''){
                return \trim($expression);
            }
            $ago = $ago - ($agoDay * 86400);
        }

        if($ago >= 3600 && ($format == '' || \stripos($format, 'h') !== FALSE)){
            if($format == ''){
                $agoHour = \round(($ago / 3600));
            }else{
                $agoHour = \floor(($ago / 3600));
            }
            $expression .= ' ' . $agoHour . ' ';
            if($agoHour > 1){
                $expression .= self::getLanguage('hours');
            }else{
                $expression .= self::getLanguage('hour');
            }
            if($format == ''){
                return \trim($expression);
            }
            $ago = $ago - ($agoHour * 3600);
        }
        if($ago >= 60 && ($format == '' || \stripos($format, 'i') !== FALSE)){
            if($format == ''){
                $agoMinute = \round(($ago / 60));
            }else{
                $agoMinute = \floor(($ago / 60));
            }
            $expression .= ' ' . $agoMinute . ' ';
            if($agoMinute > 1){
                $expression .= self::getLanguage('minutes');
            }else{
                $expression .= self::getLanguage('minute');
            }
            if($format == ''){
                return \trim($expression);
            }
            $ago = $ago - ($agoMinute * 60);
        }
        if($ago > 0 && ($format == '' || \stripos($format, 's') !== FALSE)){
            $expression .= ' ' . $ago . ' ';
            if($ago > 1){
                $expression .= self::getLanguage('seconds');
            }else{
                $expression .= self::getLanguage('second');
            }
        }
        return \trim($expression);
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

    /**
     * @param string|\DateTimeZone|null $timezone
     * @return \DateTimeZone
     */
    protected function str2Timezone($timezone): \DateTimeZone
    {
        if($timezone instanceof \DateTimeZone){
            return $timezone;
        }
        if(\is_string($timezone) && !empty($timezone)){
            return new \DateTimeZone($timezone);
        }
        if($this->timezone === null){
            $this->timezone = new \DateTimeZone(\date_default_timezone_get());
        }
        return $this->timezone;
    }

}
