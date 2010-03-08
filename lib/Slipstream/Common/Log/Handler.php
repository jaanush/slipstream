<?php
/**
 * @author jaanush
 *
 */
namespace Slipstream\Common\Log;
use Slipstream\Common\Log\Event;

/**
 * @author jaanush
 *
 */
class Handler implements \Slipstream\Common\Event\Subscriber {
	private $_eventManager;
	private $_configuration;
	private $_queue=array();
	private $_output;
	/**
	 * Flag whether we are logging from within the exception handler
	 *
	 * @var boolean
	 */
	protected $inExceptionHandler = false;

	/**
	 * Flag whether to throw PHP errors that have been converted to ErrorExceptions
	 *
	 * @var boolean
	 */
	protected $throwErrorExceptions = true;

	/**
	 * Flag whether to convert PHP assertion errors to Exceptions
	 *
	 * @var boolean
	 */
	protected $convertAssertionErrorsToExceptions = true;

	/**
	 * Flag whether to throw PHP assertion errors that have been converted to Exceptions
	 *
	 * @var boolean
	 */
	protected $throwAssertionExceptions = false;

	/**
	 * Class Injector
	 *
	 * @var object
	 */
	protected $injector = false;

	public function __construct(
			\Slipstream\Common\ConfigurationInterface $configuration,
			\Slipstream\Common\Injector\Injector $injector
		){
		$this->injector=$injector;
		$this->_configuration=$configuration;
		$this->registerErrorHandler($this->_configuration->getThrowErrorExceptions());
		$this->registerExceptionHandler();
		$this->registerAssertionHandler(
			$this->_configuration->getConvertAssertionErrorsToExceptions(),
			$this->_configuration->getThrowAssertionExceptions()
		);
		$this->log('Starting log output');
	}

	public function setEventManager(\Slipstream\Common\Event\Manager $eventManager){
		$this->_eventManager=$eventManager;
		$this->_eventManager->addEventSubscriber($this);
		$output=$this->_configuration->getLogOutput();
		foreach($output as $className){
			$this->_output[]=$this->injector->get($className);
		}
		//$this->_output[]=new Output\FirePHPLogger();
		foreach($this->_output as $obj){
			$this->_eventManager->addEventSubscriber($obj);
		}
		$this->flush();
	}

	private function dispatch($event){
		if(!isset($this->_eventManager)){
			$this->_queue[]=$event;
		} else {
			$this->_eventManager->dispatchEvent('onSlipstreamLogOutput',$event);
		}
	}

	private function flush(){
		$this->group('Flushing startup events');
		while($event=array_shift($this->_queue)){
			$this->_eventManager->dispatchEvent('onSlipstreamLogOutput',$event);
		}
		$this->groupEnd();
	}

	public function getSubscribedEvents(){
		return array(
			'onSlipstreamLogLog',
			'onSlipstreamLogInfo',
			'onSlipstreamLogWarn',
			'onSlipstreamLogError',
			'onSlipstreamLogtrace',
			'onSlipstreamLogTable',
			'onSlipstreamLogGroup',
			'onSlipstreamLogGroupEnd');
	}

	public function log($data,$label=null){
		$this->dispatch(new \Slipstream\Common\Log\Event\OutputEventArgs($data,'LOG',$label));
	}

	public function info($data,$label=null){
		$this->dispatch(new \Slipstream\Common\Log\Event\OutputEventArgs($data,'INFO',$label));
	}

	public function warn($data,$label=null){
		$this->dispatch(new \Slipstream\Common\Log\Event\OutputEventArgs($data,'WARN',$label));
	}

	public function group($data, $options=null) {
		if(!$data) {
			throw new \Exception('You must specify a label for the group!');
		}

		if($options) {
			if(!is_array($options)) {
				throw new \Exception('Options must be defined as an array!');
			}
			if(array_key_exists('Collapsed', $options)) {
				$options['Collapsed'] = ($options['Collapsed'])?'true':'false';
			}
		}

		$this->dispatch(new \Slipstream\Common\Log\Event\OutputEventArgs(null,'GROUP_START',$data));
	}

	public function groupEnd(){
		$this->dispatch(new \Slipstream\Common\Log\Event\OutputEventArgs(null,'GROUP_END'));
	}


	/**
	 * Register Slipstream as your error handler
	 *
	 * Will throw exceptions for each php error.
	 *
	 * @return mixed Returns a string containing the previously defined error handler (if any)
	 */
	public function registerErrorHandler($throwErrorExceptions=true)
	{
		//NOTE: The following errors will not be caught by this error handler:
		//      E_ERROR, E_PARSE, E_CORE_ERROR,
		//      E_CORE_WARNING, E_COMPILE_ERROR,
		//      E_COMPILE_WARNING, E_STRICT

		$this->throwErrorExceptions = $throwErrorExceptions;

		return set_error_handler(array($this,'errorHandler'));
	}

	/**
	 * Slipstream's error handler
	 *
	 * Throws exception for each php error that will occur.
	 *
	 * @param int $errno
	 * @param string $errstr
	 * @param string $errfile
	 * @param int $errline
	 * @param array $errcontext
	 */
	public function errorHandler($errno, $errstr, $errfile, $errline, $errcontext)
	{
		// Don't throw exception if error reporting is switched off
		if (error_reporting() == 0) {
			return;
		}
		// Only throw exceptions for errors we are asking for
		if (error_reporting() & $errno) {

			$exception = new \ErrorException($errstr, 0, $errno, $errfile, $errline);
			if($this->throwErrorExceptions) {
				throw $exception;
			} else {
				$this->dispatch(new \Slipstream\Common\Log\Event\OutputEventArgs($exception));
				//$this->fb($exception);
			}
		}
	}

	/**
	 * Register Slipstream as your exception handler
	 *
	 * @return mixed Returns the name of the previously defined exception handler,
	 *               or NULL on error.
	 *               If no previous handler was defined, NULL is also returned.
	 */
	public function registerExceptionHandler()
	{
		return set_exception_handler(array($this,'exceptionHandler'));
	}

	/**
	 * Slipstream's exception handler
	 *
	 * Logs all exceptions to your firebug console and then stops the script.
	 *
	 * @param Exception $Exception
	 * @throws Exception
	 */
	function exceptionHandler($exception) {

		$this->inExceptionHandler = true;

		header('HTTP/1.1 500 Internal Server Error');

		$this->dispatch(new \Slipstream\Common\Log\Event\OutputEventArgs($exception));
		//$this->fb($Exception);

		$this->inExceptionHandler = false;
	}

	/**
	 * Register Slipstream driver as your assert callback
	 *
	 * @param boolean $convertAssertionErrorsToExceptions
	 * @param boolean $throwAssertionExceptions
	 * @return mixed Returns the original setting or FALSE on errors
	 */
	public function registerAssertionHandler($convertAssertionErrorsToExceptions=true, $throwAssertionExceptions=false)
	{
		$this->convertAssertionErrorsToExceptions = $convertAssertionErrorsToExceptions;
		$this->throwAssertionExceptions = $throwAssertionExceptions;

		if($throwAssertionExceptions && !$convertAssertionErrorsToExceptions) {
			throw $this->newException('Cannot throw assertion exceptions as assertion errors are not being converted to exceptions!');
		}

		return assert_options(ASSERT_CALLBACK, array($this, 'assertionHandler'));
	}

	/**
	 * Slipstream's assertion handler
	 *
	 * Logs all assertions to your firebug console and then stops the script.
	 *
	 * @param string $file File source of assertion
	 * @param int    $line Line source of assertion
	 * @param mixed  $code Assertion code
	 */
	public function assertionHandler($file, $line, $code)
	{

		if($this->convertAssertionErrorsToExceptions) {

			$exception = new \ErrorException('Assertion Failed - Code[ '.$code.' ]', 0, null, $file, $line);

			if($this->throwAssertionExceptions) {
				throw $exception;
			} else {
				$this->fb($exception);
			}

		} else {

			$this->fb($code, 'Assertion Failed', FirePHP::ERROR, array('File'=>$file,'Line'=>$line));

		}
	}
}