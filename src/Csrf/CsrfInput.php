<?php

namespace Jnjxp\Vk\Csrf;

class CsrfInput
{
    protected $name = '__csrf';

    protected $value;

    public function __construct(string $value, string $name = '__csrf')
    {
        $this->value = $value;
        $this->name = $name;
    }

    public function __toString()
    {
        return sprintf(
            '<input type="hidden" name="%s" value="%s" />',
            $this->name,
            htmlspecialchars($this->value, ENT_QUOTES, 'UTF-8')
        );
    }
}
