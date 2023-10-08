@extends('layouts/contentLayoutMaster')

@section('title', 'User List')

@section('content')
    <div class="row" id="table-hover-row">
        <div class="col-12">
            <div class="card">
                {{-- <div class="card-header">
                    <h4 class="card-title">User List</h4>
                </div> --}}
                <div class="card-body">
                    <form action="{{ route('users') }}" method="get">
                        <div class="row">
                            <div class="col-3">
                                <input type="text" class="form-control" id="floating-label1"
                                    placeholder="Search By name or email" name="search"
                                    value="{{ Request()->get('search') }}" />
                            </div>
                            <div class="col-2">
                                <select class="select2 form-select" id="user_type" name="user_type">
                                    <option value="" disabled selected>Select a User type</option>
                                    <option value="3" {{ Request()->get('user_type') == 3 ? 'selected' : '' }}>
                                        Customer</option>
                                    <option value="2" {{ Request()->get('user_type') == 2 ? 'selected' : '' }}>
                                        Seller</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <button type="submit" class="btn btn-primary ml-5">Search</button>
                            </div>
                            <div class="col-3">
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                        data-bs-target="#editUser">Add User</button>
                            </div>
                        </div>
                    </form>
                    {{--                    <form action="{{ route('users') }}" method="get"> --}}
                    {{--                        <div class="form-floating"> --}}
                    {{--                            <input type="text" class="form-control" id="floating-label1" --}}
                    {{--                                placeholder="Search By name or email" name="search" --}}
                    {{--                                value="{{ Request()->get('search') }}" /> --}}
                    {{--                            <label for="floating-label1">Search here</label> --}}
                    {{--                            <select class="select2 form-select" id="customer_id" name="user_type"> --}}
                    {{--                                <option value="" disabled selected>Select a User type</option> --}}
                    {{--                                    <option value="1">Customer</option> --}}
                    {{--                                    <option value="2">Dealer</option> --}}
                    {{--                            </select> --}}
                    {{--                            <button type="submit" class="btn btn-primary ml-5">Search</button> --}}
                    {{--                        </div> --}}
                    {{--                    </form> --}}

                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Wallet amount</th>
                                <th>Referred By</th>
                                <th>Created at</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        {{-- <img src="{{ asset('images/icons/angular.svg') }}" class="me-75" height="20" --}}
                                        {{-- width="20" alt="Angular" /> --}}
                                        <span class="fw-bold">
                                            {{ $user->name }}
                                        </span><br>
                                        @if (!empty($user->phone))
                                            <small>{{ $user->phone }}</small><br>
                                        @endif
                                        <small>{{ $user->email }}</small><br>
                                        <small class="badge bg-info">{{ $user->ref_code }}</small>
                                    </td>
                                    <td>
                                        @if ($user->status == true)
                                            <span class="badge badge bg-success">Active</span>
                                        @else
                                            <span class="badge badge bg-danger">Deactive</span>
                                        @endif
                                        <br />
                                        <span class="badge badge bg-info">{{ 'Level-' . $user->package }}</span>
                                        <br />
                                        <span class="badge badge bg-primary">{{ config('status.type')[$user->type] }}</span>
                                    </td>
                                    <td>
                                        <span>Repurchase amount BDT: {{ $user->repurchase_amount }} ৳</span> <br>
                                        <span>Withdraw amount BDT: {{ $user->withdraw_amount }} ৳</span> <br>
                                        <span>Total amount BDT: {{ $user->total_amount }} ৳</span>
                                    </td>
                                    <td>
                                        @if (!empty($user->refer->name))
                                            <span>{{ $user->refer->name }}</span><br>
                                            @if (!empty($user->refer->phone))
                                                <span>{{ $user->refer->phone }}</span><br>
                                            @endif
                                            <small>{{ $user->refer->email }}</small>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d M Y H:ia') }}</td>
                                    <td>
                                        @if (Auth::user()->type == config('status.type_by_name.admin'))
                                            <a class="" href="{{ route('loginAsUser', $user->id) }}">
                                                <i data-feather='log-in' class="me-50"></i>
                                            </a>
                                            <a class="" href="{{ route('user.edit', $user->id) }}">
                                                <i data-feather="edit-2" class="me-50"></i>
                                            </a>
                                        @endif
                                        <a class="" href="{{ route('user.show', $user->id) }}">
                                            <i data-feather="eye" class="me-50"></i>
                                        </a>
                                        @if (Auth::user()->type == config('status.type_by_name.admin') && $user->status == true)
                                            <a class="delete-button" data-item-id="{{ $user->id }}" href="#">
                                                <i data-feather='trash-2' class="me-50"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mx-1 d-flex justify-content-end">
                        <nav aria-label="Page navigation">
                            <ul class="pagination mt-2">
                                <li class="page-item prev"><a class="page-link"
                                        style="pointer-events: {{ $users->currentPage() == 1 ? 'none' : '' }}"
                                        href="{{ $users->url($users->currentPage() - 1) }}"></a>
                                </li>
                                @for ($i = 1; $i <= $users->lastPage(); $i++)
                                    <li class="page-item {{ $i == $users->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $users->url($i) }}">{{ $i }}</a>
                                    </li>
                                @endfor
                                <li class="page-item next" disabled><a class="page-link"
                                        style="pointer-events: {{ $users->currentPage() == $users->lastPage() ? 'none' : '' }}"
                                        href="{{ $users->url($users->currentPage() + 1) }}"></a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
            <div class="modal-content">
                <div class="modal-header bg-transparent">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pb-5 px-sm-5 pt-50">
                    <div class="text-center mb-2">
                        <h1 class="mb-1">Add new user</h1>
                    </div>

                    <div class="card-body">
                        <form class="form form-horizontal" action="{{ route('userAddButton') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="first-name">Name</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" id="first-name" class="form-control" name="name"
                                                placeholder="Name" value="{{ old('name') }}" />
                                            @error('name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="email-id">Email</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="email" id="email-id" class="form-control" name="email"
                                                placeholder="Email" value="{{ old('email') }}" />
                                            @error('email')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="phn-id">Phone Number</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="number" id="phn-id" class="form-control" name="phone"
                                                placeholder="phone number" value="{{ old('phone') }}" />
                                            @error('number')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="phn-id">Image</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="file" id="image-id" class="form-control" name="image"
                                                placeholder="Image" />
                                            @error('image')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="type">Type</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <select class="hide-search form-select" id="type" name="type"
                                                value={{ old('type') }}>
                                                @if (Auth::user()->type == config('status.type_by_name.admin'))
                                                    {{-- <option value="1">Admin</option> --}}
                                                    <option value="2">Seller</option>
                                                @endif
                                                <option value="3" selected>Customer</option>
                                            </select>
                                            @error('type')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="ref_by">Refer By</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <select class="select2 form-select" id="customer_id" name="ref_by">
                                                <option value="" disabled selected>Select a User</option>
                                                @foreach ($userList ?? [] as $customer)
                                                    <option value="{{ $customer->ref_code }}"
                                                        {{ Request()->get('customer_id') == $customer->id ? 'selected' : '' }}>
                                                        {{ $customer->name }} ({{ $customer->email }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('ref_by')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="password">Password</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group-merge form-password-toggle">
                                                <input class="form-control form-control-merge" id="login-password"
                                                    type="password" name="password" placeholder="············"
                                                    aria-describedby="login-password" tabindex="2" />
                                                <span class="input-group-text cursor-pointer"><i
                                                        data-feather="eye"></i></span>
                                            </div>
                                            @error('password')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="nominee_name">Nominee Name</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group-merge form-password-toggle">
                                                <input class="form-control form-control-merge" id="nominee-name"
                                                    type="text" name="nominee_name" placeholder="Nominee Name" />

                                            </div>
                                            @error('nominee_name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="nominee_relation">Nominee Relation</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group-merge form-password-toggle">
                                                <input class="form-control form-control-merge" id="nominee-relation"
                                                    type="text" name="nominee_relation"
                                                    placeholder="Nominee Relation" />
                                            </div>
                                            @error('nominee_relation')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="nominee_nid">Nominee NID</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group-merge form-password-toggle">
                                                <input class="form-control form-control-merge" id="nominee-nid"
                                                    type="text" name="nominee_nid" placeholder="Nominee NID" />
                                            </div>
                                            @error('nominee_nid')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                @if (auth()->user()->type == config('status.type_by_name.admin'))
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="ref_by">Package</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <select class="hide-search form-select" id="package_id" name="package">
                                                    <option value="" disabled selected>Select a Package</option>
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <option value="{{ $i }}">Level-{{ $i }}
                                                        </option>
                                                    @endfor
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="ref_by"><b>Can create New
                                                        User?</b></label>
                                            </div>
                                            <div class="col-sm-9">
                                                <div class="demo-inline-spacing">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio"
                                                            name="can_create_customer" id="inlineRadio1"
                                                            value="1" />
                                                        <label class="form-check-label" for="inlineRadio1">Yes</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio"
                                                            name="can_create_customer" id="inlineRadio2" value="0"
                                                            checked />
                                                        <label class="form-check-label" for="inlineRadio2">No</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-sm-9 offset-sm-3">
                                    @if (session('error'))
                                        <div class="text-danger">
                                            {{ session('error') }}
                                        </div>
                                    @endif
                                    <button type="submit" class="btn btn-primary me-1">Submit</button>
                                    <button type="reset" class="btn btn-outline-secondary">Reset</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('vendor-script')
    <script>
        $(document).ready(function() {
            $('.delete-button').on('click', function() {
                const itemId = $(this).data('item-id');
                Swal.fire({
                    title: 'Are you confirm to deactive?',
                    // text: "",
                    html: "After deactiving this user, his/her generation with be added to his/her refer. <br /> <b class='text-danger'>You won't be able to revert this!</b>",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Confirm',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-outline-danger ms-1'
                    },
                    buttonsStyling: false
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            url: "user-delete/" + itemId,
                            type: "GET",
                            data: {
                                _token: "{{ csrf_token() }}",
                                // id: itemId
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deactived!',
                                    text: 'User has been deactivated.',
                                    customClass: {
                                        confirmButton: 'btn btn-success'
                                    }
                                }).then(function(result) {
                                    if (result.value) {
                                        window.location.reload();
                                    }
                                });
                            }
                        })
                    }
                })
            });
        });
    </script>
@endsection
