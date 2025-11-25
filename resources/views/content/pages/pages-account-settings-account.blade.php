@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')

@section('page-script')
    <script src="{{ asset('assets/js/pages-account-settings-account.js') }}"></script>
@endsection

@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Account Settings /</span> Account
    </h4>

    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-pills flex-column flex-md-row mb-4 gap-2 gap-lg-0">
                <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i
                            class="mdi mdi-account-outline mdi-20px me-1"></i>Account</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('pages/account-settings-notifications') }}"><i
                            class="mdi mdi-bell-outline mdi-20px me-1"></i>Notifications</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('pages/account-settings-connections') }}"><i
                            class="mdi mdi-link mdi-20px me-1"></i>Connections</a></li>
            </ul>
            <div class="card-body">
                <form id="formAccountSettings" method="POST" action="{{ route('pages-update-settings-account') }}"
                    enctype="multipart/form-data">
                    @csrf

                    {{-- Show global error alert --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Show success message --}}
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        {{-- Profile image --}}
                        <img src="{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('assets/img/avatars/1.png') }}"
                            alt="user-avatar" class="d-block w-px-120 h-px-120 rounded" id="uploadedAvatar" />

                        <div class="button-wrapper">
                            <label for="upload" class="btn btn-primary me-2 mb-3" tabindex="0">
                                <span class="d-none d-sm-block">Upload new photo</span>
                                <i class="mdi mdi-tray-arrow-up d-block d-sm-none"></i>
                                <input type="file" id="upload" name="profile_image" class="account-file-input" hidden
                                    accept="image/png, image/jpeg, image/gif" />
                            </label>
                            <button type="button" class="btn btn-outline-danger account-image-reset mb-3" id="resetImage">
                                <i class="mdi mdi-reload d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Reset</span>
                            </button>
                            <div class="text-muted small">Allowed JPG, GIF or PNG. Max size of 800K</div>
                        </div>
                    </div>

                    <div class="row mt-4 gy-4">
                        {{-- Name --}}
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control @error('name') is-invalid @enderror" type="text"
                                    id="name" name="name" value="{{ old('name', $user->name) }}" autofocus />
                                <label for="name">Name</label>
                            </div>
                            @error('name')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input class="form-control @error('email') is-invalid @enderror" type="email"
                                    id="email" name="email" value="{{ old('email', $user->email) }}" />
                                <label for="email">E-mail</label>
                            </div>
                            @error('email')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Phone --}}
                        <div class="col-md-6">
                            <div class="input-group input-group-merge">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" id="phoneNumber" name="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone', $user->phone) }}" placeholder="202 555 0111" />
                                    <label for="phoneNumber">Phone Number</label>
                                </div>
                                <span class="input-group-text">US (+1)</span>
                            </div>
                            @error('phoneNumber')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- New Password --}}
                        <div class="col-md-6">
                            <div class="form-floating form-floating-outline">
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="New password" />
                                <label for="password">New Password</label>
                            </div>
                            @error('password')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary me-2">Save changes</button>
                        <button type="reset" class="btn btn-outline-secondary">Reset</button>
                    </div>
                </form>
            </div>



        </div>
    </div>
@endsection
