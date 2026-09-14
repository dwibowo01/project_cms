<?php

namespace App\Http\Controllers;

use App\Http\Requests\Ship\StoreShipRequest;
use App\Http\Requests\Ship\UpdateShipRequest;
use App\Models\Client;
use App\Models\Ship;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShipController extends Controller
{
    private const SORTABLE_COLUMNS = ['ship_name', 'ship_type', 'loa_value', 'created_at'];

    public function index(Request $request): View
    {
        $sort = in_array($request->query('sort'), self::SORTABLE_COLUMNS, true)
            ? $request->query('sort')
            : 'created_at';
        $direction = $request->query('direction') === 'asc' ? 'asc' : 'desc';

        $ships = Ship::query()
            ->with('client')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');
                $query->where('ship_name', 'like', "%{$search}%");
            })
            ->when($request->filled('ship_type'), function ($query) use ($request) {
                $query->where('ship_type', $request->query('ship_type'));
            })
            ->when($request->filled('client_id'), function ($query) use ($request) {
                $query->where('client_id', $request->query('client_id'));
            })
            ->orderBy($sort, $direction)
            ->paginate(10)
            ->withQueryString();

        return view('ships.index', [
            'ships' => $ships,
            'shipTypes' => Ship::SHIP_TYPES,
            'clients' => Client::orderBy('client_name')->get(),
            'sort' => $sort,
            'direction' => $direction,
        ]);
    }

    public function create(): View
    {
        return view('ships.create', [
            'clients' => Client::orderBy('client_name')->get(),
        ]);
    }

    public function store(StoreShipRequest $request): RedirectResponse
    {
        $ship = Ship::create($request->validated());

        return redirect()->route('ships.show', $ship)
            ->with('success', __('Ship created successfully.'));
    }

    public function show(Ship $ship): View
    {
        return view('ships.show', [
            'ship' => $ship->load('client'),
        ]);
    }

    public function edit(Ship $ship): View
    {
        return view('ships.edit', [
            'ship' => $ship,
            'clients' => Client::orderBy('client_name')->get(),
        ]);
    }

    public function update(UpdateShipRequest $request, Ship $ship): RedirectResponse
    {
        $ship->update($request->validated());

        return redirect()->route('ships.show', $ship)
            ->with('success', __('Ship updated successfully.'));
    }

    public function destroy(Ship $ship): RedirectResponse
    {
        $ship->delete();

        return redirect()->route('ships.index')
            ->with('success', __('Ship deleted successfully.'));
    }
}
