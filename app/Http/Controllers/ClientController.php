<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Project;
use DB;
use Auth;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function launchClient() {

        $data['clients'] = Client::all();

        return view('clients.client',$data);
    }

    public function saveClient(Request $request) {

        $this->validate($request,[
            'clientName' => 'required|string',
            //'location'   => 'required|string',
            //'address'    => 'required|string',
        ]);
        
        $exists = Client::where('clientName',$request->clientName)
        ->where('location',$request->location)
        ->where('address',$request->address)
        ->exists();

        if($exists) {

            return back()->with('error_message','Record exists!');

        } else {

        $save = new Client;
        $save->clientName = $request->clientName;
        $save->location   = $request->location;
        $save->address    = $request->address;

        $save->save();
        }

        return back()->with('success','Client successfully added!');
    }

    //delete record
    public function deleteClient($id) {

        if(Project::where('clientID',$id)->exists()) {
            return back()->with('error_message','Cannot delete, record is in use');
        } 
        else {
        $client = Client::find($id);
        $client->delete();
        }
        return back()->with('success','Successfully deleted!');
    }

    //edit record
    public function editClient($id) {

        $data['clients'] = Client::find(base64_decode($id));

        return view('clients.editclient',$data);
    }

    //update
    public function updateClient(Request $request) {

         Client::where('id',$request->clientID)
         ->update(['clientName'=>$request->clientName, 'location'=>$request->location, 'address'=>$request->address]);

         return redirect()->route('launchClient')->with('success','Successfully updated!');
    }
}
