<?php

namespace App\Http\Controllers\Resource;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use Exception;
use Setting;
use Auth;
use App\Models\Admin;

class CountryResource extends Controller
{ 

  public function __construct(Request $request)
    {
        //$this->middleware('admin');

        
      
        $this->middleware(function ($request, $next) {
        $this->id = Auth::user()->id;
        $this->email = Auth::user()->email;
        $this->admin_type = Auth::user()->admin_type;
        $this->admin_id = Auth::user()->admin_id;
        //dd($this->admin_type);
        if($this->admin_id == null){
            
             $admin = Admin::where('id','=',$this->id)->first();
           
             if($admin->admin_type != 0 && $admin->time_zone != null){
                 date_default_timezone_set($admin->time_zone);
                
             }
         } else {

            $admin = Admin::where('id','=',$this->admin_id)->first();
         
             if($admin->admin_type != 0 && $admin->time_zone != null){
                 date_default_timezone_set($admin->time_zone);
                 
             }
         }
            
        return $next($request);
    });
        

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $countries = Country::all();
            return view('admin.country.index', compact('countries'));
        }
        catch (Exception $e) {
             return back()->with('flash_error','Something Went Wrong!');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'country_id' =>'required',
        ]);

        try{

            $country_list = array(
  array('ID' =>1,'name' => 'Afghanistan','code' => 'AF','dial_code' => '+93','currency_name' => 'Afghan afghani','currency_symbol' => '؋','currency_code' => 'AFN'),
  array('ID' =>2,'name' => 'Aland Islands','code' => 'AX','dial_code' => '+358','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>3,'name' => 'Albania','code' => 'AL','dial_code' => '+355','currency_name' => 'Albanian lek','currency_symbol' => 'L','currency_code' => 'ALL'),
  array('ID' =>4,'name' => 'Algeria','code' => 'DZ','dial_code' => '+213','currency_name' => 'Algerian dinar','currency_symbol' => 'د.ج','currency_code' => 'DZD'),
  array('ID' =>5,'name' => 'AmericanSamoa','code' => 'AS','dial_code' => '+1684','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>6,'name' => 'Andorra','code' => 'AD','dial_code' => '+376','currency_name' => 'Euro','currency_symbol' => '€','currency_code' => 'EUR'),
  array('ID' =>7,'name' => 'Angola','code' => 'AO','dial_code' => '+244','currency_name' => 'Angolan kwanza','currency_symbol' => 'Kz','currency_code' => 'AOA'),
  array('ID' =>8,'name' => 'Anguilla','code' => 'AI','dial_code' => '+1264','currency_name' => 'East Caribbean dolla','currency_symbol' => '$','currency_code' => 'XCD'),
  array('ID' =>9,'name' => 'Antarctica','code' => 'AQ','dial_code' => '+672','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>10,'name' => 'Antigua and Barbuda','code' => 'AG','dial_code' => '+1268','currency_name' => 'East Caribbean dolla','currency_symbol' => '$','currency_code' => 'XCD'),
  array('ID' =>11,'name' => 'Argentina','code' => 'AR','dial_code' => '+54','currency_name' => 'Argentine peso','currency_symbol' => '$','currency_code' => 'ARS'),
  array('ID' =>12,'name' => 'Armenia','code' => 'AM','dial_code' => '+374','currency_name' => 'Armenian dram','currency_symbol' => '','currency_code' => 'AMD'),
  array('ID' =>13,'name' => 'Aruba','code' => 'AW','dial_code' => '+297','currency_name' => 'Aruban florin','currency_symbol' => 'ƒ','currency_code' => 'AWG'),
  array('ID' =>14,'name' => 'Australia','code' => 'AU','dial_code' => '+61','currency_name' => 'Australian dollar','currency_symbol' => '$','currency_code' => 'AUD'),
  array('ID' =>15,'name' => 'Austria','code' => 'AT','dial_code' => '+43','currency_name' => 'Euro','currency_symbol' => '€','currency_code' => 'EUR'),
  array('ID' =>16,'name' => 'Azerbaijan','code' => 'AZ','dial_code' => '+994','currency_name' => 'Azerbaijani manat','currency_symbol' => '','currency_code' => 'AZN'),
  array('ID' =>17,'name' => 'Bahamas','code' => 'BS','dial_code' => '+1242','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>18,'name' => 'Bahrain','code' => 'BH','dial_code' => '+973','currency_name' => 'Bahraini dinar','currency_symbol' => '.د.ب','currency_code' => 'BHD'),
  array('ID' =>19,'name' => 'Bangladesh','code' => 'BD','dial_code' => '+880','currency_name' => 'Bangladeshi taka','currency_symbol' => '৳','currency_code' => 'BDT'),
  array('ID' =>20,'name' => 'Barbados','code' => 'BB','dial_code' => '+1246','currency_name' => 'Barbadian dollar','currency_symbol' => '$','currency_code' => 'BBD'),
  array('ID' =>21,'name' => 'Belarus','code' => 'BY','dial_code' => '+375','currency_name' => 'Belarusian ruble','currency_symbol' => 'Br','currency_code' => 'BYR'),
  array('ID' =>22,'name' => 'Belgium','code' => 'BE','dial_code' => '+32','currency_name' => 'Euro','currency_symbol' => '€','currency_code' => 'EUR'),
  array('ID' =>23,'name' => 'Belize','code' => 'BZ','dial_code' => '+501','currency_name' => 'Belize dollar','currency_symbol' => '$','currency_code' => 'BZD'),
  array('ID' =>24,'name' => 'Benin','code' => 'BJ','dial_code' => '+229','currency_name' => 'West African CFA fra','currency_symbol' => 'Fr','currency_code' => 'XOF'),
  array('ID' =>25,'name' => 'Bermuda','code' => 'BM','dial_code' => '+1441','currency_name' => 'Bermudian dollar','currency_symbol' => '$','currency_code' => 'BMD'),
  array('ID' =>26,'name' => 'Bhutan','code' => 'BT','dial_code' => '+975','currency_name' => 'Bhutanese ngultrum','currency_symbol' => 'Nu.','currency_code' => 'BTN'),
  array('ID' =>27,'name' => 'Bolivia, Plurination','code' => 'BO','dial_code' => '+591','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>28,'name' => 'Bosnia and Herzegovi','code' => 'BA','dial_code' => '+387','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>29,'name' => 'Botswana','code' => 'BW','dial_code' => '+267','currency_name' => 'Botswana pula','currency_symbol' => 'P','currency_code' => 'BWP'),
  array('ID' =>30,'name' => 'Brazil','code' => 'BR','dial_code' => '+55','currency_name' => 'Brazilian real','currency_symbol' => 'R$','currency_code' => 'BRL'),
  array('ID' =>31,'name' => 'British Indian Ocean','code' => 'IO','dial_code' => '+246','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>32,'name' => 'Brunei Darussalam','code' => 'BN','dial_code' => '+673','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>33,'name' => 'Bulgaria','code' => 'BG','dial_code' => '+359','currency_name' => 'Bulgarian lev','currency_symbol' => 'лв','currency_code' => 'BGN'),
  array('ID' =>34,'name' => 'Burkina Faso','code' => 'BF','dial_code' => '+226','currency_name' => 'West African CFA fra','currency_symbol' => 'Fr','currency_code' => 'XOF'),
  array('ID' =>35,'name' => 'Burundi','code' => 'BI','dial_code' => '+257','currency_name' => 'Burundian franc','currency_symbol' => 'Fr','currency_code' => 'BIF'),
  array('ID' =>36,'name' => 'Cambodia','code' => 'KH','dial_code' => '+855','currency_name' => 'Cambodian riel','currency_symbol' => '៛','currency_code' => 'KHR'),
  array('ID' =>37,'name' => 'Cameroon','code' => 'CM','dial_code' => '+237','currency_name' => 'Central African CFA ','currency_symbol' => 'Fr','currency_code' => 'XAF'),
  array('ID' =>38,'name' => 'Canada','code' => 'CA','dial_code' => '+1','currency_name' => 'Canadian dollar','currency_symbol' => '$','currency_code' => 'CAD'),
  array('ID' =>39,'name' => 'Cape Verde','code' => 'CV','dial_code' => '+238','currency_name' => 'Cape Verdean escudo','currency_symbol' => 'Esc or $','currency_code' => 'CVE'),
  array('ID' =>40,'name' => 'Cayman Islands','code' => 'KY','dial_code' => '+ 345','currency_name' => 'Cayman Islands dolla','currency_symbol' => '$','currency_code' => 'KYD'),
  array('ID' =>41,'name' => 'Central African Repu','code' => 'CF','dial_code' => '+236','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>42,'name' => 'Chad','code' => 'TD','dial_code' => '+235','currency_name' => 'Central African CFA ','currency_symbol' => 'Fr','currency_code' => 'XAF'),
  array('ID' =>43,'name' => 'Chile','code' => 'CL','dial_code' => '+56','currency_name' => 'Chilean peso','currency_symbol' => '$','currency_code' => 'CLP'),
  array('ID' =>44,'name' => 'China','code' => 'CN','dial_code' => '+86','currency_name' => 'Chinese yuan','currency_symbol' => '¥ or 元','currency_code' => 'CNY'),
  array('ID' =>45,'name' => 'Christmas Island','code' => 'CX','dial_code' => '+61','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>46,'name' => 'Cocos (Keeling) Isla','code' => 'CC','dial_code' => '+61','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>47,'name' => 'Colombia','code' => 'CO','dial_code' => '+57','currency_name' => 'Colombian peso','currency_symbol' => '$','currency_code' => 'COP'),
  array('ID' =>48,'name' => 'Comoros','code' => 'KM','dial_code' => '+269','currency_name' => 'Comorian franc','currency_symbol' => 'Fr','currency_code' => 'KMF'),
  array('ID' =>49,'name' => 'Congo','code' => 'CG','dial_code' => '+242','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>50,'name' => 'Congo, The Democrati','code' => 'CD','dial_code' => '+243','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>51,'name' => 'Cook Islands','code' => 'CK','dial_code' => '+682','currency_name' => 'New Zealand dollar','currency_symbol' => '$','currency_code' => 'NZD'),
  array('ID' =>52,'name' => 'Costa Rica','code' => 'CR','dial_code' => '+506','currency_name' => 'Costa Rican colón','currency_symbol' => '₡','currency_code' => 'CRC'),
  array('ID' =>53,'name' => 'Cote d\'Ivoire','code' => 'CI','dial_code' => '+225','currency_name' => 'West African CFA fra','currency_symbol' => 'Fr','currency_code' => 'XOF'),
  array('ID' =>54,'name' => 'Croatia','code' => 'HR','dial_code' => '+385','currency_name' => 'Croatian kuna','currency_symbol' => 'kn','currency_code' => 'HRK'),
  array('ID' =>55,'name' => 'Cuba','code' => 'CU','dial_code' => '+53','currency_name' => 'Cuban convertible pe','currency_symbol' => '$','currency_code' => 'CUC'),
  array('ID' =>56,'name' => 'Cyprus','code' => 'CY','dial_code' => '+357','currency_name' => 'Euro','currency_symbol' => '€','currency_code' => 'EUR'),
  array('ID' =>57,'name' => 'Czech Republic','code' => 'CZ','dial_code' => '+420','currency_name' => 'Czech koruna','currency_symbol' => 'Kč','currency_code' => 'CZK'),
  array('ID' =>58,'name' => 'Denmark','code' => 'DK','dial_code' => '+45','currency_name' => 'Danish krone','currency_symbol' => 'kr','currency_code' => 'DKK'),
  array('ID' =>59,'name' => 'Djibouti','code' => 'DJ','dial_code' => '+253','currency_name' => 'Djiboutian franc','currency_symbol' => 'Fr','currency_code' => 'DJF'),
  array('ID' =>60,'name' => 'Dominica','code' => 'DM','dial_code' => '+1767','currency_name' => 'East Caribbean dolla','currency_symbol' => '$','currency_code' => 'XCD'),
  array('ID' =>61,'name' => 'Dominican Republic','code' => 'DO','dial_code' => '+1849','currency_name' => 'Dominican peso','currency_symbol' => '$','currency_code' => 'DOP'),
  array('ID' =>62,'name' => 'Ecuador','code' => 'EC','dial_code' => '+593','currency_name' => 'United States dollar','currency_symbol' => '$','currency_code' => 'USD'),
  array('ID' =>63,'name' => 'Egypt','code' => 'EG','dial_code' => '+20','currency_name' => 'Egyptian pound','currency_symbol' => '£ or ج.م','currency_code' => 'EGP'),
  array('ID' =>64,'name' => 'El Salvador','code' => 'SV','dial_code' => '+503','currency_name' => 'United States dollar','currency_symbol' => '$','currency_code' => 'USD'),
  array('ID' =>65,'name' => 'Equatorial Guinea','code' => 'GQ','dial_code' => '+240','currency_name' => 'Central African CFA ','currency_symbol' => 'Fr','currency_code' => 'XAF'),
  array('ID' =>66,'name' => 'Eritrea','code' => 'ER','dial_code' => '+291','currency_name' => 'Eritrean nakfa','currency_symbol' => 'Nfk','currency_code' => 'ERN'),
  array('ID' =>67,'name' => 'Estonia','code' => 'EE','dial_code' => '+372','currency_name' => 'Euro','currency_symbol' => '€','currency_code' => 'EUR'),
  array('ID' =>68,'name' => 'Ethiopia','code' => 'ET','dial_code' => '+251','currency_name' => 'Ethiopian birr','currency_symbol' => 'Br','currency_code' => 'ETB'),
  array('ID' =>69,'name' => 'Falkland Islands (Ma','code' => 'FK','dial_code' => '+500','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>70,'name' => 'Faroe Islands','code' => 'FO','dial_code' => '+298','currency_name' => 'Danish krone','currency_symbol' => 'kr','currency_code' => 'DKK'),
  array('ID' =>71,'name' => 'Fiji','code' => 'FJ','dial_code' => '+679','currency_name' => 'Fijian dollar','currency_symbol' => '$','currency_code' => 'FJD'),
  array('ID' =>72,'name' => 'Finland','code' => 'FI','dial_code' => '+358','currency_name' => 'Euro','currency_symbol' => '€','currency_code' => 'EUR'),
  array('ID' =>73,'name' => 'France','code' => 'FR','dial_code' => '+33','currency_name' => 'Euro','currency_symbol' => '€','currency_code' => 'EUR'),
  array('ID' =>74,'name' => 'French Guiana','code' => 'GF','dial_code' => '+594','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>75,'name' => 'French Polynesia','code' => 'PF','dial_code' => '+689','currency_name' => 'CFP franc','currency_symbol' => 'Fr','currency_code' => 'XPF'),
  array('ID' =>76,'name' => 'Gabon','code' => 'GA','dial_code' => '+241','currency_name' => 'Central African CFA ','currency_symbol' => 'Fr','currency_code' => 'XAF'),
  array('ID' =>77,'name' => 'Gambia','code' => 'GM','dial_code' => '+220','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>78,'name' => 'Georgia','code' => 'GE','dial_code' => '+995','currency_name' => 'Georgian lari','currency_symbol' => 'ლ','currency_code' => 'GEL'),
  array('ID' =>79,'name' => 'Germany','code' => 'DE','dial_code' => '+49','currency_name' => 'Euro','currency_symbol' => '€','currency_code' => 'EUR'),
  array('ID' =>80,'name' => 'Ghana','code' => 'GH','dial_code' => '+233','currency_name' => 'Ghana cedi','currency_symbol' => '₵','currency_code' => 'GHS'),
  array('ID' =>81,'name' => 'Gibraltar','code' => 'GI','dial_code' => '+350','currency_name' => 'Gibraltar pound','currency_symbol' => '£','currency_code' => 'GIP'),
  array('ID' =>82,'name' => 'Greece','code' => 'GR','dial_code' => '+30','currency_name' => 'Euro','currency_symbol' => '€','currency_code' => 'EUR'),
  array('ID' =>83,'name' => 'Greenland','code' => 'GL','dial_code' => '+299','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>84,'name' => 'Grenada','code' => 'GD','dial_code' => '+1473','currency_name' => 'East Caribbean dolla','currency_symbol' => '$','currency_code' => 'XCD'),
  array('ID' =>85,'name' => 'Guadeloupe','code' => 'GP','dial_code' => '+590','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>86,'name' => 'Guam','code' => 'GU','dial_code' => '+1671','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>87,'name' => 'Guatemala','code' => 'GT','dial_code' => '+502','currency_name' => 'Guatemalan quetzal','currency_symbol' => 'Q','currency_code' => 'GTQ'),
  array('ID' =>88,'name' => 'Guernsey','code' => 'GG','dial_code' => '+44','currency_name' => 'British pound','currency_symbol' => '£','currency_code' => 'GBP'),
  array('ID' =>89,'name' => 'Guinea','code' => 'GN','dial_code' => '+224','currency_name' => 'Guinean franc','currency_symbol' => 'Fr','currency_code' => 'GNF'),
  array('ID' =>90,'name' => 'Guinea-Bissau','code' => 'GW','dial_code' => '+245','currency_name' => 'West African CFA fra','currency_symbol' => 'Fr','currency_code' => 'XOF'),
  array('ID' =>91,'name' => 'Guyana','code' => 'GY','dial_code' => '+595','currency_name' => 'Guyanese dollar','currency_symbol' => '$','currency_code' => 'GYD'),
  array('ID' =>92,'name' => 'Haiti','code' => 'HT','dial_code' => '+509','currency_name' => 'Haitian gourde','currency_symbol' => 'G','currency_code' => 'HTG'),
  array('ID' =>93,'name' => 'Holy See (Vatican Ci','code' => 'VA','dial_code' => '+379','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>94,'name' => 'Honduras','code' => 'HN','dial_code' => '+504','currency_name' => 'Honduran lempira','currency_symbol' => 'L','currency_code' => 'HNL'),
  array('ID' =>95,'name' => 'Hong Kong','code' => 'HK','dial_code' => '+852','currency_name' => 'Hong Kong dollar','currency_symbol' => '$','currency_code' => 'HKD'),
  array('ID' =>96,'name' => 'Hungary','code' => 'HU','dial_code' => '+36','currency_name' => 'Hungarian forint','currency_symbol' => 'Ft','currency_code' => 'HUF'),
  array('ID' =>97,'name' => 'Iceland','code' => 'IS','dial_code' => '+354','currency_name' => 'Icelandic króna','currency_symbol' => 'kr','currency_code' => 'ISK'),
  array('ID' =>98,'name' => 'India','code' => 'IN','dial_code' => '+91','currency_name' => 'Indian rupee','currency_symbol' => '₹','currency_code' => 'INR'),
  array('ID' =>99,'name' => 'Indonesia','code' => 'ID','dial_code' => '+62','currency_name' => 'Indonesian rupiah','currency_symbol' => 'Rp','currency_code' => 'IDR'),
  array('ID' =>100,'name' => 'Iran, Islamic Republ','code' => 'IR','dial_code' => '+98','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>101,'name' => 'Iraq','code' => 'IQ','dial_code' => '+964','currency_name' => 'Iraqi dinar','currency_symbol' => 'ع.د','currency_code' => 'IQD'),
  array('ID' =>102,'name' => 'Ireland','code' => 'IE','dial_code' => '+353','currency_name' => 'Euro','currency_symbol' => '€','currency_code' => 'EUR'),
  array('ID' =>103,'name' => 'Isle of Man','code' => 'IM','dial_code' => '+44','currency_name' => 'British pound','currency_symbol' => '£','currency_code' => 'GBP'),
  array('ID' =>104,'name' => 'Israel','code' => 'IL','dial_code' => '+972','currency_name' => 'Israeli new shekel','currency_symbol' => '₪','currency_code' => 'ILS'),
  array('ID' =>105,'name' => 'Italy','code' => 'IT','dial_code' => '+39','currency_name' => 'Euro','currency_symbol' => '€','currency_code' => 'EUR'),
  array('ID' =>106,'name' => 'Jamaica','code' => 'JM','dial_code' => '+1876','currency_name' => 'Jamaican dollar','currency_symbol' => '$','currency_code' => 'JMD'),
  array('ID' =>107,'name' => 'Japan','code' => 'JP','dial_code' => '+81','currency_name' => 'Japanese yen','currency_symbol' => '¥','currency_code' => 'JPY'),
  array('ID' =>108,'name' => 'Jersey','code' => 'JE','dial_code' => '+44','currency_name' => 'British pound','currency_symbol' => '£','currency_code' => 'GBP'),
  array('ID' =>109,'name' => 'Jordan','code' => 'JO','dial_code' => '+962','currency_name' => 'Jordanian dinar','currency_symbol' => 'د.ا','currency_code' => 'JOD'),
  array('ID' =>110,'name' => 'Kazakhstan','code' => 'KZ','dial_code' => '+7 7','currency_name' => 'Kazakhstani tenge','currency_symbol' => '','currency_code' => 'KZT'),
  array('ID' =>111,'name' => 'Kenya','code' => 'KE','dial_code' => '+254','currency_name' => 'Kenyan shilling','currency_symbol' => 'Sh','currency_code' => 'KES'),
  array('ID' =>112,'name' => 'Kiribati','code' => 'KI','dial_code' => '+686','currency_name' => 'Australian dollar','currency_symbol' => '$','currency_code' => 'AUD'),
  array('ID' =>113,'name' => 'Korea, Democratic Pe','code' => 'KP','dial_code' => '+850','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>114,'name' => 'Korea, Republic of S','code' => 'KR','dial_code' => '+82','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>115,'name' => 'Kuwait','code' => 'KW','dial_code' => '+965','currency_name' => 'Kuwaiti dinar','currency_symbol' => 'د.ك','currency_code' => 'KWD'),
  array('ID' =>116,'name' => 'Kyrgyzstan','code' => 'KG','dial_code' => '+996','currency_name' => 'Kyrgyzstani som','currency_symbol' => 'лв','currency_code' => 'KGS'),
  array('ID' =>117,'name' => 'Laos','code' => 'LA','dial_code' => '+856','currency_name' => 'Lao kip','currency_symbol' => '₭','currency_code' => 'LAK'),
  array('ID' =>118,'name' => 'Latvia','code' => 'LV','dial_code' => '+371','currency_name' => 'Euro','currency_symbol' => '€','currency_code' => 'EUR'),
  array('ID' =>119,'name' => 'Lebanon','code' => 'LB','dial_code' => '+961','currency_name' => 'Lebanese pound','currency_symbol' => 'ل.ل','currency_code' => 'LBP'),
  array('ID' =>120,'name' => 'Lesotho','code' => 'LS','dial_code' => '+266','currency_name' => 'Lesotho loti','currency_symbol' => 'L','currency_code' => 'LSL'),
  array('ID' =>121,'name' => 'Liberia','code' => 'LR','dial_code' => '+231','currency_name' => 'Liberian dollar','currency_symbol' => '$','currency_code' => 'LRD'),
  array('ID' =>122,'name' => 'Libyan Arab Jamahiri','code' => 'LY','dial_code' => '+218','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>123,'name' => 'Liechtenstein','code' => 'LI','dial_code' => '+423','currency_name' => 'Swiss franc','currency_symbol' => 'Fr','currency_code' => 'CHF'),
  array('ID' =>124,'name' => 'Lithuania','code' => 'LT','dial_code' => '+370','currency_name' => 'Euro','currency_symbol' => '€','currency_code' => 'EUR'),
  array('ID' =>125,'name' => 'Luxembourg','code' => 'LU','dial_code' => '+352','currency_name' => 'Euro','currency_symbol' => '€','currency_code' => 'EUR'),
  array('ID' =>126,'name' => 'Macao','code' => 'MO','dial_code' => '+853','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>127,'name' => 'Macedonia','code' => 'MK','dial_code' => '+389','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>128,'name' => 'Madagascar','code' => 'MG','dial_code' => '+261','currency_name' => 'Malagasy ariary','currency_symbol' => 'Ar','currency_code' => 'MGA'),
  array('ID' =>129,'name' => 'Malawi','code' => 'MW','dial_code' => '+265','currency_name' => 'Malawian kwacha','currency_symbol' => 'MK','currency_code' => 'MWK'),
  array('ID' =>130,'name' => 'Malaysia','code' => 'MY','dial_code' => '+60','currency_name' => 'Malaysian ringgit','currency_symbol' => 'RM','currency_code' => 'MYR'),
  array('ID' =>131,'name' => 'Maldives','code' => 'MV','dial_code' => '+960','currency_name' => 'Maldivian rufiyaa','currency_symbol' => '.ރ','currency_code' => 'MVR'),
  array('ID' =>132,'name' => 'Mali','code' => 'ML','dial_code' => '+223','currency_name' => 'West African CFA fra','currency_symbol' => 'Fr','currency_code' => 'XOF'),
  array('ID' =>133,'name' => 'Malta','code' => 'MT','dial_code' => '+356','currency_name' => 'Euro','currency_symbol' => '€','currency_code' => 'EUR'),
  array('ID' =>134,'name' => 'Marshall Islands','code' => 'MH','dial_code' => '+692','currency_name' => 'United States dollar','currency_symbol' => '$','currency_code' => 'USD'),
  array('ID' =>135,'name' => 'Martinique','code' => 'MQ','dial_code' => '+596','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>136,'name' => 'Mauritania','code' => 'MR','dial_code' => '+222','currency_name' => 'Mauritanian ouguiya','currency_symbol' => 'UM','currency_code' => 'MRO'),
  array('ID' =>137,'name' => 'Mauritius','code' => 'MU','dial_code' => '+230','currency_name' => 'Mauritian rupee','currency_symbol' => '₨','currency_code' => 'MUR'),
  array('ID' =>138,'name' => 'Mayotte','code' => 'YT','dial_code' => '+262','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>139,'name' => 'Mexico','code' => 'MX','dial_code' => '+52','currency_name' => 'Mexican peso','currency_symbol' => '$','currency_code' => 'MXN'),
  array('ID' =>140,'name' => 'Micronesia, Federate','code' => 'FM','dial_code' => '+691','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>141,'name' => 'Moldova','code' => 'MD','dial_code' => '+373','currency_name' => 'Moldovan leu','currency_symbol' => 'L','currency_code' => 'MDL'),
  array('ID' =>142,'name' => 'Monaco','code' => 'MC','dial_code' => '+377','currency_name' => 'Euro','currency_symbol' => '€','currency_code' => 'EUR'),
  array('ID' =>143,'name' => 'Mongolia','code' => 'MN','dial_code' => '+976','currency_name' => 'Mongolian tögrög','currency_symbol' => '₮','currency_code' => 'MNT'),
  array('ID' =>144,'name' => 'Montenegro','code' => 'ME','dial_code' => '+382','currency_name' => 'Euro','currency_symbol' => '€','currency_code' => 'EUR'),
  array('ID' =>145,'name' => 'Montserrat','code' => 'MS','dial_code' => '+1664','currency_name' => 'East Caribbean dolla','currency_symbol' => '$','currency_code' => 'XCD'),
  array('ID' =>146,'name' => 'Morocco','code' => 'MA','dial_code' => '+212','currency_name' => 'Moroccan dirham','currency_symbol' => 'د.م.','currency_code' => 'MAD'),
  array('ID' =>147,'name' => 'Mozambique','code' => 'MZ','dial_code' => '+258','currency_name' => 'Mozambican metical','currency_symbol' => 'MT','currency_code' => 'MZN'),
  array('ID' =>148,'name' => 'Myanmar','code' => 'MM','dial_code' => '+95','currency_name' => 'Burmese kyat','currency_symbol' => 'Ks','currency_code' => 'MMK'),
  array('ID' =>149,'name' => 'Namibia','code' => 'NA','dial_code' => '+264','currency_name' => 'Namibian dollar','currency_symbol' => '$','currency_code' => 'NAD'),
  array('ID' =>150,'name' => 'Nauru','code' => 'NR','dial_code' => '+674','currency_name' => 'Australian dollar','currency_symbol' => '$','currency_code' => 'AUD'),
  array('ID' =>151,'name' => 'Nepal','code' => 'NP','dial_code' => '+977','currency_name' => 'Nepalese rupee','currency_symbol' => '₨','currency_code' => 'NPR'),
  array('ID' =>152,'name' => 'Netherlands','code' => 'NL','dial_code' => '+31','currency_name' => 'Euro','currency_symbol' => '€','currency_code' => 'EUR'),
  array('ID' =>153,'name' => 'Netherlands Antilles','code' => 'AN','dial_code' => '+599','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>154,'name' => 'New Caledonia','code' => 'NC','dial_code' => '+687','currency_name' => 'CFP franc','currency_symbol' => 'Fr','currency_code' => 'XPF'),
  array('ID' =>155,'name' => 'New Zealand','code' => 'NZ','dial_code' => '+64','currency_name' => 'New Zealand dollar','currency_symbol' => '$','currency_code' => 'NZD'),
  array('ID' =>156,'name' => 'Nicaragua','code' => 'NI','dial_code' => '+505','currency_name' => 'Nicaraguan córdoba','currency_symbol' => 'C$','currency_code' => 'NIO'),
  array('ID' =>157,'name' => 'Niger','code' => 'NE','dial_code' => '+227','currency_name' => 'West African CFA fra','currency_symbol' => 'Fr','currency_code' => 'XOF'),
  array('ID' =>158,'name' => 'Nigeria','code' => 'NG','dial_code' => '+234','currency_name' => 'Nigerian naira','currency_symbol' => '₦','currency_code' => 'NGN'),
  array('ID' =>159,'name' => 'Niue','code' => 'NU','dial_code' => '+683','currency_name' => 'New Zealand dollar','currency_symbol' => '$','currency_code' => 'NZD'),
  array('ID' =>160,'name' => 'Norfolk Island','code' => 'NF','dial_code' => '+672','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>161,'name' => 'Northern Mariana Isl','code' => 'MP','dial_code' => '+1670','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>162,'name' => 'Norway','code' => 'NO','dial_code' => '+47','currency_name' => 'Norwegian krone','currency_symbol' => 'kr','currency_code' => 'NOK'),
  array('ID' =>163,'name' => 'Oman','code' => 'OM','dial_code' => '+968','currency_name' => 'Omani rial','currency_symbol' => 'ر.ع.','currency_code' => 'OMR'),
  array('ID' =>164,'name' => 'Pakistan','code' => 'PK','dial_code' => '+92','currency_name' => 'Pakistani rupee','currency_symbol' => '₨','currency_code' => 'PKR'),
  array('ID' =>165,'name' => 'Palau','code' => 'PW','dial_code' => '+680','currency_name' => 'Palauan dollar','currency_symbol' => '$','currency_code' => ''),
  array('ID' =>166,'name' => 'Palestinian Territor','code' => 'PS','dial_code' => '+970','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>167,'name' => 'Panama','code' => 'PA','dial_code' => '+507','currency_name' => 'Panamanian balboa','currency_symbol' => 'B/.','currency_code' => 'PAB'),
  array('ID' =>168,'name' => 'Papua New Guinea','code' => 'PG','dial_code' => '+675','currency_name' => 'Papua New Guinean ki','currency_symbol' => 'K','currency_code' => 'PGK'),
  array('ID' =>169,'name' => 'Paraguay','code' => 'PY','dial_code' => '+595','currency_name' => 'Paraguayan guaraní','currency_symbol' => '₲','currency_code' => 'PYG'),
  array('ID' =>170,'name' => 'Peru','code' => 'PE','dial_code' => '+51','currency_name' => 'Peruvian nuevo sol','currency_symbol' => 'S/.','currency_code' => 'PEN'),
  array('ID' =>171,'name' => 'Philippines','code' => 'PH','dial_code' => '+63','currency_name' => 'Philippine peso','currency_symbol' => '₱','currency_code' => 'PHP'),
  array('ID' =>172,'name' => 'Pitcairn','code' => 'PN','dial_code' => '+872','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>173,'name' => 'Poland','code' => 'PL','dial_code' => '+48','currency_name' => 'Polish z?oty','currency_symbol' => 'zł','currency_code' => 'PLN'),
  array('ID' =>174,'name' => 'Portugal','code' => 'PT','dial_code' => '+351','currency_name' => 'Euro','currency_symbol' => '€','currency_code' => 'EUR'),
  array('ID' =>175,'name' => 'Puerto Rico','code' => 'PR','dial_code' => '+1939','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>176,'name' => 'Qatar','code' => 'QA','dial_code' => '+974','currency_name' => 'Qatari riyal','currency_symbol' => 'ر.ق','currency_code' => 'QAR'),
  array('ID' =>177,'name' => 'Romania','code' => 'RO','dial_code' => '+40','currency_name' => 'Romanian leu','currency_symbol' => 'lei','currency_code' => 'RON'),
  array('ID' =>178,'name' => 'Russia','code' => 'RU','dial_code' => '+7','currency_name' => 'Russian ruble','currency_symbol' => '','currency_code' => 'RUB'),
  array('ID' =>179,'name' => 'Rwanda','code' => 'RW','dial_code' => '+250','currency_name' => 'Rwandan franc','currency_symbol' => 'Fr','currency_code' => 'RWF'),
  array('ID' =>180,'name' => 'Reunion','code' => 'RE','dial_code' => '+262','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>181,'name' => 'Saint Barthelemy','code' => 'BL','dial_code' => '+590','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>182,'name' => 'Saint Helena, Ascens','code' => 'SH','dial_code' => '+290','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>183,'name' => 'Saint Kitts and Nevi','code' => 'KN','dial_code' => '+1869','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>184,'name' => 'Saint Lucia','code' => 'LC','dial_code' => '+1758','currency_name' => 'East Caribbean dolla','currency_symbol' => '$','currency_code' => 'XCD'),
  array('ID' =>185,'name' => 'Saint Martin','code' => 'MF','dial_code' => '+590','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>186,'name' => 'Saint Pierre and Miq','code' => 'PM','dial_code' => '+508','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>187,'name' => 'Saint Vincent and th','code' => 'VC','dial_code' => '+1784','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>188,'name' => 'Samoa','code' => 'WS','dial_code' => '+685','currency_name' => 'Samoan t?l?','currency_symbol' => 'T','currency_code' => 'WST'),
  array('ID' =>189,'name' => 'San Marino','code' => 'SM','dial_code' => '+378','currency_name' => 'Euro','currency_symbol' => '€','currency_code' => 'EUR'),
  array('ID' =>190,'name' => 'Sao Tome and Princip','code' => 'ST','dial_code' => '+239','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>191,'name' => 'Saudi Arabia','code' => 'SA','dial_code' => '+966','currency_name' => 'Saudi riyal','currency_symbol' => 'ر.س','currency_code' => 'SAR'),
  array('ID' =>192,'name' => 'Senegal','code' => 'SN','dial_code' => '+221','currency_name' => 'West African CFA fra','currency_symbol' => 'Fr','currency_code' => 'XOF'),
  array('ID' =>193,'name' => 'Serbia','code' => 'RS','dial_code' => '+381','currency_name' => 'Serbian dinar','currency_symbol' => 'дин. or din.','currency_code' => 'RSD'),
  array('ID' =>194,'name' => 'Seychelles','code' => 'SC','dial_code' => '+248','currency_name' => 'Seychellois rupee','currency_symbol' => '₨','currency_code' => 'SCR'),
  array('ID' =>195,'name' => 'Sierra Leone','code' => 'SL','dial_code' => '+232','currency_name' => 'Sierra Leonean leone','currency_symbol' => 'Le','currency_code' => 'SLL'),
  array('ID' =>196,'name' => 'Singapore','code' => 'SG','dial_code' => '+65','currency_name' => 'Brunei dollar','currency_symbol' => '$','currency_code' => 'BND'),
  array('ID' =>197,'name' => 'Slovakia','code' => 'SK','dial_code' => '+421','currency_name' => 'Euro','currency_symbol' => '€','currency_code' => 'EUR'),
  array('ID' =>198,'name' => 'Slovenia','code' => 'SI','dial_code' => '+386','currency_name' => 'Euro','currency_symbol' => '€','currency_code' => 'EUR'),
  array('ID' =>199,'name' => 'Solomon Islands','code' => 'SB','dial_code' => '+677','currency_name' => 'Solomon Islands doll','currency_symbol' => '$','currency_code' => 'SBD'),
  array('ID' =>200,'name' => 'Somalia','code' => 'SO','dial_code' => '+252','currency_name' => 'Somali shilling','currency_symbol' => 'Sh','currency_code' => 'SOS'),
  array('ID' =>201,'name' => 'South Africa','code' => 'ZA','dial_code' => '+27','currency_name' => 'South African rand','currency_symbol' => 'R','currency_code' => 'ZAR'),
  array('ID' =>202,'name' => 'South Georgia and th','code' => 'GS','dial_code' => '+500','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>203,'name' => 'Spain','code' => 'ES','dial_code' => '+34','currency_name' => 'Euro','currency_symbol' => '€','currency_code' => 'EUR'),
  array('ID' =>204,'name' => 'Sri Lanka','code' => 'LK','dial_code' => '+94','currency_name' => 'Sri Lankan rupee','currency_symbol' => 'Rs or රු','currency_code' => 'LKR'),
  array('ID' =>205,'name' => 'Sudan','code' => 'SD','dial_code' => '+249','currency_name' => 'Sudanese pound','currency_symbol' => 'ج.س.','currency_code' => 'SDG'),
  array('ID' =>206,'name' => 'Suriname','code' => 'SR','dial_code' => '+597','currency_name' => 'Surinamese dollar','currency_symbol' => '$','currency_code' => 'SRD'),
  array('ID' =>207,'name' => 'Svalbard and Jan May','code' => 'SJ','dial_code' => '+47','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>208,'name' => 'Swaziland','code' => 'SZ','dial_code' => '+268','currency_name' => 'Swazi lilangeni','currency_symbol' => 'L','currency_code' => 'SZL'),
  array('ID' =>209,'name' => 'Sweden','code' => 'SE','dial_code' => '+46','currency_name' => 'Swedish krona','currency_symbol' => 'kr','currency_code' => 'SEK'),
  array('ID' =>210,'name' => 'Switzerland','code' => 'CH','dial_code' => '+41','currency_name' => 'Swiss franc','currency_symbol' => 'Fr','currency_code' => 'CHF'),
  array('ID' =>211,'name' => 'Syrian Arab Republic','code' => 'SY','dial_code' => '+963','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>212,'name' => 'Taiwan','code' => 'TW','dial_code' => '+886','currency_name' => 'New Taiwan dollar','currency_symbol' => '$','currency_code' => 'TWD'),
  array('ID' =>213,'name' => 'Tajikistan','code' => 'TJ','dial_code' => '+992','currency_name' => 'Tajikistani somoni','currency_symbol' => 'ЅМ','currency_code' => 'TJS'),
  array('ID' =>214,'name' => 'Tanzania, United Rep','code' => 'TZ','dial_code' => '+255','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>215,'name' => 'Thailand','code' => 'TH','dial_code' => '+66','currency_name' => 'Thai baht','currency_symbol' => '฿','currency_code' => 'THB'),
  array('ID' =>216,'name' => 'Timor-Leste','code' => 'TL','dial_code' => '+670','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>217,'name' => 'Togo','code' => 'TG','dial_code' => '+228','currency_name' => 'West African CFA fra','currency_symbol' => 'Fr','currency_code' => 'XOF'),
  array('ID' =>218,'name' => 'Tokelau','code' => 'TK','dial_code' => '+690','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>219,'name' => 'Tonga','code' => 'TO','dial_code' => '+676','currency_name' => 'Tongan pa?anga','currency_symbol' => 'T$','currency_code' => 'TOP'),
  array('ID' =>220,'name' => 'Trinidad and Tobago','code' => 'TT','dial_code' => '+1868','currency_name' => 'Trinidad and Tobago ','currency_symbol' => '$','currency_code' => 'TTD'),
  array('ID' =>221,'name' => 'Tunisia','code' => 'TN','dial_code' => '+216','currency_name' => 'Tunisian dinar','currency_symbol' => 'د.ت','currency_code' => 'TND'),
  array('ID' =>222,'name' => 'Turkey','code' => 'TR','dial_code' => '+90','currency_name' => 'Turkish lira','currency_symbol' => '','currency_code' => 'TRY'),
  array('ID' =>223,'name' => 'Turkmenistan','code' => 'TM','dial_code' => '+993','currency_name' => 'Turkmenistan manat','currency_symbol' => 'm','currency_code' => 'TMT'),
  array('ID' =>224,'name' => 'Turks and Caicos Isl','code' => 'TC','dial_code' => '+1649','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>225,'name' => 'Tuvalu','code' => 'TV','dial_code' => '+688','currency_name' => 'Australian dollar','currency_symbol' => '$','currency_code' => 'AUD'),
  array('ID' =>226,'name' => 'Uganda','code' => 'UG','dial_code' => '+256','currency_name' => 'Ugandan shilling','currency_symbol' => 'Sh','currency_code' => 'UGX'),
  array('ID' =>227,'name' => 'Ukraine','code' => 'UA','dial_code' => '+380','currency_name' => 'Ukrainian hryvnia','currency_symbol' => '₴','currency_code' => 'UAH'),
  array('ID' =>228,'name' => 'United Arab Emirates','code' => 'AE','dial_code' => '+971','currency_name' => 'United Arab Emirates','currency_symbol' => 'د.إ','currency_code' => 'AED'),
  array('ID' =>229,'name' => 'United Kingdom','code' => 'GB','dial_code' => '+44','currency_name' => 'British pound','currency_symbol' => '£','currency_code' => 'GBP'),
  array('ID' =>230,'name' => 'United States','code' => 'US','dial_code' => '+1','currency_name' => 'United States dollar','currency_symbol' => '$','currency_code' => 'USD'),
  array('ID' =>231,'name' => 'Uruguay','code' => 'UY','dial_code' => '+598','currency_name' => 'Uruguayan peso','currency_symbol' => '$','currency_code' => 'UYU'),
  array('ID' =>232,'name' => 'Uzbekistan','code' => 'UZ','dial_code' => '+998','currency_name' => 'Uzbekistani som','currency_symbol' => '','currency_code' => 'UZS'),
  array('ID' =>233,'name' => 'Vanuatu','code' => 'VU','dial_code' => '+678','currency_name' => 'Vanuatu vatu','currency_symbol' => 'Vt','currency_code' => 'VUV'),
  array('ID' =>234,'name' => 'Venezuela, Bolivaria','code' => 'VE','dial_code' => '+58','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>235,'name' => 'Vietnam','code' => 'VN','dial_code' => '+84','currency_name' => 'Vietnamese ??ng','currency_symbol' => '₫','currency_code' => 'VND'),
  array('ID' =>236,'name' => 'Virgin Islands, Brit','code' => 'VG','dial_code' => '+1284','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>237,'name' => 'Virgin Islands, U.S.','code' => 'VI','dial_code' => '+1340','currency_name' => '','currency_symbol' => '','currency_code' => ''),
  array('ID' =>238,'name' => 'Wallis and Futuna','code' => 'WF','dial_code' => '+681','currency_name' => 'CFP franc','currency_symbol' => 'Fr','currency_code' => 'XPF'),
  array('ID' =>239,'name' => 'Yemen','code' => 'YE','dial_code' => '+967','currency_name' => 'Yemeni rial','currency_symbol' => '﷼','currency_code' => 'YER'),
  array('ID' =>240,'name' => 'Zambia','code' => 'ZM','dial_code' => '+260','currency_name' => 'Zambian kwacha','currency_symbol' => 'ZK','currency_code' => 'ZMW'),
  array('ID' =>241,'name' => 'Zimbabwe','code' => 'ZW','dial_code' => '+263','currency_name' => 'Botswana pula','currency_symbol' => 'P','currency_code' => 'BWP')
);

            $country_id = $request->country_id-1;
            $country = $country_list[$country_id];
            $Result['countryid'] = $country['ID'];
            $Result['name'] = $country['name'];
            $Result['code'] = $country['code'];
            $Result['dial_code'] = $country['dial_code'];
            $Result['currency_name'] = $country['currency_name'];
            $Result['currency_symbol'] = $country['currency_symbol'];
            $Result['currency_code'] = $country['currency_code'];
            $exist = Country::where('countryid','=',$country['ID'])->first();
            if($exist !=null){
              return back()->with('flash_error','Country Details already exists');
            }
            $Result = Country::create($Result);
            return back()->with('flash_success','Country Details Saved Successfully');

        } 

        catch (Exception $e) {
           return back()->with('flash_error', 'Country Not Found');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\country  $country
     * @return \Illuminate\Http\Response
     */
    public function show(Country $country)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\country  $country
     * @return \Illuminate\Http\Response
     */
    public function edit(Country $country)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\country  $country
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Country $country)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\country  $country
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Country::find($id)->delete();
            return back()->with('message', 'Country deleted successfully');
        } 
        catch (Exception $e) {
            return back()->with('flash_error', 'Country Not Found');
        }
    }
}
