<?php

use Carbon\Carbon;


function authUser()
{
    return Auth::user();
}


function generateToken($length)
{
    return bin2hex(openssl_random_pseudo_bytes($length));
}

function localDatetime($dateTime)
{
    return Carbon::parse($dateTime)->timezone('Asia/Kathmandu');
}


function paginate()
{
    return Config::get('constants.PAGINATE');
}

function pageIndex($items)
{
    $sn = 0;
    if (method_exists($items, 'perPage') && method_exists($items, 'currentPage')) {
        $sn = $items->perPage() * ($items->currentPage() - 1);
    }

    return $sn;
}

function SN($sn, $key)
{
    return $sn += $key + 1;
}

function getSystemPrefix()
{
    return env('SYSTEM_PREFIX', 'system');
}

function getImageUploadFirstLevelPath()
{
    return env('IMAGE_UPLOAD_PATH', 'uploads');
}

function getConfigTableData()
{
    return \App\Models\Config::first();
}

function modules()
{
    $modules = Config::get('cmsConfig.modules');
    return $modules;
}

function isPermissionSelected($permission, $permissions)
{
    $permission = json_decode($permission);
    $permissions = json_decode($permissions);
    $check = false;
    if (!is_array($permission)) {
        if ($permissions != null) {
            $exists = in_array($permission, $permissions);
            if ($exists) {
                $check = true;
            }
        }
    } else {
        $temCheck = false;
        if ($permissions != null) {
            foreach ($permission as $perm) {
                $exists = in_array($perm, $permissions);
                if ($exists) {
                    $temCheck = true;
                }
            }
        }
        $check = $temCheck;
    }

    return $check;
}

function hasPermission($url, $method = 'get')
{
    if (!authUser()) {
        return false;
    }
    $role = authUser()->role;
    $method = strtolower($method);
    $splittedUrl = explode('/' . getSystemPrefix(), $url);
    if (count($splittedUrl) > 1) {
        $url = $splittedUrl[1];
    } else {
        $url = $splittedUrl[0];
    }

    if ($role->id == 1) {
        $permissionDeniedToSuperUserRoutes = Config::get('cmsConfig.permissionDeniedToSuperUserRoutes');
        $checkDeniedRoute = true;
        foreach ($permissionDeniedToSuperUserRoutes as $route) {
            if (\Str::is($route['url'], $url) && $route['method'] == $method) {
                $checkDeniedRoute = false;
            }
        }

        return $checkDeniedRoute;
    }

    $permissionIgnoredUrls = Config::get('cmsConfig.permissionGrantedbyDefaultRoutes');

    $check = false;

    foreach ($permissionIgnoredUrls as $piurl) {
        if (\Str::is($piurl['url'], $url) && $piurl['method'] == $method) {
            $check = true;
        }
    }
    if ($check) {
        return true;
    }

    $permissions = $role->permissions;

    if ($permissions == null) {
        return false;
    }

    // foreach ($permissions as $piurl) {
    //     if (\Str::is($piurl['url'], $url) && $piurl['method'] == $method) {
    //         $check = true;
    //     }
    // }

    foreach ($permissions as $piurl) {
        if (fnmatch($piurl['url'], $url, FNM_PATHNAME) && $piurl['method'] == $method) {
            $check = true;
            break;
        }
    }
    if ($check) {
        return true;
    }

    return false;
}

function hasPermissionOnModule($module)
{
    $check = false;
    if (!$module['hasSubmodules']) {
        $check = hasPermission($module['route']);
    } else {
        try {
            foreach ($module['submodules'] as $submodule) {
                /* added third level */
                if (!$submodule['hasSubmodules']) {
                    /* after end third level if only two levels */
                    $check = hasPermission($submodule['route']);
                    if ($check == true) {
                        break;
                    }
                    /**/
                } else {
                    try {
                        foreach ($submodule['submodules'] as $childModule) {
                            $check = hasPermission($childModule['route']);
                            if ($check == true) {
                                break;
                            }
                        }
                    } catch (\Exception $e) {
                        return false;
                    }
                }
                /* end third level */
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    return $check;
}

function cssIndexProgramColorsRandom()
{
    $colors = ["green", "blue", "purple", "pink", "black"];
    return $colors[array_rand($colors)];
}

function uploadImage($dir, $inputName, $resize = false, $width = null, $height = null)
{
    // Ensure the directory exists
    $directory = public_path($dir);
    if (!is_dir($directory)) {
        mkdir($directory, 0775, true);
    }

    // Get uploaded file details
    $file = $_FILES[$inputName];
    $originalName = pathinfo($file['name'], PATHINFO_FILENAME);
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);

    // Sanitize filename
    $originalName = preg_replace('/[^A-Za-z0-9\-_]/', '-', $originalName) . '-' . substr(str_replace('.', '', microtime(true)), -3);

    // Check if filename already exists and increment it if necessary
    $fileName = $originalName . '.' . $extension;
    $counter = 1;

    while (file_exists($directory . '/' . $fileName)) {
        $fileName = $originalName . '-' . $counter . '.' . $extension;
        $counter++;
    }

    // Define thumbnail names
    $fileThumbnail = pathinfo($fileName, PATHINFO_FILENAME) . '-medium.' . $extension;
    $fileSmall = pathinfo($fileName, PATHINFO_FILENAME) . '-small.' . $extension;

    // Move uploaded file to the target directory
    $originalPath = $directory . '/' . $fileName;
    move_uploaded_file($file['tmp_name'], $originalPath);

    // Resize image if needed
    if ($resize || $width || $height) {
        resizeImage($originalPath, $originalPath, $width, $height);
    }

    // Create medium thumbnail
    $thumbnailPath = $directory . '/' . $fileThumbnail;
    resizeImage($originalPath, $thumbnailPath, 400, null);

    // Create small thumbnail
    $smallPath = $directory . '/' . $fileSmall;
    resizeImage($originalPath, $smallPath, 70, null);

    // Return the original filename
    return $fileName;
}

function resizeImage($sourcePath, $destinationPath, $targetWidth = null, $targetHeight = null)
{
    list($originalWidth, $originalHeight, $imageType) = getimagesize($sourcePath);
    $image = createImageFromType($sourcePath, $imageType);

    // Calculate new dimensions
    if (!$targetWidth && !$targetHeight) {
        $targetWidth = $originalWidth;
        $targetHeight = $originalHeight;
    } elseif (!$targetWidth) {
        $targetWidth = ($targetHeight / $originalHeight) * $originalWidth;
    } elseif (!$targetHeight) {
        $targetHeight = ($targetWidth / $originalWidth) * $originalHeight;
    } else {
        $aspectRatio = $originalWidth / $originalHeight;
        if ($targetWidth / $targetHeight > $aspectRatio) {
            $targetWidth = $targetHeight * $aspectRatio;
        } else {
            $targetHeight = $targetWidth / $aspectRatio;
        }
    }

    // Create resized image
    $resizedImage = imagecreatetruecolor($targetWidth, $targetHeight);

    // Handle transparency for PNG and GIF
    if ($imageType == IMAGETYPE_PNG || $imageType == IMAGETYPE_GIF) {
        imagecolortransparent($resizedImage, imagecolorallocatealpha($resizedImage, 0, 0, 0, 127));
        imagealphablending($resizedImage, false);
        imagesavealpha($resizedImage, true);
    }

    imagecopyresampled(
        $resizedImage,
        $image,
        0, 0, 0, 0,
        $targetWidth,
        $targetHeight,
        $originalWidth,
        $originalHeight
    );

    saveImageFromType($resizedImage, $destinationPath, $imageType);

    imagedestroy($image);
    imagedestroy($resizedImage);
}

function createImageFromType($filePath, $imageType)
{
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            return imagecreatefromjpeg($filePath);
        case IMAGETYPE_PNG:
            return imagecreatefrompng($filePath);
        case IMAGETYPE_GIF:
            return imagecreatefromgif($filePath);
        default:
            throw new Exception('Unsupported image type');
    }
}

function saveImageFromType($image, $filePath, $imageType)
{
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            imagejpeg($image, $filePath, 100);
            break;
        case IMAGETYPE_PNG:
            imagepng($image, $filePath);
            break;
        case IMAGETYPE_GIF:
            imagegif($image, $filePath);
            break;
        default:
            throw new Exception('Unsupported image type');
    }
}

function removeImage($dir)
{
    $f1 = $dir;
    $f2 = str_replace('.', '-medium.', $f1);
    $f3 = str_replace('.', '-small.', $f1);
    File::delete(public_path() . '/' . $f1);
    File::delete(public_path() . '/' . $f2);
    File::delete(public_path() . '/' . $f3);
}

if (!function_exists('getRedirectionType')) {
    function getRedirectionType()
    {
        return [
            301 => '301 Permanent Move',
            302 => '302 Temporary Move',
            307 => '307 Temporary Redirect'
        ];
    }
}
function countryList()
{
    return [
        'afghanistan' => 'Afghanistan',
        'albania' => 'Albania',
        'algeria' => 'Algeria',
        'andorra' => 'Andorra',
        'angola' => 'Angola',
        'antigua-and-barbuda' => 'Antigua and Barbuda',
        'argentina' => 'Argentina',
        'armenia' => 'Armenia',
        'australia' => 'Australia',
        'austria' => 'Austria',
        'azerbaijan' => 'Azerbaijan',
        'bahamas' => 'Bahamas',
        'bahrain' => 'Bahrain',
        'bangladesh' => 'Bangladesh',
        'barbados' => 'Barbados',
        'belarus' => 'Belarus',
        'belgium' => 'Belgium',
        'belize' => 'Belize',
        'benin' => 'Benin',
        'bhutan' => 'Bhutan',
        'bolivia' => 'Bolivia',
        'bosnia-and-herzegovina' => 'Bosnia and Herzegovina',
        'botswana' => 'Botswana',
        'brazil' => 'Brazil',
        'brunei' => 'Brunei',
        'bulgaria' => 'Bulgaria',
        'burkina-faso' => 'Burkina Faso',
        'burundi' => 'Burundi',
        'cabo-verde' => 'Cabo Verde',
        'cambodia' => 'Cambodia',
        'cameroon' => 'Cameroon',
        'canada' => 'Canada',
        'central-african-republic' => 'Central African Republic',
        'chad' => 'Chad',
        'chile' => 'Chile',
        'china' => 'China',
        'colombia' => 'Colombia',
        'comoros' => 'Comoros',
        'congo' => 'Congo',
        'costa-rica' => 'Costa Rica',
        'croatia' => 'Croatia',
        'cuba' => 'Cuba',
        'cyprus' => 'Cyprus',
        'czech-republic' => 'Czech Republic',
        'denmark' => 'Denmark',
        'djibouti' => 'Djibouti',
        'dominica' => 'Dominica',
        'dominican-republic' => 'Dominican Republic',
        'ecuador' => 'Ecuador',
        'egypt' => 'Egypt',
        'el-salvador' => 'El Salvador',
        'equatorial-guinea' => 'Equatorial Guinea',
        'eritrea' => 'Eritrea',
        'estonia' => 'Estonia',
        'eswatini' => 'Eswatini',
        'ethiopia' => 'Ethiopia',
        'fiji' => 'Fiji',
        'finland' => 'Finland',
        'france' => 'France',
        'gabon' => 'Gabon',
        'gambia' => 'Gambia',
        'georgia' => 'Georgia',
        'germany' => 'Germany',
        'ghana' => 'Ghana',
        'greece' => 'Greece',
        'grenada' => 'Grenada',
        'guatemala' => 'Guatemala',
        'guinea' => 'Guinea',
        'guinea-bissau' => 'Guinea-Bissau',
        'guyana' => 'Guyana',
        'haiti' => 'Haiti',
        'honduras' => 'Honduras',
        'hungary' => 'Hungary',
        'iceland' => 'Iceland',
        'india' => 'India',
        'indonesia' => 'Indonesia',
        'iran' => 'Iran',
        'iraq' => 'Iraq',
        'ireland' => 'Ireland',
        'israel' => 'Israel',
        'italy' => 'Italy',
        'jamaica' => 'Jamaica',
        'japan' => 'Japan',
        'jordan' => 'Jordan',
        'kazakhstan' => 'Kazakhstan',
        'kenya' => 'Kenya',
        'kiribati' => 'Kiribati',
        'korea-north' => 'Korea, North',
        'korea-south' => 'Korea, South',
        'kosovo' => 'Kosovo',
        'kuwait' => 'Kuwait',
        'kyrgyzstan' => 'Kyrgyzstan',
        'laos' => 'Laos',
        'latvia' => 'Latvia',
        'lebanon' => 'Lebanon',
        'lesotho' => 'Lesotho',
        'liberia' => 'Liberia',
        'libya' => 'Libya',
        'liechtenstein' => 'Liechtenstein',
        'lithuania' => 'Lithuania',
        'luxembourg' => 'Luxembourg',
        'madagascar' => 'Madagascar',
        'malawi' => 'Malawi',
        'malaysia' => 'Malaysia',
        'maldives' => 'Maldives',
        'mali' => 'Mali',
        'malta' => 'Malta',
        'marshall-islands' => 'Marshall Islands',
        'mauritania' => 'Mauritania',
        'mauritius' => 'Mauritius',
        'mexico' => 'Mexico',
        'micronesia' => 'Micronesia',
        'moldova' => 'Moldova',
        'monaco' => 'Monaco',
        'mongolia' => 'Mongolia',
        'montenegro' => 'Montenegro',
        'morocco' => 'Morocco',
        'mozambique' => 'Mozambique',
        'myanmar' => 'Myanmar',
        'namibia' => 'Namibia',
        'nauru' => 'Nauru',
        'nepal' => 'Nepal',
        'netherlands' => 'Netherlands',
        'new-zealand' => 'New Zealand',
        'nicaragua' => 'Nicaragua',
        'niger' => 'Niger',
        'nigeria' => 'Nigeria',
        'north-macedonia' => 'North Macedonia',
        'norway' => 'Norway',
        'oman' => 'Oman',
        'pakistan' => 'Pakistan',
        'palau' => 'Palau',
        'palestine' => 'Palestine',
        'panama' => 'Panama',
        'papua-new-guinea' => 'Papua New Guinea',
        'paraguay' => 'Paraguay',
        'peru' => 'Peru',
        'philippines' => 'Philippines',
        'poland' => 'Poland',
        'portugal' => 'Portugal',
        'qatar' => 'Qatar',
        'romania' => 'Romania',
        'russia' => 'Russia',
        'rwanda' => 'Rwanda',
        'saint-kitts-and-nevis' => 'Saint Kitts and Nevis',
        'saint-lucia' => 'Saint Lucia',
        'saint-vincent-and-the-grenadines' => 'Saint Vincent and the Grenadines',
        'samoa' => 'Samoa',
        'san-marino' => 'San Marino',
        'sao-tome-and-principe' => 'Sao Tome and Principe',
        'saudi-arabia' => 'Saudi Arabia',
        'senegal' => 'Senegal',
        'serbia' => 'Serbia',
        'seychelles' => 'Seychelles',
        'sierra-leone' => 'Sierra Leone',
        'singapore' => 'Singapore',
        'slovakia' => 'Slovakia',
        'slovenia' => 'Slovenia',
        'solomon-islands' => 'Solomon Islands',
        'somalia' => 'Somalia',
        'south-africa' => 'South Africa',
        'spain' => 'Spain',
        'sri-lanka' => 'Sri Lanka',
        'sudan' => 'Sudan',
        'suriname' => 'Suriname',
        'sweden' => 'Sweden',
        'switzerland' => 'Switzerland',
        'syria' => 'Syria',
        'taiwan' => 'Taiwan',
        'tajikistan' => 'Tajikistan',
        'tanzania' => 'Tanzania',
        'thailand' => 'Thailand',
        'timor-leste' => 'Timor-Leste',
        'togo' => 'Togo',
        'tonga' => 'Tonga',
        'trinidad-and-tobago' => 'Trinidad and Tobago',
        'tunisia' => 'Tunisia',
        'turkey' => 'Turkey',
        'turkmenistan' => 'Turkmenistan',
        'tuvalu' => 'Tuvalu',
        'uganda' => 'Uganda',
        'ukraine' => 'Ukraine',
        'united-arab-emirates' => 'United Arab Emirates',
        'united-kingdom' => 'United Kingdom',
        'united-states' => 'United States',
        'uruguay' => 'Uruguay',
        'uzbekistan' => 'Uzbekistan',
        'vanuatu' => 'Vanuatu',
        'vatican-city' => 'Vatican City',
        'venezuela' => 'Venezuela',
        'vietnam' => 'Vietnam',
        'yemen' => 'Yemen',
        'zambia' => 'Zambia',
        'zimbabwe' => 'Zimbabwe',
    ];
}

function getCononicalUrl()
{
    if (!getConfigTableData()?->canonical_url) {
        return request()->url();
    }
    $curl = str_replace(request()->getHost(), getConfigTableData()?->canonical_url, request()->url());
    return $curl;
}


function getAllEmails()
{
    return array_filter(collect()
        ->merge(\App\Models\User::pluck('email'))
        ->merge(\App\Models\NewsletterSubscription::pluck('email'))
        ->merge(
            \App\Models\Email::pluck('to_email')
                ->flatMap(function ($item) {
                    // Decode each JSON entry and return as flat array
                    return is_array($item) ? $item : [];
                })
        )->merge(\App\Models\ContactUs::pluck('email'))
        ->unique()
        ->values()
        ->toArray());
}
function getAllPhoneNumbers()
{
    $collect= collect()
        ->merge(\App\Models\User::pluck('mobile_number'))
        ->merge(
            \App\Models\Sms::pluck('receiver')
                ->flatMap(function ($item) {
                    // Decode each JSON entry and return as flat array
                    return is_array($item) ? $item : [];
                })
        )->merge(\App\Models\ContactUs::pluck('mobile_number'))
        ->unique()
        ->values()
        ->toArray();
    return array_filter($collect);
}

function emailStatus()
{
    return
        ['draft', 'sent', 'inbox', 'send-now', 'failed'];
}
