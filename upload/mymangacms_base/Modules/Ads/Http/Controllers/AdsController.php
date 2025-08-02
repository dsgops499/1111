<?php

namespace Modules\Ads\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use Modules\Ads\Entities\Ad;
use Modules\Ads\Entities\Placement;

class AdsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:settings.edit_general');
    }
    
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $ads = Ad::all();
        $adsList = array('' => 'NO AD');
		
        if (count($ads)>0) {
            foreach ($ads as $ad) {
                $adsList[$ad->id] = $ad->bloc_id;
            }
        }
		
        $reader = Placement::where('page', '=', 'READER')->first();
        $homepage = Placement::where('page', '=', 'HOMEPAGE')->first();
        $info = Placement::where('page', '=', 'MANGAINFO')->first();
        $placements = ['reader' => array(), 'homepage' => array(), 'info' => array()];

        foreach ($reader->ads()->get() as $key => $ad) {
            $placements['reader'][$ad->pivot->placement] = $ad->id;
        }

        foreach ($homepage->ads()->get() as $key => $ad) {
            $placements['homepage'][$ad->pivot->placement] = $ad->id;
        }

        foreach ($info->ads()->get() as $key => $ad) {
            $placements['info'][$ad->pivot->placement] = $ad->id;
        }

        return view('ads::admin.settings.ads',
                [
                    'ads' => $ads,
                    'adsList' => $adsList,
                    'placements' => $placements,
                ]
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
		
        foreach ($input['bloc_id'] as $key => $bloc) {
            $ad = new Ad();
            if(isset($input['id'][$key])) {
                $ad = Ad::find($input['id'][$key]);
                $ad->bloc_id = $bloc;
                $ad->code = $input['code'][$key];
            } else {
                $ad->bloc_id = $bloc;
                $ad->code = $input['code'][$key];
            }
            $ad->save();
        }

        return redirect()->back()
            ->withSuccess(trans('messages.admin.settings.update.success'));
    }

    public function storePlacements()
    {
        $inputs = request()->all();
		
        foreach ($inputs as $id => $input) {
            if($id == 'reader') {
                $ads = array();
                $placements = array();
                $placement = Placement::where('page', '=', 'READER')->first();
                foreach ($input as $key => $ad) {
                    if($ad != "") {
                        array_push($ads, $ad);
                        array_push($placements, $key);
                    }
                }

                if(count($ads)>0) {
                    $placement->ads()->detach();
                    foreach ($ads as $key => $ad) {
                        $placement->ads()->attach($ad, ['placement' => $placements[$key]]);
                    }
                }
            } else if($id == 'homepage') {
                $ads = array();
                $placements = array();
                $placement = Placement::where('page', '=', 'HOMEPAGE')->first();
                foreach ($input as $key => $ad) {
                    if($ad != "") {
                        array_push($ads, $ad);
                        array_push($placements, $key);
                    }
                }

                if(count($ads)>0) {
                    $placement->ads()->detach();
                    foreach ($ads as $key => $ad) {
                        $placement->ads()->attach($ad, ['placement' => $placements[$key]]);
                    }
                }
            } else if($id == 'info') {
                $ads = array();
                $placements = array();
                $placement = Placement::where('page', '=', 'MANGAINFO')->first();
                foreach ($input as $key => $ad) {
                    if($ad != "") {
                        array_push($ads, $ad);
                        array_push($placements, $key);
                    }
                }

                if(count($ads)>0) {
                    $placement->ads()->detach();
                    foreach ($ads as $key => $ad) {
                        $placement->ads()->attach($ad, ['placement' => $placements[$key]]);
                    }
                }
            }
        }
		
        return redirect()->back()
            ->withSuccess(trans('messages.admin.settings.update.success'));
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        Ad::find($id)->delete();
    }
}
