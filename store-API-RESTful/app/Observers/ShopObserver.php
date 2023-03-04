<?php

namespace App\Observers;

use App\Models\Shop;

class ShopObserver
{
    /**
     * Handle the Shop "created" event.
     */
    public function created(Shop $shop): void
    {
        //
    }

    /**
     * Handle the Shop "updated" event.
     */
    public function updated(Shop $shop): void
    {
        //
    }

    /**
     * Handle the Shop "deleted" event.
     */
    public function deleted(Shop $shop): void
    {
        //
    }

    /**
     * Handle the Shop "restored" event.
     */
    public function restored(Shop $shop): void
    {
        //
    }

    /**
     * Handle the Shop "force deleted" event.
     */
    public function forceDeleted(Shop $shop): void
    {
        //
    }

    /**
     * Handle the Shop "deleting" event.
     *
     * @param \App\Models\Shop $post
     * @return void
     */
    public function deleting(Shop $shop): void
    {
        if($shop) $shop->products()->detach();
    }
}
