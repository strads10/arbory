<?php

namespace Arbory\Base\Http\Middleware;

use Arbory\Base\Repositories\NodesRepository;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Closure;

class ArborySetLocaleMiddleware
{
    /**
     * @var NodesRepository
     */
    private $nodesRepository;

    /**
     * @var Application
     */
    private $app;

    /**
     * ArborySetLocaleMiddleware constructor.
     * @param NodesRepository $nodesRepository
     * @param Application $app
     */
    public function __construct(NodesRepository $nodesRepository, Application $app)
    {
        $this->nodesRepository = $nodesRepository;
        $this->app = $app;
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

        return $next($request);
    }

    /**
     * @return string
     */
    private function defaultLocale(): string
    {
        return config('arbory.locale') ?: config('app.locale');
    }
}
