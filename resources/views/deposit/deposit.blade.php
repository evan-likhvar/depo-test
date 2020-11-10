@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Create deposit') }}</div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                        <form action="{{route('deposit.create.post')}}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="amount">Create deposit</label>
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
