<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $search = request('search');

        $sort = request()->input('sort', 'surname'); // По умолчанию сортировка по ID
        $direction = request()->input('direction', 'asc'); // Направление по умолчанию

        $user = Auth::user();

        $contacts = Contact::orderBy($sort, $direction)->when($search, function ($query) use ($search) {
            return $query->where('surname', 'like', "%$search%")
                ->orWhere('surname', 'like', "%$search%")
                ->orWhere('name', 'like', "%$search%")
                ->orWhere('phone', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('firm', 'like', "%$search%");
        })->get();

        return view('contact.main', compact("contacts"), [
            'contacts' => $contacts,
            'sort' => $sort,
            'direction' => $direction,
            'user' => $user,
        ]);
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
        return redirect()->route("contact.main");
    }

    public function destroy(Contact $contact)
    {// $contact->restore();
        $contact->delete();
        return redirect()->route("contact.main");
    }
}
