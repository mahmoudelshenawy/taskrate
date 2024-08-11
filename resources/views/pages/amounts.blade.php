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
    <div class="card">
        <div class="card-header">
            <button class="btn btn-success float-right" data-toggle="modal" data-target="#addNewAmountModal">Add new
                Amount</button>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Amounts</th>
                        @foreach ($currencies as $currency)
                            <th>{{ $currency }}</th>
                        @endforeach
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($amounts as $amount)
                        <tr>
                            <td>{{ number_format($amount->amount, 2) }} {{ $amount->currency }}</td>
                            @foreach ($currencies as $currency)
                                <td>
                                    {{ number_format($amount->amount * ($exchange_rates[$amount->currency][$currency] ?? 1), 2) }}
                                </td>
                            @endforeach
                            <td>
                                <div>
                                    <button class="btn btn-sm btn-info"
                                        onclick="openEditModal({{ $amount->id }},'{{ $amount->amount }}', '{{ $amount->currency }}')">Edit</button>
                                    <button class="btn btn-sm btn-danger"
                                        onclick="openDeleteModal({{ $amount->id }})">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
        <!-- /.card-body -->
    </div>

    <div class="modal fade" id="addNewAmountModal">
        <div class="modal-dialog addNewAmountModal">
            <form action="{{ route('addNewAmount') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add new Amount</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <label for="amount">amount</label>
                            <input type="number" name="amount" min="0" class="form-control">
                            @error('amount')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="currency">currency</label>
                            <select name="currency" id="currency" class="form-control">
                                <option value="" hidden>select currency</option>
                                @foreach ($currencies as $currency)
                                    <option value="{{ $currency }}">{{ $currency }}</option>
                                @endforeach
                            </select>
                            @error('currency')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </form>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="editAmountModal">
        <div class="modal-dialog editAmountModal">
            <form action="{{ route('editAmount') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">edit Amount</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" class="id" name="id_edit">
                        <div class="form-group">
                            <input type="number" min="0" name="amount_edit" class="form-control amount">
                            @error('amount')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="currency">currency</label>
                            <select name="currency_edit" class="form-control currency">
                                <option value="" hidden>select currency</option>
                                @foreach ($currencies as $currency)
                                    <option value="{{ $currency }}">{{ $currency }}</option>
                                @endforeach
                            </select>
                            @error('currency')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </form>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="deleteAmountModal">
        <div class="modal-dialog deleteAmountModal">
            <form action="{{ route('deleteAmount') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h4 class="modal-title">delete Amount</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" class="id" name="id_delete">
                        <p>Are you sure you want to delete this Amount?</p>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </div>
            </form>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection

@push('js')
    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "autoWidth": false,
            });
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });

        function openEditModal(id, amount, currency) {
            $(".amount").val(amount);
            $(".id").val(id);
            $(".currency").val(currency);
            $("#editAmountModal").modal("show");
        }

        function openDeleteModal(id) {
            $(".id").val(id);
            $("#deleteAmountModal").modal("show");
        }
    </script>
@endpush
