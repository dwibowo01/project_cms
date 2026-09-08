<?php

namespace App\Http\Controllers;

use App\Http\Requests\Client\StoreClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    private const SORTABLE_COLUMNS = ['client_no', 'client_name', 'client_company_type', 'client_email', 'created_at'];

    public function index(Request $request): View
    {
        $sort = in_array($request->query('sort'), self::SORTABLE_COLUMNS, true)
            ? $request->query('sort')
            : 'created_at';
        $direction = $request->query('direction') === 'asc' ? 'asc' : 'desc';

        $clients = Client::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');
                $query->where(function ($q) use ($search) {
                    $q->where('client_name', 'like', "%{$search}%")
                        ->orWhere('client_no', 'like', "%{$search}%")
                        ->orWhere('client_id', 'like', "%{$search}%")
                        ->orWhere('client_email', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('company_type'), function ($query) use ($request) {
                $query->where('client_company_type', $request->query('company_type'));
            })
            ->orderBy($sort, $direction)
            ->paginate(10)
            ->withQueryString();

        return view('clients.index', [
            'clients' => $clients,
            'companyTypes' => Client::COMPANY_TYPES,
            'sort' => $sort,
            'direction' => $direction,
        ]);
    }

    public function create(): View
    {
        return view('clients.create');
    }

    public function store(StoreClientRequest $request): RedirectResponse
    {
        $client = Client::create($request->validated());

        return redirect()->route('clients.show', $client)
            ->with('success', __('Client created successfully.'));
    }

    public function show(Client $client): View
    {
        return view('clients.show', [
            'client' => $client,
            'contacts' => $client->contacts()->latest()->get(),
        ]);
    }

    public function edit(Client $client): View
    {
        return view('clients.edit', [
            'client' => $client,
        ]);
    }

    public function update(UpdateClientRequest $request, Client $client): RedirectResponse
    {
        $client->update($request->validated());

        return redirect()->route('clients.show', $client)
            ->with('success', __('Client updated successfully.'));
    }

    public function destroy(Request $request, Client $client): RedirectResponse
    {
        $request->validateWithBag($client->id, [
            'password' => ['required', 'current_password'],
        ]);

        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', __('Client deleted successfully.'));
    }
}
