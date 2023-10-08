@extends('layouts/contentLayoutMaster')

@section('title', 'New User')

@section('content')
    <section id="basic-horizontal-layouts">
        <div class="row">
            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit User : <span class="badge badge-glow bg-primary">{{ $user_data->name }}
                            </span></h4>
                    </div>
                    <div class="card-body">
                        <form class="form form-horizontal" action="{{ route('user.edited', $user_data->id) }}" method="POST"
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
                                                placeholder="Name" value="{{ old('name') ?? $user_data->name }}" />
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
                                                placeholder="Email" value="{{ old('email') ?? $user_data->email }}"
                                                disabled />
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
                                                placeholder="phone number"
                                                value="{{ old('phone') ?? $user_data->phone }}" />
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
                                            <select class="hide-search form-select" id="select2-hide-search" name="type"
                                                value={{ old('type') ?? $user_data->phone }}>
                                                {{-- <option
                                                    value="1"{{ old('type') ?? $user_data->type == 1 ? 'selected' : '' }}>
                                                    Admin</option> --}}
                                                @if (Auth::user()->type != config('status.type_by_name.customer'))
                                                    <option
                                                        value="2"{{ old('type') ?? $user_data->type == 2 ? 'selected' : '' }}>
                                                        Seller</option>
                                                @endif
                                                <option value="3"
                                                    {{ old('type') ?? $user_data->type == 3 ? 'selected' : '' }}>Customer
                                                </option>
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
                                            <select class="select2 form-select" id="ref_id" name="ref_by" {{ auth()->user()->type == config('status.type_by_name.admin') ? null : 'disabled' }}>
                                                <option value="" disabled selected>Select a User</option>
                                                @foreach ($userList ?? [] as $customer)
                                                    <option value="{{ $customer->ref_code }}"
                                                        {{ old('ref_by') ?? $user_data->ref_by == $customer->ref_code ? 'selected' : '' }}>
                                                        {{ $customer->name }} ({{ $customer->email }})
                                                    </option>
                                                @endforeach
                                            </select>
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
                                                    type="text" name="nominee_name" placeholder="Nominee Name"
                                                    value="{{ old('nominee_name') ?? $user_data->nominee_name }}" />
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
                                                    type="text" name="nominee_relation" placeholder="Nominee Relation"
                                                    value="{{ old('nominee_relation') ?? $user_data->nominee_relation }}" />
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
                                                    type="text" name="nominee_nid" placeholder="Nominee NID"
                                                    value="{{ old('nominee_nid') ?? $user_data->nominee_nid }}" />
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
                                                <select class="select2 form-select" id="customer_id" name="package">
                                                    <option value="" disabled selected>Select a Package</option>
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <option value="{{ $i }}"
                                                            {{ old('package') ?? $user_data->package == $i ? 'selected' : '' }}>
                                                            Level-{{ $i }}
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
                                                            name="can_create_customer" id="inlineRadio1" value="1"
                                                            {{ $user_data->can_create_customer == 1 ? 'checked' : '' }} />
                                                        <label class="form-check-label" for="inlineRadio1">Yes</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio"
                                                            name="can_create_customer" id="inlineRadio2" value="0"
                                                            {{ $user_data->can_create_customer != 1 ? 'checked' : '' }} />
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
    </section>
@endsection
