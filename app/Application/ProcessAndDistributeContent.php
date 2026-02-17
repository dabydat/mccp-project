<?php

namespace App\Application;

use App\Domain\Entities\Message;
use App\Domain\Entities\DeliveryLog;
use App\Domain\Repositories\MessageRepository;
use App\Domain\Repositories\DeliveryLogRepository;
use App\Domain\Services\AIService;
use App\Domain\Services\NotificationChannel;
use App\Domain\ValueObjects\Identity;
use App\Domain\ValueObjects\MessageTitle;
use App\Domain\ValueObjects\MessageContent;
use App\Domain\ValueObjects\ChannelName;
use App\Domain\ValueObjects\DeliveryLogDetails;
use App\Domain\Enums\DeliveryStatus;
use App\Domain\Exceptions\AIProcessingException;
use Illuminate\Support\Facades\Log;

class ProcessAndDistributeContent
{
    /**
     * @param MessageRepository $messageRepository
     * @param DeliveryLogRepository $deliveryLogRepository
     * @param AIService $aiService
     * @param NotificationChannel[] $channels
     */
    public function __construct(
        private MessageRepository $messageRepository,
        private DeliveryLogRepository $deliveryLogRepository,
        private AIService $aiService,
        private array $channels
    ) {}

    public function execute(string $title, string $content, array $selectedChannelNames): Message
    {
        try {
            $summary = $this->aiService->generateSummary($content);
        } catch (\Exception $e) {
            Log::error("IA Processing failed: " . $e->getMessage());
            throw AIProcessingException::failedToGenerateSummary($e->getMessage());
        }

        $message = new Message(
            new Identity(null),
            new MessageTitle($title),
            new MessageContent($content),
            $summary
        );

        $savedMessage = $this->messageRepository->save($message);

        foreach ($this->channels as $channel) {
            if (in_array($channel->getName(), $selectedChannelNames)) {
                $channelNameVO = new ChannelName($channel->getName());
                try {
                    $channel->send($savedMessage);
                    $this->logDelivery($savedMessage->getId(), $channelNameVO, DeliveryStatus::SUCCESS);
                } catch (\Exception $e) {
                    Log::error("Delivery failed for channel {$channel->getName()}: " . $e->getMessage());
                    $this->logDelivery($savedMessage->getId(), $channelNameVO, DeliveryStatus::FAILED, $e->getMessage());
                }
            }
        }

        return $savedMessage;
    }

    private function logDelivery(Identity $messageId, ChannelName $channel, DeliveryStatus $status, ?string $details = null): void
    {
        $log = new DeliveryLog(new Identity(null), $messageId, $channel, $status, new DeliveryLogDetails($details));
        $this->deliveryLogRepository->save($log);
    }
}
