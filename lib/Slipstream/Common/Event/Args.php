<?php
namespace Slipstream\Common\Event;

class Args
{
    private static $_emptyEventArgsInstance;

    public static function getEmptyInstance()
    {
        if ( ! self::$_emptyEventArgsInstance) {
            self::$_emptyEventArgsInstance = new Args;
        }

        return self::$_emptyEventArgsInstance;
    }
}