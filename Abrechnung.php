<?php
/**
 * User: Tjark
 * Date: 31.12.11
 * Time: 01:24
 *
 *
 * hxCndcQ39fSWeWyR
 */
 
class Abrechnung {
    private $_db;

    public function __construct($user, $passwd, $name)
    {
        $this->_db = new mysqli('localhost', $user, $passwd, $name);
    }

    /**
     * @param $uid ID of user
     * @param $amount amount of the invoice in ct.
     * @param $subject subject of invoice
     *
     * generates an invoices, saves it to database and sends an mail with invoice details
     */
    public function generateInvoice($uid, $amount, $subject)
    {
        $q = "INSERT INTO `invoices`(`uid`, `amount`, `subject`) VALUES ($uid, $amount, '$subject');";
        $this->_db->query($q);
        $q = "SELECT * FROM `balances` WHERE `uid` = $uid LIMIT 1;";
        $res = $this->_db->query($q);
        $r = $res->fetch_assoc();
        $balance = $r['balance'];
        $newbalance = $balance - $amount;
        $q = "UPDATE `balances` SET `balance` = $newbalance WHERE `uid` = $uid LIMIT 1;";
        $this->_db->query($q);
        $msg = $this->generateMessage($uid, $amount, $subject, $balance, $newbalance);
        $q = "SELECT * FROM `accounts` WHERE `uid` = $uid LIMIT 1;";
        $res = $this->_db->query($q);
        $r = $res->fetch_assoc();
        $email = $r['email'];
        $fname = $r['fname'];
        $lname = $r['lname'];
        $date = date('d.m.Y');
        $this->sendMail('rg-noreply@saul.li', $email, 'Rechnung vom ' . $date, $msg, $fname, $lname);
    }

    private function generateMessage($uid, $amount, $subject, $premon, $newbalance)
    {
        $q = "SELECT * FROM `accounts` WHERE `uid` = $uid LIMIT 1;";
        $res = $this->_db->query($q);
        $r = $res->fetch_assoc();
        $fname = $r['fname'];
        $lname = $r['lname'];
        $street = $r['street'];
        $city = $r['city'];
        $email = $r['email'];
        $date = date('d.m.Y');
        $frist = date('d.m.Y', time() + 14 * 24 * 60 * 60);
        $amount = number_format($amount / 100, 2, ',', ' ');
        $premon = number_format($premon / 100, 2, ',', ' ');
        $newbalance = number_format($newbalance / 100, 2, ',', ' ');
        $msg = <<<EOD
<html>
<head>
    <style type="text/css">
        .toptd {
            border-bottom: 1px solid black;
        }
    </style>
</head>
<body>
    <div class="address">
        <span style="font-size: smaller;" class="toptd">Tjark Saul &middot; Hinter den Höfen 4 &middot; 27619 Schiffdorf</span><br/>
        $fname $lname<br />
        $street<br />
        $city<br />
        Email: $email
    </div><br/>
    <br />
    <br />
    <h2>Rechnung</h2>
    Datum: $date<br />
    <br />
    <br />
    <table>
        <tr><th class="toptd">Betreff</th><th align="right" class="toptd">Betrag</th></tr>
        <tr><td>Betrag vom Vormonat</td><td align="right">$premon &euro;</td></tr>
        <tr><td class="toptd">$subject</td><td align="right" class="toptd">-$amount &euro;</td></tr>
        <tr><td>Gesamt</td><td align="right">$newbalance &euro;</td></tr>
    </table>
    <br />
    Mit der Bitte um Begleichung des Rechnungsbeitrages bis zum $frist.
</body>
</html>
EOD;
        return $msg;
    }

    private function sendMail ($from, $to, $subject, $content, $fname, $lname)
    {
        $fp = fopen('log.txt', 'a');
        $string = "(" . $from . " to " . $to . ") <" . $subject . "\n" . $content . "\n\n";
        fwrite($fp, $string);
        fclose($fp);

        // To send HTML mail, the Content-type header must be set
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

        // Additional headers
        $headers .= "To: $fname $lname <$to>" . "\r\n";
        $headers .= 'From: Saul.li Rechnungen <rg-noreply@saul.li>' . "\r\n";
        mail($to, $subject, $content, $headers);
    }
}
