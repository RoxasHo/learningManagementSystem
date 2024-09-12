<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Content extends Component
{
    /**
     * Create a new component instance.
     */
    public $content;
    public $materials;
    public $quizzs;
    public $selectedType;
    public function mount()
    {
        $this->content = "Default content";
    }

    public function updateContent($newContent)
    {
        $this->content = $newContent;
    }

    public function render()
    {
        return view('livewire.content');
    }
}
