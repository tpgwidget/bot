<?php
namespace TPGwidget\Bot\Controllers;

use TPGwidget\Bot\Models\{Twitter, UserState, Strings, Subscriptions};

class TwitterController
{
    /**
     * Handles a new message received by the bot user
     * @param stdClass $event Twitter message_create event details object
     */
    public static function newMessage($event)
    {
        $senderId = $event->message_create->sender_id;
        $message = trim($event->message_create->message_data->text);

        self::handleMessage($senderId, $message);
    }

    private static function handleMessage($senderId, $message)
    {
        switch ($message) {
            case Strings::get('actions.goHome'):
                UserState::set($senderId, UserState::DEFAULT);
                Twitter::message(Strings::get('messages.home'))
                    ->withDefaultActions()
                    ->sendTo($senderId);
                break;

            case Strings::get('actions.subscribe'):
                UserState::set($senderId, UserState::SUBSCRIBING);
                Twitter::message(Strings::get('messages.chooseLineToSubscribe'))->sendTo($senderId);
                break;

            case Strings::get('actions.unsubscribe'):
                UserState::set($senderId, UserState::UNSUBSCRIBING);
                Twitter::message(Strings::get('messages.chooseLineToUnsubscribe'))->sendTo($senderId);
                break;

            default:
                $state = UserState::get($senderId);

                if ($state === UserState::SUBSCRIBING || $state === UserState::UNSUBSCRIBING) {
                    $line = Subscriptions::validateLineName($message);

                    if ($line === false) {
                        Twitter::message(Strings::get('messages.invalidLineName'))->sendTo($senderId);
                    } else {
                        $action = $state === UserState::SUBSCRIBING ? 'subscribe' : 'unsubscribe';

                        if ($action === 'subscribe') {
                            Subscriptions::subscribe($senderId, $line);
                        } else {
                            Subscriptions::unsubscribe($senderId, $line);
                        }

                        Twitter::message(Strings::get('messages.'.$action.'OK', [$line]))
                            ->withDefaultActions()
                            ->sendTo($senderId);
                        UserState::set($senderId, UserState::DEFAULT);
                    }
                } else {
                    Twitter::message(Strings::get('messages.unknownRequest'))
                        ->withDefaultActions()
                        ->sendTo($senderId);
                }

                break;
        }
    }
}
