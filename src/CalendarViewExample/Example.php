<?php
/*
 * This file is part of CalendarView.
 *
 * (c) 2016 jun00rbiter
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CalendarViewExample;

use CalendarView\CalendarView;

class Example
{
    public static function runBasic()
    {
        $view = new CalendarView;
        $view->setUrlBase('./day');
        $view->setUrlDateFormat('Y-m-d');
        $view->setFirstWeekday(0);
        $view->setWeekDayLabels(['日', '月', '火', '水', '木', '金', '土']);
        $view->setCalendar(new \DateTime());
        $view->addEvent(new \DateTime('-1 day'), "event1");
        $view->addEvent(null, "event2");
        $view->addEvent(new \DateTime('1 day'), "event3");
        echo $view->renderCalendar();
    }
}
