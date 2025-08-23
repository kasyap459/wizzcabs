<h4>Basic Info</h4>

{{-- Name --}}
<div class="form-group row">
    <label for="name" class="col-sm-3 col-form-label">Name *</label>
    <div class="col-sm-6">
        <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $model->name ?? '') }}">
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Email --}}
<div class="form-group row">
    <label for="email" class="col-sm-3 col-form-label">Email *</label>
    <div class="col-sm-6">
        <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email', $model->email ?? '') }}">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Mobile --}}
<div class="form-group row">
    <label for="mobile" class="col-sm-3 col-form-label">Mobile *</label>
    <div class="col-sm-6">
        <input id="mobile" name="mobile" type="text" class="form-control @error('mobile') is-invalid @enderror"
            oninput="this.value=this.value.replace(/[^0-9]/g,'');" value="{{ old('mobile', $model->mobile ?? '') }}">
        @error('mobile')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- DOB --}}
<div class="form-group row">
    <label for="dob" class="col-sm-3 col-form-label">Date of Birth</label>
    <div class="col-sm-6">
        <input id="dob" name="dob" type="date" class="form-control @error('dob') is-invalid @enderror"
            value="{{ old('dob', $model->dob ?? '') }}">
        @error('dob')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Image --}}
<div class="form-group row">
    <label for="picture" class="col-xs-3 col-form-label">Profile Image</label>
    <div class="col-xs-6">
        <input type="file" accept="image/*" name="image" class="dropify form-control-file" id="picture"
            aria-describedby="fileHelp" data-default-file="{{ $model->image }}">
        @error('image')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Five Passenger --}}
<div class="form-group row">
    <label for="five_passenger" class="col-sm-3 col-form-label">Five Passenger</label>
    <div class="col-sm-6">
        <select id="five_passenger" name="five_passenger"
            class="form-control @error('five_passenger') is-invalid @enderror">
            <option value="">Select If Five Passenger Allowed</option>
            <option value="0" @if ((string) old('five_passenger', $model->five_passenger ?? '') === '0') selected @endif>
                No
            </option>
            <option value="1" @if ((string) old('five_passenger', $model->five_passenger ?? '') === '1') selected @endif>
                Yes
            </option>
        </select>
        @error('five_passenger')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<hr>
<h4>Driving License</h4>

{{-- DL Front --}}
<div class="form-group row">
    <label for="dl_front" class="col-xs-3 col-form-label">DL Front</label>
    <div class="col-xs-6">
        <input type="file" accept="image/*" name="dl_front" class="dropify form-control-file" id="dl_front"
            aria-describedby="fileHelp" data-default-file="{{ $model->dl_front }}">
        @error('dl_front')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- DL Back --}}
<div class="form-group row">
    <label for="dl_back" class="col-xs-3 col-form-label">DL Back</label>
    <div class="col-xs-6">
        <input type="file" accept="image/*" name="dl_back" class="dropify form-control-file" id="dl_back"
            aria-describedby="fileHelp" data-default-file="{{ $model->dl_back }}">
        @error('dl_back')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- DL Number --}}
<div class="form-group row">
    <label for="dl_number" class="col-sm-3 col-form-label">License No</label>
    <div class="col-sm-6">
        <input id="dl_number" name="dl_number" type="text"
            class="form-control @error('dl_number') is-invalid @enderror"
            value="{{ old('dl_number', $model->dl_number ?? '') }}">
        @error('dl_number')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- DL Expiry --}}
<div class="form-group row">
    <label for="dl_expiry" class="col-sm-3 col-form-label">Expiry</label>
    <div class="col-sm-6">
        <input id="dl_expiry" name="dl_expiry" type="date"
            class="form-control @error('dl_expiry') is-invalid @enderror"
            value="{{ old('dl_expiry', $model->dl_expiry ?? '') }}">
        @error('dl_expiry')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<hr>
<h4>REGO</h4>

{{-- REGO Picture --}}
<div class="form-group row">
    <label for="rego_picture" class="col-xs-3 col-form-label">Picture</label>
    <div class="col-xs-6">
        <input type="file" accept="image/*" name="rego_picture" class="dropify form-control-file"
            id="rego_picture" aria-describedby="fileHelp" data-default-file="{{ $model->rego_picture }}">
        @error('rego_picture')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- License Plate No --}}
<div class="form-group row">
    <label for="rego_plate_no" class="col-sm-3 col-form-label">License Plate No</label>
    <div class="col-sm-6">
        <input id="rego_plate_no" name="rego_plate_no" type="text"
            class="form-control @error('rego_plate_no') is-invalid @enderror"
            value="{{ old('rego_plate_no', $model->rego_plate_no ?? '') }}">
        @error('rego_plate_no')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- REGO Expiry --}}
<div class="form-group row">
    <label for="rego_expiry" class="col-sm-3 col-form-label">Expiry</label>
    <div class="col-sm-6">
        <input id="rego_expiry" name="rego_expiry" type="date"
            class="form-control @error('rego_expiry') is-invalid @enderror"
            value="{{ old('rego_expiry', $model->rego_expiry ?? '') }}">
        @error('rego_expiry')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Vehicle --}}
<div class="form-group row">
    <label for="rego_vehicle" class="col-sm-3 col-form-label">Vehicle</label>
    <div class="col-sm-6">
        <input id="rego_vehicle" name="rego_vehicle" type="text"
            class="form-control @error('rego_vehicle') is-invalid @enderror"
            value="{{ old('rego_vehicle', $model->rego_vehicle ?? '') }}">
        @error('rego_vehicle')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Vehicle Type --}}
<div class="form-group row">
    <label for="rego_vehicle_type" class="col-sm-3 col-form-label">Vehicle Type</label>
    <div class="col-sm-6">
        <select id="rego_vehicle_type" name="rego_vehicle_type"
            class="form-control @error('rego_vehicle_type') is-invalid @enderror">
            <option value="">Select Vehicle Type</option>
            @foreach ($serviceTypes as $serviceType)
                <option value="{{ $serviceType->id }}" @if ((string) old('rego_vehicle_type', $model->rego_vehicle_type ?? '') === (string) $serviceType->id) selected @endif>
                    {{ $serviceType->name }}
                </option>
            @endforeach
        </select>
        @error('rego_vehicle_type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<hr>
<h4>Taxi Insurance</h4>

{{-- Taxi Insurance Picture --}}
<div class="form-group row">
    <label for="taxi_insurance_picture" class="col-xs-3 col-form-label">Picture</label>
    <div class="col-xs-6">
        <input type="file" accept="image/*" name="taxi_insurance_picture" class="dropify form-control-file"
            id="taxi_insurance_picture" aria-describedby="fileHelp"
            data-default-file="{{ $model->taxi_insurance_picture }}">
        @error('taxi_insurance_picture')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Taxi Insurance Expiry --}}
<div class="form-group row">
    <label for="taxi_insurance_expiry" class="col-sm-3 col-form-label">Expiry</label>
    <div class="col-sm-6">
        <input id="taxi_insurance_expiry" name="taxi_insurance_expiry" type="date"
            class="form-control @error('taxi_insurance_expiry') is-invalid @enderror"
            value="{{ old('taxi_insurance_expiry', $model->taxi_insurance_expiry ?? '') }}">
        @error('taxi_insurance_expiry')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<hr>
<h4>Passenger Transport Vehicle Certificate (PTV)</h4>

{{-- PTV Picture --}}
<div class="form-group row">
    <label for="ptv_picture" class="col-xs-3 col-form-label">Picture</label>
    <div class="col-xs-6">
        <input type="file" accept="image/*" name="ptv_picture" class="dropify form-control-file"
            id="ptv_picture" aria-describedby="fileHelp" data-default-file="{{ $model->ptv_picture }}">
        @error('ptv_picture')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- PTV Number --}}
<div class="form-group row">
    <label for="ptv_number" class="col-sm-3 col-form-label">PTV Number</label>
    <div class="col-sm-6">
        <input id="ptv_number" name="ptv_number" type="text"
            class="form-control @error('ptv_number') is-invalid @enderror"
            value="{{ old('ptv_number', $model->ptv_number ?? '') }}">
        @error('ptv_number')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- PTV Expiry --}}
<div class="form-group row">
    <label for="ptv_expiry" class="col-sm-3 col-form-label">Expiry</label>
    <div class="col-sm-6">
        <input id="ptv_expiry" name="ptv_expiry" type="date"
            class="form-control @error('ptv_expiry') is-invalid @enderror"
            value="{{ old('ptv_expiry', $model->ptv_expiry ?? '') }}">
        @error('ptv_expiry')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<hr>
<h4>Passenger Transport Driver Certificate (PTD)</h4>
{{-- PTD Picture --}}
<div class="form-group row">
    <label for="ptd_picture" class="col-xs-3 col-form-label">Picture</label>
    <div class="col-xs-6">
        <input type="file" accept="image/*" name="ptd_picture" class="dropify form-control-file"
            id="ptd_picture" aria-describedby="fileHelp" data-default-file="{{ $model->ptd_picture }}">
        @error('ptd_picture')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- PTD Number --}}
<div class="form-group row">
    <label for="ptd_number" class="col-sm-3 col-form-label">PTD Number</label>
    <div class="col-sm-6">
        <input id="ptd_number" name="ptd_number" type="text"
            class="form-control @error('ptd_number') is-invalid @enderror"
            value="{{ old('ptd_number', $model->ptd_number ?? '') }}">
        @error('ptd_number')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- PTD Expiry --}}
<div class="form-group row">
    <label for="ptd_expiry" class="col-sm-3 col-form-label">Expiry</label>
    <div class="col-sm-6">
        <input id="ptd_expiry" name="ptd_expiry" type="date"
            class="form-control @error('ptd_expiry') is-invalid @enderror"
            value="{{ old('ptd_expiry', $model->ptd_expiry ?? '') }}">
        @error('ptd_expiry')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<hr>
<h4>Bank Details</h4>

{{-- Account Holder Name --}}
<div class="form-group row">
    <label for="bank_holder_name" class="col-sm-3 col-form-label">Name</label>
    <div class="col-sm-6">
        <input id="bank_holder_name" name="bank_holder_name" type="text"
            class="form-control @error('bank_holder_name') is-invalid @enderror"
            value="{{ old('bank_holder_name', $model->bank_holder_name ?? '') }}">
        @error('bank_holder_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Bank (Bank Name) --}}
<div class="form-group row">
    <label for="bank_name" class="col-sm-3 col-form-label">Bank Name</label>
    <div class="col-sm-6">
        <input id="bank_name" name="bank_name" type="text"
            class="form-control @error('bank_name') is-invalid @enderror"
            value="{{ old('bank_name', $model->bank_name ?? '') }}">
        @error('bank_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Account No --}}
<div class="form-group row">
    <label for="account_no" class="col-sm-3 col-form-label">Account No</label>
    <div class="col-sm-6">
        <input id="account_no" name="account_no" type="text"
            class="form-control @error('account_no') is-invalid @enderror"
            value="{{ old('account_no', $model->account_no ?? '') }}">
        @error('account_no')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Branch Name --}}
<div class="form-group row">
    <label for="branch_name" class="col-sm-3 col-form-label">Branch Name</label>
    <div class="col-sm-6">
        <input id="branch_name" name="branch_name" type="text"
            class="form-control @error('branch_name') is-invalid @enderror"
            value="{{ old('branch_name', $model->branch_name ?? '') }}">
        @error('branch_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Actions --}}
<div class="form-group row">
    <div class="col-sm-6 offset-sm-3">
        <button type="submit" class="btn btn-success">
            <i class="fa fa-check"></i> Save
        </button>
    </div>
</div>
