@extends('layouts.auth')
@section('title','Masuk ke Akun')
@section('content')
    <div class="page-header min-vh-75">
        <div class="container">
            <div class="row">
                <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
                    <div class="card card-plain mt-8">
                        <div class="card-header pb-0 text-left bg-transparent">
                            <h3 class="font-weight-bolder text-info text-gradient">Selamat Datang</h3>
                            <p class="mb-0">Masukan Email atau Username dan Password untuk Masuk</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col col-12 col-md-12 col-sm-12">
                                    @if($errors->any())
                                        <div class="alert alert-danger text-light" role="alert">
                                            {{$errors->first()}}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="row">
                                    <div class="col col-12 col-md-12 col-sm-12 col-xs-12">
                                        <label>Username / Email</label>
                                        <input type="username" name="username" class="form-control" placeholder="Username or Email" aria-label="username" aria-describedby="username-addon">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col col-12 col-md-12 col-sm-12 col-xs-12">
                                        <label>Password</label>
                                        <input type="password" name="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="password-addon">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col col-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="rememberMe" checked="true">
                                            <label class="form-check-label" for="rememberMe">Remember me</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col col-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                        <button type="submit" class="btn bg-gradient-info w-100 mt-4 mb-0">Masuk</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- <div class="card-footer text-center pt-0 px-lg-2 px-1">
                            <p class="mb-4 text-sm mx-auto">
                            Don't have an account?
                            <a href="javascript:;" class="text-info text-gradient font-weight-bold">Sign up</a>
                            </p>
                        </div> -->
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
                    <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image:url({{ asset('assets/main/img/backgroud.jpg') }})"></div>
                    </div>
                </div>
            </div>
        </div>
      </div>
@endsection