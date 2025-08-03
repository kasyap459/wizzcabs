@extends('customercare.layout.base')

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
    <h1 class="m-b-1 center">Welcome back, {{ Auth::guard('customercare')->user()->name }}</h1>
    <div class="row">
        <div class="blocks">
        </div>
        <div class="blocks">
        </div>
    </div>
  </div>
</div>
@endsection
