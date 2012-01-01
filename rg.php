<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Tjark
 * Date: 31.12.11
 * Time: 03:35
 * To change this template use File | Settings | File Templates.
 */

require_once 'Locale.de.php';
require_once 'Abrechnung.php';
$a = new Abrechnung('abrechnung', 'hxCndcQ39fSWeWyR', 'abrechnung');
$mon = LocaleDe::Month(date('F')) . date(' Y');
$a->generateInvoice(1, 100, 'Teamspeak 3 Server ' . $mon);
$a->generateInvoice(2, 100, 'Teamspeak 3 Server ' . $mon);