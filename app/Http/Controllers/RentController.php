<?php

namespace App\Http\Controllers;

use App\Models\Lease;
use App\Models\Payment;
use App\Models\Property;
use App\Models\Renter;
use App\Models\Unit;
use Illuminate\Http\Request;

class RentController extends Controller
{
    // field = [name, label, input type, select options, required]
    private function def(string $t): array
    {
        $o = fn ($m, $with = []) => $m::with($with)->get()->mapWithKeys(fn ($x) => [$x->id => $x->label])->all();

        return match ($t) {
            'properties' => ['m' => Property::class, 'title' => 'Properties', 'with' => [], 'cols' => ['name', 'type', 'address'], 'f' => [
                ['name', 'Name', 'text', null, true],
                ['type', 'Type', 'select', ['house' => 'House', 'shop' => 'Shop', 'flat' => 'Flat', 'plaza' => 'Plaza', 'hostel' => 'Hostel'], true],
                ['address', 'Address', 'text', null, false]]],
            'units' => ['m' => Unit::class, 'title' => 'Rooms, shops and flats', 'with' => ['property'], 'cols' => ['property.name', 'name', 'monthly_rent', 'status'], 'f' => [
                ['property_id', 'Property', 'select', $o(Property::class), true],
                ['name', 'Name (Shop 5, Room 3)', 'text', null, true],
                ['monthly_rent', 'Monthly rent (Rs)', 'number', null, true],
                ['capacity', 'Beds / capacity', 'number', null, false]]],
            'renters' => ['m' => Renter::class, 'title' => 'Renters', 'with' => [], 'cols' => ['name', 'phone', 'cnic'], 'f' => [
                ['name', 'Name', 'text', null, true], ['phone', 'Phone (03xx...)', 'text', null, true],
                ['cnic', 'CNIC', 'text', null, false], ['guardian_phone', 'Guardian phone', 'text', null, false]]],
            'leases' => ['m' => Lease::class, 'title' => 'Rent agreements', 'with' => ['unit', 'renter'], 'cols' => ['unit.name', 'renter.name', 'rent_amount', 'due_day', 'status'], 'f' => [
                ['unit_id', 'Room / shop', 'select', $o(Unit::class, ['property']), true],
                ['renter_id', 'Renter', 'select', $o(Renter::class), true],
                ['rent_amount', 'Monthly rent (Rs)', 'number', null, true],
                ['due_day', 'Due day of month (1-28)', 'number', null, true],
                ['start_date', 'Start date', 'date', null, true],
                ['deposit', 'Deposit (Rs)', 'number', null, false]]],
            'payments' => ['m' => Payment::class, 'title' => 'Payments', 'with' => ['lease.unit', 'lease.renter'], 'cols' => ['lease.unit.name', 'lease.renter.name', 'amount', 'for_month', 'method'], 'f' => [
                ['lease_id', 'Agreement', 'select', $o(Lease::class, ['unit', 'renter']), true],
                ['amount', 'Amount (Rs)', 'number', null, true],
                ['for_month', 'Rent for month', 'month', null, true],
                ['paid_on', 'Paid on', 'date', null, true],
                ['method', 'Method', 'select', ['cash' => 'Cash', 'jazzcash' => 'JazzCash', 'easypaisa' => 'Easypaisa', 'bank' => 'Bank'], true]]],
        };
    }

    public function dashboard()
    {
        $month = now()->startOfMonth();
        $paid = Payment::whereDate('for_month', $month)->where('status', 'approved')->pluck('lease_id')->all();
        $dues = Lease::with(['unit', 'renter'])->where('status', 'active')->get()
            ->reject(fn ($l) => in_array($l->id, $paid))
            ->map(function ($l) use ($month) {
                $due = $month->copy()->day(min($l->due_day, $month->daysInMonth));
                $l->days_late = $due->isPast() ? (int) $due->diffInDays(now()) : 0;
                $p = preg_replace('/\D/', '', $l->renter->phone);
                $p = str_starts_with($p, '0') ? '92'.substr($p, 1) : $p;
                $msg = "Assalam o Alaikum {$l->renter->name}, {$l->unit->name} ka {$month->format('F')} ka kiraya Rs {$l->rent_amount} baqi hai. Shukriya.";
                $l->wa = "https://wa.me/{$p}?text=".rawurlencode($msg);

                return $l;
            })->sortByDesc('days_late');

        return view('dashboard', [
            'dues' => $dues, 'month' => $month,
            'collected' => Payment::whereDate('for_month', $month)->sum('amount'),
            'vacant' => Unit::where('status', 'vacant')->count(),
        ]);
    }

    public function index(string $type)
    {
        $d = $this->def($type);

        return view('list', ['type' => $type, 'd' => $d, 'rows' => $d['m']::with($d['with'])->latest()->get()]);
    }

    public function store(Request $r, string $type)
    {
        $d = $this->def($type);
        $rules = collect($d['f'])->mapWithKeys(fn ($f) => [$f[0] => ($f[4] ? 'required' : 'nullable').($f[2] == 'number' ? '|integer|min:0' : '|max:255')])->all();
        $data = $r->validate($rules);
        if ($type === 'payments') {
            $data['for_month'] .= '-01';
        }
        $d['m']::create($data);
        if ($type === 'leases') {
            Unit::whereKey($data['unit_id'])->update(['status' => 'occupied']);
        }

        return back()->with('ok', 'Saved.');
    }

    public function destroy(string $type, int $id)
    {
        $row = $this->def($type)['m']::findOrFail($id);   // owner scope blocks other owners' rows
        if ($type === 'leases') {
            Unit::whereKey($row->unit_id)->update(['status' => 'vacant']);
        }
        $row->delete();

        return back()->with('ok', 'Deleted.');
    }
}
