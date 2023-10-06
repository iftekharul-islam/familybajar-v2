<?php

namespace App\Http\Controllers;

use App\Models\GlobalSetting;
use App\Models\GlobalWithdrawSetting;
use App\Models\ManualSetting;
use App\Models\User;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function global()
    {
        $breadcrumbs = [
            ['name' => "Settings"], ['link' => "settings/global", 'name' => "Global"]
        ];
        $userOptions = User::get();
        $settings = GlobalSetting::latest()->first();
        return view('pages.settings.global', compact('settings', 'userOptions', 'breadcrumbs'));
    }

    public function manualEdit($id)
    {
        $breadcrumbs = [
            ['name' => "Settings"], ['link' => "settings/manual", 'name' => "Global"]
        ];
        $userOptions = User::get();
        $settings = ManualSetting::with('user')->where('user_id', $id)->first();
        return view('pages.settings.manual-edit', compact('settings', 'userOptions', 'breadcrumbs'));
    }

    public function updateManual(Request $request, $id)
    {
//        return $request->all();
        $total = 0;
        $total = $total + $request->buyer + $request->dealer;
        foreach ($request->percentage ?? [] as $percentage) {
            if ($percentage <= 0) {
                return $percentage;
                return redirect()->back()->with('error', 'Percentage must be greater than 0')->withInput();
            }
            $total += $percentage;
        }

        $manual_list = [];
        foreach ($request->manual ?? [] as $key => $manual) {
            if ($manual <= 0) {
                return $manual;
                return redirect()->back()->with('error', 'Manual percentage must be greater than 0')->withInput();
            }
            $total += $manual;
            $manual_list[] = [
                'user_id' => $request->user_id[$key],
                'percentage' => $manual,
            ];
        }
        if ($total != 100) {
            return redirect()->back()->with('error', 'Total percentage must be 100')->withInput();
        }
        ManualSetting::updateOrCreate(
            ['id' => $id],
            [
                'hierarchy' => count($request->percentage),
                'percentage' => $request->percentage,
                'manual' => $manual_list,
                'buyer' => $request->buyer,
                'dealer' => $request->dealer,
            ]
        );

        return redirect()->route('manual')->with('success', 'Manual setting updated successfully!')->withInput();
    }

    public function updateGlobal(Request $request)
    {
        $total = 0;
        $total = $total + $request->buyer + $request->dealer;
        foreach ($request->percentage ?? [] as $percentage) {
            if ($percentage <= 0) {
                return redirect()->back()->with('error', 'Percentage must be greater than 0')->withInput();
            }
            $total += $percentage;
        }
        $manual_list = [];
        foreach ($request->manual ?? [] as $key => $manual) {
            if ($manual <= 0) {
                return redirect()->back()->with('error', 'Percentage must be greater than 0')->withInput();
            }
            $total += $manual;
            $manual_list[] = [
                'user_id' => $request->user_id[$key],
                'percentage' => $manual,
            ];
        }
        if ($total != 100) {
            return redirect()->back()->with('error', 'Total percentage must be 100')->withInput();
        }
        GlobalSetting::create([
            'hierarchy' => count($request->percentage),
            'percentage' => $request->percentage,
            'manual' => $manual_list,
            'buyer' => $request->buyer,
            'dealer' => $request->dealer,
        ]);
        return redirect()->back()->with('success', 'Global Settings Updated successfully')->withInput();
    }


    public function manual()
    {
        $breadcrumbs = [
            ['name' => "Settings"], ['name' => "Manual"]
        ];
        $users = ManualSetting::with('user')->paginate('10');
        return view('pages.settings.manual', compact('users', 'breadcrumbs'));
    }

    public function manualAdd()
    {
        $breadcrumbs = [
            ['name' => "Settings"], ['link' => "settings/manual", 'name' => "Manual"], ['name' => "Add"]
        ];
        $users = User::where('type', config('status.type_by_name.seller'))->whereDoesntHave('manual_mapping')->get();
        $userOptions = User::get();
        return view('pages.settings.manual-add', compact('users', 'userOptions', 'breadcrumbs'));
    }

    public function createManual(Request $request)
    {
        $request->validate(
            [
                'user' => 'required|exists:users,id',
            ],
            [
                'user.required' => 'User is required',
                'user.exists' => 'User is not exists',
            ]
        );
        $total = 0;
        $total = $total + $request->buyer + $request->dealer;
        foreach ($request->percentage ?? [] as $percentage) {
            if ($percentage <= 0) {
                return redirect()->back()->with('error', 'Percentage must be greater than 0')->withInput();
            }
            $total += $percentage;
        }
        $manual_list = [];
        foreach ($request->manual ?? [] as $key => $manual) {
            if ($manual <= 0) {
                return redirect()->back()->with('error', 'Percentage must be greater than 0')->withInput();
            }
            $total += $manual;
            $manual_list[] = [
                'user_id' => $request->user_id[$key],
                'percentage' => $manual,
            ];
        }
        if ($total != 100) {
            return redirect()->back()->with('error', 'Total percentage must be 100')->withInput();
        }


        ManualSetting::create([
            'user_id' => $request->user,
            'hierarchy' => count($request->percentage ?? []) + count($manual_list),
            'percentage' => $request->percentage ?? [],
            'manual' => $manual_list ?? [],
            'buyer' => $request->buyer,
            'dealer' => $request->dealer,
        ]);
        return redirect()->route('manual')->with('success', 'Manual setting created successfully!')->withInput();
    }

    public function withdraw()
    {
        $breadcrumbs = [
            ['name' => "Settings"], ['name' => "Withdraw"]
        ];
        $settings = GlobalWithdrawSetting::first();
        return view('pages.settings.withdraw', compact('settings', 'breadcrumbs'));
    }

    public function withdrawUpdate(Request $request)
    {
        $request->validate(
            [
                'minimum_withdraw_amount' => 'required|numeric|min:0',
                'company_charge' => 'required|numeric|min:0|max:100'
            ],
            [
                'minimum_withdraw_amount.required' => 'Withdraw is required',
                'minimum_withdraw_amount.numeric' => 'Withdraw must be numeric',
                'minimum_withdraw_amount.min' => 'Withdraw must be greater than or equal 0',
                'company_charge.required' => 'Company charge is required',
                'company_charge.numeric' => 'Company charge must be numeric',
                'company_charge.min' => 'Company charge must be greater than or equal 0',
                'company_charge.max' => 'Company charge must be less than 100',
            ]
        );
        $settings = GlobalWithdrawSetting::first();
        if($settings) {
            $settings->update([
                'minimum_withdraw_amount' => $request->minimum_withdraw_amount ?? 0,
                'company_charge' => $request->company_charge ?? 0,
            ]);
            return redirect()->back()->with('success', 'Withdraw Settings Updated successfully')->withInput();
        } else {
            GlobalWithdrawSetting::create([
                'minimum_withdraw_amount' => $request->minimum_withdraw_amount ?? 0,
                'company_charge' => $request->company_charge ?? 0,
            ]);

            return redirect()->back()->with('success', 'Withdraw Settings created successfully')->withInput();
        }
    }
}
