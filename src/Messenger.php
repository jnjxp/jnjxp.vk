<?php
// @codingStandardsIgnoreFile

class Messenger extends ArrayObject
{
    const SESSION_SEGMENT = 'messages';
    const SESSION_KEY     = 'messenger';

    const MESSAGE_TXT     = 'text';
    const MESSAGE_STATUS  = 'status';

    const STATUS_SUCCESS  = 'success';
    const STATUS_ERROR    = 'error';
    const STATUS_WARNING  = 'warning';
    const STATUS_INFO     = 'info';

    public function __construct(Session $session)
    {
        $session->getSegment(self::SESSION_SEGMENT)
            ->setFlash(self::SESSION_KEY, $this);
    }

    public function add(string $text, string $status = self::STATUS_INFO)
    {
        $this->append(
            [
                SELF::MESSAGE_TXT => $text,
                SELF::MESSAGE_STATUS => $status
            ]
        );
    }

    public function success(string $message)
    {
        $this->add($message, self::STATUS_SUCCESS);
    }

    public function error(string $message)
    {
        $this->add($message, self::STATUS_ERROR);
    }

    public function info(string $message)
    {
        $this->add($message, self::STATUS_INFO);
    }

    public function warning(string $message)
    {
        $this->add($message, self::STATUS_WARNING);
    }

}
