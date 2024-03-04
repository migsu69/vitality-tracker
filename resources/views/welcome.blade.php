@extends('layouts.app')

@section('content')

    {{-- Alert Modal --}}
    <div id="errorModal" class="modal fade">
        <div class="modal-dialog modal-confirm">
            <div class="modal-content">
                <div class="modal-header justify-content-center">
                    <div class="icon-box">
                        <i class="material-icons">&#xE5CD;</i>
                    </div>
                </div>
                <div class="modal-body text-center">
                    <h1>Ooops!</h1>	
                    <h5>{{ session('error') }}.</h5>
                    <button class="btn btn-success" data-dismiss="modal">Try Again</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Alert Modal End --}}

    <div class="container">
        <div class="row">
            <div class="col-12 col-xl-6 offset-xl-3 my-5">
                <div class="card bg-dark bg-opacity-25">
                    <div class="card-body m-xl-4">
                        <div class="text-center">
                            <img src="{{ asset('img/logo.png') }}"class="logo">
                            <h2 class="fw-bold text-danger">MABALACAT CITY COLLEGE</h2>
                        </div>
                        <hr>
                        <p class="text-muted">Scan the QR Code in your ID to Login:</p>
                        <div class="w-100 border" id="qr-box">
                            <video id="scanner" height="100%" width="100%"></video>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection