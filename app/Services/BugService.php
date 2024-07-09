<?php

namespace App\Services;

use App\Models\Bugs;
use App\Models\Projects;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class BugService
{
    public function validateProject($projectId)
    {
        $project = Projects::with('users')->find($projectId);
        if (!$project) {
            return ['error' => 'The project ID is invalid. There is no project found with the given ID.'];
        }
        if (Gate::denies('create-bug', $project)) {
            return ['error' => 'Only QA person with project access are able to create the bug.'];
        }
        return $project;
    }

    public function getAssociatedDevs($project)
    {
        return $project->users->filter(function ($user) {
            return $user->user_type == 'Developer';
        });
    }

    public function createBug($data, $projectId)
    {
 
        // Checking for the unique name of bug in a project
        $presentBug = Bugs::where('title', $data['title'])->where('project_id', $projectId)->first();
        if ($presentBug) {
            return ['error' => 'A bug with this title already exists in this project! Please choose a different title for the bug.'];
        }

        // Handle file upload
        $fileNameToStore = null;
        if (isset($data['screenshot'])) {
            $fileNameToStore = $this->handleFileUpload($data['screenshot']);
            if (isset($fileNameToStore['error'])) {
                return ['error' => 'Only JPG or PNG image extensions are allowed.'];
            }
        }

        $bug = new Bugs();
        $bug->title = $data['title'];
        $bug->description = $data['description'];
        $bug->deadline = $data['deadline'];
        $bug->screenshot = $fileNameToStore;
        $bug->type = $data['bugtype'];
        $bug->status = $data['bugstatus'];
        $bug->project_id = $projectId;
        $bug->qa_id = Auth::user()->id;
        $bug->developer_id = $data['assigntodev'];
        $bug->save();

        // $data['qa_id'] = Auth::user()->id;
        // $data['project_id'] = $projectId;
        // $data['screenshot'] = $fileNameToStore;
        // $bug = Bugs::create($data);
        return $bug;
    }

    public function handleFileUpload($file)
    {
        $fileNameWithExt = $file->getClientOriginalName();
        $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        if($extension != 'jpg' && $extension != 'png'){
            return ['error' => 'Only JPG or PNG image extensions are allowed.'];
        }
        $fileNameToStore = $fileName .'_'. time() .'.'. $extension;
        $file->storeAs('public/images', $fileNameToStore);        
        return $fileNameToStore;
    }

    public function editBug($id){
        $bug = Bugs::find($id);
        if(empty($bug)){
            return ['error' => 'No bug found for the given id!'];
        }

        $project = Projects::with('users')->where('id', $bug->project_id)->first();
        $associatedDevs = $this->getAssociatedDevs($project);

        if(Gate::denies('edit-bug', $bug)){
            return ['error' => 'You are not permitted to edit the bug; only the QA who created it can do so.'];
        }

        $bug->associatedDevs = $associatedDevs;
        return $bug;
    }
}
