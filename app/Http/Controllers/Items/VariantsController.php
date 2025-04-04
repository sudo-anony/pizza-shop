<?php

namespace App\Http\Controllers\Items;

use App\Extras;
use App\Http\Controllers\Controller;
use App\Items;
use App\Models\Variants;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VariantsController extends Controller
{
    private function getOptionsForItem(Items $item)
    {
        $options = [];
        foreach ($item->options->toArray() as $option) {
            $data = [];
            foreach (explode(',', $option['options']) as $key => $value) {
                $data[str_replace(' ', '-', mb_strtolower(trim($value)))] = $value;
            }
            array_push($options, ['id' => $option['id'], 'name' => $option['name'], 'data' => $data]);
        }

        return [
            ['ftype' => 'multiselect', 'name' => 'Options', 'id' => 'option', 'placeholder' => 'Enter option', 'required' => false,
                'data' => $options, ],
        ];
    }

    private function getFields(Items $item)
    {
        $variantData = [
            ['ftype' => 'input', 'type' => 'number', 'name' => 'Price', 'id' => 'price', 'placeholder' => 'Enter variant price', 'required' => true],
        ];

        $variantQTY = [];
        if ($item->qty_management == 1) {
            array_push($variantQTY, ['ftype' => 'input', 'value' => 1, 'type' => 'number', 'name' => 'Quantity', 'id' => 'qty', 'placeholder' => 'Enter variant quantity', 'required' => true]);
        }

        return array_merge($variantData, $this->getOptionsForItem($item), $variantQTY);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Items $item): View
    {

        return view('items.variants.index', ['restorant' => $this->getRestaurant(), 'item' => $item, 'setup' => [
            'title' => __('Variants for').' '.$item->name,
            'action_link' => route('items.variants.create', ['item' => $item->id]),
            'action_name' => 'Add new variant',

            'items' => $item->uservariants()->paginate(10),
            'item_names' => 'variants',
            'breadcrumbs' => [
                [__('Menu'), '/items'],
                [$item->name, '/items/'.$item->id.'/edit'],
                [__('Variants'), null],
            ],
        ]]);
    }

    public function extras(Variants $variant): JsonResponse
    {
        $theExtras = $variant->extras->toArray();
        $theExtrasGlobal = Extras::where('extra_for_all_variants', 1)->where('item_id', $variant->item_id)->get()->toArray();

        return response()->json([
            'data' => array_merge($theExtras, $theExtrasGlobal),
            'status' => true,
            'errMsg' => '',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Items $item)
    {
        if ($item->options->count() == 0) {
            return redirect()->route('items.options.create', ['item' => $item->id])->withError(__('First, you will need to add some options. Add the item first option now'));
        }

        return view('general.form', ['setup' => [
            'title' => __('New variant for').' '.$item->name,
            'action_link' => route('items.variants.index', ['item' => $item->id]),
            'action_name' => __('Back'),
            'iscontent' => true,
            'action' => route('items.variants.store', ['item' => $item->id]),
            'breadcrumbs' => [
                [__('Menu'), '/items'],
                [$item->name, '/items/'.$item->id.'/edit'],
                [__('Variants'), route('items.variants.index', ['item' => $item->id])],
                [__('New'), null],
            ],
        ],
            'fields' => $this->getFields($item), ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Items $item, Request $request): RedirectResponse
    {

        // Validate options
        $options = $request->option;
        $item_options = $item->options->pluck('id')->toArray();
        // Check if the first option is empty
        if (empty($options[$item_options[0]])) {
            return redirect()->back()->withError(__('The first option cannot be empty.'));
        }

        // Check if the last option is not empty while the first option is empty
        if (!empty($options[$item_options[count($item_options) - 1]]) && empty($options[$item_options[0]])) {
            return redirect()->back()->withError(__('The first option must be filled if the last option is not empty.'));
        }


        $variant = Variants::create([
            'price' => $request->price,
            'item_id' => $item->id,
            'options' => json_encode($request->option),
        ]);

        if ($request->has('qty')) {
            $variant->qty = $request->qty;
            $variant->enable_qty = 1;
        } else {
            $variant->enable_qty = 0;
        }
        $variant->save();
        if ($request->has('qty')) {
            Items::findOrFail($variant->item->id)->calculateQTYBasedOnVariants();
        }
        $this->doUpdateOfSystemVariants($variant->item);

        return redirect()->route('items.variants.index', ['item' => $item->id])->withStatus(__('Variant has been added'));
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
     */
    public function edit(Variants $variant): View
    {
        $fields = $this->getFields($variant->item);
        $fields[0]['value'] = $variant->price;

        //Now fill the options
        if (is_object(json_decode($variant->options))) {
            foreach (json_decode($variant->options, true) as $key => $value) {
                foreach ($fields[1]['data'] as &$option) {
                    if ($key.'' == $option['id'].'') {
                        $option['value'] = $value;
                    }
                }
            }
        }

        //Now fill the qty
        if (isset($fields[2])) {
            $fields[2]['value'] = $variant->qty;
        }

        return view('general.form', ['setup' => [
            'title' => __('Edit variant').' #'.$variant->id,
            'action_link' => route('items.variants.index', ['item' => $variant->item]),
            'action_name' => __('Back'),
            'iscontent' => true,
            'isupdate' => true,
            'action' => route('items.variants.update', ['variant' => $variant->id]),
            'breadcrumbs' => [
                [__('Menu'), '/items'],
                [$variant->item->name, '/items/'.$variant->item->id.'/edit'],
                [__('Variants'), route('items.variants.index', ['item' => $variant->item->id])],
                ['#'.$variant->id, null],
            ],
        ],
            'fields' => $fields, ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     */
   public function update(Request $request, Variants $variant): RedirectResponse
{
    // Fetch the associated item from the variant
    $item = $variant->item;

    if (!$item) {
        return redirect()->back()->withError(__('Item not found.'));
    }

    // Validate options
    $options = $request->option ?? [];
    $item_options = $item->options->pluck('id')->toArray();

    if (!empty($item_options)) {
        // Check if the first option is empty
        if (empty($options[$item_options[0]])) {
            return redirect()->back()->withError(__('The first option cannot be empty.'));
        }

        // Check if the last option is not empty while the first option is empty
        if (!empty($options[end($item_options)]) && empty($options[$item_options[0]])) {
            return redirect()->back()->withError(__('The first option must be filled if the last option is not empty.'));
        }
    }

    // Update variant details
    $variant->price = $request->price;
    $variant->options = json_encode($options);
    $variant->enable_qty = $request->has('qty') ? 1 : 0;
    
    if ($variant->enable_qty) {
        $variant->qty = $request->qty;
    }

    $variant->save();

    // Recalculate item quantity if needed
    if ($variant->enable_qty) {
        $item->calculateQTYBasedOnVariants();
    }

    // Perform system variant updates
    $this->doUpdateOfSystemVariants($item);

    return redirect()->route('items.variants.index', ['item' => $item->id])
        ->withStatus(__('Variant has been updated.'));
}


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy(Variants $variant): RedirectResponse
    {
        $item = $variant->item;
        $variant->delete();
        Items::findOrFail($variant->item->id)->calculateQTYBasedOnVariants();
        $this->doUpdateOfSystemVariants($item);

        return redirect()->route('items.variants.index', ['item' => $variant->item->id])->withStatus(__('Variant has been removed'));
    }

    private function doUpdateOfSystemVariants(Items $item)
    {
        if ($item->enable_system_variants == 1) {
            //Delete all system
            $item->systemvariants()->forceDelete();
            $item->makeAllMissingVariants($item->price);
        }
    }
}
