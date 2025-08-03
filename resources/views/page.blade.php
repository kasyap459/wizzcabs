<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>{{ $page->page_title }}</title>
	<style>
	body{
		color: #595959 !important;
    font-size: 14px !important;
    font-family: Roboto, Arial;
	}
	.container{
		width: 64%;
	    margin: 0 auto;
	    padding-top: 70px;
	    padding-bottom: 50px;
	}
	p{
		font-size: 15px;
    	text-align: justify;
		text-align: justify;
		font-family: Roboto, Arial;
		line-height: 25px;
	}
	</style>
</head>
<body>
	<div class="container">
<img src="{{ Setting::get('site_logo','') }}" style="width: 100px">
 <h2 style="font-size: 26px;color: #000000 !important;"><strong>{{ $page->page_title }}</strong></h2>
 {!! $page->content !!}
</div>
</body>
</html>