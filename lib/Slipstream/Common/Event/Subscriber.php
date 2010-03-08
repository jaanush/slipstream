<?php
namespace Slipstream\Common\Event;

interface Subscriber
{
    /**
     * Returns an array of events that this subscriber listens
     *
     * @return array
     */
    public function getSubscribedEvents();
}