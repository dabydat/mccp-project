<?php

namespace App\Infrastructure\Channels;

use App\Domain\Entities\Message;
use App\Domain\Services\NotificationChannel;
use Illuminate\Support\Facades\Log;

class SmsLegacyChannel implements NotificationChannel
{
    public function send(Message $message): void
    {
        $xml = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:sms="http://ultracem.com/sms">
   <soapenv:Header/>
   <soapenv:Body>
      <sms:SendSmsRequest>
         <sms:destination>+570000000000</sms:destination>
         <sms:message>{$message->getSummary()?->value()}</sms:message>
         <sms:reference>{$message->getTitle()->value()}</sms:reference>
      </sms:SendSmsRequest>
   </soapenv:Body>
</soapenv:Envelope>
XML;

        Log::info("SMS SOAP XML Generated:\n" . $xml);
    }

    public function getName(): string { return 'sms'; }
}
