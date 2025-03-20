<?php

namespace App\Http\Controllers;

use App\Categories;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $category = new Categories;
        $category->name = strip_tags($request->category_name);
        $category->subtitle = strip_tags($request->subtitle);
        $category->company_id = $request->restaurant_id;
        $category->save();

        if (auth()->user()->hasRole('admin')) {
            //Direct to that page directly
            return redirect()->route('items.admin', ['restorant' => $request->restaurant_id])->withStatus(__('Category successfully created.'));
        }

        return redirect()->route('items.index')->withStatus(__('Category successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     */
    public function update(Request $request, Categories $category): RedirectResponse
    {
        $category->name = $request->category_name;
        $category->subtitle = $request->subtitle;
        $category->save(); // Changed from update() to save() to persist changes correctly
        return redirect()->back()->withStatus(__('Category successfully updated.'));
    }

    /** 
     * Toggles the status of the category
     * 
     * @param Categories $category
     */
    public function toggle(Categories $category): RedirectResponse
    {
        $category->active = !$category->active;
        $category->save();

        return redirect()->back()->withStatus(__('Category status successfully toggled.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy(Categories $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('items.index')->withStatus(__('Category successfully deleted.'));
    }
}
