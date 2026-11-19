<?php

namespace App\Http\Controllers;

use App\Models\Occupant;
use App\Models\Payment;
use App\Models\Space;
use Illuminate\Http\Request;

class AppController extends Controller
{
    private function active()
    {
        return fn ($q) => $q->where('status', 'active');
    }

    /* ---------- first-time setup ---------- */
    public function setup()
    {
        return auth()->user()->onboarded ? redirect('/dashboard') : view('setup');
    }

    public function setupSave(Request $r)
    {
        if (auth()->user()->onboarded) {
            return redirect('/dashboard');
        }
        $d = $r->validate([
            'business_name' => 'required|max:80', 'business_type' => 'required|in:hostel,house,building',
            'names' => 'required|array|min:1|max:200', 'names.*' => 'required|max:60',
            'beds' => 'array', 'beds.*' => 'nullable|integer|min:1|max:50',
            'rents' => 'array', 'rents.*' => 'nullable|integer|min:0',
        ]);
        $hostel = $d['business_type'] === 'hostel';
        foreach ($d['names'] as $i => $n) {
            Space::create(['name' => $n, 'beds' => $hostel ? ($d['beds'][$i] ?? 1) : 1, 'rent' => $d['rents'][$i] ?? 0]);
        }
        auth()->user()->update(['business_name' => $d['business_name'], 'business_type' => $d['business_type'], 'onboarded' => true]);

        return redirect('/dashboard')->with('ok', 'Your account is ready.');
    }

    /* ---------- dashboard ---------- */
    public function dashboard()
    {
        $month = now()->startOfMonth();
        $spaces = Space::withCount(['occupants as used' => $this->active()])->get();
        $paid = Payment::whereDate('for_month', $month)->get()->groupBy('occupant_id')->map->sum('amount');

        $pending = Occupant::with('space')->where('status', 'active')->get()->map(function ($o) use ($paid, $month) {
            $o->owes = max(0, $o->rent - ($paid[$o->id] ?? 0));
            $due = $month->copy()->day(min($o->due_day, $month->daysInMonth));
            $o->late = $due->isPast() ? (int) $due->diffInDays(now()) : 0;
            $p = preg_replace('/\D/', '', $o->phone);
            $p = str_starts_with($p, '0') ? '92'.substr($p, 1) : $p;
            $o->wa = "https://wa.me/{$p}?text=".rawurlencode("Assalam o Alaikum {$o->name}, {$month->format('F')} ka kiraya Rs {$o->owes} baqi hai. Shukriya.");

            return $o;
        })->filter(fn ($o) => $o->owes > 0)->sortByDesc('late');

        return view('dashboard', [
            'u' => auth()->user(),
            'month' => $month, 'beds' => $spaces->sum('beds'), 'used' => $spaces->sum('used'),
            'pending' => $pending, 'collected' => $paid->sum(),
            'openSpaces' => $spaces->filter(fn ($s) => $s->used < $s->beds),
        ]);
    }

    /* ---------- rooms and assigning people ---------- */
    public function rooms()
    {
        return view('rooms', ['u' => auth()->user(), 'spaces' => Space::with(['occupants' => $this->active()])->get()]);
    }

    public function assignForm(Space $space)
    {
        $free = $space->beds - $space->occupants()->where('status', 'active')->count();
        if ($free < 1) {
            return redirect('/rooms')->withErrors(['full' => $space->name.' is full.']);
        }

        return view('assign', ['u' => auth()->user(), 'space' => $space, 'free' => $free]);
    }

    public function assign(Request $r, Space $space)
    {
        $free = $space->beds - $space->occupants()->where('status', 'active')->count();
        if ($free < 1) {
            return redirect('/rooms')->withErrors(['full' => $space->name.' is full.']);
        }
        $d = $r->validate([
            'name' => 'required|max:80', 'phone' => 'required|max:20', 'cnic' => 'nullable|max:20',
            'photo' => 'nullable|image|max:5120', 'rent' => 'required|integer|min:0',
            'due_day' => 'required|integer|min:1|max:28', 'joined_on' => 'required|date',
        ]);
        $d['photo'] = $r->file('photo')?->store('photos', 'public');
        Occupant::create($d + ['space_id' => $space->id, 'status' => 'active']);

        return redirect('/rooms')->with('ok', $d['name'].' added to '.$space->name.'.');
    }

    public function leave($id)
    {
        Occupant::findOrFail($id)->update(['status' => 'left', 'left_on' => now()]);

        return back()->with('ok', 'Done. The bed is free again.');
    }

    /* ---------- payments ---------- */
    public function payments(Request $r)
    {
        $month = now()->startOfMonth();
        $paid = Payment::whereDate('for_month', $month)->get()->groupBy('occupant_id')->map->sum('amount');
        $people = Occupant::with('space')->where('status', 'active')->orderBy('name')->get()
            ->each(fn ($o) => $o->owes = max(0, $o->rent - ($paid[$o->id] ?? 0)));

        return view('payments', [
            'people' => $people, 'pick' => $people->firstWhere('id', (int) $r->query('occupant')),
            'history' => Payment::with('occupant.space')->latest('paid_on')->latest('id')->limit(40)->get(),
        ]);
    }

    public function payStore(Request $r)
    {
        $d = $r->validate([
            'occupant_id' => 'required|integer', 'amount' => 'required|integer|min:1',
            'for_month' => 'required|date_format:Y-m', 'paid_on' => 'required|date', 'method' => 'required|in:cash,jazzcash,easypaisa,bank',
        ]);
        Occupant::findOrFail($d['occupant_id']);          // owner scope blocks other owners' people
        $d['for_month'] .= '-01';
        Payment::create($d);

        return redirect('/payments')->with('ok', 'Payment saved.');
    }

    /* ---------- settings ---------- */
    public function settings()
    {
        return view('settings', ['u' => auth()->user(), 'spaces' => Space::withCount(['occupants as used' => $this->active()])->get()]);
    }

    public function settingsSave(Request $r)
    {
        auth()->user()->update($r->validate(['business_name' => 'required|max:80']));

        return back()->with('ok', 'Saved.');
    }

    public function spaceStore(Request $r)
    {
        $d = $r->validate(['name' => 'required|max:60', 'beds' => 'nullable|integer|min:1|max:50', 'rent' => 'required|integer|min:0']);
        $d['beds'] = auth()->user()->business_type === 'hostel' ? ($d['beds'] ?? 1) : 1;
        Space::create($d);

        return back()->with('ok', 'Added.');
    }

    public function spaceUpdate(Request $r, $id)
    {
        $s = Space::findOrFail($id);
        $d = $r->validate(['name' => 'required|max:60', 'beds' => 'nullable|integer|min:1|max:50', 'rent' => 'required|integer|min:0']);
        $d['beds'] = auth()->user()->business_type === 'hostel' ? ($d['beds'] ?? $s->beds) : 1;
        $used = $s->occupants()->where('status', 'active')->count();
        if ($d['beds'] < $used) {
            return back()->withErrors(['beds' => "{$s->name} has {$used} people living in it. Mark some as left before reducing beds."]);
        }
        $s->update($d);

        return back()->with('ok', 'Saved.');
    }

    public function spaceDelete($id)
    {
        $s = Space::findOrFail($id);
        if ($s->occupants()->where('status', 'active')->exists()) {
            return back()->withErrors(['name' => "{$s->name} still has people living in it."]);
        }
        $s->delete();

        return back()->with('ok', 'Deleted.');
    }
}
