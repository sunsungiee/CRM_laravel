<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Contact;
use App\Models\Phase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DealController extends Controller
{
    public function index()
    {
        $search = request('search');
        $sort = request()->input('sort', 'id');
        $direction = request()->input('direction', 'asc');

        $userId = Auth::id();
        $user = Auth::user();

        $deals = Deal::where("user_id", $userId)
            ->orderBy($sort, $direction)
            ->join('phases', 'deals.phase_id', '=', 'phases.id')
            ->join('contacts', 'deals.contact_id', '=', 'contacts.id')
            ->when($search, function ($query) use ($search) {
                return $query->where('deals.subject', 'like', "%$search%")
                    ->orWhere('deals.description', 'like', "%$search%")
                    ->orWhere('deals.end_date', 'like', "%$search%")
                    ->orWhere('deals.end_time', 'like', "%$search%")
                    ->orWhere('contacts.contact', 'like', "%$search%")
                    ->orWhere('phases.phase', 'like', "%$search%");
            })
            ->select('deals.*')
            ->get();

        $contacts = Contact::all();
        $phases = Phase::all();

        return view("deal.main", [
            'deals' => $deals,
            'sort' => $sort,
            'direction' => $direction,
            'user' => $user,
            'contacts' => $contacts,
            'phases' => $phases,
        ]);
    }

    public function store()
    {
        $data = request()->validate([
            'contact_id' => 'nullable|integer|exists:contacts,id',
            'subject' => 'required|string|max:255',
            'sum' => 'required|integer',
            'date' => 'nullable|date',
            'time' => 'nullable|date_format:H:i',
            'phase_id' => 'required|integer|exists:phases,id'
        ]);

        if (isset($data['contact_id']) && $data['contact_id'] == 0) {
            $data['contact_id'] = null;
        }

        $data['user_id'] = auth()->id();

        try {
            Deal::create($data);
            return redirect()->route("deal.main")->with('success', 'Сделка успешно создана!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Ошибка при создании задачи: ' . $e->getMessage());
        }
    }

    public function update()
    {
        return "mew mew";
    }

    public function edit(Deal $deal)
    {
        return response()->json($deal);
    }
}
