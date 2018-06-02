<?php
namespace TPGwidget\Bot\Models;

use TPGwidget\Bot\Models\{Twitter, Strings};

/**
 * Class to send a direct message on Twitter
 */
class TwitterDirectMessage {
    private $text;
    private $goHomeAction = true;

    /**
     * Creates a new message with a text
     * @param string $text
     */
    public function __construct(string $text)
    {
        $this->text = $text;
    }

    /**
     * Disable the default ‘goHome’ action
     * @return TwitterDirectMessage $this (useful for method chaining)
     */
    public function withoutGoHome()
    {
        $this->goHomeAction = false;
        return $this;
    }

    /**
     * Adds an action to the message
     * @param  string               $title
     * @param  string               $description
     * @return TwitterDirectMessage $this (useful for method chaining)
     */
    public function addAction($title, $description = null)
    {
        $this->actions[] = [
            'title' => $title,
            'description' => $description,
        ];

        return $this;
    }

    /**
     * Add the default actions (home screen)
     * @return TwitterDirectMessage $this (useful for method chaining)
     */
    public function withDefaultActions()
    {
        return $this
            ->addAction(Strings::get('actions.subscribe'))
            ->addAction(Strings::get('actions.unsubscribe'))
            ->withoutGoHome();
    }

    /**
     * Sends the message to an user
     * @param  string               $userId
     * @return TwitterDirectMessage $this (useful for method chaining)
     */
    public function sendTo($userId)
    {
        if ($this->goHomeAction) {
            $this->addAction(Strings::get('actions.goHome'));
        }

        Twitter::lib()->post('direct_messages/new', [
            'user_id' => $userId,
            'text' => $this->text.$this->formatActions(),
        ]);

        return $this;
    }

    /**
     * TEMP : Format the actions as text
     * @return string
     */
    private function formatActions(): string {
        if (empty($this->actions)) {
            return '';
        }

        $result = "\n\nActions disponibles :";

        foreach ($this->actions as $action) {
            $result .= "\n• ".$action['title'];
            if (!is_null($action['description'])) {
                $result .= "\n".$action['description'];
            }
        }

        return $result;
    }
}
