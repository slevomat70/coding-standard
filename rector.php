<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Set\ValueObject\DowngradeLevelSetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Rector\DowngradePhp70\Rector\String_\DowngradeGeneratedScalarTypesRector;

return static function (ContainerConfigurator $containerConfigurator) {
	// get parameters
	$parameters = $containerConfigurator->parameters();

	$parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_70);

	// paths to refactor; solid alternative to CLI arguments
	$parameters->set(Option::PATHS, [
		__DIR__.'/SlevomatCodingStandard',
		__DIR__.'/tests',
		__DIR__.'/autoload-bootstrap.php'
	]);

	// here we can define, what sets of rules will be applied
	$containerConfigurator->import(DowngradeLevelSetList::DOWN_TO_PHP_70);

	$parameters->set(Option::SKIP, [
		__DIR__.'/tests/Helpers/data',
		__DIR__.'/tests/Sniffs/Arrays/data',
		__DIR__.'/tests/Sniffs/Classes/data',
		__DIR__.'/tests/Sniffs/Commenting/data',
		__DIR__.'/tests/Sniffs/ControlStructures/data',
		__DIR__.'/tests/Sniffs/Exceptions/data',
		__DIR__.'/tests/Sniffs/Files/data',
		__DIR__.'/tests/Sniffs/Functions/data',
		__DIR__.'/tests/Sniffs/Namespaces/data',
		__DIR__.'/tests/Sniffs/Numbers/data',
		__DIR__.'/tests/Sniffs/Operators/data',
		__DIR__.'/tests/Sniffs/PHP/data',
		__DIR__.'/tests/Sniffs/TypeHints/data',
		__DIR__.'/tests/Sniffs/Variables/data',
		__DIR__.'/tests/Sniffs/Whitespaces/data',

		DowngradeGeneratedScalarTypesRector::class,
	]);

	$containerConfigurator->bootstrapFiles([
		__DIR__.'/tests/bootstrap.php',
	]);
};
