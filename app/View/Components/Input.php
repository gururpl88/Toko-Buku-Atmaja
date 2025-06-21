<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Input extends Component
{
    public $name, $label, $type, $value, $required;
    public function __construct($name, $label = '', $type = 'text', $value = '', $required = false)
    {
        $this->name = $name;
        $this->label = $label ?: ucwords(str_replace('_', ' ', $name));
        $this->type = $type;
        $this->value = $value;
        $this->required = $required;
    }
    
    public function render(): View|Closure|string
    {
        return view('components.input');
    }
}
