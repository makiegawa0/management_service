<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;

class FieldWrapper extends Component
{
    /** @var string */
    public $name;

    /** @var string */
    public $label;

    /** @var string */
    public $wrapperClass;

    /** @var bool */
    public $required;

    /**
     * Create the component instance.
     */
    public function __construct(string $name, string $label, string $wrapperClass = '', bool $required = false)
    {
        $this->name = $name;
        $this->label = $label;
        $this->wrapperClass = $wrapperClass;
        $this->required = $required;
    }

    public function errorClass(string $field): string
    {
        if ($errors = session('errors')) {
            return $errors->first($field) ? ' has-error' : '';
        }

        return '';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.field-wrapper');
    }
}
