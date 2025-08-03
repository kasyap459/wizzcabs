@extends('corporate.layout.base')

@section('title', 'Dashboard ')

@section('styles')
<style type="text/css">
.center{
  text-align: center;
}
.blocks{
    width: 67%;
    margin: 30px auto;
    padding: 30px;
}
.icon-blk {
    width: 68px;
    display: inline-block;
}
.text-blk {
    width: 82%;
    display: inline-block;
}
.fa.pull-right {
    margin-left: .3em;
}
.intro-arrow{
  display: inline-block;
  vertical-align: middle;
}
.blocks p{
  font-size: 13px;
}
</style>

@endsection

@section('content')

<div class="content-area py-1">
<div class="container-fluid">
  <div class="row bg-title">
        
    </div>
	<div id="content">
    
	</div>
  <div class="box box-block bg-white">
    <h1 class="m-b-1 center" style="color: #e91e63">Welcome back, {{ Auth::guard('corporate')->user()->display_name }}</h1>
    <div class="row">
        <div class="blocks">
          <div class="col-md-12">
            <a href="{{ route('corporate.group.create') }}" style="color: #e91e63">
              <div class="icon-blk">
                  <i class="fa fa-th fa-4x fa-corp push-10-r"></i>
              </div>
              <div class="text-blk">
                <h3>Create Group</h3>
                <p>Control the ride policies and travel budgets for your employees</p>
              </div>
                <i class="pull-right fa fa-angle-right fa-2x fa-corp intro-arrow"></i>
              </a>
          </div>
        </div>
        <div class="blocks">
          <div class="col-md-12">
            <a href="{{ route('corporate.user.create') }}" style="color: #e91e63">
              <div class="icon-blk">
                  <i class="fa fa-th fa-4x fa fa-users push-10-r"></i>
              </div>
              <div class="text-blk">
                <h3>Add Employess</h3>
                <p>Empower your employees to book official rides</p>
              </div>
                <i class="pull-right fa fa-angle-right fa-2x fa-corp intro-arrow"></i>
            </a>
          </div>
        </div>
    </div>
  </div>
</div>
@endsection
