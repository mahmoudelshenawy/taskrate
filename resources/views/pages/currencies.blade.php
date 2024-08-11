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
            <button class="btn btn-success float-right" data-toggle="modal" data-target="#addNewCurrenyModal">Add new
                Currency</button>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Currency Code</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($currencies as $code)
                        <tr>
                            <td>{{ $code }}</td>
                            <td>
                                <div>
                                    <button class="btn btn-sm btn-info"
                                        onclick="openEditModal('{{ $code }}')">Edit</button>
                                    <button class="btn btn-sm btn-danger"
                                        onclick="openDeleteModal('{{ $code }}')">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
        <!-- /.card-body -->
    </div>

    <div class="modal fade" id="addNewCurrenyModal">
        <div class="modal-dialog addNewCurrenyModal">
            <form action="{{ route('addNewCurrency') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add new currency</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="code" class="form-control"
                            placeholder="add currency code like: USD, UER..">
                        @error('code')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
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

    <div class="modal fade" id="editCurrenyModal">
        <div class="modal-dialog editCurrenyModal">
            <form action="{{ route('editCurrency') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">edit currency</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" class="code_edit" name="old_code">
                        <input type="text" name="code" class="form-control code_edit"
                            placeholder="add currency code like: USD, UER..">
                        @error('code')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
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
    <div class="modal fade" id="deleteCurrencyModal">
        <div class="modal-dialog deleteCurrencyModal">
            <form action="{{ route('deleteCurrency') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <h4 class="modal-title">delete currency</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" class="code_edit" name="code">
                        <p>Are you sure you want to delete this currency?</p>
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

        function openEditModal(code) {
            $(".code_edit").val(code);
            $("#editCurrenyModal").modal("show");
        }

        function openDeleteModal(code) {
            $(".code_edit").val(code);
            $("#deleteCurrencyModal").modal("show");
        }
    </script>
@endpush
