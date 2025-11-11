<?php

namespace App\Observers;

use App\Models\Materia;

class MateriaObserver
{
    /**
     * Handle the Materia "created" event.
     */
    public function created(Materia $materia): void
    {
        //
    }

    /**
     * Handle the Materia "updated" event.
     */
    public function updated(Materia $materia): void
    {
        //
    }

    /**
     * Handle the Materia "deleted" event.
     */
    public function deleted(Materia $materia): void
    {
        //
    }

    /**
     * Handle the Materia "restored" event.
     */
    public function restored(Materia $materia): void
    {
        //
    }

    /**
     * Handle the Materia "force deleted" event.
     */
    public function forceDeleted(Materia $materia): void
    {
        //
    }
}
