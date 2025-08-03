@extends('corporate.layout.base')

@section('title', 'Add Employee ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	
    	<div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">Employees</h4><a href="{{ route('corporate.user.index') }}" class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">List Employees</a>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                
                <ol class="breadcrumb">
                    <li><a href="{{ route('corporate.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">Add Employee</li>
                </ol>
            </div>
        </div>

    	<div class="box box-block bg-white">
			<h5 style="margin-bottom: 2em;">Add Employee</h5>
            <form class="form-horizontal" action="{{ route('corporate.user.store') }}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
				<div class="form-group row">
					<label for="emp_name" class="col-xs-2 col-form-label">Employee Name</label>
					<div class="col-xs-6">
						<input class="form-control" type="text" value="{{ old('emp_name') }}" name="emp_name" required id="emp_name" placeholder="Employee Name">
					</div>
				</div>

				<div class="form-group row">
					<label for="emp_email" class="col-xs-2 col-form-label">Employee Email ID</label>
					<div class="col-xs-6">
						<input class="form-control" type="email" required name="emp_email" value="{{old('emp_email')}}" id="emp_email" placeholder="Employee Email ID">
					</div>
				</div>

				<div class="form-group row">
					<label for="corporate_group_id" class="col-xs-2 col-form-label">Group name</label>
					<div class="col-xs-6">
						<select name="corporate_group_id" id="corporate_group_id" required class="form-control">
							<option value="">Select Group</option>
							@foreach($Groups as $Group)
								<option value="{{ $Group->id }}">{{ $Group->group_name }}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label for="emp_gender" class="col-xs-2 col-form-label">Employee Gender</label>
					<div class="col-xs-6">
						<select name="emp_gender" id="emp_gender" required="required" class="form-control">
                        	<option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
					</div>
				</div>
				<div class="form-group row">
					<label for="emp_code" class="col-xs-2 col-form-label">Employee Code</label>
					<div class="col-xs-6">
						<input class="form-control" type="text" value="{{ old('emp_code') }}" name="emp_code" id="emp_code" placeholder="Employee Code">
					</div>
				</div>

				<div class="form-group row">
					<label for="emp_phone" class="col-xs-2 col-form-label">Employee mobile number</label>
					<div class="col-xs-6">
						<input class="form-control" type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" value="{{ old('emp_phone') }}" name="emp_phone" id="emp_phone" placeholder="Employee mobile number">
					</div>
				</div>
	<!-- 			<div class="form-group row">
					<label for="manager_email" class="col-xs-2 col-form-label">Manager Email ID</label>
					<div class="col-xs-6">
						<input class="form-control" type="text" name="manager_email" value="{{old('manager_email')}}" id="manager_email" placeholder="Manager Email ID">
					</div>
				</div>

				<div class="form-group row">
					<label for="manager_name" class="col-xs-2 col-form-label">Manager Name</label>
					<div class="col-xs-6">
						<input class="form-control" type="text" value="{{ old('manager_name') }}" name="manager_name" id="manager_name" placeholder="Manager Name">
					</div>
				</div>
				<div class="form-group row">
					<label for="emp_brand" class="col-xs-2 col-form-label">Employee Brand</label>
					<div class="col-xs-6">
						<input class="form-control" type="text" value="{{ old('emp_brand') }}" name="emp_brand" id="emp_brand" placeholder="Employee Brand">
					</div>
				</div>

				<div class="form-group row">
					<label for="emp_costcenter" class="col-xs-2 col-form-label">Employee Cost Centre</label>
					<div class="col-xs-6">
						<input class="form-control" type="text" value="{{ old('emp_costcenter') }}" name="emp_costcenter" id="emp_costcenter" placeholder="Employee Cost Centre">
					</div>
				</div>

				<div class="form-group row">
					<label for="emp_desig" class="col-xs-2 col-form-label">Employee Designation</label>
					<div class="col-xs-6">
						<input class="form-control" type="text" value="{{ old('emp_desig') }}" name="emp_desig" id="emp_desig" placeholder="Employee Designation">
					</div>
				</div> -->

				<div class="form-group row">
					<label for="emp_baseloc" class="col-xs-2 col-form-label">Employee Base Location</label>
					<div class="col-xs-6">
						<input class="form-control" type="text" value="{{ old('emp_baseloc') }}" name="emp_baseloc" id="emp_baseloc" placeholder="Employee Base Location">
					</div>
				</div>
	
				<div class="form-group row">
					<label for="zipcode" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> Add Employee</button>
						<a href="{{route('corporate.user.index')}}" class="btn btn-inverse waves-effect waves-light">@lang('admin.member.cancel')</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection
