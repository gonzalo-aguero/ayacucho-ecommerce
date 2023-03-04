<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ProductList extends Component
{
    public $productsLoaded = false;
    protected $listeners = ['setProductsLoaded'];

    public function setProductsLoaded()
    {
        $this->productsLoaded = true;
    }

    public function render()
    {
        return view('livewire.product-list');
    }
}
