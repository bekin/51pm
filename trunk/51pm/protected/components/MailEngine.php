<?php
class MailEngine
{
    /**
    * $from: mixed, can be a string or an array
    * $tos: an array of strings or arrays
    */
    public function mailnow($from, $tos, $subject, $message)
    {
        $mailer = new PHPMailer(true);
        $mailer->CharSet = 'utf-8';
        
        if (Yii::app()->params['mailEngine'] == 'smtp')
        {
            $mailer->IsSMTP();
            
            if (!empty(Yii::app()->params['username']) && !empty(Yii::app()->params['password']))
            {
                $mailer->SMTPAuth   = true;                  // enable SMTP authentication
                $mailer->SMTPSecure = "ssl";                 // sets the prefix to the servier
                $mailer->Username   = Yii::app()->params['username'];
                $mailer->Password   = Yii::app()->params['password'];           
            }
             
            $mailer->Host       = Yii::app()->params['smtp_host'];
            $mailer->Port       = Yii::app()->params['smtp_port'];
        }
        else if (Yii::app()->params['mailEngine'] == 'sendmail') {
            $mail->IsSendmail();
        }
        else 
        {
            $mailer->IsMail();
        }
        
        if (is_array($from))
        {
            $mailer->FromName       = $from['name'];
            $mailer->From   = $from['email'];         
        }
        else 
        {           
            $mailer->From = $from;
        }

        $mailer->AddReplyTo($mailer->From,$mailer->FromName);                
        foreach ($tos as $to ) {
            if (is_array($to))
            {
                $mailer->AddAddress($to['email'], $to['name']); 
            }
            else 
            {
                $mailer->AddAddress($to, ''); 
            }        
        }
        
        $mailer->Subject = $subject;
        $mailer->MsgHTML($message); 
        try {
            $mailer->Send();
            return true;
        }
        catch (phpmailerException $e) {
            Yii::log($e->__toString(), $e->errorMessage());
        } catch (Exception $e) {
            Yii::log($e->__toString(), $e->errorMessage());
        }
        
        return false;
    }
}