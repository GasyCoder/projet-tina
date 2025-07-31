<?php
namespace App\Observers;

use App\Models\Dechargement;
use App\Models\StockRetour;

class DechargementObserver
{
    public function created(Dechargement $dechargement): void
    {
        if ($dechargement->type === 'retour') {
            StockRetour::creerDepuisDecharement($dechargement);
        }
    }

    public function updated(Dechargement $dechargement): void
    {
        // Si devient retour
        if ($dechargement->type === 'retour' && $dechargement->getOriginal('type') !== 'retour') {
            StockRetour::creerDepuisDecharement($dechargement);
        }
        
        // Si était retour mais plus maintenant
        if ($dechargement->getOriginal('type') === 'retour' && $dechargement->type !== 'retour') {
            StockRetour::where('dechargement_id', $dechargement->id)->delete();
        }
    }

    public function deleted(Dechargement $dechargement): void
    {
        if ($dechargement->type === 'retour') {
            StockRetour::where('dechargement_id', $dechargement->id)->delete();
        }
    }
}
