<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientContact\StoreClientContactRequest;
use App\Http\Requests\ClientContact\UpdateClientContactRequest;
use App\Models\Client;
use App\Models\ClientContact;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClientContactController extends Controller
{
    public function index(Client $client): View
    {
        return view('clients.contacts.index', [
            'client' => $client,
            'contacts' => $client->contacts()->latest()->paginate(10),
        ]);
    }

    public function create(Client $client): View
    {
        return view('clients.contacts.create', [
            'client' => $client,
        ]);
    }

    public function store(StoreClientContactRequest $request, Client $client): RedirectResponse
    {
        $client->contacts()->create($request->validated());

        return redirect()->route('clients.contacts.index', $client)
            ->with('success', __('Contact created successfully.'));
    }

    public function edit(Client $client, ClientContact $contact): View
    {
        return view('clients.contacts.edit', [
            'client' => $client,
            'contact' => $contact,
        ]);
    }

    public function update(UpdateClientContactRequest $request, Client $client, ClientContact $contact): RedirectResponse
    {
        $contact->update($request->validated());

        return redirect()->route('clients.contacts.index', $client)
            ->with('success', __('Contact updated successfully.'));
    }

    public function destroy(Client $client, ClientContact $contact): RedirectResponse
    {
        $contact->delete();

        return redirect()->route('clients.contacts.index', $client)
            ->with('success', __('Contact deleted successfully.'));
    }
}
