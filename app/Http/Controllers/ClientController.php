<?php

namespace App\Http\Controllers;

use Akaunting\Module\Facade as Module;
use App\Exports\ClientsExport;
use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->hasRole('admin')) {
            return view('clients.index', [
                'clients' => User::role('client')->where(['active' => 1])->paginate(15),
            ]
            );
        } elseif (auth()->user()->hasRole('owner')) {
            //Get all our orders's clients
            //Get the vendor
            $client_ids = $this->getRestaurant()
                ->orders()
                ->whereNotNull('client_id')
                ->where('client_id', '!=', auth()->user()->id)
                ->get()->pluck('client_id')->unique()->toArray();

            return view('clients.index', [
                'clients' => User::role('client')->where(['active' => 1])->whereIn('id', $client_ids)->paginate(15),
            ]);
        } else {
            return redirect()->route('orders.index')->withStatus(__('No Access'));
        }
    }

    public function exportCSV()
    {
        $items = [];
        if (auth()->user()->hasRole('admin')) {
            $clientsToDownload = User::role('client')->where(['active' => 1])->get();
        } elseif (auth()->user()->hasRole('owner')) {
            $client_ids = $this->getRestaurant()
                ->orders()
                ->whereNotNull('client_id')
                ->where('client_id', '!=', auth()->user()->id)
                ->get()->pluck('client_id')->unique()->toArray();
            $clientsToDownload = User::whereIn('id', $client_ids)->get();
        }
        foreach ($clientsToDownload as $key => $client) {
            $item = [
                'client_name' => $client->name,
                'client_id' => $client->id,
                'client_email' => $client->email,
                'client_phone' => $client->phone,
                'created' => $client->created_at,
            ];
            array_push($items, $item);
        }

        return Excel::download(new ClientsExport($items), 'clients_'.time().'.csv', \Maatwebsite\Excel\Excel::CSV);
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
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function edit(User $client)
    {
        if (auth()->user()->hasAnyRole(['admin', 'owner'])) {
            $clientOrders = $client->orders()->orderBy('id', 'DESC');
            $orders = $clientOrders->paginate(5);
            $orderCount = $clientOrders->count();

            return view('clients.edit', [
                'client' => $client,
                'hasPoints' => false, //Module::has('cards'),
                'movements' => null, //Module::has('cards')?$client->movements()->orderBy('id','DESC')->paginate(5):null,
                'orders' => $orders,
                'orderCount' => $orderCount]);
        } else {
            return redirect()->route('orders.index')->withStatus(__('No Access'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy(User $client): RedirectResponse
    {
        $client->active = 0;
        $client->save();

        return redirect()->route('clients.index')->withStatus(__('Client successfully deleted.'));
    }
}
