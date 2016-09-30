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

class BasicEvent extends Event
{
    // Name of Event
    protected $name;

    public function __construct($uid, \DateTime $start, \DateTime $end, $name = '')
    {
        parent::__construct($uid, $start, $end);
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}
