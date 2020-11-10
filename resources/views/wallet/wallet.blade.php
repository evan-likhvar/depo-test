@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                        <form action="{{route('wallet.enter.post')}}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="amount">Add amount to your wallet</label>
                                <input type="text" class="form-control" name="amount" id="amount" placeholder="amount">
                                @error('amount')
                                <small id="amount" class="form-text text-muted">
                                    <div class="alert alert-danger">{{ $message }}</div>
                                </small>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
