@extends('layouts/contentLayoutMaster')

@section('title', 'New User')

@section('content')
    <section id="basic-horizontal-layouts">
        <div class="row">
            <div class="col-md-6 col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Add new user</h4>
                    </div>
                    <div class="card-body">
                        <form class="form form-horizontal" action="{{ route('user.edited', $user_data->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="first-name">Image</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="file" class="form-control" name="image" placeholder="Image" />
                                            @error('image')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-1 row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label" for="first-name">Name</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" id="first-name" class="form-control" name="name"
                                                placeholder="Name" value="{{ $user_data->name }}" />
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
                                                placeholder="Email" value="{{ $user_data->email }}" disabled />
                                            @error('email')
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
                                                value={{ $user_data->type }} disabled>
                                                <option value="1" {{ $user_data->type == 1 && 'selected' }}>Admin
                                                </option>
                                                <option value="2" {{ $user_data->type == 2 && 'selected' }}>Seller
                                                </option>
                                                <option value="3" {{ $user_data->type == 3 && 'selected' }}>Customer
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
                                            <input type="text" id="ref_by" class="form-control" name="ref_by"
                                                placeholder="jdk7s6" value="{{ $user_data?->refer?->name }}" disabled />
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
