<?php
namespace Slipstream\Common\Event;

class Manager implements Subscriber{
	protected $_listners=array();
	protected $_events=array();

    public function dispatchEvent($eventName, Args $eventArgs = null)
    {
        if (isset($this->_listeners[$eventName])) {
            $eventArgs = $eventArgs === null ? Args::getEmptyInstance() : $eventArgs;

            foreach ($this->_listeners[$eventName] as $listener) {
                $listener->$eventName($eventArgs);
            }
        }
    }

    public function getListeners($event = null)
    {
        return $event ? $this->_listeners[$event] : $this->_listeners;
    }

    public function hasListeners($event)
    {
        return isset($this->_listeners[$event]) && $this->_listeners[$event];
    }

    public function addEventListener($events, $listener)
    {
        // Picks the hash code related to that listener
        $hash = spl_object_hash($listener);

        foreach ((array) $events as $event) {
            // Overrides listener if a previous one was associated already
            // Prevents duplicate listeners on same event (same instance only)
            $this->_listeners[$event][$hash] = $listener;
        }
    }

    public function addEventSubscriber(Subscriber $subscriber)
    {
        $this->addEventListener($subscriber->getSubscribedEvents(), $subscriber);
    }

    public function removeEventListener($events, $listener)
    {
        // Picks the hash code related to that listener
        $hash = spl_object_hash($listener);

        foreach ((array) $events as $event) {
            // Check if actually have this listener associated
            if (isset($this->_listeners[$event][$hash])) {
                unset($this->_listeners[$event][$hash]);
            }
        }
    }

    public function __call($eventName,$args){
    	$this->dispatchEvent($eventName, $args[0]);
    }

	public function getSubscribedEvents(){
		return $this->$_events;
	}
}