@extends('admin.layout.base')

@section('title', 'Drivers ')
@section('styles')
<style>
	ul.dropdown-menu.show {
    		top: 76px !important;
	}
	ul.dropdown-menu {
    		top: 76px !important;
	}
</style>
@endsection
@section('content')
<div class="content-area py-1">
    <div class="container-fluid">

        <!-- <div class="row bg-title">
             <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">@lang('admin.member.drivers')</h4><a href="{{ route('admin.provider.create') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">@lang('admin.member.add_new_driver')</a>
            </div> 
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">@lang('admin.list_drivers')</li>
                </ol>
            </div>
        </div> -->

        <div class="box box-block bg-white">
            <table class="table table-striped table-bordered dataTable" id="provider-list">
                <thead>
                    <tr>
                    <th>@lang('admin.member.id')</th>
                        <th>@lang('admin.member.full_name')</th>
                        <th>@lang('admin.member.email')</th>
                        <th>@lang('admin.member.mobile')</th>
                        <th>Current Earnings</th>
                        <th>View</th>
                        <th>Reset Driver Earnings</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div id="resetdrivermodal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Reset Driver Earnings</h4>
      </div>
      <div class="modal-body">
      <form action="{{ route('admin.earnings.reset')}}" method="POST" id="senddata" role="form" enctype="multipart/form-data">
      {{csrf_field()}}
           <div class="form-group row">
                    <label for="amount" class="col-xs-12 col-form-label">Enter the Amount</label>
                    <div class="col-xs-8">
                        <input class="form-control" type="number" value="{{ old('amount') }}" name="amount" required id="amount" placeholder="Enter the Amount">
                    </div>
                    <input  type="hidden" value="" name="driverid"  id="driverid" >
                    
                </div>
        <!-- <p style="text-align: center;"></p> -->
        <!-- <button type="submit" id="resetid" class="btn btn-success btn-block waves-effect waves-light col-xs-8">Update</button> -->
        <p style="text-align: center;" id="resetid"></p>
      </div>
</form>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('admin.triplist.close')</button>
      </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function update_content(){
        $('#provider-list').DataTable({
            "destroy": true,
            "responsive": true,
            "dom": 'Bfrtip',
            "buttons": [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            "oLanguage": {
                'sProcessing': '<i class="fa fa-circle-o-notch fa-spin" style="font-size:24px;color:#a377b1;"></i>'
            },
            "processing": true,
            "serverSide": true,
            "ajax":{
                     "url": "{{ route('admin.cashout.provider.row') }}",
                     "dataType": "json",
                     "type": "POST",
                     "data":function ( d ) {
                            d._token = "{{csrf_token()}}";
                        },
                   },
            "columns": [
                { "data": "id" },
                { "data": "full_name" },
                { "data": "email" },
                { "data": "mobile" },
                { "data": "wallet_balance" },
                { "data": "view" },
                { "data": "action" },
            ]    

        });
    }

    $(window).load(function(){
        update_content();
    });
</script>

<script>
    $(document).on('click','#resetdriver', function() {
        $("#resetid").empty();
        var amount = $("#amount").val();
        var id = $(this).attr("data-id");
        //   $("#resetid").append("<a href='reset/"+id+"?amount="+amount+"'  class='btn btn-success'>Update</a>");
     $("#driverid").val(id);
        $("#resetid").append("<a> <button type='submit'  id='resetid' class='btn btn-success '>Update</button> </a>");
           $("#resetdrivermodal").modal("toggle");
    });
</script>

@endsection 