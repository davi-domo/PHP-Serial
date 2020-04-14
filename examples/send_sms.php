<?php
include 'PhpSerialGSM.php';

// Let's start the class
$serial = new PhpSerial;

/**
  * initSerial()
  * Initialization of the phpSerial class
  *
  * @param string $com          Com port of destination
  * @param int    $Bauds        Baud rate
  */

 function initSerial($com="/dev/ttyUSB2",$baud=9600)
 {
   $serial = new PhpSerial();
   $serial->deviceSet($com);
   $serial->confBaudRate($baud);
   $serial->confParity("none");
   $serial->confCharacterLength(8);
   $serial->confStopBits(1);
   $serial->confFlowControl("none");
   $serial->deviceOpen();
   return $serial;
}
/**
  * sendSMS()
  * Sends a SMS text
  *
  * @param string $str          string to be sent SMS
  * @param string $num_tel      recipient number
  * @param int    $timeout      Max time execution
  * @return bool
  */
 function sendSMS($str, $num_tel, $timeout=15){
   $serial = initSerial();
   if ($serial->writeReadTimeout("AT+CSMP=17,167,0,16 \r","OK") !== FALSE) {
       if ($serial->writeReadTimeout("AT+CMGF=1\r","OK") !== FALSE) {
         if ($serial->writeReadTimeout("AT+CSCS=\"GSM\"\r","OK") !== FALSE) {
           if ($serial->writeReadTimeout("AT+CMGS=\"".$num_tel."\"\r",">") !== FALSE) {
             if ($serial->writeReadTimeout($str.chr(26)."\r","CMGS",$timeout) !== FALSE) {
               $serial->deviceClose();
               return true ;
             }
             else {$serial->deviceClose();return false ;}
           }
           else {$serial->deviceClose();return false ;}
         }
         else {$serial->deviceClose();return false ;}
       }
       else {$serial->deviceClose();return false ;}
   }
   else {$serial->deviceClose();return false ;}
 }
 
 
 if (sendSMS("your message", "your phone number") !== FALSE) {
    echo "SUCCES SMS\n\r" ;
  }
  else {
    echo "ERROR SMS\n\r";
  }
  
  ?>
 
 
