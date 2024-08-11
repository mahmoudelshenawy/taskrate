@extends('layouts.layout')
@push('css')
@endpush
@section('content')
    @if (session()->has('error'))
        <div class="alert alert-danger">{{ session()->get('error') }}</div>
    @endif
    @if (session()->has('success'))
        <div class="alert alert-success">{{ session()->get('success') }}</div>
    @endif
    <form action="{{ route('updateExchangeRate') }}" method="POST">
        <div class="row">
            @csrf
            @forelse ($exchange_rates as $currency => $list)
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h3>{{ $currency }}</h3>
                            @foreach ($list as $sub_curr => $amount)
                                <div class="form-group">
                                    <label for="">{{ $sub_curr }}</label>
                                    <input type="number" name="exchange_rates[{{ $currency }}][{{ $sub_curr }}]"
                                        class="form-control" value="{{ $amount }}">
                                </div>
                            @endforeach
                            <br>
                            <button type="submit" class="btn btn-primary">save</button>
                        </div>
                    </div>
                </div>
            @empty
                No Currencies Added Yet
            @endforelse
        </div>
    </form>
@endsection

@push('js')
    <script></script>
@endpush
