<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WithdrawHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawController extends Controller
{
    public function withdraws(Request $request)
    {
        $users = User::all();
        $breadcrumbs = [
            ['name' => "Withdraw"]
        ];
        $withdraws = WithdrawHistory::query();

        if(Auth::user()->type == config('status.type_by_name.admin') && $request->has('user_id')){
            $withdraws->where('user_id', $request->user_id);
        } else {
            $withdraws->where('user_id', Auth::user()->id);
        }
        $withdraws = $withdraws->latest()->paginate('10');

        return view('pages.withdraw.list', compact('withdraws', 'breadcrumbs', 'users'));
    }

    public function withdrawAdd(Request $request)
    {
        $breadcrumbs = [
            ['link' => "withdraws", 'name' => "Withdraw"], ['name' => "Add"]
        ];
        return view('pages.withdraw.add', compact('breadcrumbs'));
    }

    public function withdrawAddButton(Request $request)
    {
        $request->validate(
            [
                'amount' => 'required|numeric|min:1500',
            ],
            [
                'amount.required' => 'Amount is required',
                'amount.min' => 'Minimum amount should be 1500 tk',
            ]
        );
        if (Auth::user()->total_amount < $request->amount) {
            return redirect()->route('withdrawAdd')
                ->with('error', 'You do not have enough balance!')->withInput();
        }
        $withdraw = WithdrawHistory::create([
            'user_id' => Auth::user()->id,
            'amount' => $request->amount,
            'status' => 1,
        ]);
        $user = Auth::user();
        $user->update([
            'withdraw_amount' => $user->withdraw_amount + $request->amount,
            'total_amount' => $user->total_amount - $request->amount,
        ]);
        return redirect()->route('withdraws')
            ->with('success', 'Withdraw request sent successfully!');
    }

    public function withdrawCancelButton($id)
    {
        $withdraw = WithdrawHistory::with('user')->find($id);
        if ($withdraw->status == 1) {
            $withdraw->update([
                'status' => 2,
            ]);
            $user = $withdraw->user;
            $user->update([
                'withdraw_amount' => $user->withdraw_amount - $withdraw->amount,
                'total_amount' => $user->total_amount + $withdraw->amount,
            ]);
            return redirect()->route('withdraws')
                ->with('success', 'Withdraw request canceled successfully!');
        }
        return redirect()->route('withdraws')
            ->with('error', 'Withdraw request already canceled!');
    }

    public function withdrawRequests(Request $request)
    {
        $users = User::all();
        $breadcrumbs = [
            ['name' => "Withdraw Requests"]
        ];
        $withdraws = WithdrawHistory::latest()->paginate('10');
        return view('pages.withdraw.list', compact('withdraws', 'breadcrumbs', 'users'));
    }

    public function withdrawRequestEdit($id)
    {
        $breadcrumbs = [
            ['link' => "withdraw-requests", 'name' => "Withdraw Requests"], ['name' => "Edit"]
        ];
        $withdraw = WithdrawHistory::find($id);
        return view('pages.withdraw.edit', compact('withdraw', 'breadcrumbs'));
    }

    public function withdrawRequestEditButton(Request $request)
    {
        $request->validate(
            [
                'status' => 'required',
            ]
        );
        $withdraw = WithdrawHistory::find($request->id);


        if ($withdraw->status == 1 && $request->status == 3) {
            $request->validate(
                [
                    'TrxID' => 'required',
                ],
                [
                    'TrxID.required' => 'Transaction ID is required',
                ]
            );
            $withdraw->update([
                'status' => $request->status,
                'remarks' => $request->remarks,
            ]);
            return redirect()->route('withdrawRequestEdit', $request->id)
                ->with('success', 'Withdraw request approved successfully!');
        } elseif ($withdraw->status == 1 && $request->status == 4) {
            $withdraw->update([
                'status' => $request->status,
                'remarks' => $request->remarks,
            ]);
            $user = $withdraw->user;
            $user->update([
                'withdraw_amount' => $user->withdraw_amount - $withdraw->amount,
                'total_amount' => $user->total_amount + $withdraw->amount,
            ]);
            return redirect()->route('withdrawRequestEdit', $request->id)
                ->with('success', 'Withdraw request rejected successfully!');
        } else {
            return redirect()->route('withdrawRequestEdit', $request->id)
                ->with('error', 'Withdraw request already approved or rejected or canceled!');
        }
    }
}
