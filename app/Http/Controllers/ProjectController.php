<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Client;
use App\Models\ProductMovement;
use DB;
use Auth;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function launchProject() {

        $data['clients'] = Client::all();

        $data['projects'] = DB::table('projects')
        ->leftjoin('clients','projects.clientID','=','clients.id')
        ->select('*','projects.id as pid','projects.location as plocation')
        ->get();

        return view('projects.project', $data);
    }

    public function saveProject(Request $request) {

        $this->validate( $request, [
            'projectName' => 'required|string',
            'clientName'  => 'required|numeric',
            //'location'    => 'required|string',
        ]);
        
        $exists = Project::where('projectName',$request->projectName)
                ->where('clientID',$request->clientName)
                ->where('location',$request->location)
                ->exists();
        
        if($exists) {

            return back()->with('error_message','Record exists!');

        } else {

        $save = new Project;
        $save->projectName = $request->projectName;
        $save->clientID    = $request->clientName;
        $save->location    = $request->location;

        $save->save();

        }

        return back()->with('success','Project successfully added!');
    }

     //delete record
     public function deleteProject($id) {

        if(ProductMovement::where('projectID',$id)->exists()) {
            return back()->with('error_message','Cannot delete, record is in use');
        } 
        else {
            $project = Project::find($id);
            $project->delete();
        }
        return back()->with('success','Successfully deleted!');
    }

    //edit record
    public function editProject($id) {
        //dd(base64_decode($id));
       // $data['id']=base64_decode($id);
        $data['clients'] = Client::all();
        $data['projects'] = Project::find(base64_decode($id));

        return view('projects.editproject',$data);
    }

    //update
    public function updateProject(Request $request) {

        Project::where('id',$request->projectID)
        ->update(['projectName'=>$request->projectName, 'clientID'=>$request->clientName, 'location'=>$request->location]);

         return redirect()->route('launchProject')->with('success','Successfully updated!');
    }
    //
}
