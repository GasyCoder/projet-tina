<?php

namespace App\Livewire\Stocks;

use Livewire\Component;

class StockIndex extends Component
{
    public $activeTab = 'ventes'; // Valeur par défaut

    public function render()
    {
        return view('livewire.stocks.stock-index');
    }
}
