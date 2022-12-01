<?php

namespace App\Http\Controllers\LA;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Models\PartnerAccess;

class PartnerAccessController extends Controller
{
    /**
     * Store a newly created permission in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $data = $request->all();
        unset($data['_token']);
        $keys = array_keys($data);

        foreach($keys as $i => $key) {
            $id = explode('_',$key);
            $PartnerAccess = PartnerAccess::find($id['1']);
            $PartnerAccess->is_access = $data[$key]?:0;
            $PartnerAccess->save();
        }
        
        return redirect()->back();
    }
}
