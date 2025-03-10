<?php

namespace App\Http\Controllers;

use App\Pages;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Pages $pages)
    {
        if (auth()->user() && auth()->user()->hasRole('admin')) {
            return view('pages.index', ['pages' => $pages->paginate(10)]);
        } elseif (auth()->guest()) {
            return redirect()->route('front');
        } else {
            return redirect()->route('orders.index')->withStatus(__('No Access'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (auth()->user() && auth()->user()->hasRole('admin')) {
            return view('pages.create');
        } elseif (auth()->guest()) {
            return redirect()->route('front');
        } else {
            return redirect()->route('orders.index')->withStatus(__('No Access'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $page = new Pages;
        $page->title = strip_tags($request->title);
        $page->content = $request->input('ckeditor');
        if (! $page->content) {
            $page->content = '';
        }

        $page->save();

        return redirect()->route('pages.index')->withStatus(__('Page successfully created!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show(Pages $page): View
    {
        return view('pages.show', ['page' => $page]);
    }

    public function blog($slug): View
    {
        $pages = Pages::where('id', '>', 0)->get();
        foreach ($pages as $key => $page) {
            if ($page->slug == $slug) {
                return view('pages.show', ['page' => $page]);
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Pages $page)
    {
        if (auth()->user() && auth()->user()->hasRole('admin')) {
            return view('pages.edit', ['page' => $page]);
        } elseif (auth()->guest()) {
            return redirect()->route('front');
        } else {
            return redirect()->route('orders.index')->withStatus(__('No Access'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     */
    public function update(Request $request, Pages $page): RedirectResponse
    {
        $page->title = strip_tags($request->title);
        $page->content = $request->input('ckeditor');

        $page->update();

        return redirect()->route('pages.index')->withStatus(__('Page successfully updated!'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy(Pages $page): RedirectResponse
    {
        $page->delete();

        return redirect()->route('pages.index')->withStatus(__('Page successfully deleted!'));
    }

    public function change(Pages $page, Request $request): JsonResponse
    {
        $page->showAsLink = $request->value;
        $page->update();

        return response()->json([
            'data' => [
                'showAsLink' => $page->showAsLink,
            ],
            'status' => true,
            'errMsg' => '',
        ]);
    }

    public function getPages(): JsonResponse
    {
        return response()->json([
            'data' => Pages::where(['showAsLink' => 1])->get(),
        ]);
    }
}
