<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class BugsProvider extends ServiceProvider
{
    
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Only qa can create bug if he has the access to project 
        Gate::define("create-bug", function ($user, $project) {        
            $allow = false;
            if ($user->user_type == "QA") {
                if($project->users && $project->users->count() > 0) {
                    for ($i=0; $i < count($project->users); $i++) { 
                        if($project->users[$i]->id == $user->id){
                            $allow = true;
                        }
                    }
                }
            }
            return $allow;
        });

        // Only those users can see the bug details 
        // if the user(qa || dev) have access to that project 
        // if user(manager) created this project 
        Gate::define("view-bug", function ($user, $project) {        
            $allow = false;  
             if ($user->user_type == "Manager") {
              return $user->id == $project->manager_id;
            }
            else if($user->user_type == "QA" || $user->user_type == "Developer") {
                if($project->users && $project->users->count() > 0) {
                    for ($i=0; $i < count($project->users); $i++) { 
                        if($project->users[$i]->id == $user->id){
                            $allow = true;
                        }
                    }
                }
            }
            return $allow;
        });

        // Qa can edit the project if he created it 
        Gate::define("edit-bug", function ($user, $bug) {
            if($user->user_type == 'QA'){
                return $user->id == $bug->qa_id;   
            }
            return false;
        });

    }
}
