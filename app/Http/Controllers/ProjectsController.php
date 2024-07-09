<?php

namespace App\Http\Controllers;

use App\Models\Bugs;
use App\Models\Projects;
use App\Models\User;
use App\Models\UserProjects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ProjectsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->user_type === 'Manager') {
             $projects = Projects::where('manager_id', $user->id)->paginate(9);
        } else {
            // for the qa and developers (show only those projects which are assigned to that user)
            $projects = Projects::whereHas('users', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->paginate(9);
         }
    
        return view('projects.index')->with('projects', $projects);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Users to show in the form - all the available dev's and qa's
        $users = User::whereIn('user_type', ['Developer', 'QA'])->get();
        if(Gate::denies('create-project')){
            return redirect('/projects')->with('error','You are not allowed to create the project only managers can do that.');
        }
        return view('projects.create')->with('users', $users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title'=> 'required|min:5',
            'description' => 'required|min:15',
        ]);

        $project = new Projects();
        $project->title = $request->title;
        $project->description = $request->description;
        $project->manager_id = Auth::user()->id;
        $project->save();

        // if any dev or qa selected by manager then storing there id's in user_projects table
        if($request->assignto && count($request->assignto) > 0){
            for( $i = 0; $i < count($request->assignto); $i++ ){
                $userproject = new UserProjects();
                $userproject->user_id = $request->assignto[$i];
                $userproject->project_id = $project->id;
                $userproject->save();
            }
        }
        return redirect('/projects')->with('success','Project ('. $project->title . ') has been created successfully!');     
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project = Projects::with('users')->findOrFail($id);
                 
        // if(empty($project)){
        //     return redirect('/projects')->with('error','No project found for the given id!');
        // }
        
        if(Gate::denies('view-project', $project)){
            return redirect('/projects')->with('error','You are not allowed to access the project which does not belongs to you.');
        }

        $bugs = Bugs::with('developer', 'qa')->where('project_id', $project->id)->get();
        $project->bugs = $bugs;

        return view('projects.show')->with('project', $project);  
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $project = Projects::with('users')->find($id);
        
        if(empty($project)){
            return redirect('/projects')->with('error','No project found for the given id.');
        }
        if(Gate::denies('edit-project', $project)){
            return redirect('/projects/'.$project->id)->with('error','You are not allowed to edit the project, only the manager who created it can do so.');
        }
        $availableUsers = User::whereIn('user_type', ['Developer', 'QA'])->get();
        return view('projects.edit')->with('project', $project)->with('availableUsers', $availableUsers);
    }

    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'title'=> 'required|min:5',
            'description' => 'required|min:15',
        ]);

        // Update the project
        $project = Projects::with('users')->find($id);
        $project->title = $request->title;
        $project->description = $request->description;
        $project->save();

        // users already have access 
        $currentUsers = $project->users->pluck('id')->toArray();
        
        // new users after update
        $newUsers = $request->assignto ?? [];
        
        $usersToRemove = array_values(array_diff($currentUsers, $newUsers));
        $usersToAdd = array_values(array_diff($newUsers, $currentUsers));
 
        // Remove users not in updated list
        if (!empty($usersToRemove)) {
            UserProjects::where('project_id', $id)
                ->whereIn('user_id', $usersToRemove)
                ->delete();
        }

        // Add new users to the project
        if (!empty($usersToAdd)) {
            foreach ($usersToAdd as $userId) {
                $userproject = new UserProjects();
                $userproject->user_id = $userId;
                $userproject->project_id = $id;
                $userproject->save();
            }
        }

        return redirect('/projects/' . $id)->with('success', 'Project has been updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(string $id)
    // {
    //     $project = Projects::with('users')->find($id);
    //     $bugs = Bugs::where('project_id', $project->id)->get();
             
    //     $project->delete();

    //     // Removing all the bugs on deletion of project
    //     if($bugs && count($bugs) > 0){
    //         for($i = 0; $i < count($bugs); $i++){
    //             $bug = Bugs::find($bugs[$i]->id);
    //              //  delete the image associated with the bug
    //             if($bug->screenshot != null){
    //                 Storage::delete('public/images/'. $bug->screenshot);
    //             }
    //             $bug->delete();
    //         }
    //     }

    //     // Removing the access for all the users who has access to this project
    //     if($project->users && count($project->users) > 0){
    //         for($i = 0; $i < count($project->users); $i++){
    //             $user = UserProjects::where('user_id' , $project->users[$i]->id)->where('project_id', $id);
    //             $user->delete();
    //         }
    //     }
    //     return redirect('/projects')->with('success', 'The project and all associated bugs have been successfully deleted! Access for associated members has been removed as well.');
    // }

    public function destroy(string $id)
    {
        // Retrieve the project with its users
        $project = Projects::with('users')->find($id);

        if (!$project) {
            return redirect('/projects')->with('error', 'Project not found.');
        }

        // Retrieve all bugs associated with the project
        $bugs = Bugs::where('project_id', $project->id)->get();

        // Delete the project
        $project->delete();

        // Delete bugs and associated images
        foreach ($bugs as $bug) {
            if ($bug->screenshot != null) {
                Storage::delete('public/images/' . $bug->screenshot);
            }
            $bug->delete();
        }

        // Remove access for users associated with this project
        foreach ($project->users as $user) {
            UserProjects::where('user_id', $user->id)->where('project_id', $project->id)->delete();
        }

        return redirect('/projects')->with('success', 'The project and all associated bugs have been successfully deleted! Access for associated members has been removed as well.');
    }

}
