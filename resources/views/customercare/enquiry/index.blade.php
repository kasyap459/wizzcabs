@extends('customercare.layout.base')

@section('title', 'Enquiry')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">

        <div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                
                <ol class="breadcrumb">
                    <li><a href="{{ route('customercare.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">List Enquiries</li>
                </ol>
            </div>
        </div>

        <div class="box box-block bg-white">
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>@lang('admin.member.id')</th>
                        <th>Ticket ID</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Enquiry</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($enquiries as $index => $enquiry)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $enquiry->ticket_id }}</td>
                        <td>{{ $enquiry->user_name }}</td>
                        <td>{{ $enquiry->mobile }}</td>
                        <td>{{ $enquiry->enquiry }}</td>
                        <td>
                            @if($enquiry->status==0)
                            <a class="btn btn-danger btn-rounded btn-sm waves-effect waves-light" href="{{ route('customercare.inprogress', $enquiry->id ) }}"
                           >Open</a>   
                            @elseif($enquiry->status==1)
                            <a class="btn btn-warning btn-rounded btn-sm waves-effect waves-light" href="{{ route('customercare.closed', $enquiry->id ) }}">Inprogress</a>
                            @elseif($enquiry->status==2)
                            <a class="btn btn-success btn-rounded btn-sm waves-effect waves-light">Closed</a>
                            @endif
                        </td>
                        <td>{{  date("Y-m-d h:i A", strtotime($enquiry->created_at))}} </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>@lang('admin.member.id')</th>
                        <th>Name</th>
                        <th>Ticket ID</th>
                        <th>Mobile</th>
                        <th>Enquiry</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection