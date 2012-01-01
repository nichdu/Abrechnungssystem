<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Tjark
 * Date: 31.12.11
 * Time: 17:53
 * To change this template use File | Settings | File Templates.
 */
class LocaleDe
{
    /**
     * Wandelt einen englischsprachigen Monat in das deutschsprachige Aequivalent um
     * @static
     * @param $mon Der Monat
     * @return string eingedeutschter Monat, der uebergebene Parameter, wenn dies kein Monat ist
     */
    public static function Month($mon)
    {
        $ret = $mon;
        switch($mon)
        {
            case 'January':
                $ret = 'Januar';
                break;
            case 'February':
                $ret = 'Februar';
                break;
            case 'March':
                $ret = 'März';
                break;
            case 'May':
                $ret = 'Mai';
                break;
            case 'June':
                $ret = 'Juni';
                break;
            case 'July':
                $ret = 'Juli';
                break;
            case 'October':
                $ret = 'Oktober';
                break;
            case 'December':
                $ret = 'Dezember';
        }
        return $ret;
    }
}
