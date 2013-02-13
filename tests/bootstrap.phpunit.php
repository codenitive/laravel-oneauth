<?php

/*
|--------------------------------------------------------------------------
| Reuse Laravel PHPUnit bootstrap
|--------------------------------------------------------------------------
|
| It might not be ideal but solve issue by not implemented duplicated code 
| unless bundle has it's own customization for PHPUnit bootstrap.
|
*/

require dirname(__FILE__).'/../../../laravel/cli/tasks/test/phpunit.php';