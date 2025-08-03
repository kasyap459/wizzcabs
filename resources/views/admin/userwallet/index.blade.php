@extends('admin.layout.base')

@section('title', 'User Wallet')
@section('styles')
<style>
.perfect-scrollbar-on .main-panel, .perfect-scrollbar-on .sidebar {
    height: auto !important;
    max-height: none !important;
}
</style>

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">

        <div class="row bg-title">
            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                <h4 class="page-title">User Wallet</h4>
<!--                 <a href="{{ route('admin.userwallet.create') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">Add User Wallet</a>
 -->            </div>
        </div>

        <div class="box box-block bg-white">
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>@lang('admin.member.id')</th>
                        <th>@lang('admin.member.name')</th>
                        <th>@lang('admin.member.email')</th>
                        <th>@lang('admin.member.mobile')</th>
                        <th>wallet</th>
<!--                         <th>@lang('admin.member.action')</th>
 -->                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><a href="{{ route('admin.credit', $user->id) }}" id="addwallet" data-id="{{ $user->id }}">{{$user->first_name}}</a>
                         </td>
                        <td>{{ $user->email }}</td>
                        <td> {{ $user->mobile }}</td>
                        <td>{{ Setting::get('currency', '$') }} {{ $user->wallet_balance }}</td>
<!--                         <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-info btn-sm waves-effect waves-light dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                @lang('admin.member.action')
                            </button>
                            <div class="dropdown-menu">
                                <a href="{{ route('admin.credit', $user->id) }}" class="dropdown-item">
                                    <i class="fa fa-pencil-square-o"></i>Credited
                                </a>
                                <a href="{{ route('admin.credit', $user->id) }}" class="dropdown-item">
                                    <i class="fa fa-pencil-square-o"></i>Debited
                                </a>
                            </div>
                        </div>
                        </td>
 -->                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>@lang('admin.member.id')</th>
                        <th>@lang('admin.member.name')</th>
                        <th>@lang('admin.member.email')</th>
                        <th>@lang('admin.member.mobile')</th>
                        <th>wallet</th>
<!--                         <th>@lang('admin.member.action')</th>
 -->                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

  


<div class="modal fade" tabindex="-1" role="dialog" id="waletmodal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add Walet</h4>
            <form class="form-horizontal" action="{{route('admin.userwallet.store')}}" method="POST" enctype="multipart/form-data" role="form">
      </div>
                {{csrf_field()}}      
        <div class="modal-body">
            <label for="ex2">Amount</label>
            <input class="form-control" id="amount" type="text" name="amount" required>
            <input type="hidden" name="user_id" id="user_id" value="">
      </div>
      <div class="modal-footer">
         <button type="submit" id="submit" class="btn btn-sm justify mx-auto waves-effect waves-light submitter" style="background-color: #27AB18;color:#fff;" onclick=>Cancel</button>
         <button type="submit" id="submit" class="btn btn-sm justify mx-auto waves-effect waves-light submitter" style="background-color: #27AB18;color:#fff;" onclick=>Add Wallet</button>
      </div>
     </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</html>
@endsection

@section('scripts')
<script>
    /*$(document).on('click','#addwallet', function() {
        $('#waletmodal').modal("show");
        var id = $(this).attr("data-id");
            $('#user_id').val(id);
        });*/
</script>
@endsection
