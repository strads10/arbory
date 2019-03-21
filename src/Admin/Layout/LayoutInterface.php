<?php


namespace Arbory\Base\Admin\Layout;

use Closure;
use Illuminate\Contracts\Support\Renderable;

/**
 * Interface LayoutInterface
 *
 * @package Arbory\Base\Admin\Layout
 */
interface LayoutInterface extends Renderable, TransformableInterface
{
    /**
     * @param $content
     *
     * @return LayoutInterface
     */
    public function setContent($content): self;

    /**
     * @return mixed
     */
    public function getContent();

    /**
     * @param mixed $content
     *
     * @return mixed
     */
    public function contents($content);

    /**
     * @param LayoutInterface|TransformableInterface|string $layout
     *
     * @return mixed
     */
    public function use($layout);
}
