<?php
// Put default application configuration in this file.
// Individual sites (servers) can override it.
require_once 'Bucket.php';
date_default_timezone_set('Europe/Stockholm');


function create_container() {
	$factory = new \Slipstream\Common\Injector\Factory();
	$container = new \Slipstream\Common\Injector\BucketInjector($factory);
	$container->registerImplementation('Slipstream\Common\ConfigurationInterface', 'Slipstream\JsonRest\Configuration');
	$container->set($cf=createConfiguration($container),'Slipstream\JsonRest\Configuration');
	$container->set($container,'Slipstream\Common\Injector\BucketInjector');
	$container->registerImplementation('Slipstream\Common\Injector\Injector', 'Slipstream\Common\Injector\BucketInjector');
	$factory->setInjector($container);
	//print_r($container->get('\Slipstream\Common\ConfigurationInterface'));
	//die();
	//$factory->setEntityManager(createEntityManager());
	$log=$container->get('Slipstream\Common\Log\Handler');
	//$log->log($cf,'Configuration');
	$log->log('Logger online');
	/*
	 $factory->template_dir = realpath(dirname(__FILE__) . '/../templates');
	 $factory->pdo_dsn = 'mysql:host=localhost;dbname=test';
	 $factory->pdo_username = 'root';
	 */
	return $container;
}

function createConfiguration($container){
	$config= $container->get('Slipstream\Common\ConfigurationInterface');
	//$config= new \Slipstream\JsonRest\Configuration();
	require_once('domaindef.php');
	$config->setDomainDefinition($domaindef);
	$config->setJsonRestuploadFileManager(array(
	'Image'=>array(
		'class'=>'\Slipstream\JsonRest\Component\Upload\Image',
		'basepath'=>SS_SITE_HTDOCS.'/images/',
		'baseurl'=>'/images/'),
	'Feature'=>array(
		'class'=>'\Slipstream\JsonRest\Component\Upload\Image',
		'basepath'=>SS_SITE_HTDOCS.'/features/',
		'baseurl'=>'/features/')
	));
	$config->setFileLoggerPath(SS_SITE_ROOT.'/debug.log');
	return $config;
}

function createEntityManager(){
	$config = new \Doctrine\ORM\Configuration;
	$cache = new \Doctrine\Common\Cache\ApcCache;
	$config->setMetadataCacheImpl($cache);
	$config->setQueryCacheImpl($cache);
	$config->setProxyDir(dirname(dirname(__DIR__)).'/demo/Proxies');
	$config->setProxyNamespace('\demo\Proxies');
	$config->setAutoGenerateProxyClasses(true);
	$connectionOptions = array(
	'dbname' => 'ssdev',
    'user' => 'ssdev',
    'password' => 'ssdev',
	//    'host' => 'localhost',
	'unix_socket' => '/var/run/mysqld/mysqld.sock',
    'driver' => 'pdo_mysql'
	);
	return \Doctrine\ORM\EntityManager::create($connectionOptions, $config);

}
