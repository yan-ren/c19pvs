<?php

/**
 * Tests if a string is a valid mysql date.
 *
 * @param   string   date to check
 * @return  boolean
 */
function validateMysqlDate($date)
{
    return preg_match('#^(?P<year>\d{2}|\d{4})([- /.])(?P<month>\d{1,2})\2(?P<day>\d{1,2})$#', $date, $matches)
        && checkdate($matches['month'], $matches['day'], $matches['year']);
}
