@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">{{ __('Deposit list') }}</div>

                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Сумма вклада</th>
                            <th scope="col">Процент</th>
                            <th scope="col">Начислений</th>
                            <th scope="col">Начислено</th>
                            <th scope="col">Состояние</th>
                            <th scope="col">Дата</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($user->deposits as $deposit)
                            <tr>
                                <td>{{$deposit->id}}</td>
                                <td>{{$deposit->invested}}</td>
                                <td>{{$deposit->percent}}</td>
                                <td>{{$deposit->accrue_times}}</td>
                                <td>{{$deposit->accrue_times * $deposit->invested * $deposit->percent / 100}}</td>
                                <td>{{$deposit->active == 0 ? 'Closed' : 'Active'}}</td>
                                <td>{{$deposit->created_at}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
