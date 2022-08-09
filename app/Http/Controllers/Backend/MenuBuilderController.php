<?php

namespace App\Http\Controllers\Backend;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class MenuBuilderController extends Controller
{
    public function index($id)
    {
        Gate::authorize('app.menus.index');
        $menu = Menu::findOrFail($id);
        return view('backend.menus.builder', compact('menu'));
    }
    public function itemCreate($id)
    {
        Gate::authorize('app.menus.create');
        $menu = Menu::findOrFail($id);
        return view('backend.menus.item.form', compact('menu'));
    }
    public function itemStore(Request $request, $id)
    {
        $this->validate($request, [
            'divider_title' =>  'nullable|string',
            'title' =>  'nullable|string',
            'url' =>  'nullable|string',
            'target' =>  'nullable|string',
            'icon_class' =>  'nullable|string',
        ]);
        $menu = Menu::findOrFail($id);
        MenuItem::create([
            'menu_id' => $menu->id,
            'type' => $request->type,
            'title' => $request->title,
            'divider_title' => $request->divider_title,
            'url' => $request->url,
            'target' => $request->target,
            'icon_class' => $request->icon_class
        ]);
        notify()->success('Menu Item Successfully Added.', 'Added');
        return redirect()->route('app.menus.builder', $menu->id);
    }
}