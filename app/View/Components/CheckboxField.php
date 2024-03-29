<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CheckboxField extends Component
{
    /** @var string */
    public $name;

    /** @var string */
    public $label;

    /** @var int|mixed */
    public $value;

    /** @var bool */
    public $checked;

    /**
     * Create the component instance.
     *
     * @param  int  $value
     */
    public function __construct(string $name, string $label = '', $value = 1, bool $checked = false)
    {
        $this->name = $name;
        $this->label = $label;
        $this->value = $value;
        $this->checked = $checked;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.checkbox-field');
    }
}
