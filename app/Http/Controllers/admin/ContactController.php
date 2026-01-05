<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::withCount('checkins')
            ->with([
                'checkins' => function ($q) {
                    $q->orderByDesc('entry_at');
                }
            ])
            ->orderBy('lastname')
            ->get()
            ->map(function ($contact) {
                $lastCheckin = $contact->checkins->first();

                $contact->last_qr_token = $lastCheckin?->qr_token;
                $contact->last_entry_at = $lastCheckin?->entry_at;

                return $contact;
            });

        return view('admin.contacts.index', compact('contacts'));
    }
 
 
 
 public function show(Contact $contact)
{
    $checkins = $contact->checkins()
        ->orderByDesc('entry_at')
        ->get();

    return view('admin.contacts.show', [
        'contact'  => $contact,
        'checkins' => $checkins,
    ]);
}



}
