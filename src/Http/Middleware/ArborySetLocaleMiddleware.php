<?php

namespace Arbory\Base\Http\Middleware;

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Config\Repository as ConfigurationRepository;

class ArborySetLocaleMiddleware
{
    /**
     * @var Application
     */
    private $app;

    /**
     * @var ConfigurationRepository
     */
    private $config;

    /**
     * ArborySetLocaleMiddleware constructor.
     * @param Application $app
     * @param ConfigurationRepository $configurationRepository
     */
    public function __construct(
        Application $app,
        ConfigurationRepository $configurationRepository
    ) {
        $this->app = $app;
        $this->config = $configurationRepository;
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $this->app->setLocale($this->defaultLocale());
        $request->setLocale($this->defaultLocale());

        $resourceLocale = $this->config->get('arbory.resource_locale');
        if ($resourceLocale) {
            $this->config->set('translatable.locale', $resourceLocale);
        }

        return $next($request);
    }

    /**
     * @return string
     */
    private function defaultLocale(): string
    {
        return $this->config->get('arbory.locale') ?: $this->config->get('app.locale');
    }
}
