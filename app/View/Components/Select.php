<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Select extends Component
{
    public $name;
    public $label;
    public $options;
    public $selected;
    public $required;

    public function __construct($name, $label, $options = [], $selected = null, $required = false)
    {
        $this->name = $name;
        $this->label = $label;
        $this->options = $options;
        $this->selected = $selected;
        $this->required = $required;
    }
    
    public function render(): View|Closure|string
    {
        return view('components.select');
    }
}
