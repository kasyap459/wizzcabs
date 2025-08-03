@extends('admin.layout.base')

@section('title', 'Country ')

@section('styles')

<link rel="stylesheet" href="{{asset('main/vendor/multi-select/css/multi-select.css')}}">
  <link rel="stylesheet" href="{{asset('main/vendor/select2/dist/css/select2.min.css')}}">

<style>
	input[type="checkbox"]{
	  width: 20px !important; 
	  height: 20px !important;
	}
</style>
@endsection

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">

    	<div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">Country</h4>
            </div>
            <div class="col-lg-6 col-sm-6 col-md-6 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a></li>
                    <li class="active">Country</li>
                </ol>
            </div>
        </div>

    	<div class="box box-block bg-white">
    		<h5 style="margin-bottom: 2em;">Add Country</h5>
            <form class="form-horizontal" action="{{route('admin.country.store')}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
            	<div class="row">
            	<div class="col-md-6">	
					<div class="form-group row">
						<label for="country_id" class="col-xs-12 col-form-label">Country name</label>
						<div class="col-xs-12">
							<select id="country_id" name="country_id" class="form-control" data-plugin="select2">
								<option value="1">Afghanistan</option>
								<option value="2">Aland Islands</option>
								<option value="3">Albania</option>
								<option value="4">Algeria</option>
								<option value="5">AmericanSamoa</option>
								<option value="6">Andorra</option>
								<option value="7">Angola</option>
								<option value="8">Anguilla</option>
								<option value="9">Antarctica</option>
								<option value="10">Antigua and Barbuda</option>
								<option value="11">Argentina</option>
								<option value="12">Armenia</option>
								<option value="13">Aruba</option>
								<option value="14">Australia</option>
								<option value="15">Austria</option>
								<option value="16">Azerbaijan</option>
								<option value="17">Bahamas</option>
								<option value="18">Bahrain</option>
								<option value="19">Bangladesh</option>
								<option value="20">Barbados</option>
								<option value="21">Belarus</option>
								<option value="22">Belgium</option>
								<option value="23">Belize</option>
								<option value="24">Benin</option>
								<option value="25">Bermuda</option>
								<option value="26">Bhutan</option>
								<option value="27">Bolivia, Plurination</option>
								<option value="28">Bosnia and Herzegovi</option>
								<option value="29">Botswana</option>
								<option value="30">Brazil</option>
								<option value="31">British Indian Ocean</option>
								<option value="32">Brunei Darussalam</option>
								<option value="33">Bulgaria</option>
								<option value="34">Burkina Faso</option>
								<option value="35">Burundi</option>
								<option value="36">Cambodia</option>
								<option value="37">Cameroon</option>
								<option value="38">Canada</option>
								<option value="39">Cape Verde</option>
								<option value="40">Cayman Islands</option>
								<option value="41">Central African Repu</option>
								<option value="42">Chad</option>
								<option value="43">Chile</option>
								<option value="44">China</option>
								<option value="45">Christmas Island</option>
								<option value="46">Cocos (Keeling) Isla</option>
								<option value="47">Colombia</option>
								<option value="48">Comoros</option>
								<option value="49">Congo</option>
								<option value="50">Congo, The Democrati</option>
								<option value="51">Cook Islands</option>
								<option value="52">Costa Rica</option>
								<option value="53">Cote d'Ivoire</option>
								<option value="54">Croatia</option>
								<option value="55">Cuba</option>
								<option value="56">Cyprus</option>
								<option value="57">Czech Republic</option>
								<option value="58">Denmark</option>
								<option value="59">Djibouti</option>
								<option value="60">Dominica</option>
								<option value="61">Dominican Republic</option>
								<option value="62">Ecuador</option>
								<option value="63">Egypt</option>
								<option value="64">El Salvador</option>
								<option value="65">Equatorial Guinea</option>
								<option value="66">Eritrea</option>
								<option value="67">Estonia</option>
								<option value="68">Ethiopia</option>
								<option value="69">Falkland Islands (Ma</option>
								<option value="70">Faroe Islands</option>
								<option value="71">Fiji</option>
								<option value="72">Finland</option>
								<option value="73">France</option>
								<option value="74">French Guiana</option>
								<option value="75">French Polynesia</option>
								<option value="76">Gabon</option>
								<option value="77">Gambia</option>
								<option value="78">Georgia</option>
								<option value="79">Germany</option>
								<option value="80">Ghana</option>
								<option value="81">Gibraltar</option>
								<option value="82">Greece</option>
								<option value="83">Greenland</option>
								<option value="84">Grenada</option>
								<option value="85">Guadeloupe</option>
								<option value="86">Guam</option>
								<option value="87">Guatemala</option>
								<option value="88">Guernsey</option>
								<option value="89">Guinea</option>
								<option value="90">Guinea-Bissau</option>
								<option value="91">Guyana</option>
								<option value="92">Haiti</option>
								<option value="93">Holy See (Vatican Ci</option>
								<option value="94">Honduras</option>
								<option value="95">Hong Kong</option>
								<option value="96">Hungary</option>
								<option value="97">Iceland</option>
								<option value="98">India</option>
								<option value="99">Indonesia</option>
								<option value="100">Iran, Islamic Republ</option>
								<option value="101">Iraq</option>
								<option value="102">Ireland</option>
								<option value="103">Isle of Man</option>
								<option value="104">Israel</option>
								<option value="105">Italy</option>
								<option value="106">Jamaica</option>
								<option value="107">Japan</option>
								<option value="108">Jersey</option>
								<option value="109">Jordan</option>
								<option value="110">Kazakhstan</option>
								<option value="111">Kenya</option>
								<option value="112">Kiribati</option>
								<option value="113">Korea, Democratic Pe</option>
								<option value="114">Korea, Republic of S</option>
								<option value="115">Kuwait</option>
								<option value="116">Kyrgyzstan</option>
								<option value="117">Laos</option>
								<option value="118">Latvia</option>
								<option value="119">Lebanon</option>
								<option value="120">Lesotho</option>
								<option value="121">Liberia</option>
								<option value="122">Libyan Arab Jamahiri</option>
								<option value="123">Liechtenstein</option>
								<option value="124">Lithuania</option>
								<option value="125">Luxembourg</option>
								<option value="126">Macao</option>
								<option value="127">Macedonia</option>
								<option value="128">Madagascar</option>
								<option value="129">Malawi</option>
								<option value="130">Malaysia</option>
								<option value="131">Maldives</option>
								<option value="132">Mali</option>
								<option value="133">Malta</option>
								<option value="134">Marshall Islands</option>
								<option value="135">Martinique</option>
								<option value="136">Mauritania</option>
								<option value="137">Mauritius</option>
								<option value="138">Mayotte</option>
								<option value="139">Mexico</option>
								<option value="140">Micronesia, Federate</option>
								<option value="141">Moldova</option>
								<option value="142">Monaco</option>
								<option value="143">Mongolia</option>
								<option value="144">Montenegro</option>
								<option value="145">Montserrat</option>
								<option value="146">Morocco</option>
								<option value="147">Mozambique</option>
								<option value="148">Myanmar</option>
								<option value="149">Namibia</option>
								<option value="150">Nauru</option>
								<option value="151">Nepal</option>
								<option value="152">Netherlands</option>
								<option value="153">Netherlands Antilles</option>
								<option value="154">New Caledonia</option>
								<option value="155">New Zealand</option>
								<option value="156">Nicaragua</option>
								<option value="157">Niger</option>
								<option value="158">Nigeria</option>
								<option value="159">Niue</option>
								<option value="160">Norfolk Island</option>
								<option value="161">Northern Mariana Isl</option>
								<option value="162">Norway</option>
								<option value="163">Oman</option>
								<option value="164">Pakistan</option>
								<option value="165">Palau</option>
								<option value="166">Palestinian Territor</option>
								<option value="167">Panama</option>
								<option value="168">Papua New Guinea</option>
								<option value="169">Paraguay</option>
								<option value="170">Peru</option>
								<option value="171">Philippines</option>
								<option value="172">Pitcairn</option>
								<option value="173">Poland</option>
								<option value="174">Portugal</option>
								<option value="175">Puerto Rico</option>
								<option value="176">Qatar</option>
								<option value="177">Romania</option>
								<option value="178">Russia</option>
								<option value="179">Rwanda</option>
								<option value="180">Reunion</option>
								<option value="181">Saint Barthelemy</option>
								<option value="182">Saint Helena, Ascens</option>
								<option value="183">Saint Kitts and Nevi</option>
								<option value="184">Saint Lucia</option>
								<option value="185">Saint Martin</option>
								<option value="186">Saint Pierre and Miq</option>
								<option value="187">Saint Vincent and th</option>
								<option value="188">Samoa</option>
								<option value="189">San Marino</option>
								<option value="190">Sao Tome and Princip</option>
								<option value="191">Saudi Arabia</option>
								<option value="192">Senegal</option>
								<option value="193">Serbia</option>
								<option value="194">Seychelles</option>
								<option value="195">Sierra Leone</option>
								<option value="196">Singapore</option>
								<option value="197">Slovakia</option>
								<option value="198">Slovenia</option>
								<option value="199">Solomon Islands</option>
								<option value="200">Somalia</option>
								<option value="201">South Africa</option>
								<option value="202">South Georgia and th</option>
								<option value="203">Spain</option>
								<option value="204">Sri Lanka</option>
								<option value="205">Sudan</option>
								<option value="206">Suriname</option>
								<option value="207">Svalbard and Jan May</option>
								<option value="208">Swaziland</option>
								<option value="209">Sweden</option>
								<option value="210">Switzerland</option>
								<option value="211">Syrian Arab Republic</option>
								<option value="212">Taiwan</option>
								<option value="213">Tajikistan</option>
								<option value="214">Tanzania, United Rep</option>
								<option value="215">Thailand</option>
								<option value="216">Timor-Leste</option>
								<option value="217">Togo</option>
								<option value="218">Tokelau</option>
								<option value="219">Tonga</option>
								<option value="220">Trinidad and Tobago</option>
								<option value="221">Tunisia</option>
								<option value="222">Turkey</option>
								<option value="223">Turkmenistan</option>
								<option value="224">Turks and Caicos Isl</option>
								<option value="225">Tuvalu</option>
								<option value="226">Uganda</option>
								<option value="227">Ukraine</option>
								<option value="228">United Arab Emirates</option>
								<option value="229">United Kingdom</option>
								<option value="230">United States</option>
								<option value="231">Uruguay</option>
								<option value="232">Uzbekistan</option>
								<option value="233">Vanuatu</option>
								<option value="234">Venezuela, Bolivaria</option>
								<option value="235">Vietnam</option>
								<option value="236">Virgin Islands, Brit</option>
								<option value="237">Virgin Islands, U.S.</option>
								<option value="238">Wallis and Futuna</option>
								<option value="239">Yemen</option>
								<option value="240">Zambia</option>
								<option value="241">Zimbabwe</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label for="zipcode" class="col-xs-12 col-form-label"></label>
						<div class="col-xs-12">
							<button type="submit" class="btn btn-success"><i class="fa fa-check"></i>Add Country</button>
							<a href="{{route('admin.country.index')}}" class="btn btn-inverse waves-effect waves-light">@lang('admin.member.cancel')</a>
						</div>
					</div>
				</div>
				</div>
			</form>
		</div>

	<div class="box box-block bg-white">
		<table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                    	<th>ID</th>
                    	<th>Country name</th>
                        <th>Dial code</th>
                        <th>Currency</th>
                        <th>Symbol</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($countries as $index => $country)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $country->name }}</td>
                        <td>{{ $country->dial_code }}</td>
 						<td>{{ $country->currency_name }}</td>
 						<td>{{ $country->currency_symbol }}</td>
 						<td>
 							<form action="{{ route('admin.country.destroy', $country->id) }}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="DELETE">
                                <button class="btn btn-danger btn-sm btn-rounded label-left b-a-0 waves-effect waves-light" onclick="return confirm('Are you sure?')"><span class="btn-label"><i class="fa fa-trash"></i></span> @lang('admin.member.delete')</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>ID</th>
                    	<th>Country name</th>
                        <th>Dial code</th>
                        <th>Currency</th>
                        <th>Symbol</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
            </table>
	    </div>
    </div>
</div>

@endsection
@section('scripts')
<script type="text/javascript" src="{{asset('main/vendor/select2/dist/js/select2.min.js')}}"></script>

<script type="text/javascript">
 $('[data-plugin="select2"]').select2($(this).attr('data-options'));   
</script>
@endsection
