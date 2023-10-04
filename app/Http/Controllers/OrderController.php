<?php

namespace App\Http\Controllers;

use App\Models\GlobalSetting;
use App\Models\ManualSetting;
use App\Models\Order;
use App\Models\RepurchaseHistory;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $users = User::all();
        $breadcrumbs = [
            ['name' => "Order"]
        ];
        $orders = Order::query()->with('seller', 'customer');
        if ($request->has('user_id')) {
            $orders->where('customer_id', $request->user_id)
                ->orWhere('seller_id', $request->user_id);
        }
        if (Auth::user()->type == config('status.type_by_name.seller')) {
            $orders = $orders->where('seller_id', Auth::user()->id);
        } else if (Auth::user()->type != config('status.type_by_name.admin')) {
            $orders = $orders->where('customer_id', Auth::user()->id);
        }
        $orders = $orders->latest()->paginate('10');
        $customers = User::whereIn('type', [2, 3])->get();
        $sellers = User::where('type', 2)->get();

        return view('pages.orders.list', compact('orders', 'breadcrumbs', 'users', 'sellers', 'customers'));
    }

    public function orderShow($id)
    {
        $breadcrumbs = [
            ['link' => "orders", 'name' => "Order"], ['name' => "Details"]
        ];
        $order = Order::with('seller', 'customer', 'repurchase_history.user')->find($id);

        return view('pages.orders.view', compact('order', 'breadcrumbs'));
    }

    public function orderAdd(Request $request)
    {
        $breadcrumbs = [
            ['link' => "orders", 'name' => "Order"], ['name' => "Add"]
        ];
        $customers = User::where('type', 3)->get();
        $sellers = User::where('type', 2)->get();
        return view('pages.orders.add', compact('customers', 'sellers', 'breadcrumbs'));
    }

    public function orderAddButton(Request $request)
    {
        $request->validate(
            [
                'seller_id' => 'required',
                'customer_id' => 'required',
                'total_price' => 'required',
                'repurchase_price' => 'required|lt:total_price'
            ],
            [
                'seller_id.required' => 'Seller is required',
                'customer_id.required' => 'Customer is required',
                'total_price.required' => 'Total price is required',
                'repurchase_price.required' => 'Repurchase price is required',
                'repurchase_price.lt' => 'Repurchase price must be less than total price'
            ]
        );
        try {
            $exception = DB::transaction(function () use ($request) {
                $data = $request->only(['seller_id', 'customer_id', 'total_price', 'repurchase_price']);
                $order = Order::create($data);
                $user = User::find($request->seller_id);
                $user->update([
                    'total_order_amount' => $user->total_order_amount + $request->total_price,
                    'total_order_repurchase_amount' => $user->total_order_repurchase_amount + $request->repurchase_price,
                ]);
                $this->repurchase_calculation($order);
            });

            if (is_null($exception)) {
                return redirect()->route('orders')->with('success', 'Order Created successfully!')->withInput();
            } else {
                throw new Exception;
            }
        } catch (Exception $e) {
            info($e->getMessage());
            return redirect()->route('orderAdd')
                ->with('error', 'Something wents wrong!')->withInput();
        }
    }

    public function repurchase_calculation($order)
    {
        $total = 0;
        $serial = 1;
        $user = User::find($order->customer_id);
        $settings_type = 2;
        $settings = ManualSetting::where('user_id', $order->seller_id)->first();
        if (!$settings) {
            $settings = GlobalSetting::latest()->first();
            $settings_type = 1;
        }
        $order->update([
            'setting_id' => $settings->id,
            'setting_type' => $settings_type,
        ]);
        foreach ($settings->percentage ?? [] as $index => $percentage) {
            $user = User::where('ref_code', $user->ref_by)->first();
            if ($user) {
                RepurchaseHistory::create([
                    'order_id' => $order->id,
                    'user_id' => $user->id,
                    'amount' => $order->repurchase_price * $percentage / 100,
                    'percentage' => $percentage,
                    'chain_serial' => $serial,
                    'is_heirarchy' => true,
                ]);
                $total += $percentage;

                $user->update([
                    'repurchase_amount' => $user->repurchase_amount + $order->repurchase_price * $percentage / 100,
                    'total_amount' => $user->total_amount + $order->repurchase_price * $percentage / 100,
                ]);
                $serial++;
            } else {
                break;
            }
        }
        foreach ($settings->manual ?? [] as $index => $manual) {
            RepurchaseHistory::create([
                'order_id' => $order->id,
                'user_id' => $manual['user_id'],
                'amount' => $order->repurchase_price * $manual['percentage'] / 100,
                'percentage' => $manual['percentage'],
                'chain_serial' => $serial,
                'is_heirarchy' => false,
            ]);
            $total += $manual['percentage'];

            $user = User::find($manual['user_id']);
            $user->update([
                'repurchase_amount' => $user->repurchase_amount + $order->repurchase_price * $manual['percentage'] / 100,
                'total_amount' => $user->total_amount + $order->repurchase_price * $manual['percentage'] / 100,
            ]);
            $serial++;
        }

        if ($settings->buyer > 0) {
            RepurchaseHistory::create([
                'order_id' => $order->id,
                'user_id' => $order->customer_id,
                'amount' => $order->repurchase_price * $settings->buyer / 100,
                'percentage' => $settings->buyer,
                'chain_serial' => $serial,
                'is_heirarchy' => false,
                'remarks' => "Customer Commision"
            ]);
            $total += $settings->buyer;

            $user = User::find($order->customer_id);
            $user->update([
                'repurchase_amount' => $user->repurchase_amount + $order->repurchase_price * $settings->buyer / 100,
                'total_amount' => $user->total_amount + $order->repurchase_price * $settings->buyer / 100,
            ]);

            $serial++;
        }
        if ($settings->dealer > 0) {
            RepurchaseHistory::create([
                'order_id' => $order->id,
                'user_id' => $order->seller_id,
                'amount' => $order->repurchase_price * $settings->dealer / 100,
                'percentage' => $settings->dealer,
                'chain_serial' => $serial,
                'is_heirarchy' => false,
                'remarks' => "Dealer Commision"
            ]);
            $total += $settings->dealer;

            $user = User::find($order->seller_id);
            $user->update([
                'repurchase_amount' => $user->repurchase_amount + $order->repurchase_price * $settings->dealer / 100,
                'total_amount' => $user->total_amount + $order->repurchase_price * $settings->dealer / 100,
            ]);
            $serial++;
        }
        if ($total < 100) {
            RepurchaseHistory::create([
                'order_id' => $order->id,
                'user_id' => 1,
                'amount' => $order->repurchase_price * (100 - $total) / 100,
                'percentage' => 100 - $total,
                'chain_serial' => $serial,
                'is_heirarchy' => false,
                'remarks' => "Remaining amount"
            ]);
            $user = User::find(1);
            $user->update([
                'repurchase_amount' => $user->repurchase_amount + $order->repurchase_price * (100 - $total) / 100,
                'total_amount' => $user->total_amount + $order->repurchase_price * (100 - $total) / 100,
            ]);
        }
    }

    public function repurchaseHistory(Request $request)
    {
        $users = User::all();
        $breadcrumbs = [
            ['name' => "Re-purchase History"]
        ];
        $histories = RepurchaseHistory::query()->with('user', 'order.customer', 'order.seller');
        if ($request->has('customer_id')) {
            $histories = $histories->where('user_id', $request->customer_id);
        }
        if (Auth::user()->type != config('status.type_by_name.admin')) {
            $orders = $histories->where('user_id', Auth::user()->id);
        }
        $histories = $histories->paginate('10');
        return view('pages.repurchase.list', compact('histories', 'breadcrumbs', 'users'));
    }
}
