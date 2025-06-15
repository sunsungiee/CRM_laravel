<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Contact;
use App\Models\Phase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class DealController extends Controller
{
    public function index()
    {
        $search = request('search');
        $sort = request()->input('sort', 'subject');
        $direction = request()->input('direction', 'asc');

        $userId = Auth::id();
        $user = Auth::user();

        $deals = Deal::where("user_id", $userId)
            ->orderBy($sort, $direction)
            ->join('phases', 'deals.phase_id', '=', 'phases.id')
            ->join('contacts', 'deals.contact_id', '=', 'contacts.id')
            ->when($search, function ($query) use ($search) {
                return $query->where('deals.subject', 'like', "%$search%")
                    ->orWhere('deals.end_date', 'like', "%$search%")
                    ->orWhere('deals.end_time', 'like', "%$search%")
                    ->orWhere('contacts.surname', 'like', "%$search%")
                    ->orWhere('contacts.name', 'like', "%$search%")
                    ->orWhere('phases.phase', 'like', "%$search%");
            })
            ->select('deals.*')
            ->get();

        $contacts = Contact::all();
        $phases = Phase::where('id', '<', 4)->get();

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
            'contact_id' => 'required|integer|exists:contacts,id',
            'subject' => 'required|string|max:255',
            'sum' => 'required|integer',
            'end_date' => 'required|date',
            'end_time' => 'required|date_format:H:i',
            'phase_id' => 'required|integer|exists:phases,id'
        ]);

        $data['user_id'] = auth()->id();

        try {
            Deal::create($data);
            return redirect()->route("deal.main")->with('success', 'Сделка успешно создана!');
        } catch (\Exception $e) {
            return dd($e->getMessage());
        }
    }
    public function edit(Deal $deal)
    {
        return response()->json($deal);
    }

    public function update(Request $request, Deal $deal)
    {
        // Log::info('Полученные данные:', $request->all());
        // Log::info($deal);

        $data = request()->validate([
            'contact_id' => 'required|integer|exists:contacts,id',
            'subject' => 'required|string|max:255',
            'sum' => 'required|integer',
            'end_date' => 'required|date',
            'end_time' => 'required|date_format:H:i:s',
            'phase_id' => 'required|integer|exists:phases,id'
        ]);

        $data['user_id'] = auth()->id();

        // Log::info('Обновляем сделку с данными:', $data);
        $deal->update($data);
        // Log::info('Сделка после обновления:', $deal->fresh()->toArray());
        return redirect()->route("deal.main");
    }



    public function destroy(Deal $deal)
    {
        $data = request()->validate([
            "phase_id" => 'required'
        ]);

        $deal->update($data);

        $deal->delete();
        return redirect()->route("deal.main");
    }

    public function showArchive()
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
                    ->orWhere('deals.end_date', 'like', "%$search%")
                    ->orWhere('deals.end_time', 'like', "%$search%")
                    ->orWhere('contacts.surname', 'like', "%$search%")
                    ->orWhere('contacts.name', 'like', "%$search%")
                    ->orWhere('phases.phase', 'like', "%$search%");
            })
            ->select('deals.*')
            ->onlyTrashed()->get();


        return view("deal.archive", [
            'deals' => $deals,
            'sort' => $sort,
            'direction' => $direction,
            'user' => $user,
        ]);
    }
}
