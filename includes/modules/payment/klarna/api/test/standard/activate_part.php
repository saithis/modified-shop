<?php session_start(); ?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
        <title><?php echo basename(dirname(__FILE__)) . ' > ' . basename(__FILE__, '.php'); ?></title>
    </head>
    <body><?php
        $link = dirname(dirname($_SERVER['SCRIPT_NAME']));
        echo "<a href='".$link."'>".basename($link)."</a> &gt; ";
        $link = dirname($_SERVER['SCRIPT_NAME']);
        echo "<a href='".$link."'>".basename($link)."</a> &gt; ".basename($_SERVER['SCRIPT_NAME'])."<br/>\n";

        include_once('../config.inc.php'); //KCONFIG is defined in here.
        require_once('../../Klarna.php'); //relative from test/

        $config = new KlarnaConfig(KCONFIG);

        $klarna = new Klarna();
        $klarna->setConfig($config);
        
        include_once('../ko.inc.php');

        if(isset($_GET['invno'])) {
            try {
                include '../testgoods.inc.php';
                $klarna->addArtNo($testgoods[1]['qty'], $testgoods[1]['artNo']);
                $result = $klarna->activatePart($_GET['invno']);

                if($result['invno'] != "0") {
                    echo "Partially activated invoice <a href='$result[url]'>#$_GET[invno]</a><br/>";
                    echo "A new passive invoice has been created with the remaining items: #$result[invno]";
                }
                else {
                    echo "Invoice <a href='$result[url]'>#$_GET[invno]</a> was fully activated, no items remain.";
                }

                echo "<br/><h3>Invoice functions</h3><ul>
                        <li><a href='../invoice_functions/credit_invoice.php?invno=$_GET[invno]' target='_blank'>Credit invoice</a></li>
                        <li><a href='../invoice_functions/credit_part.php?invno=$_GET[invno]' target='_blank'>Credit part</a></li>
                        <li><a href='../invoice_functions/email_invoice.php?invno=$_GET[invno]' target='_blank'>Email invoice</a></li>
                        <li><a href='../invoice_functions/send_invoice.php?invno=$_GET[invno]' target='_blank'>Send invoice</a></li>
                        <li><a href='../invoice_functions/return_amount.php?invno=$_GET[invno]&vat=25&amount=5' target='_blank'>Return amount 5 at 25% VAT</a></li>
                    <ul>";
            }
            catch(Exception $e) {
                echo $e->getMessage() . " (" . $e->getCode() . ")";
            }
        }
        else {
            echo "First create a invoice <a href='add_transaction.php'>here</a><br />";
        }

        ?></body>
</html>