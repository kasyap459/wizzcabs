<?php

namespace App\Helpers;

use File;
use Setting;
use Auth;
use Carbon\Carbon;
use App\Models\Corporate;
use App\Models\Complaint;
use App\Models\User;
use App\Models\Card;
use App\Models\Country;
use App\Models\ServiceType;
use App\Models\FareModel;
use App\Models\Promocode;
use App\Models\PromocodeUsage;
use App\Models\UserRequest;
use App\Models\Provider;
use App\Models\CorporateUser;
use App\Models\CorporateGroup;
use App\Models\UserRequestRating;
use App\Models\UserRequestPayment;
use App\Models\Location;
use App\Models\PoiFare;
use App\Models\LocationWiseFare;
use App\Models\RestrictLocation;
use App\Models\Token;
use App\Models\Admin;
use App\Models\MemberNotification;
use DateTimeZone;
use App\Models\RequestFilter;
use Log;
use App\Models\FavouriteLocation;
use App\Models\UserNote;
use App\Models\UserCare;
use App\Models\ContactList;
use App\Models\ProviderBankDetail;
use App\Models\ProviderDocument;
use App\Models\UserRating;
use App\Models\ReferEarn;
use App\Models\WebNotify;
use App\Models\Waypoint;
use App\Models\ProviderWallet;
use App\Models\Vehicle;
use Illuminate\Support\Facades\File as FacadesFile;
use Str;

class Helper
{

    const PROVIDER_DOCUMENT_TYPES = [
        'DRIVING_LICENSE' => 'Driving License',
        'REGISTRATION' => 'Vehicle Registration',
        'TAXI_INSURANCE' => 'Taxi Insurance',
        'PTV_CERTIFICATE' => 'Passenger Transport Vehicle Certificate',
        'PTD_CERTIFICATE' => 'Passenger Transport Driver Certificate',
    ];

    public static function upload_picture($picture)
    {
        $file_name = time();
        $file_name .= rand();
        $file_name = sha1($file_name);
        if ($picture) {
            $ext = $picture->getClientOriginalExtension();
            $picture->move(public_path() . "/uploads", $file_name . "." . $ext);
            $local_url = $file_name . "." . $ext;

            $s3_url = url('/') . '/uploads/' . $local_url;

            return $s3_url;
        }
        return "";
    }


    public static function delete_picture($picture)
    {
        File::delete(public_path() . "/uploads/" . basename($picture));
        return true;
    }

    public static function generate_booking_id()
    {
        return Setting::get('booking_prefix') . mt_rand(100000, 999999);
    }
    public static function hideEmail($email)
    {
        $length = strlen($email);
        $replace = str_repeat("*", $length);
        return substr_replace($email, $replace, 4);
    }
    public static function hidechar($char)
    {

        $length = strlen($char);
        $replace = str_repeat("*", $length);
        return substr_replace($char, $replace, 3);
    }

    public static function corporate_name($id)
    {

        return Corporate::where('id', '=', $id)->pluck('display_name')->first();
    }

    public static function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y)
    {
        $i = $j = $c = 0;
        for ($i = 0, $j = $points_polygon - 1; $i < $points_polygon; $j = $i++) {
            if (
                (($vertices_y[$i] > $latitude_y != ($vertices_y[$j] > $latitude_y)) &&
                    ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i]))
            )
                $c = !$c;
        }
        return $c;
    }

    public static function distance($lat1, $lon1, $lat2, $lon2, $unit)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

    public static function generate_refferal_code()
    {
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $res = "";
        for ($i = 0; $i < 10; $i++) {
            $res .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
        return $res;
    }


    public static function invoice($request_id)
    {
        //try {

        // if(Auth::user()->admin_id !=  null){
        // $admin = Admin::where('id','=',Auth::user()->admin_id)->first();
        // if($admin->admin_type != 0 && $admin->time_zone != null){
        //      date_default_timezone_set($admin->time_zone);
        //  }
        // }

        $UserRequest = UserRequest::where('id', '=', $request_id)->where('status', '=', 'PICKEDUP')->first();
        $meter = 0;
        $seconds = 0;
        $route_key = '';
        $startTime = $UserRequest->started_at;
        $finishTime = $UserRequest->finished_at;
        $minutes = $finishTime->diffInMinutes($startTime);
        $current = Carbon::now()->toTimeString();
        $currentday = Carbon::now()->format('l');
        $fare_base = 0.00;
        $fare_distance = 0.00;
        $fare_minute = 0.00;
        $fare_waiting = 0.00;
        $distance_fare = 0.00;
        $min_fare = 0.00;
        $waiting_fare = 0.00;
        $vat_fare = 0.00;
        $Discount = 0.00;
        $Commision = 0.00;
        $base_fare = 0.00;
        $flat_fare = 0.00;
        $due_balance = 0.00;

        $waiting_time = strtotime("1970-01-01 $UserRequest->waiting_time UTC");
        $stop_waiting_time = strtotime("1970-01-01 $UserRequest->stop_waiting_time UTC");
        $waiting_time = $waiting_time / 60;
        $stop_waiting_time = $stop_waiting_time / 60;

        $commission_enable = Setting::get('commission_enable', 0);
        $commission_percentage = Setting::get('commission_percentage', 0);
        $refer_enable = Setting::get('refferal', 0);
        $refer_type = Setting::get('refferal_type', "first ride");
        $refferal_value = Setting::get('refferal_value', 1);
        $first_ride = User::where('id', $UserRequest->user_id)->first();


        $fare_calc = Helper::fare_calc($UserRequest->service_type_id, $UserRequest->s_latitude, $UserRequest->s_longitude, $UserRequest->d_latitude, $UserRequest->d_longitude, $UserRequest->distance, $minutes);
        $UserRequest->estimated_fare = round($fare_calc['fare_base'], 2);
        $fare_base = round($fare_calc['fare_base'], 2);
        $base_fare = round($fare_calc['fare_base'], 2);
        $check_type = $fare_calc['check_type'];
        $flat_fare = round($fare_calc['fare_flat'], 2);
        $distance_fare = round($fare_calc['distance_fare'], 2);
        $min_fare = round($fare_calc['min_fare'], 2);
        $free_wait_time = $fare_calc['fare_trip_waiting'];
        $free_stop_time = $fare_calc['fare_stop_waiting'];

        if ($UserRequest->waiting_time) {
            if ($free_wait_time < $waiting_time) {
                $waiting_time = $waiting_time - $free_wait_time;
            }
        }
        if ($UserRequest->stop_waiting_time) {
            if ($free_stop_time < $stop_waiting_time) {
                $stop_waiting_time = $stop_waiting_time - $free_stop_time;
            }
        }


        $fare_waiting = round($fare_calc['fare_waiting'], 2);
        $waiting_fare = round($fare_waiting * $waiting_time, 2);
        $stop_waiting = round($fare_calc['stop_waiting'], 2);
        $stop_waiting_fare = round($stop_waiting * $stop_waiting_time, 2);

        if ($UserRequest->stop1_address != null || $UserRequest->stop2_address != null) {
            $fare_base = $fare_base + $distance_fare + $waiting_fare + $min_fare + $stop_waiting_fare;
            $flat_fare = 0.00;
            $fare_type = 3;
        } else {
            if ($check_type == 0) {
                $flat_fare = 0.00;
                $fare_type = 3;
                $fare_base = $fare_base + $distance_fare + $waiting_fare + $min_fare + $stop_waiting_fare;
            } else {
                $distance_fare = 0.00;
                $fare_base = $flat_fare + $distance_fare + $waiting_fare + $min_fare + $stop_waiting_fare;
                $base_fare = 0.00;
                $fare_type = 2;
            }
        }


        $service_type = ServiceType::where('id', '=', $UserRequest->service_type_id)->first();

        if (Setting::get('vat_percent') != 0) {
            $vat_fare = $fare_base * Setting::get('vat_percent') / 100;
        }

        if ($vat_fare < 0) {
            $vat_fare = 0.00; // prevent from negative value
        }

        if ($PromocodeUsage = PromocodeUsage::where('user_id', '=', $UserRequest->user_id)->where('status', 'ADDED')->first()) {
            if ($Promocode = Promocode::find($PromocodeUsage->promocode_id)) {

                if ($Promocode->discount_type == "percent") {
                    $sub_total = $fare_base + $vat_fare;
                    $Discount = (int) $sub_total * (int) $Promocode->discount / 100;
                    $PromocodeUsage->status = 'USED';
                    $PromocodeUsage->save();
                } else {
                    $Discount = $Promocode->discount;
                    $PromocodeUsage->status = 'USED';
                    $PromocodeUsage->save();
                }

                // Log::info($Discount);
                //   dd($Discount); die;

            }
        }

        if ($UserRequest->fare_type == 3) {
            $Total = $fare_base + $vat_fare - $Discount;
            $fare_base = $fare_base;
        } else {
            $Total = $fare_base - $Discount;
            $fare_base = $fare_base - $vat_fare;
        }


        if ($UserRequest->payment_mode == 'CASH') {
            $Total = ceil($Total);
        }
        if ($fare_base < 0) {
            $fare_base = 0.00; // prevent from negative value
        }
        if ($Total < 0) {
            $Total = 0.00; // prevent from negative value
        }

        if ($commission_enable == 1) {
            $total_amount = $fare_base;
            $Commision = $total_amount * $commission_percentage / 100;
            $earnings = $total_amount - $Commision;
            $revenue = $total_amount - $Commision;
        } else {
            $total_amount = $fare_base;
            $Commision = 0;
            $earnings = $total_amount;
            $revenue = $total_amount;
        }
        if ($UserRequest->booking_by == 'APP') {
            if ($first_ride->refferal_by != null) {
                if ($refer_enable == 1) {
                    if ($refer_type == "first ride") {
                        $Discount = $Total;
                        $Total = 0.00;
                    } else {
                        $Discount = $Total * $Total / $refferal_value;
                        $Total = $Total - $Discount;
                    }
                }
            }
        }

        // $user = User::find($UserRequest->user_id);
        // if($user != Null)
        // {
        //     if($user->wallet_balance < 0)
        //     {
        //     $due_balance = $user->wallet_balance;
        //     $user->wallet_balance = 0;
        //     $Total = $Total + abs($due_balance);
        //     // dd($Total); die;
        //     $user->save();
        //     }
        // }

        $Payment = new UserRequestPayment;
        $Payment->request_id = $UserRequest->id;
        $Payment->currency = Setting::get('currency');
        $Payment->base_fare = $base_fare;
        $Payment->flat_fare = $flat_fare;
        $Payment->distance_fare = $distance_fare;
        $Payment->commision = $Commision;
        $Payment->earnings = $earnings;
        $Payment->revenue = $revenue;
        $Payment->min_fare = $min_fare;
        $Payment->waiting_fare = $waiting_fare;
        $Payment->stop_waiting_fare = $stop_waiting_fare;
        $Payment->vat = $vat_fare;
        $Payment->payment_mode = $UserRequest->payment_mode;
        $Payment->discount = $Discount;
        $Payment->tip_fare = 0;
        $Payment->cash = abs($Total);
        $Payment->total = abs($Total);
        $Payment->due_pending = abs($due_balance);
        $Payment->save();

        $UserRequest->estimated_fare = abs($Total);
        $UserRequest->status = "DROPPED";
        // $UserRequest->distance = $kilometer;
        $UserRequest->fare_type = $fare_type;
        $UserRequest->minutes = $minutes;
        $UserRequest->route_key = $route_key;
        $UserRequest->save();

        if ($UserRequest->booking_by == 'APP') {
            if ($first_ride->refferal_by != null) {
                $first_ride->refferal_by = null;
                $first_ride->save();
            }
        }
        $wallet = Provider::find(Auth::user()->id);
        // if($Payment->payment_mode == "CASH")
        // {
        //     if($due_balance > 0 )
        //     {
        //     $wallet->wallet_balance -=  $due_balance;
        //     $wallet->save();
        //     ProviderWallet::create([
        //         'provider_id' => Auth::user()->id,
        //         'trip_id' =>$UserRequest->id,
        //         'amount' => $due_balance,
        //         'mode' => 'Debited  by Trips for due balance',
        //         'status' => 'Credited',
        //         ]);
        //     }
        // }else{
        //     $wallet->wallet_balance +=  $Payment->total ;
        //     $wallet->save();
        //     $wallet->wallet_balance -=  $due_balance;
        //     $wallet->save();
        //     ProviderWallet::create([
        //         'provider_id' => Auth::user()->id,
        //         'trip_id' =>$UserRequest->id,
        //         'amount' => $earnings,
        //         'mode' => 'Added by Trips',
        //         'status' => 'Credited',
        //         ]);

        // }

        return $Payment;

        // } catch (ModelNotFoundException $e) {
        //     return false;
        // }
    }


    public static function fare_calc($service_type_id, $s_lat, $s_long, $d_lat, $d_long, $kilometer, $minutes)
    {

        //try {
        //     if(Auth::user()->admin_id !=  null){
        //     $admin = Admin::where('id','=',Auth::user()->admin_id)->first();
        //     if($admin->admin_type != 0 && $admin->time_zone != null){
        //          date_default_timezone_set($admin->time_zone);
        //      }
        //  }
        $check_type = 0;
        $fare_type = 0;
        $data_id = 0;
        $fare_flat = 0;
        $current = Carbon::now()->toTimeString();
        $currentday = Carbon::now()->format('l');

        $fare_base = 0;
        $base_dist = 0;
        $distance_fare = 0;
        $min_fare = 0;
        $fare_waiting = 0;
        $stop_waiting = 0;
        $fare_trip_waiting = 0.00;
        $fare_stop_waiting = 0.00;
        $fare_final = 0.00;
        $fare_distance = 0.00;

        $fare_model = FareModel::where('service_type_id', '=', $service_type_id)->first();
        if ($fare_model != null) {
            if ($currentday == 'Friday' || $currentday == 'Saturday' || $currentday == 'Sunday') {
                if ($current > $fare_model->t1_s_stime && $current < $fare_model->t1_s_etime) {
                    $fare_base = $fare_model->t1_s_base;
                    $base_dist = $fare_model->t1_s_base_dist;
                    $fare_distance = $fare_model->t1_s_distance;
                    $fare_minute = $fare_model->t1_s_minute;
                    $fare_waiting = $fare_model->t1_s_waiting;
                    $stop_waiting = $fare_model->s3_waiting;
                    $fare_trip_waiting = $fare_model->t3_base_wait;
                    $fare_stop_waiting = $fare_model->t3s_base_wait;
                } else if ($current > $fare_model->t2_s_stime && $current < $fare_model->t2_s_etime) {
                    $fare_base = $fare_model->t2_s_base;
                    $base_dist = $fare_model->t2_s_base_dist;
                    $fare_distance = $fare_model->t2_s_distance;
                    $fare_minute = $fare_model->t2_s_minute;
                    $fare_waiting = $fare_model->t2_s_waiting;
                    $stop_waiting = $fare_model->s4_waiting;
                    $fare_trip_waiting = $fare_model->t4_base_wait;
                    $fare_stop_waiting = $fare_model->t4s_base_wait;
                } else if ($current > $fare_model->t3_s_stime && $current < $fare_model->t3_s_etime) {
                    $fare_base = $fare_model->t3_s_base;
                    $base_dist = $fare_model->t3_s_base_dist;
                    $fare_distance = $fare_model->t3_s_distance;
                    $fare_minute = $fare_model->t3_s_minute;
                    $fare_waiting = $fare_model->t3_s_waiting;
                } else if ($current > $fare_model->t4_s_stime && $current < $fare_model->t4_s_etime) {
                    $fare_base = $fare_model->t4_s_base;
                    $base_dist = $fare_model->t4_s_base_dist;
                    $fare_distance = $fare_model->t4_s_distance;
                    $fare_minute = $fare_model->t4_s_minute;
                    $fare_waiting = $fare_model->t4_s_waiting;
                } else {
                    $fare_base = $fare_model->t1_s_base;
                    $base_dist = $fare_model->t1_s_base_dist;
                    $fare_distance = $fare_model->t1_s_distance;
                    $fare_minute = $fare_model->t1_s_minute;
                    $fare_waiting = $fare_model->t1_s_waiting;
                    $stop_waiting = $fare_model->s1_waiting;
                    $fare_trip_waiting = $fare_model->t4_base_wait;
                    $fare_stop_waiting = $fare_model->t4s_base_wait;
                }
            } else {
                if ($current > $fare_model->t1_stime && $current < $fare_model->t1_etime) {
                    $fare_base = $fare_model->t1_base;
                    $base_dist = $fare_model->t1_base_dist;
                    $fare_distance = $fare_model->t1_distance;
                    $fare_minute = $fare_model->t1_minute;
                    $fare_waiting = $fare_model->t1_waiting;
                    $stop_waiting = $fare_model->s1_waiting;
                    $fare_trip_waiting = $fare_model->t1_base_wait;
                    $fare_stop_waiting = $fare_model->t1s_base_wait;
                } else if ($current > $fare_model->t2_stime && $current < $fare_model->t2_etime) {
                    $fare_base = $fare_model->t2_base;
                    $base_dist = $fare_model->t2_base_dist;
                    $fare_distance = $fare_model->t2_distance;
                    $fare_minute = $fare_model->t2_minute;
                    $fare_waiting = $fare_model->t2_waiting;
                    $stop_waiting = $fare_model->s2_waiting;
                    $fare_trip_waiting = $fare_model->t2_base_wait;
                    $fare_stop_waiting = $fare_model->t2s_base_wait;
                } else if ($current > $fare_model->t3_stime && $current < $fare_model->t3_etime) {
                    $fare_base = $fare_model->t3_base;
                    $base_dist = $fare_model->t3_base_dist;
                    $fare_distance = $fare_model->t3_distance;
                    $fare_minute = $fare_model->t3_minute;
                    $fare_waiting = $fare_model->t3_waiting;
                } else if ($current > $fare_model->t4_stime && $current < $fare_model->t4_etime) {
                    $fare_base = $fare_model->t4_base;
                    $base_dist = $fare_model->t4_base_dist;
                    $fare_distance = $fare_model->t4_distance;
                    $fare_minute = $fare_model->t4_minute;
                    $fare_waiting = $fare_model->t4_waiting;
                } else {
                    $fare_base = $fare_model->t1_base;
                    $base_dist = $fare_model->t1_base_dist;
                    $fare_distance = $fare_model->t1_distance;
                    $fare_minute = $fare_model->t1_minute;
                    $fare_waiting = $fare_model->t1_waiting;
                    $stop_waiting = $fare_model->s1_waiting;
                    $fare_trip_waiting = $fare_model->t1_base_wait;
                    $fare_stop_waiting = $fare_model->t1s_base_wait;
                }
            }
            $distance_fare = 0.00;
            $min_fare = 0.00;
            $base_distance = $base_dist;
            if ($base_distance < $kilometer) {
                $kilometer1 = $kilometer - $base_distance;
                $distance_fare = ($kilometer1 * $fare_distance);
                $min_fare = ($fare_minute * $minutes);
            }
            $min_fare = ($fare_minute * $minutes);
            $fare_flat = $fare_base + $distance_fare + $min_fare;
            $fare_final = $fare_flat;
            $fare_type = 3;
        }
        $result = array('fare_type' => $fare_type, 'fare_flat' => $fare_final, 'data_id' => $data_id, 'fare_base' => $fare_base, 'base_dist' => $base_dist, 'distance_fare' => $distance_fare, 'min_fare' => $min_fare, 'fare_waiting' => $fare_waiting, 'stop_waiting' => $stop_waiting, 'fare_trip_waiting' => $fare_trip_waiting, 'fare_stop_waiting' => $fare_stop_waiting, 'check_type' => $check_type, 'km_fare' => $fare_distance);
        return $result;

        // } catch (Exception $e) {
        //     return response()->json(['error' => $e->getMessage()], 500);
        // }
    }

    public static function uploadFile($file)
    {
        // Dynamic date-based path
        $datePath = date('Y') . '/' . date('F') . '/' . date('dD');
        // Example output: 2025/May/12Tue

        // Full upload directory
        $uploadPath = public_path("uploads/{$datePath}");

        // Create the directory if it doesn't exist
        if (!FacadesFile::exists($uploadPath)) {
            FacadesFile::makeDirectory($uploadPath, 0777, true);
        }

        // File name
        $ext = $file->getClientOriginalExtension();
        $file_name = time() . "-" . Str::random(10) . "." . $ext;

        // Move file
        $file->move($uploadPath, $file_name);

        // Relative path for URL
        $s3_url = "/uploads/{$datePath}/{$file_name}";
        $finalUrl = url($s3_url);

        return $finalUrl;
    }

    public static function getProviderProfileData($user)
    {
        $provider = Provider::where('id', $user->id)->select('id', 'name', 'email', 'mobile', 'avatar', 'account_status')->first();
        $vehicle = Vehicle::where('id', '=', $user->mapping_id)->select('vehicle_no', 'service_type_id')->first();

        if ($vehicle != null) {
            $provider['service_type'] = ServiceType::where('id', $vehicle->service_type_id)->pluck('name')->first();
            $provider['vehicle'] = $vehicle->vehicle_no;
        }

        $provider->avatar = $provider->avatar;
        $provider->currency = Setting::get('currency');
        $provider->contact_number = Setting::get('contact_number');
        $provider->sos_number = Setting::get('sos_number');

        $providerId = $provider->id;

        $provider = json_decode(json_encode($provider), true);

        $provider['documents'] = [];

        $providerDocumentTypes = Helper::PROVIDER_DOCUMENT_TYPES;
        foreach ($providerDocumentTypes as $providerDocumentType) {
            $document = ProviderDocument::where('provider_id', $providerId)
                ->where('document_type', $providerDocumentType)->first();
            if ($document != null) {
                $provider['documents'][$providerDocumentType] = $document;
            } else {
                $provider['documents'][$providerDocumentType] = null;
            }
        }

        $bankDetails = ProviderBankDetail::where('provider_id', $providerId)->first();
        if ($bankDetails != null) {
            $provider['bankDetails'] = $bankDetails;
        } else {
            $provider['bankDetails'] = null;
        }

        return $provider;
    }

}
?>