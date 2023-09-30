<?php

namespace App\Http\Controllers;

use App\Models\GlobalSetting;
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
        $total = 0;
        $total = $total + $request->buyer + $request->dealer;
        foreach ($request->percentage ?? [] as $percentage) {
            if ($percentage < 1) {
                return redirect()->back()->with('error', 'Percentage must be greater than 0')->withInput();
            }
            $total += $percentage;
        }
        $manual_list = [];
        foreach ($request->manual ?? [] as $key => $manual) {
            if ($manual < 1) {
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
        ManualSetting::updateOrCreate(
            [ 'id' => $id ],
            [
                'hierarchy' => count($request->percentage),
                'percentage' => $request->percentage,
                'manual' => $manual_list,
                'buyer' => $request->buyer,
                'dealer' => $request->dealer,
            ]
        );

        return redirect()->route('manual');
    }

    public function updateGlobal(Request $request)
    {
        $total = 0;
        $total = $total + $request->buyer + $request->dealer;
        foreach ($request->percentage ?? [] as $percentage) {
            if ($percentage < 1) {
                return redirect()->back()->with('error', 'Percentage must be greater than 0')->withInput();
            }
            $total += $percentage;
        }
        $manual_list = [];
        foreach ($request->manual ?? [] as $key => $manual) {
            if ($manual < 1) {
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

        return redirect()->route('global');
    }


    public function manual()
    {
        $breadcrumbs = [
            ['name' => "Settings"], ['name' => "Manual"]
        ];
        $users = ManualSetting::with('user')->get();
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
            if ($percentage < 1) {
                return redirect()->back()->with('error', 'Percentage must be greater than 0')->withInput();
            }
            $total += $percentage;
        }
        $manual_list = [];
        foreach ($request->manual ?? [] as $key => $manual) {
            if ($manual < 1) {
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
        return redirect()->route('manual');
    }
}
