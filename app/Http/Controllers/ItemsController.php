<?php

namespace App\Http\Controllers;

use Akaunting\Module\Facade as Module;
use App\Categories;
use App\Extras;
use App\Imports\ItemsImport;
use App\Items;
use App\Models\Allergens;
use App\Restorant;
use App\Services\ConfChanger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ItemsController extends Controller
{
    private $imagePath = 'uploads/restorants/';

    public function reorderCategories(Categories $up): RedirectResponse
    {
        $up->moveOrderUp();

        return redirect()->route('items.index')->withStatus(__('Sort order updated'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->hasRole(['owner', 'staff'])) {

            $canAdd = $this->getCompany()->getPlanAttribute()['canAddNewItems'];

            //Change language
            ConfChanger::switchLanguage($this->getCompany());

            if (isset($_GET['remove_lang']) && $this->getCompany()->localmenus()->count() > 1) {
                $localMenuToDelete = $this->getCompany()->localmenus()->where('language', $_GET['remove_lang'])->first();
                $isMenuToDeleteIsDefault = $localMenuToDelete->default.'' == '1';
                $localMenuToDelete->delete();

                $nextLanguageModel = $this->getCompany()->localmenus()->first();
                $nextLanguage = $nextLanguageModel->language;
                app()->setLocale($nextLanguage);
                session(['applocale_change' => $nextLanguage]);

                if ($isMenuToDeleteIsDefault) {
                    $nextLanguageModel->default = 1;
                    $nextLanguageModel->update();
                }
            }

            if (isset($_GET['make_default_lang'])) {
                $newDefault = $this->getCompany()->localmenus()->where('language', $_GET['make_default_lang'])->first();
                $oldDefault = $this->getCompany()->localmenus()->where('default', '1')->first();

                if ($oldDefault && $oldDefault->language != $_GET['make_default_lang']) {
                    $oldDefault->default = 0;
                    $oldDefault->update();
                }
                $newDefault->default = 1;
                $newDefault->update();

            }

            $currentEnvLanguage = isset(config('config.env')[2]['fields'][0]['data'][config('app.locale')]) ? config('config.env')[2]['fields'][0]['data'][config('app.locale')] : 'UNKNOWN';

            //Change currency
            ConfChanger::switchCurrency($this->getCompany());
            $defaultLng = $this->getCompany()->localmenus->where('default', '1')->first();

            //Since 2.1.7 - there is sorting.
            $categories = $this->getCompany()->all_categories;

            //If first item order starts with 0
            if ($categories->first() && $categories->first()->order_index == 0) {
                Categories::setNewOrder($categories->pluck('id')->toArray());

                //Re-get categories
                $categories = $this->getCompany()->all_categories;
            }

            return view('items.index', [
                'hasMenuPDf' => Module::has('menupdf'),
                'canAdd' => $canAdd,
                'categories' => $categories,
                'restorant_id' => $this->getCompany()->id,
                'currentLanguage' => $currentEnvLanguage,
                'availableLanguages' => $this->getCompany()->localmenus,
                'defaultLanguage' => $defaultLng ? $defaultLng->language : '',
            ]);
        } else {
            return redirect()->route('orders.index')->withStatus(__('No Access'));
        }
    }

    public function indexAdmin(Restorant $restorant)
    {
        if (auth()->user()->hasRole('admin')) {
            return view('items.index', ['categories' => Restorant::findOrFail($restorant->id)->categories->reverse(), 'restorant_id' => $restorant->id]);
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $item = new Items;
        $item->name = strip_tags($request->item_name);
        $item->description = strip_tags($request->item_description);
        $item->price = strip_tags($request->item_price);
        $item->category_id = strip_tags($request->category_id);
        $defVat = 0;
        $resto = $this->getRestaurant();
        if ($resto) {
            $defVat = $resto->getConfig('default_tax_value', 0);
        }
        $item->vat = $defVat;

        if ($request->hasFile('item_image')) {
            $item->image = $this->saveImageVersions(
                $this->imagePath,
                $request->item_image,
                [
                    ['name' => 'large', 'w' => 590, 'h' => 400],
                    //['name'=>'thumbnail','w'=>300,'h'=>300],
                    ['name' => 'medium', 'w' => 295, 'h' => 200],
                    ['name' => 'thumbnail', 'w' => 200, 'h' => 200],
                ]
            );
        }
        $item->save();

        return redirect()->route('items.index')->withStatus(__('Item successfully updated.'));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  public function edit(Items $item)
    {
        //if item belongs to owner restorant menu return view
        if (auth()->user()->hasRole(['owner', 'staff']) && $item->category->restorant->id == $this->getCompany()->id || auth()->user()->hasRole('admin')) {

            //Change currency
            ConfChanger::switchCurrency($item->category->restorant);

            $extraViews = [];
            foreach (Module::all() as $key => $module) {
                if (is_array($module->get('menuview'))) {
                    foreach ($module->get('menuview') as $key => $menu) {
                        array_push($extraViews, $menu);
                    }
                }
            }
            $items = $item->extras->load('variants');
            // dd($items);

            return view('items.edit',
                [
                    'extraViews' => $extraViews,
                    'allergens' => Allergens::where('post_type', 'allergen')->get(),
                    'item' => $item,
                    'setup' => ['items' => $item->uservariants()->paginate(1000)],
                    'restorant' => $item->category->restorant,
                    'categories' => $item->category->restorant->all_categories->pluck('name', 'id'),
                    'restorant_id' => $item->category->restorant->id, ]);
        } else {
            return redirect()->route('items.index')->withStatus(__('No Access'));
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     */
    public function update(Request $request, Items $item): RedirectResponse
    {
        $makeVariantsRecreate = false;
        $item->name = strip_tags($request->item_name);
        $item->subtitle = strip_tags($request->item_subtitle);
        $item->description = strip_tags($request->item_description);
        $item->category_id = $request->category_id;
        $item->uid = $request->item_uid;
        $item->type = $request->type;
        if ($item->price != strip_tags($request->item_price)) {
            $makeVariantsRecreate = true;
        }
        $item->price = strip_tags($request->item_price);
        $item->discounted_price = strip_tags($request->discounted_price);

        if (isset($request->vat)) {
            $item->vat = $request->vat;
        }

        $item->available = $request->exists('itemAvailable');
        $item->has_variants = $request->exists('has_variants');

        if ($request->exists('qty_management')) {
            $item->qty_management = 1;
        } else {
            $item->qty_management = 0;
        }

        if ($request->has('qty')) {
            $item->qty = $request->qty;
        }

        if (! $item->has_variants) {
            $item->enable_system_variants = 0;

            //Delete all system variants
            $item->systemvariants()->delete();

        } else {

            //We have variants, but do we have system variables
            $doWoHave_enable_system_variants = $request->exists('enable_system_variants') ? 1 : 0;

            //In case value changes from no to yes, we need to recreate
            if ($item->enable_system_variants == 0 && $doWoHave_enable_system_variants == 1) {
                $makeVariantsRecreate = true;
            }

            //Set the flag for the system
            $item->enable_system_variants = $doWoHave_enable_system_variants;

            //When we have System Variables
            if ($item->enable_system_variants == 1) {
                //And we do need to make recreation
                if ($makeVariantsRecreate) {
                    //Delete all of them - since this can be a price change
                    $item->systemvariants()->forceDelete();

                    //And recreate once  again - with new item price
                    $item->makeAllMissingVariants($item->price);
                }

            } else {
                //Delete all system variants - we don't need system variables
                $item->systemvariants()->forceDelete();
            }
        }

        if ($request->hasFile('item_image')) {
            $request->validate([
                'item_image' => ['dimensions:max_width=2000'],
            ]);
            if ($request->hasFile('item_image')) {
                $large_image = ['name' => 'large'];
                if (config('settings.do_large_image_resize', false)) {
                    $large_image = ['name' => 'large', 'w' => 590, 'h' => 400];
                }
                $item->image = $this->saveImageVersions(
                    $this->imagePath,
                    $request->item_image,
                    [
                        $large_image,
                        ['name' => 'medium', 'w' => 295, 'h' => 200],
                        ['name' => 'thumbnail', 'w' => 200, 'h' => 200],
                    ]
                );
            }
        }

        $item->update();

        return redirect()->route('items.edit', $item)->withStatus(__('Item successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy(Items $item): RedirectResponse
    {
        $item->delete();

        return redirect()->route('items.index')->withStatus(__('Item successfully deleted.'));
    }

    public function import(Request $request): RedirectResponse
    {
        $restorant = Restorant::findOrFail($request->res_id);

        Excel::import(new ItemsImport($restorant), request()->file('items_excel'));

        return redirect()->route('items.index')->withStatus(__('Items successfully imported'));
    }

    public function change(Items $item, Request $request): JsonResponse
    {
        $item->available = $request->value;
        $item->update();

        return response()->json([
            'data' => [
                'itemAvailable' => $item->available,
            ],
            'status' => true,
            'errMsg' => '',
        ]);
    }

    public function storeExtras(Request $request, Items $item): RedirectResponse
    {
        //dd($request->all());
        if ($request->extras_id.'' == '') {
            //New
            $extras = new Extras;
            $extras->name = strip_tags($request->extras_name);
            $extras->price = strip_tags($request->extras_price);
            $extras->item_id = $item->id;

            $extras->save();
        } else {
            //Update
            $extras = Extras::where(['id' => $request->extras_id])->get()->first();

            $extras->name = strip_tags($request->extras_name);
            $extras->price = strip_tags($request->extras_price);

            $extras->update();
        }

        //For variants
        //Does the item of this extra have item?
        if ($item->has_variants.'' == 1) {
            //In cas we have variants, we  need to check if this variant is for all variants, or only for selected one
            if ($request->exists('variantsSelector')) {
                $extras->extra_for_all_variants = 0;
                //Now sync the connection
                $extras->variants()->sync($request->variantsSelector);
            } else {
                $extras->extra_for_all_variants = 1;
            }
        } else {
            $extras->extra_for_all_variants = 1;
        }
        $extras->update();

        return redirect()->route('items.edit', ['item' => $item, 'restorant' => $item->category->restorant, 'restorant_id' => $item->category->restorant->id])->withStatus(__('Extras successfully added/modified.'));
    }

    public function editExtras(Request $request, Items $item): RedirectResponse
    {
        $extras = Extras::where(['id' => $request->extras_id])->get()->first();

        $extras->name = strip_tags($request->extras_name_edit);
        $extras->price = strip_tags($request->extras_price_edit);

        $extras->update();

        return redirect()->route('items.edit', ['item' => $item, 'restorant' => $item->category->restorant, 'restorant_id' => $item->category->restorant->id])->withStatus(__('Extras successfully updated.'));
    }

    public function deleteExtras(Items $item, Extras $extras): RedirectResponse
    {
        $extras->delete();

        return redirect()->route('items.edit', ['item' => $item, 'restorant' => $item->category->restorant, 'restorant_id' => $item->category->restorant->id])->withStatus(__('Extras successfully deleted.'));
    }
}
