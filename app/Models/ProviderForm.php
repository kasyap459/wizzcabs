<?php

namespace App\Models;

class ProviderForm
{
    public ?string $name = null;
    public ?string $email = null;
    public ?string $mobile = null;
    public ?string $dob = null;
    public ?string $image = null;
    public ?string $dl_front = null;
    public ?string $dl_back = null;
    public ?string $dl_number = null;
    public ?string $dl_expiry = null;
    public ?string $rego_picture = null;
    public ?string $rego_plate_no = null;
    public ?string $rego_expiry = null;
    public ?string $rego_vehicle = null;
    public ?string $rego_vehicle_type = null;
    public ?string $taxi_insurance_picture = null;
    public ?string $taxi_insurance_expiry = null;
    public ?string $ptv_picture = null;
    public ?string $ptv_number = null;
    public ?string $ptv_expiry = null;
    public ?string $ptd_picture = null;
    public ?string $ptd_number = null;
    public ?string $ptd_expiry = null;
    public ?string $bank_holder_name = null;
    public ?string $bank_name = null;
    public ?string $account_no = null;
    public ?string $branch_name = null;
    public ?string $five_passenger = "0";

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}
