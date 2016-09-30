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

use CalendR\Event\Collection\CollectionInterface;

interface RenderingHelperInterface
{
    public function setWeekDayLabels(array $labels);
    public function getMonthHeaderHtml(\DateTime $month);
    public function getWeekDayLabelHtml(int $weekDayNumber);
    public function getDayHtml(\DateTime $date, bool $inMonth, bool $isToday, CollectionInterface $events = null);
}
