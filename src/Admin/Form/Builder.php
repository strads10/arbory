<?php

namespace Arbory\Base\Admin\Form;

use Arbory\Base\Admin\Layout\WrappableInterface;
use Arbory\Base\Admin\Panels\Renderer;
use Arbory\Base\Admin\Form;
use Arbory\Base\Html\Elements\Content;
use Arbory\Base\Html\Elements\Element;
use Arbory\Base\Html\Html;
use Illuminate\Contracts\Support\Renderable;

/**
 * Class Builder
 * @package Arbory\Base\Admin\Form
 */
class Builder implements Renderable, WrappableInterface
{
    /**
     * @var string
     */
    protected $id = 'edit-resource';

    /**
     * @var Form
     */
    protected $form;

    /**
     * @var mixed
     */
    protected $content;

    /**
     * Builder constructor.
     *
     * @param Form $form
     */
    public function __construct(Form $form)
    {
        $this->form = $form;
    }

    /**
     * @param       $route
     * @param array $parameters
     *
     * @return string
     */
    public function url($route, $parameters = [])
    {
        return $this->form->getModule()->url($route, $parameters);
    }

    /**
     * @return Element
     */
    protected function form()
    {
        $form = Html::form()->addAttributes([
            'id' => $this->getId(),
            'class' => 'edit-resource',
            'novalidate' => 'novalidate',
            'enctype' => 'multipart/form-data',
            'accept-charset' => 'UTF-8',
            'method' => 'post',
            'action' => $this->form->getAction(),
            'data-remote' => 'true',
            'data-remote-validation' => 'true',
            'data-type' => 'json',
        ]);

        $form->append(csrf_field());

        if ($this->form->getModel()->getKey()) {
            $form->append(Html::input()->setName('_method')->setType('hidden')->setValue('PUT'));
        }

        return $form;
    }

    /**
     * @return Element
     */
    public function render()
    {
        $content = Html::div()->addClass('body');
        $content->append(new Content($this->getContent()));

        return $this->form()
            ->append($content);
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     *
     * @return Builder
     */
    public function setContent($content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     *
     * @return Builder
     */
    public function setId(?string $id): self
    {
        $this->id = $id;

        return $this;
    }
}
