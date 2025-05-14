<?php

namespace App\Http\Controllers;

use App\Models\GlobalPool;
use Illuminate\Http\Request;

class GlobalPoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $globalPool = GlobalPool::first();

        return response()->json($globalPool);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(GlobalPool $globalPool)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GlobalPool $globalPool)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GlobalPool $globalPool)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GlobalPool $globalPool)
    {
        //
    }
}
