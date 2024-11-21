<?php

namespace App\Http\Controllers;

use App\Models\QuickLink;
use App\Models\User;
use App\Http\Resources\QuickLinkResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class QuickLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // $this->authorize('viewAny', QuickLink::class);

        $quickLinks = QuickLink::when(Auth::user()->role_id !== "1", function ($query) {
            return $query->where('user_id', '=', Auth::user()->id);
        })->orderBy('created_at','desc')->paginate(20);

        return QuickLinkResource::collection($quickLinks)->preserveQuery();
    }

    public function quickLinks()
    {
        //
        // $this->authorize('viewAny', QuickLink::class);
        $quickLinks = QuickLink::when(Auth::user()->role_id !== "1", function ($query) {
            return $query->where('user_id', '=', Auth::user()->id);
        })->orderBy('created_at','asc')->get();

        return response(['quickLinks' => QuickLinkResource::collection($quickLinks)]);
    }

    public function search(Request $request)
    {
        //
        // $this->authorize('viewAny', QuickLink::class);
        $input=$request->all();

        $quickLinks = QuickLink::when(Auth::user()->role_id !== "1", function ($query) {
            return $query->where('user_id', '=', Auth::user()->id);
        })->where('name','LIKE','%'.$input['searchTerm'].'%')
            ->orderBy('created_at','asc')
            ->paginate(20);

        return QuickLinkResource::collection($quickLinks)->preserveQuery();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        // $this->authorize('create', QuickLink::class);
        $input=$request->all();
        $validator = Validator::make($input, [
         ]);
        if ($validator->fails()) {
            return response(['error' => $validator->errors()]);
          }
        $quickLink=QuickLink::create($input);

        return new QuickLinkResource($quickLink);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\quickLink  $quickLink
     * @return \Illuminate\Http\Response
     */

    public function show(QuickLink $quickLink)
    {
        //
        // $this->authorize('view', $quickLink);
        return new QuickLinkResource($quickLink);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\quickLink  $quickLink
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, QuickLink $quickLink)
    {
        //

        // $this->authorize('update', $quickLink);
        $input=$request->all();
        $validator = Validator::make($input, [
         ]);
        if ($validator->fails()) {
            return response(['error' => $validator->errors()]);
          }
        $quickLink->update($input);
        return new QuickLinkResource($quickLink);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\quickLink  $quickLink
     * @return \Illuminate\Http\Response
     */
    public function destroy(QuickLink $quickLink)
    {
        //
        // $this->authorize('delete', $quickLink);
        $quickLink->delete();
        return response(['message' => 'Quick Link was deleted successfully']);
    }
}