<?php

/**
 * An example module to demonstrate middleware.
 */

declare(strict_types=1);

namespace ExampleNamespace;

require __DIR__ . '/ExampleModuleMiddleware.php';

return new ExampleModuleMiddleware();
