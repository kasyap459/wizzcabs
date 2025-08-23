<?php

// app/Http/Requests/ProviderFormRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProviderFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // allow all for now
    }

    public function rules(): array
    {
        return [
            // Basic Info
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile' => 'required|string|max:20',
            'dob' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'five_passenger' => 'required|max:255',

            // Driving License
            'dl_front' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'dl_back' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'dl_number' => 'nullable|string|max:100',
            'dl_expiry' => 'nullable|date',

            // REGO
            'rego_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'rego_plate_no' => 'nullable|string|max:100',
            'rego_expiry' => 'nullable|date',
            'rego_vehicle' => 'nullable|string|max:255',
            'rego_vehicle_type' => 'nullable|string|max:255',

            // Taxi Insurance
            'taxi_insurance_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'taxi_insurance_expiry' => 'nullable|date',

            // Passenger Transport Vehicle Certificate
            'ptv_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'ptv_number' => 'nullable|string|max:100',
            'ptv_expiry' => 'nullable|date',

            // Passenger Transport Driver Certificate
            'ptd_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'ptd_number' => 'nullable|string|max:100',
            'ptd_expiry' => 'nullable|date',

            // Bank Details
            'bank_holder_name' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'account_no' => 'nullable|string|max:100',
            'branch_name' => 'nullable|string|max:255',
        ];
    }
}
