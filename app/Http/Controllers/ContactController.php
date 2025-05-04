<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::all();
        return view("contact.main", compact("contacts"));
    }

    public function store()
    {
        $data = request()->validate([
            'surname' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|string',
            'phone' => 'required|string',
            'firm' => 'string'
        ]);

        Contact::create($data);
        return redirect()->route("contact.main");
    }


    public function edit(Contact $contact)
    {
        return response()->json($contact);
    }

    public function update(Contact $contact)
    {
        $data = request()->validate([
            'surname' => 'required|string',
            'name' => 'required|string',
            'email' => 'required|string',
            'phone' => 'required|string',
            'firm' => 'string'
        ]);

        $contact->update($data);
    }
}
