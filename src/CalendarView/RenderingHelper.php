<?php
/*
 * This file is part of CalendarView.
 *
 * (c) 2016 jun00rbiter
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CalendarView;

use CalendR\Event\Event;
use CalendR\Event\Collection\CollectionInterface;

define('DEF_WEEK_DAY_LABELS', ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']);
define('DEF_URL_DATE_FORMAT', 'Ymd');
define('DEF_URL_BASE', '.');

class RenderingHelper implements RenderingHelperInterface
{
    protected $weekDayLabels;
    protected $urlDateFormat;
    protected $urlBase;

    public function __construct()
    {
        $this->weekDayLabels = DEF_WEEK_DAY_LABELS;
        $this->urlDateFormat = DEF_URL_DATE_FORMAT;
        $this->urlBase = DEF_URL_BASE;
    }

    public function setUrlBase($urlBase)
    {
        $this->urlBase = $urlBase;
        return true;
    }

    public function setUrlDateFormat($urlDateFormat)
    {
        $this->urlDateFormat = $urlDateFormat;
        return true;
    }

    /**
     * Set label of the week day.
     *
     * @param   arrayg  $labels     array of string that include 7 labels of week day. ([0]:sunday - [6]:saturday)
     * @return  boolean
     */
    public function setWeekDayLabels(array $labels = DEF_WEEK_DAY_LABELS)
    {
        if (!is_array($labels) || count($labels)!==7) {
            return false;
        }
        for ($i=0; $i<7; $i++) {
            if (!is_string($labels[$i])) {
                return false;
            }
        }
        $this->weekDayLabels = $labels;
        return true;
    }

    public function getMonthHeaderHtml(\DateTime $month)
    {
        return $month->format('M. Y');
    }

    public function getWeekDayLabelHtml(int $weekDayNumber)
    {
        return $this->weekDayLabels[$weekDayNumber];
    }

    public function getDayHtml(\DateTime $date, bool $inMonth, bool $isToday, CollectionInterface $events = null)
    {
        if (count($events)) {
            $uuid = '';
            foreach ($events->all() as $event) {
                if ($event instanceof BasicEvent && !empty(trim($event->getName()))) {
                    $title .= $event->getName() . '&#10;';
                }
            }
            $title = trim($title);
            $urlDate = $date->format($this->urlDateFormat);
            return "<a href=\"{$this->urlBase}/{$urlDate}\" title=\"{$title}\">" . $date->format('d') . "</a>";
        }
        return $date->format('d');
    }
}
