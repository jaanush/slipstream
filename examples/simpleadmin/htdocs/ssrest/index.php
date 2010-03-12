<?php
ob_start();
$ss_timer=microtime(true);
require_once('../../conf/global.conf.php');
//create_container();

k()
  // Use container for wiring of components
  ->setComponentCreator(new \Slipstream\Common\Injector\BucketAdapter($i=create_container()))
  // Location of debug logging
  ->setLog($debug_log_path)
  // Enable/disable in-browser debugging
  ->setDebug($debug_enabled)
  // Dispatch request
  ->run('Slipstream\JsonRest\Component\Root')
  ->out();
$i->get('Slipstream\Common\Log\Handler')->log(microtime(true)-$ss_timer,'Execution time');
ob_end_flush ();
?>