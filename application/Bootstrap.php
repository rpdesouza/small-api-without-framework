<?php
System\IoC::init(System\Config::getInstance( ENVIRONMENT ));
$application = System\IoC::getInstance('Application');
$application->run();