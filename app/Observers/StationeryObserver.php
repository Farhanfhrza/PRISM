<?php

namespace App\Observers;

use App\Models\Stationery;
use App\Models\Activity_log;
use Illuminate\Support\Facades\Auth; 

class StationeryObserver
{
    /**
     * Handle the Stationery "created" event.
     */
    private function logActivity(string $activityType, Stationery $stationery, string $description): void  
    {  
        Activity_log::create([  
            'user_id' => Auth::id(),  
            'activity_type' => $activityType,  
            'activity_category' => 'stationery',  
            'description' => $description,  
        ]);  
    }  

    public function created(Stationery $stationery): void
    {
        $username = Auth::user()->name;  
        $this->logActivity(  
            'create',  
            $stationery,  
            "User '{$username}' menambah alat tulis baru '{$stationery->name}'"  
        );
    }

    /**
     * Handle the Stationery "updated" event.
     */
    public function updated(Stationery $stationery): void
    {
        $username = Auth::user()->name;  
        $this->logActivity(  
            'update',  
            $stationery,  
            "User '{$username}' memperbarui alat tulis '{$stationery->name}'"  
        ); 
    }

    /**
     * Handle the Stationery "deleted" event.
     */
    public function deleted(Stationery $stationery): void
    {
        $username = Auth::user()->name;  
        $this->logActivity(  
            'delete',  
            $stationery,  
            "User '{$username}' menghapus alat tulis '{$stationery->name}'"  
        );
    }

    /**
     * Handle the Stationery "restored" event.
     */
    public function restored(Stationery $stationery): void
    {
        //
    }

    /**
     * Handle the Stationery "force deleted" event.
     */
    public function forceDeleted(Stationery $stationery): void
    {
        //
    }
}
