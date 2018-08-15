<?php
namespace TPGwidget\Bot\Controllers;

use TPGwidget\Bot\Models\{Disruptions, Twitter, UserState, Strings, Subscriptions};

/**
 * Manages the incoming Twitter direct messages
 */
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
        $message = str_replace("'", "’", $message);

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
                $lines = Subscriptions::getSubscriptionsFrom($senderId);

                if (empty($lines)) {
                    UserState::set($senderId, UserState::DEFAULT);
                    Twitter::message(Strings::get('messages.noLineToUnsubscribe'))
                        ->withDefaultActions()
                        ->sendTo($senderId);
                } else {
                    UserState::set($senderId, UserState::UNSUBSCRIBING);
                    $message = Twitter::message(Strings::get('messages.chooseLineToUnsubscribe'));

                    // Put the “go home” button first
                    $message->addAction(Strings::get('actions.goHome'))
                        ->withoutGoHome();

                    // Add actions for each line
                    foreach ($lines as $line) {
                        $message->addAction($line);
                    }

                    $message->sendTo($senderId);
                }
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

                        UserState::set($senderId, UserState::DEFAULT);

                        Twitter::message(Strings::get('messages.'.$action.'OK', [$line]))
                            ->withDefaultActions()
                            ->sendTo($senderId);

                        // Send the current line status
                        if ($action === 'subscribe') {
                            $currentDisruptions = Disruptions::getCurrentFrom($line);

                            if (empty($currentDisruptions)) {
                                $stringName = 'messages.noOngoingDisruptions';
                            } else {
                                $stringName = count($currentDisruptions) === 1
                                    ? 'messages.ongoingDisruption'
                                    : 'messages.ongoingDisruptions';
                            }

                            Twitter::message(Strings::get($stringName))
                                ->withDefaultActions()
                                ->sendTo($senderId);

                            foreach ($currentDisruptions as $disruption) {
                                Twitter::message(Disruptions::format($disruption, false))
                                    ->withDefaultActions()
                                    ->sendTo($senderId);
                            }
                        }
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
