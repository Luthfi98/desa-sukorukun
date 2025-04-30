<?php

if (!function_exists('send_wa')) {
    /**
     * Send WhatsApp message using Fonte API
     * 
     * @param string $phone_number Recipient's phone number (with country code, e.g. 6281234567890)
     * @param string $message Message to be sent
     * @param string|null $media_url URL of media file (optional)
     * @return array Response from Fonte API
     */
    function send_wa($phone_number, $message, $media_url = null)
    {

        

        $api_key = 'EBX7QwPbiBJP3cTKvaG2';
        $device_id = '6285173226048';
        
        $url = "https://api.fonnte.com/send";

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.fonnte.com/send',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array(
        'target' => $phone_number,
        'message' => $message,
        'url' => new CURLFile($media_url, 'application/pdf', 'media.pdf'), 
        'countryCode' => '62', //optional
        ),
        CURLOPT_HTTPHEADER => array(
            'Authorization: EBX7QwPbiBJP3cTKvaG2' //change TOKEN to your actual token
        ),
        ));

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            var_dump($error_msg);die;
        }
        var_dump($response);die;
        curl_close($curl);

        if (isset($error_msg)) {
        // echo $error_msg;
        }
        // echo $response;
    }
}

if (!function_exists('send_wa_template')) {
    /**
     * Send WhatsApp template message using Fonte API
     * 
     * @param string $phone_number Recipient's phone number (with country code, e.g. 6281234567890)
     * @param string $template_name Name of the template
     * @param array $parameters Template parameters
     * @return array Response from Fonte API
     */
    function send_wa_template($phone_number, $template_name, $parameters = [])
    {
        $api_key = config('services.fonte.api_key');
        $device_id = config('services.fonte.device_id');
        
        $url = "https://api.fonte.id/v1/messages/template";
        
        $data = [
            'device_id' => $device_id,
            'phone' => $phone_number,
            'template_name' => $template_name,
            'parameters' => $parameters
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key
        ]);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return [
            'status' => $http_code === 200,
            'response' => json_decode($response, true),
            'http_code' => $http_code
        ];
    }
    
}

if (!function_exists('send_email')) {
    /**
     * Send email using CodeIgniter's Email class
     * 
     * @param string|array $to Recipient email address(es)
     * @param string $subject Email subject
     * @param string $message Email message content
     * @param array $attachments Optional array of file paths to attach
     * @param string|array $cc Optional CC recipient(s)
     * @param string|array $bcc Optional BCC recipient(s)
     * @return bool Whether the email was sent successfully
     */
    function send_email($to, $subject, $message, $attachments = null, $cc = null, $bcc = null)
    {
        $email = \Config\Services::email();
        
        // Set email configuration
        // $email->setFrom('luthfi.ihdalhusnayain98@gmail.com', 'Desa Sukorukun');
        $email->setTo($to);
        $email->setSubject($subject);
        $email->setMessage($message);
        
        // Add CC if provided
        if ($cc) {
            $email->setCC($cc);
        }
        
        // Add BCC if provided
        if ($bcc) {
            $email->setBCC($bcc);
        }
        
        // Add attachments if provided
        if ($attachments && is_file($attachments)) {
            $file = new \CodeIgniter\Files\File($attachments);
            $email->attach($file->getPathname(), $file->getFilename());
        }
        
        // Send email
        if ($email->send()) {
            return true;
        } else {
            var_dump($email->printDebugger());die;
            log_message('error', 'Email sending failed: ' . $email->printDebugger());
            return false;
        }
    }
} 