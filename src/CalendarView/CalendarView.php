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

use CalendR\Calendar;
use CalendR\Period\PeriodInterface;
use CalendR\Event\Manager;
use CalendR\Event\Event;
use CalendR\Event\Provider\Basic;
use CalendR\Event\Provider\ProviderInterface;
use Ramsey\Uuid\Uuid;

define('DEF_HTML_TEMPLATE', __DIR__ . '/CalendarLayout.tmpl.php');
define('DEF_FIRST_WEEK_DAY', 0);

/**
 * CalendarView class for calendar html rendaring
 */
class CalendarView
{
    // first week day (1:Monday, 0:Sunday)
    protected $firstWeekDay;
    // array of string that include 7 labels of week day. ([0]:sunday - [6]:saturday)
    protected $weekDayLabels;
    // instance of yohang/CalendR/Calendar class
    protected $factory;
    // instance of yohang/CalendR/Period/Month class
    protected $calendar;
    // path of the html template file for rendering calendar
    protected $template;
    // instance of RenderingHelperInterface class
    protected $renderer;
    // instance of Manager
    protected $manager;
    // instance of Provider
    protected $provider;
    // Month of the calendar to display
    protected $month;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->calendar = null;
        $this->factory = null;
        $this->template = DEF_HTML_TEMPLATE;
        $this->firstWeekDay = DEF_FIRST_WEEK_DAY;
        $this->setCalendar(new \DateTime());

        $this->factory = new Calendar;
        $this->provider =  new Basic;
        $this->manager = new Manager(['basic'=>$this->provider]);
        $this->factory->setEventManager($this->manager);
        $this->renderer = new RenderingHelper;
    }

    /**
     * Adds a provider to the event manager
     *
     * @param $name
     * @param ProviderInterface $provider
     */
    public function addProvider($name, ProviderInterface $provider)
    {
        return $this->manager->addProvider($name, $provider);
    }

    public function addEvent($date = null, $name = '')
    {
        if (get_class($this->provider) === 'CalendR\Event\Provider\Basic') {
            if ($date == null) {
                $date = new \DateTime((new \DateTime())->format('Y-m-d'));
            }
            $this->provider->add(new BasicEvent(Uuid::uuid1()->toString(), $date, $date, $name));
            return true;
        }
        return false;
    }

    public function setUrlBase($urlBase)
    {
        if ($this->renderer instanceof RenderingHelper) {
            $this->renderer->setUrlBase($urlBase);
            return ture;
        }
        return false;
    }

    public function setUrlDateFormat($urlDateFormat)
    {
        if ($this->renderer instanceof RenderingHelper) {
            $this->renderer->setUrlDateFormat($urlDateFormat);
            return ture;
        }
        return false;
    }

    /**
     * Set first day of week for rendaring calendar.
     * @param   int             $firstWeekday   first day of week (0:sunday or 1:monday)
     * @return  boolean
     */
    public function setFirstWeekday($firstWeekday)
    {
        if ($firstWeekday===0 || $firstWeekday===1) {
            $this->firstWeekDay = $firstWeekday;
            return true;
        }
        return false;
    }

    /**
     * Set label of the week day.
     *
     * @param   arrayg  $labels     array of string that include 7 labels of week day. ([0]:sunday - [6]:saturday)
     * @return  boolean
     */
    public function setWeekDayLabels($labels = DEF_WEEK_DAY_LABELS)
    {
        return $this->renderer->setWeekDayLabels($labels);
    }

    /**
     * Set file path of html template for rendering calendar.
     *
     * @param   string          $file           file path of html template
     * @return  boolean
     */
    public function setHtmlTemplate($file)
    {
        if (!empty($file) || !file_exists($file)) {
            $this->template = $file;
            return true;
        }
        return false;
    }

    /**
     * Set the month of calendar for displaying.
     *
     * @param   \DateTime|int   $yearOrStart    year if month is filled, month begin datetime otherwise
     * @param   null|int        $month          number of month (1~12)
     * @return  reference of CalendarView
     */
    public function & setCalendar($yearOrStart, $month = null)
    {
        if (!$yearOrStart instanceof \DateTime) {
            if ($month<1 || 12<$month) {
                return false;
            }
            $this->month = new \DateTime(sprintf('%s-%s-01', $yearOrStart, $month));
        } else {
            if (empty($yearOrStart)) {
                $yearOrStart = new \DateTime;
            }
            $this->month = new \DateTime($yearOrStart->format('Y-m-01'));
        }
        return true;
    }

    /**
     * Render html of calendar.
     *
     * @param   string          $file           file path of html template
     * @return  string                          html of calendar
     */
    public function renderCalendar($file = null)
    {
        // check path of html template
        if (empty($file) || !file_exists($file)) {
            $file = $this->template;
        }

        // make calendar
        $this->factory->setFirstWeekday($this->firstWeekDay);
        $this->calendar = $this->factory->getMonth($this->month);
        // make parameter of calendar for passing to template
        $calendar = array();
        $calendar['month'] = $this->calendar->getBegin();
        $calendar['begin'] = $this->calendar->getBegin();
        $calendar['end'] = $this->calendar->getEnd();
        $calendar['month_header'] = $this->renderer->getMonthHeaderHtml($this->calendar->getBegin());
        for ($i=0 ; $i<7 ; $i++) {
            $calendar['weekday_header'][$i] = $this->renderer->getWeekDayLabelHtml(($this->firstWeekDay+$i)%7);
        }
        $weekNum = 1;
        foreach ($this->calendar as $week) {
            $calendar['weeks'][$weekNum] = [
                'week' => $week->getBegin(),
                'begin' => $week->getBegin(),
                'end' => $week->getEnd(),
            ];
            foreach ($week as $day) {
                $date = $day->getBegin();
                $dateNum = $date->format('d');
                $inRange = $this->calendar->contains($date);
                $today = $day->contains(new \DateTime());
                $events = $this->factory->getEvents($day);
                $calendar['weeks'][$weekNum]['days'][$dateNum]['day'] = $date;
                $calendar['weeks'][$weekNum]['days'][$dateNum]['begin'] = $date;
                $calendar['weeks'][$weekNum]['days'][$dateNum]['end'] = $day->getEnd();
                $calendar['weeks'][$weekNum]['days'][$dateNum]['day_html'] = $this->renderer->getDayHtml($date, $inRange, $today, $events);
                $calendar['weeks'][$weekNum]['days'][$dateNum]['contain'] = $contain;
                $calendar['weeks'][$weekNum]['days'][$dateNum]['today'] = $today;
                $calendar['weeks'][$weekNum]['days'][$dateNum]['events'] = $events;

                $css = 'cal-td';
                if ($inRange) {
                    $css .= ' cal-td-in-range';
                } else {
                    $css .= ' cal-td-out-range';
                }
                if ($date->format('w') == "0") {
                    $css .= ' cal-td-sunday';
                } elseif ($date->format('w') == "6") {
                    $css .= ' cal-td-saturday';
                }
                if ($today) {
                    $css .= ' cal-td-today';
                }
                $calendar['weeks'][$weekNum]['days'][$dateNum]['css'] = $css;
            }
            $weekNum++;
        }
        ob_start();
        include($file);
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }
}
