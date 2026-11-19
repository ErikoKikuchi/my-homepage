<?php

namespace App\Http\Controllers\Pilates\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pilates\Client;
use App\Models\Pilates\GuestPassPurchase;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountingController extends Controller
{
    public function clients(Request $request)
    {
        $period = $request->query('period') === '3months' ? '3months' : '30days';
        $today = today('Asia/Tokyo');
        $start = $period === '3months'
            ? $today->copy()->subMonthsNoOverflow(3)
            : $today->copy()->subDays(29);

        $recent = GuestPassPurchase::query()
            ->whereBetween('purchased_at', [$start->toDateString(), $today->toDateString()]);
        $amount = DB::connection('client_db')->table('payment_items')
            ->join('payments', 'payments.id', '=', 'payment_items.payment_id')
            ->whereColumn('payment_items.guest_pass_purchase_id', 'guest_pass_purchases.id')
            ->where('payments.status', 'succeeded')
            ->where('payments.currency', 'jpy')
            ->selectRaw('SUM(payment_items.unit_price * payment_items.quantity)');

        $purchases = (clone $recent)
            ->select(['id', 'client_id', 'purchased_at', 'pass_type', 'passes_purchased'])
            ->addSelect(['amount' => $amount])
            ->with('client:id,name')
            ->orderByDesc('purchased_at')
            ->orderByDesc('id')
            ->paginate(20, pageName: 'purchase_page')
            ->withQueryString()
            ->through(fn (GuestPassPurchase $purchase) => [
                'id' => $purchase->id,
                'date' => $purchase->purchased_at->toDateString(),
                'client' => $purchase->client->only(['id', 'name']),
                'type' => $purchase->pass_type,
                'passes' => $purchase->passes_purchased,
                'amount' => $purchase->amount === null ? null : (int) $purchase->amount,
            ]);

        return Inertia::render('Admin/Pilates/Sessions/Clients', [
            'section' => 'accounting',
            'recentPurchases' => [
                'period' => $period,
                'buyerCount' => $recent->distinct()->count('client_id'),
                'purchases' => $purchases,
            ],
            'clients' => Client::query()
                ->active()
                ->where('is_provisional', false)
                ->orderBy('name')
                ->orderBy('id')
                ->paginate(20, ['id', 'name'])->withQueryString(),
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/Pilates/Accounting/Index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
