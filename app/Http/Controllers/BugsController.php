<?php

namespace App\Http\Controllers;

use App\Models\Bugs;
use App\Models\Projects;
use App\Services\BugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class BugsController extends Controller
{
 
    protected $bugService;

    public function __construct(BugService $bugService)
    {
        $this->middleware('auth');
        $this->bugService = $bugService;
    }

    public function index(){
        return redirect('/projects');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $projectId = $request->query('project_id');
        $project = $this->bugService->validateProject($projectId);
 
        // if(empty($projectId)) {
        //     return redirect('/projects')->with('error','Please provide the project ID so we can continue creating the bug in that project.');
        // }
        // $project = Projects::with('users')->where('id', $projectId)->first();

        // if(empty($project)) {
        //     return redirect('/projects')->with('error','The project ID is invalid. There is no project found with the given ID.');
        // }

        // if(Gate::denies('create-bug', $project)){
        //     return redirect('/projects/'.$projectId)->with('error','Only QA person with project access are able to create the bug.');
        // }
        if (isset($project['error'])) 
        {
            return redirect('/projects')->with('error', $project['error']);
        }
        session(['project_id' => $projectId]);

        // Only those developers who has the access of this specific project
        // $associatedDevs = $project->users->filter(function ($user) {
        //     return $user->user_type == 'Developer';
        // });
        $associatedDevs = $this->bugService->getAssociatedDevs($project);
        return view("bugs.create")->with('project', $project)->with('users', $associatedDevs);
    }
 

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title'=> 'required|min:5',
            'bugtype'=> 'required',
            'bugstatus'=> 'required',
            'deadline'=> 'required',
            'assigntodev'=> 'required',
        ]);

        $projectId = session('project_id');

        $bug = $this->bugService->createBug($request->all(), $projectId);

        if (isset($bug['error'])) {
            return redirect('/bugs/create?project_id=' . $projectId)->with('error', $bug['error']);
        }

        // $projectId = session('project_id');

        // if(empty($projectId)) {
        //     return redirect('/projects')->with('error','Please provide the project ID so we can continue creating the bug in that project.');
        // }

        // Checking for the unique name of bug in a project 
        // $presentBug = Bugs::where('title', $request->title)->where('project_id', $projectId)->first();
        // if($presentBug) {
        //     return redirect('/bugs/create?project_id='.$projectId)->with('error','A bug with this title already exists in this project! Please choose a different title for the bug.');
        // }

        // if($request->hasFile('screenshot')){
        //     $fileNameWithExt = $request->file('screenshot')->getClientOriginalName();
        //     $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
        //     $extension = $request->file('screenshot')->getClientOriginalExtension();
        //     if($extension != 'jpg' && $extension != 'png'){
        //         return redirect('/bugs/create?project_id='.$projectId)->with('error','Only JPG or PNG image extensions are allowed.');
        //     }
        //     $fileNameToStore = $fileName .'_'. time() .'.'. $extension;
        //     $request->file('screenshot')->storeAs('public/images', $fileNameToStore);  
        // }
         
      
        // $bug = new Bugs();
        // $bug->title = $request->title;
        // $bug->description = $request->description;
        // $bug->deadline = $request->deadline;
        // $bug->screenshot = $fileNameToStore ?? null;
        // $bug->type = $request->bugtype;
        // $bug->status = $request->bugstatus;
        // $bug->project_id = $projectId;
        // $bug->qa_id = Auth::user()->id;
        // $bug->developer_id = $request->assigntodev;
        // $bug->save();

        return redirect('/bugs/'.$bug->id)->with('success','The bug has been successfully reported!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    { 
        $bug = Bugs::with('project', 'developer', 'qa')->find($id);
        if(empty($bug)){
            return redirect('/projects')->with('error','No bug found for the given id.');
        }

        $project = Projects::with('users')->find($bug->project_id);
        
        if(Gate::denies('view-bug', $project)){
            return redirect('/projects')->with('error','You are trying to view a bug that does not belong to you.');
        }

        return view('bugs.show')->with('bug', $bug);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // $bug = Bugs::find($id);

        // if(empty($bug)){
        //     return redirect('/projects')->with('error','No bug found for the given id!');
        // }
        // $project = Projects::with('users')->where('id', $bug->project_id)->first();
        // $associatedDevs = $project->users->filter(function ($user) {
        //     return $user->user_type == 'Developer';
        // });

        // if(Gate::denies('edit-bug', $bug)){
        //     return redirect('/bugs/'.$bug->id)->with('error','You are not permitted to edit the bug; only the QA who created it can do so.');
        // }

        $bug = $this->bugService->editBug($id);
       
        if (isset($bug['error'])) {
            return redirect('/projects')->with('error', $bug['error']);
        }  
        return view('bugs.edit')->with('bug', $bug)->with('users', $bug->associatedDevs);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // update only the status if there is a developer
        if($request->bugStatusUpdate){
            $bug = Bugs::find($id);
            $bug->status = $request->bugStatusUpdate;
            $bug->save();
            return redirect('/bugs/'.$bug->id)->with('success','Bug Status has been updated successfully!');  
        }

        $this->validate($request, [
            'title'=> 'required|min:5',
            'bugtype'=> 'required',
            'bugstatus'=> 'required',
            'deadline'=> 'required',
            'assigntodev'=> 'required',
        ]);

        $bug = Bugs::find($id);

        // if($request->hasFile('screenshot')) {
        //     $fileNameWithExt = $request->file('screenshot')->getClientOriginalName();
        //     $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
        //     $extension = $request->file('screenshot')->getClientOriginalExtension();
        //     if($extension != 'jpg' && $extension != 'png'){
        //         return redirect('/bugs/'.$bug->id.'/edit')->with('error','Only JPG or PNG image extensions are allowed.');
        //     }
        //     $fileNameToStore = $fileName .'_'. time() .'.'. $extension;
        //     $request->file('screenshot')->storeAs('public/images', $fileNameToStore);  

        //     // also removing the old image
        //     if($bug->screenshot != null){
        //         Storage::delete('public/images/'. $bug->screenshot);
        //     }
        // }

        if($request->hasFile('screenshot')) {
            $fileNameToStore = $this->bugService->handleFileUpload($request->screenshot);
            if (isset($fileNameToStore['error'])) {
                return redirect('/bugs/'.$bug->id)->with('error',$fileNameToStore['error']);    
            }
            $bug->screenshot = $fileNameToStore;
        }

        $bug->title = $request->title;
        $bug->description = $request->description;
        $bug->deadline = $request->deadline;
        $bug->type = $request->bugtype;
        $bug->status = $request->bugstatus;
        $bug->developer_id = $request->assigntodev;
        $bug->save();

        return redirect('/bugs/'.$bug->id)->with('success','Bug has been updated successfully!');    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bug = Bugs::find($id);
       
        //  delete the image associated with the bug
        if($bug->screenshot != null){
            Storage::delete('public/images/'. $bug->screenshot);
        }

        $bug->delete();
        return redirect('/projects/'.$bug->project_id)->with('success', 'Bug has been deleted Successfully!');
    }
}
