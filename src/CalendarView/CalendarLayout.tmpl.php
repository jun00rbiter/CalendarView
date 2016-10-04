<?php
/*
 * This file is the html template for rendering calendar.
 * It is included by instance of CarendarView class.
 *
 * (c) 2016 jun00rbiter
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
?>
<table class="cal-table">
    <thead class="cal-header">
        <tr class="cal-header-tr">
            <th class="cal-th cal-th-month" colspan=7 >
                <?php echo $calendar['month_header']?>
            </th>
        </tr>
        <tr class="cal-header-tr">
            <?php foreach ($calendar['weekday_header'] as $w): ?>
                <th class="cal-th cal-th-day"><?php echo $w ?></th>
            <?php endforeach ?>
        </tr>
    </thead>
    <tbody class="cal-body">
        <?php foreach ($calendar['weeks'] as $week): ?>
            <tr class="cal-body-tr">
                <?php foreach ($week['days'] as $day): ?>
                    <td class="<?php echo $day['css'] ?>" >
                        <?php echo $day['day_html'] ?>
                    </td>
                <?php endforeach ?>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>
