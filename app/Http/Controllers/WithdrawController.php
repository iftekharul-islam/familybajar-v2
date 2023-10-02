<?php

namespace App\Http\Controllers;

use App\Models\GlobalWithdrawSetting;
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

        if (Auth::user()->type == config('status.type_by_name.admin') && $request->has('user_id')) {
            $withdraws->where('user_id', $request->user_id);
        } else {
            $withdraws->where('user_id', Auth::user()->id);
        }
        $withdraws = $withdraws->latest()->paginate('10');

        $withdraw_settings = GlobalWithdrawSetting::first();

        return view('pages.withdraw.list', compact('withdraws', 'breadcrumbs', 'users', 'withdraw_settings'));
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
        $withdraw_settings = GlobalWithdrawSetting::first();
        if ($withdraw_settings->minimum_withdraw_amount > $request->withdraw_amount) {
            return redirect()->back()
                ->with('error', 'Minimum withdraw amount is ' . $withdraw_settings->minimum_withdraw_amount . ' tk')->withInput();
        };

        if (Auth::user()->total_amount < $request->withdraw_amount) {
            return redirect()->back()
                ->with('error', 'You do not have enough balance!')->withInput();
        }
        $withdraw = WithdrawHistory::create([
            'user_id' => Auth::user()->id,
            'amount' => $request->withdraw_amount,
            'company_charge' => $request->withdraw_amount * $withdraw_settings->company_charge / 100,
            'withdrawable_amount' => $request->withdraw_amount - ($request->withdraw_amount * $withdraw_settings->company_charge / 100),
            'status' => 1,
        ]);
        $user = Auth::user();
        $user->update([
            'withdraw_amount' => $user->withdraw_amount + $request->withdraw_amount,
            'total_amount' => $user->total_amount - $request->withdraw_amount,
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
        $withdraw_settings = GlobalWithdrawSetting::first();
        return view('pages.withdraw.list', compact('withdraws', 'breadcrumbs', 'users', 'withdraw_settings'));
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
                    'trxID' => 'required',
                ],
                [
                    'trxID.required' => 'Transaction ID is required',
                ]
            );
            $withdraw->update([
                'status' => $request->status,
                'trxID' => $request->trxID ?? null,
                'remarks' => $request->remarks ?? null,
            ]);
            $user = User::find(auth()->user()->id);
            $user->update([
                'user_withdraw_amount' => $user->user_withdraw_amount + $withdraw->withdrawable_amount,
                'user_withdraw_charge' => $user->user_withdraw_charge + $withdraw->company_charge,
            ]);
            return redirect()->route('withdrawRequests')
                ->with('success', 'Withdraw request approved successfully!');
        } elseif ($withdraw->status == 1 && $request->status == 4) {
            $withdraw->update([
                'status' => $request->status,
                'trxID' => $request->trxID ?? null,
                'remarks' => $request->remarks ?? null,
            ]);
            $user = $withdraw->user;
            $user->update([
                'withdraw_amount' => $user->withdraw_amount - $withdraw->amount,
                'total_amount' => $user->total_amount + $withdraw->amount,
            ]);
            return redirect()->route('withdrawRequests')
                ->with('success', 'Withdraw request rejected successfully!');
        } else {
            return redirect()->route('withdrawRequests')
                ->with('error', 'Withdraw request already approved or rejected or canceled!');
        }
    }
}
