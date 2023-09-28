@extends('layouts/contentLayoutMaster')

@section('title', 'Profile')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset(mix('fonts/font-awesome/css/font-awesome.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/jstree.min.css')) }}">
@endsection
@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/page-profile.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-tree.css')) }}">
@endsection

@section('content')
    <div id="user-profile">
        <div class="row">
            <div class="col-12">
                <div class="card profile-header mb-2">
                    <img class="card-img-top" src="{{ asset('images/profile/user-uploads/timeline.jpg') }}"
                        alt="User Profile Image" />
                    <div class="position-relative">
                        <div class="profile-img-container d-flex align-items-center">
                            <div class="profile-img">
                                <img src="https://i2.wp.com/ui-avatars.com/api/{{ $user->name }}/400"
                                    class="rounded img-fluid" alt="Card image" />
                            </div>
                            <div class="profile-title ms-3">
                                <h2 class="text-white">{{ $user->name }}</h2>
                                <p class="text-white">{{ config('status.type')[$user->type] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="profile-header-nav mb-1">
                        <nav
                            class="navbar navbar-expand-md navbar-light justify-content-end justify-content-md-between w-100">

                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <section id="profile-info">
            <div class="row">
                <div class="col-lg-6 col-12 order-2 order-lg-1">
                    <div class="card">
                        <div class="card-header">
                            <h5>
                                Personal Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 col-12 order-2 order-lg-1">
                                    <div class="mt-2">
                                        <h5 class="mb-75">Email:</h5>
                                        <p class="card-text">{{ $user->email }}</p>
                                    </div>
                                    <div class="mt-2">
                                        <h5 class="mb-75">Joined at:</h5>
                                        <p class="card-text">{{ $user->created_at->format('d M Y') }}</p>
                                    </div>
                                    <div class="mt-2">
                                        <h5 class="mb-75">Reference Code:</h5>
                                        <p class="card-text">{{ $user->ref_code }}</p>
                                    </div>
                                    <div class="mt-2">
                                        <h5 class="mb-75">Refered By:</h5>
                                        <p class="card-text">{{ $user->refer->name ?? 'N/A' }}</p>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-12 order-2 order-lg-1">
                                    <div class="mt-2">
                                        <h5 class="mb-75">Re-purchased Amount:</h5>
                                        <p class="card-text">{{ $user->repurchase_amount }} tk</p>
                                    </div>
                                    <div class="mt-2">
                                        <h5 class="mb-75">Withdraw Amount:</h5>
                                        <p class="card-text">{{ $user->withdraw_amount }} tk</p>
                                    </div>
                                    <div class="mt-2">
                                        <h5 class="mb-75">Current Amount:</h5>
                                        <p class="card-text">{{ $user->total_amount }} tk</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5>Your Generations</h5>
                        </div>
                        <div class="card-body">
                            <div id="jstree-basic">
                                <ul>
                                    @foreach ($tree as $item)
                                        @include('pages/users/treeItem', ['user' => $item])
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-12 order-1 order-lg-2">
                    <div class="card">
                        <div class="card-header">
                            <h5>
                                Your Orders
                            </h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Seller</th>
                                        <th>Repurchase Amount</th>
                                        <th>Total Amount</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user->orders as $order)
                                        <tr>
                                            <td>
                                                <span class="fw-bold">{{ $order->id }}</span>
                                            </td>
                                            <td>{{ $order->seller->name ?? 'N/A' }}</td>
                                            <td>{{ $order->repurchase_price ?? 0 }} tk</td>
                                            <td>{{ $order->total_price ?? 0 }} tk</td>
                                            <td>
                                                <a class="" href="/order/{{ $order->id }}">
                                                    <i data-feather="eye" class="me-50"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-body d-flex justify-content-center">
                            <a href="/orders">see more</a>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5>
                                Your Withdraws
                            </h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Seller</th>
                                        <th>Repurchase Amount</th>
                                        <th>Total Amount</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @foreach ($user->orders as $order)
                                        <tr>
                                            <td>
                                                <span class="fw-bold">{{ $order->id }}</span>
                                            </td>
                                            <td>{{ $order->seller->name }}</td>
                                            <td>{{ $order->repurchase_price }}</td>
                                            <td>{{ $order->total_price ?? 'N/A' }}</td>
                                            <td>
                                                <a class="" href="/order/{{ $order->id }}">
                                                    <i data-feather="eye" class="me-50"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach --}}
                                </tbody>
                            </table>
                        </div>
                        <div class="card-body d-flex justify-content-center">
                            <a href="/orders">see more</a>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
@endsection

@section('page-script')
    <script src="{{ asset(mix('js/scripts/pages/page-profile.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/extensions/ext-component-tree.js')) }}"></script>
@endsection


@section('vendor-script')
    <script src="{{ asset(mix('vendors/js/extensions/jstree.min.js')) }}"></script>
@endsection
