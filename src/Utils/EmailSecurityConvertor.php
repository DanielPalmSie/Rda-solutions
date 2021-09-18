<?php

namespace App\Utils;

class EmailSecurityConvertor
{
    /**
     * @param $mail
     * @return string
     */
   public function convertMail($mail)
   {
      $mailSegments = explode("@" ,$mail);

       $length = strlen($mailSegments[0]);

       $convertedEmail = '';

       for ($i=0; $i<$length; $i++) {
           if($i == 0 || $i == $length - 1){
               $convertedEmail .= $mailSegments[0][$i];
           }else{
               $convertedEmail .= '*';
           }
       }

       return $convertedEmail.'@'.$mailSegments[1];
   }
}