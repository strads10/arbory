<?php

namespace CubeSystems\Leaf\Admin;

use Closure;
use CubeSystems\Leaf\Admin\Widgets\Breadcrumbs;
use CubeSystems\Leaf\Admin\Module\ResourceRoutes;
use CubeSystems\Leaf\Admin\Module\Route;
use CubeSystems\Leaf\Services\AssetPipeline;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Controller;

class Module
{
    /**
     * @var Controller
     */
    protected $controller;

    /**
     * @var ResourceRoutes
     */
    protected $routes;

    /**
     * @var Breadcrumbs
     */
    protected $breadcrumbs;

    /**
     * @var AssetPipeline
     */
    protected $pipeline;

    /**
     * @param Controller $controller
     * @param AssetPipeline $pipeline
     */
    public function __construct( Controller $controller, AssetPipeline $pipeline )
    {
        $this->controller = $controller;
        $this->routes = new ResourceRoutes( $controller );
        $this->pipeline = $pipeline;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return class_basename( $this->controller );
    }

    /**
     * @return Breadcrumbs
     */
    public function breadcrumbs()
    {
        if( $this->breadcrumbs === null )
        {
            $this->breadcrumbs = new Breadcrumbs();
            $this->breadcrumbs->addItem( $this->name(), $this->url( 'index' ) );
        }

        return $this->breadcrumbs;
    }

    /**
     * @return AssetPipeline
     */
    public function assets()
    {
        return $this->pipeline;
    }

    /**
     * @param Model $model
     * @param Closure $closure
     * @return Form
     */
    public function form( Model $model, Closure $closure )
    {
        $form = new Form( $model, $closure );
        $form->setModule( $this );

        return $form;
    }

    /**
     * @param Model $model
     * @param Closure $builder
     * @return Grid
     */
    public function grid( Model $model, Closure $builder )
    {
        $grid = new Grid( $model, $builder );
        $grid->setModule( $this );

        return $grid;
    }

    /**
     * @return string
     */
    public function name()
    {
        return title_case( Route::getControllerSlug( get_class( $this->controller ) ) );
    }

    /**
     * @param $route
     * @param array $parameters
     * @return Route
     */
    public function url( $route, $parameters = [] )
    {
        return $this->routes->getUrl( $route, $parameters );
    }
}
