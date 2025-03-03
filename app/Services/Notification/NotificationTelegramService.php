<?php

namespace App\Services\Notification;

use App\Repositories\HandBook\TelegramRepository;
use Carbon\Carbon;
use App\Dto\User\MessageDto;

class NotificationTelegramService
{

    public function __construct(protected TelegramRepository $telegramRepository)
    {
    }

    /**
     * @param MessageDto $dto
     * @return bool[]
     */
    public function push(MessageDto $dto): array
    {
        $user = \Auth::user();
        $message =
            "Имя: " . $user->name . "\n" .
            "email: " . $dto->email . "\n" .
            "Телефон: " . $user->phone . "\n" .
            "Ответ на email: " . ($dto->email == 'YES' ? 'Нужно' : 'Не нужно') . "\n" .
            "Тема: " . $dto->theme . "\n" .
            "Формат: " . $dto->format . "\n" .
            "Сообщение: " . $dto->message . "\n" .
            "Дата : " . \date('d.m.Y | G:i:s') . "\n";
        $this->telegramRepository->getAll()->each(function ($telegram) use($message){
            $this->send($telegram->telegram_id,$message);
        });

        return ['success' => true];
    }


    /**
     * @param $chatId
     * @param $message
     * @return void
     */
    private function send($chatId,$message):void {
        $ch = curl_init();
        $ch_post = [
            CURLOPT_URL => 'https://api.telegram.org/bot' . config('services.telegram_token') . '/sendMessage',
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_POSTFIELDS => [
                'chat_id' => $chatId,
                'parse_mode' => 'HTML',
                'text' => $message,
                'reply_markup' => '',
            ]
        ];
        curl_setopt_array($ch, $ch_post);
        $res = curl_exec($ch); // Делаем запрос
        curl_close($ch); // Завершаем сеанс cURL
    }

}