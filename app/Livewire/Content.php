<?php

namespace App\Livewire;

use Livewire\Component;

class Content extends Component
{
    public $content;
    public $materials;
    public $quizzs;
    public $selectedType;
    public function mount($new_materials,$new_quizzs,$new_selectedType)
    {
        $this->content = "Default content";
        $this->materials=$new_materials;
        $this->quizzs=$new_quizzs;
        $this->selectedType=$new_selectedType;
    }

    public function updateContent($newContent)
    {
        $this->content = $newContent;
    }
    public function render()
    {
        return view('livewire.content',['materials'=>$this->materials,'quizzs'=>$this->quizzs]);
    }
}
