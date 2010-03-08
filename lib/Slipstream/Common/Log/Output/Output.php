<?php
namespace Slipstream\Common\Log\Output;

interface Output extends \Slipstream\Common\Event\Subscriber {
	public function onSlipstreamLogOutput(\Slipstream\Common\Log\Event\OutputEventArgs $event);
}