<?php

/**
 * An example module to demonstrate middleware.
 */

declare(strict_types=1);

namespace ExampleNamespace;

use Fisharebest\Webtrees\Http\Exceptions\HttpAccessDeniedException;
use Fisharebest\Webtrees\Module\AbstractModule;
use Fisharebest\Webtrees\Module\ModuleCustomInterface;
use Fisharebest\Webtrees\Module\ModuleCustomTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function in_array;

class ExampleModuleMiddleware extends AbstractModule implements ModuleCustomInterface, MiddlewareInterface {
    use ModuleCustomTrait;

    // List of unwanted IP addresses.
    private const BAD_IP_ADDRESSES = [
        '127.0.0.1',
    ];

    /**
     * How should this module be identified in the control panel, etc.?
     *
     * @return string
     */
    public function title(): string
    {
        return 'Example module';
    }

    /**
     * Code here is executed before and after we process the request/response.
     * We can block access by throwing an exception.
     *
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // Code here is executed before we process the request/response.
        $ip_address = $request->getAttribute('client-ip');
        if (in_array($ip_address, self::BAD_IP_ADDRESSES, true)) {
            // Throwing an Http exception creates a friendly error page.
            throw new HttpAccessDeniedException('IP address is not allowed: ' . $ip_address);
        }

        // Generate the response.
        $response = $handler->handle($request);

        // Code here is executed after we process the request/response.
        // We can also modify the response.
        $response = $response->withHeader('X-Powered-By', 'Fish');

        return $response;
    }
}
