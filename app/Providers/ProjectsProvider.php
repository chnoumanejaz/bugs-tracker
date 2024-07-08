<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class ProjectsProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Only users with the projects can access 
        Gate::define("view-project", function ($user, $project) {        
            if($user->user_type == "Manager") {
                return $user->id == $project->manager_id;
            }
            else if ($user->user_type == "QA" || $user->user_type == "Developer") {
                $allow = false;
                if($project->users && $project->users->count() > 0) {
                    for ($i=0; $i < count($project->users); $i++) { 
                        if($project->users[$i]->id == $user->id){
                            $allow = true;
                        }
                    }
                }
                return $allow;
            }
        });

        // Only manager can edit the project if it belongs to them 
        Gate::define("edit-project", function ($user, $project) {        
            return $user->id == $project->manager_id && $user->user_type == "Manager";
        });

        // Only manager can create the project 
        Gate::define("create-project", function ($user) {        
            return $user->user_type == "Manager";
        });
    }
}
