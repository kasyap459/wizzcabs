@extends('admin.layout.base')

@section('title', 'Update Driver ')

@section('styles')
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/jquery.datetimepicker.min.css" />
    <link rel="stylesheet" href="{{ asset('main/vendor/select2/dist/css/select2.min.css') }}">
@endsection

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">

            <div class="row bg-title">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <h4 class="page-title">@lang('admin.member.drivers')</h4><a href="{{ route('admin.provider.index') }}"
                        class="btn btn-outline-warning btn-rounded w-min-sm m-l-0-75 waves-effect waves-light">@lang('admin.list_drivers')</a>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">

                    <ol class="breadcrumb">
                        <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                        <li class="active">@lang('admin.member.update_driver')</li>
                    </ol>
                </div>
            </div>

            <div class="box box-block bg-white">
                <h5 style="margin-bottom: 2em;">@lang('admin.member.update_driver')</h5>

                <form class="form-horizontal" action="{{ route('admin.provider.store', ['id' => $provider->id]) }}"
                    method="POST" enctype="multipart/form-data" role="form">
                    {{ csrf_field() }}
                    {{-- <input type="hidden" name="_method" value="PATCH"> --}}
                    @include('admin/providers/_form', ['model' => $providerForm])
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.full.min.js">
    </script>
    <script type="text/javascript" src="{{ asset('main/vendor/select2/dist/js/select2.min.js') }}"></script>
    <script type="text/javascript">
        var mindate = {!! json_encode(\Carbon\Carbon::today()->format('Y-m-d\TH:i')) !!}
        $('#license_expire').datetimepicker({
            format: 'Y-m-d',
            timepicker: false,
            minDate: mindate
        });

        $('[data-plugin="select2"]').select2($(this).attr('data-options'));
        $('#allowed_service').val([{{ $provider->allowed_service }}]).trigger('change');
        $('#language').val([{{ $provider->language }}]).trigger('change');
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".mobile-int").each(function() {
                var input = this;
                var iti = window.intlTelInput(input, {
                    initialCountry: "in", // default India
                    separateDialCode: true,
                    nationalMode: false, // always return full international format
                    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/utils.js"
                });

                // Save instance on element for later use
                $(input).data("iti", iti);
            });

            // On form submit → replace value with full international number
            $("form").on("submit", function() {
                $(".mobile-int").each(function() {
                    var iti = $(this).data("iti");
                    if (iti) {
                        $(this).val(iti.getNumber());
                    }
                });
            });
        });
    </script>

@endsection
