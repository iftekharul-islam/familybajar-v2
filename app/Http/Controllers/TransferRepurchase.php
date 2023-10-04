<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\TransferRepurchaseHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransferRepurchase extends Controller
{
    public function index(Request $request)
    {
        $breadcrumbs = [
            ['name' => "Transfer Repurchase"]
        ];
        $histories = TransferRepurchaseHistory::query()->with('seller');
        if ($request->has('seller_id')) {
            $histories->where('user_id', $request->seller_id);
        }
        if (Auth::user()->type == config('status.type_by_name.seller')) {
            $histories = $histories->where('user_id', Auth::user()->id);
        }
        $histories = $histories->latest()->paginate('10');
        $sellers = User::where('type', 2)->get();

        return view('pages.transfer_repurchase.list', compact('histories', 'breadcrumbs', 'sellers'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'amount' => 'required|numeric|min:1',
                'account' => 'required',
                'trxID' => 'required',
            ],
            [
                'amount.required' => 'Amount is required',
                'amount.min' => 'Amount must be greater than 0',
                'account.required' => 'Transfer Medium is required',
                'trxID.required' => 'TrxID is required',
            ]
        );

        if (auth()->user()->total_order_repurchase_amount < $request->amount) {
            return redirect()->back()->with('error', 'You do not have enough balance!')->withInput();
        }

        $seller = User::find(auth()->user()->id);
        $seller->total_order_repurchase_amount -= $request->amount;
        $seller->save();

        TransferRepurchaseHistory::create([
            'user_id' => auth()->user()->id,
            'amount' => $request->amount,
            'trxID' => $request->trxID,
            'account' => $request->account,
            'status' => 1,
            'remarks' => $request->remarks
        ]);

        return redirect()->route('transfer.index')->with('success', 'Transfer Repurchase Successful');
    }

    public function cancel($id)
    {
        $withdraw = TransferRepurchaseHistory::with('seller')->find($id);
        if ($withdraw->status == 1) {
            $withdraw->update([
                'status' => 2,
            ]);
            $user = $withdraw->seller;
            $user->total_order_repurchase_amount += $withdraw->amount;
            $user->save();

            return redirect()->route('transfer.index')
                ->with('success', 'Withdraw request canceled successfully!');
        }
        return redirect()->route('transfer.index')
            ->with('error', 'Withdraw request already canceled!');
    }

    public function edit($id)
    {
        $breadcrumbs = [
            ['link' => "transfer-repurchase", 'name' => "Transfer Repurchase"], ['name' => "Edit"]
        ];
        $history = TransferRepurchaseHistory::find($id);
        return view('pages.transfer_repurchase.edit', compact('history', 'breadcrumbs'));
    }

    public function update(Request $request)
    {
        $request->validate(
            [
                'status' => 'required',
            ]
        );
        $transfer = TransferRepurchaseHistory::find($request->id);
        if ($transfer->status == 1 && $request->status == 3) {

            $transfer->update([
                'status' => $request->status,
                'remarks' => $request->remarks ?? null,
            ]);

            $user = User::find(auth()->user()->id);
            $user->seller_repurchase_transfer_amount += $transfer->amount;
            $user->save();

            return redirect()->route('transfer.index')
                ->with('success', 'Transfer request approved successfully!');
        } elseif ($transfer->status == 1 && $request->status == 4) {
            $transfer->update([
                'status' => $request->status,
                'remarks' => $request->remarks ?? null,
            ]);
            $user = User::where('id', $transfer->user_id)->first();
            $user->total_order_repurchase_amount += $transfer->amount;
            $user->save();
            return redirect()->route('transfer.index')
                ->with('success', 'Transfer request rejected successfully!');
        } else {
            return redirect()->route('transfer.index')
                ->with('error', 'Transfer request already approved or rejected or canceled!');
        }
    }
}
