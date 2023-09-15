<?php

namespace App\Helpers;

use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class MainHelper
{

    /**
     * Return prepared response
     *
     * @param bool $status
     * @param array|null $additionalData
     * @param array|null $errors
     * @return array
     */
    public static function getResponse(bool $status = false, array $additionalData = null, array $errors = null): array
    {
        $response = [
            'status' => $status
        ];

        if ($additionalData && is_array($additionalData) && !empty($additionalData)) {
            $response['data'] = $additionalData;
        }

        if (!empty($errors) && is_array($errors)) {
            $response['errors'] = $errors;
        }

        if (empty($errors) && $status === false) {
            $response['errors'] = [
                [
                    'message' => 'Undefined error',
                    'code' => 404
                ]
            ];
        }

        return $response;
    }

    /**
     * Return prepared error response
     *
     * @param array $errors
     * @return bool[]
     */
    public static function getErrorResponse(array $errors = []): array
    {
        return self::getResponse(false, null, $errors);
    }

    /**
     * Return line with single error for error response
     *
     * @param int $errorCode
     * @param string $errorMessage
     * @param array $data
     * @return array
     */
    public static function getErrorItem(int $errorCode = 404, string $errorMessage = 'Undefined error', array $data = []): array
    {
        $result = [
            'message' => $errorMessage,
            'code' => $errorCode,
        ];

        if (!empty($data)) {
            $result['data'] = $data;
        }

        return $result;
    }

    /**
     * Transliterate cyrilic to latin for URL
     *
     * @param string $originalText
     * @return string
     */
    public static function cyr2lat(string $originalText): string
    {
        $clearText = str_ireplace(' ', '-', trim($originalText));
        $clearText = preg_replace('/[^a-zA-Zа-яА-Я0-9-_]/ui', '', $clearText);

        $cyr = [
            'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п',
            'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я',
            'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П',
            'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я'
        ];

        $lat = [
            'a', 'b', 'v', 'g', 'd', 'e', 'io', 'zh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p',
            'r', 's', 't', 'u', 'f', 'h', 'ts', 'ch', 'sh', 'sht', 'a', 'i', 'y', 'e', 'yu', 'ya',
            'A', 'B', 'V', 'G', 'D', 'E', 'Io', 'Zh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P',
            'R', 'S', 'T', 'U', 'F', 'H', 'Ts', 'Ch', 'Sh', 'Sht', 'A', 'I', 'Y', 'e', 'Yu', 'Ya'
        ];

        return strtolower(str_replace($cyr, $lat, $clearText));
    }

    /**
     * Transliterate cyrilic to latin for Files
     *
     * @param string $originalText
     * @return string
     */
    public static function cyr2latForFiles(string $originalText): string
    {
        $clearText = str_ireplace(' ', '-', trim($originalText));
        $clearText = preg_replace('/[^a-zA-Zа-яА-Я0-9-\._]/ui', '', $clearText);

        $cyr = [
            'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п',
            'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я',
            'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П',
            'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', '.'
        ];

        $lat = [
            'a', 'b', 'v', 'g', 'd', 'e', 'io', 'zh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p',
            'r', 's', 't', 'u', 'f', 'h', 'ts', 'ch', 'sh', 'sht', 'a', 'i', 'y', 'e', 'yu', 'ya',
            'A', 'B', 'V', 'G', 'D', 'E', 'Io', 'Zh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P',
            'R', 'S', 'T', 'U', 'F', 'H', 'Ts', 'Ch', 'Sh', 'Sht', 'A', 'I', 'Y', 'e', 'Yu', 'Ya', '.'
        ];

        return strtolower(str_replace($cyr, $lat, $clearText));
    }

    /**
     * Prepare unit case by number
     *
     * @param int $n
     * @param array $titles
     * @return string
     */
    public static function getUnitCase(int $n, array $titles): string
    {
        $cases = array(2, 0, 1, 1, 1, 2);
        return $titles[($n % 100 > 4 && $n % 100 < 20) ? 2 : $cases[min($n % 10, 5)]];
    }

    /**
     * Generate random string
     *
     * @param int $length
     * @return string
     */
    public static function randomString(int $length = 8): string
    {
        $alphabet = 'G9bd4eafg2hcijknlmopVqrtuvwxyzXBDEFIHJKALMNs_OPCQRSTUWYZ1730568';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    /**
     * Getting current auth user data
     *
     * @return User|false
     */
    public static function getUser(): User|false
    {

        $token = (string)app('request')->header('Client-Token', '');

        if (strlen($token) <= 32) {
            return false;
        }

        global $userData;

        if ($userData instanceof User) {
            return $userData;
        }

        $authorization = Authorization::where('token', $token)->with('user')->first();

        if( !$authorization?->id ) {
            return false;
        }

        $user = $authorization->user;

        if (!$user?->id) {
            return false;
        }

        $userData = $user;
        return $user;
    }

    /**
     * Get current user id
     *
     * @return int
     */
    public static function getUserId(): int
    {
        try {
            return (int)self::getUser()?->id;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Get role id of current user
     *
     * @return int
     */
    public static function getUserRoleId(): int
    {
        try {
            return (int)self::getUser()?->role_id;
        } catch (Exception $e) {
            return 0;
        }
    }

    /**
     * Get role of current user
     *
     * @return Role|null
     */
    public static function getUserRole(): Role|null
    {
        try {
            return self::getUser()?->role;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Is current user has moder rules
     *
     * @return bool
     */
    public static function isModer(): bool
    {
        return (bool)self::getUserRole()?->is_moder;
    }

    /**
     * Is current user has admin rules
     *
     * @return bool
     */
    public static function isAdmin(): bool
    {
        return (bool)self::getUserRole()?->is_admin;
    }

    /**
     * Is current user has moder or admin rules
     *
     * @return bool
     */
    public static function isAdminOrModer(): bool
    {
        return self::isModer() || self::isAdmin();
    }

    /**
     * Is current user has Guide rules
     *
     * @return bool
     */
    public static function isGuide(): bool
    {
        return self::getUserRole()?->is_guide || self::isAdminOrModer();
    }

    /**
     * Is current user has Guide rules but hasn't Admin or Model Rules
     *
     * @return bool
     */
    public static function isGuideNotModer(): bool
    {
        return self::getUserRole()?->is_guide && !self::isAdminOrModer();
    }

    /**
     * Return DB Error for responses
     *
     * @param Exception $e
     * @return Response
     */
    public static function getDBError(Exception $e): Response
    {
        return response([
            'status' => false,
            'error' => 'Error in database',
            'database_error' => $e->getMessage()
        ], 500);
    }

    /**
     * Sending action to client from websocket
     *
     * @param string $action
     * @param string $token
     * @param array $data
     * @return void
     */
    public static function sendAction(string $action, string|null $token, array $data): void
    {

        if( !$token ) {
            return;
        }

        $url = config('websockets.server_url');

        $response = Http::post($url . '/api/action', [
            'action' => $action,
            'data' => $data,
            'token' => $token
        ]);

        if (app()->isLocal()) {
            $caches = Cache::get('websocket.cache.testing') ?? [];
            $caches[] = [
                'action' => $action,
                'data' => $data,
                'token' => $token
            ];
            Cache::put('websocket.cache.testing', $caches, 60 * 5);
        }

        if( $response ) {

        }
    }

    /**
     * Replace variables in template from values array
     *
     * @param string|null $template
     * @param array $values
     * @return string|null
     */
    public static function replaceTemplate(string|null $template, array $values = []): string|null
    {

        $resultString = $template;

        if( $template == null ) {
            return $resultString;
        }

        foreach ($values as $key => $value) {
            $resultString = str_replace("{{$key}}", $value, $resultString);
        }

        return $resultString;
    }

    /**
     * Clear string from template variables
     *
     * @param string|null $template
     * @return string|null
     */
    public static function clearTemplate(string|null $template): string|null
    {
        if( $template === null ) {
            return null;
        }

        $result = preg_replace("/\{[A-Za-z0-9_]+\}/i", '', $template);
        return preg_replace("/\s+/i", ' ', $result);
    }

    /**
     * Validate fields
     *
     * @param Request $request
     * @param array $rules
     * @return ValidateResult
     * @throws ValidationException
     */
    public static function validate(Request $request, array $rules): ValidateResult
    {

        $data = $request->all();
        foreach ($data as $key => $field) {
            switch ($key) {
                case 'address': $data[$key] = $field ?? ''; break;
                default: break;
            }
        }

        $validate = Validator::make($data, $rules);
        if ($validate->fails()) {
            return ValidateResult::create(
                false,
                [],
                'Validation. Credentials is wrong',
                $validate->getMessageBag()->toArray()
            );
        } else {
            return ValidateResult::create(true, $validate->validate());
        }
    }

    /**
     * Return array with pre moderate fields for user
     *
     * @return string[]
     */
    public static function getUserFieldsIsModerating(): array
    {
        return [
            'name',
            'email',
            'phone',
            'additional_properties',
            'photo'
        ];
    }

    /**
     * For generating temporary tokens
     *
     * @param string $appendData
     * @return string
     */
    public static function generateToken(string $appendData = ''): string {
        $data = [];

        if( $appendData && mb_strlen($appendData) >= 1 ) {
            $data[] = $appendData;
        }

        $data[] = rand(1000, 100000);
        $data[] = date('M-Yd-HuimSs-Ihy-SA-S');
        $data[] = rand(1000, 100000);
        return Hash::make(implode(':', $data));
    }

    /**
     * Generate normal format from search created_at
     *
     * @param $date
     * @param $format
     * @return string|null
     */
    public static function dateFormat(string|null $date, string $format): string|null
    {
        if (is_null($date)) {
            return null;
        }

        $dateFormat = [
            'date_from' => 'Y-m-d 00:00:00',
            'date_to' => 'Y-m-d 23:59:59'
        ];
        $dateTime = new \DateTime($date);

        if ($dateTime->format("H:i:s") !== '00:00:00') {
            $date = $dateTime->format('Y-m-d H:i:s');
        } else {
            $date = $dateTime->format($dateFormat[$format]);
        }

        return $date;
    }

    /**
     * Change first symbol to UpperCase
     *
     * @param string|null $text
     * @return string|null
     */
    public static function ucfirst(string|null $text): string|null
    {
        if (is_null($text)) {
            return null;
        }

        return mb_strtoupper(mb_substr($text, 0, 1)) . mb_substr($text, 1);
    }

    /**
     * Return file type
     *
     * @param string $url
     * @return string
     */
    public static function getFileType(string $url): string
    {
        $exUrl = explode('.', $url);
        $fileExtension = mb_strtolower($exUrl[count($exUrl) - 1]);

        if( mb_strlen($fileExtension) >= 10 ) {
            $fileExtension = 'none';
        }

        return match ($fileExtension) {
            'png','svg','jpg','jpeg','webp','gif','apng','bmp','ico', => 'image',
            'tiff','heif','avif' => 'imageNonSupport',
            'pdf','doc','docs','docx','xls','xlsx','txt' => 'document',
            'exe','sh','bash','bat','js','vbs','jse','wsf','wsh','msc','msi','com','vbe' => 'executable',
            'mp3','wav','aac','midi','m4a','amr','ac3','ra','3ga','ogg','aiff','vqf','asf','dsd','flac','wma','tak' => 'audio',
            'mp4','mov','wmv','avi','flv','f4v','swf','webm','mkv','mpeg-2','3gp' => 'video',
            'psd' => 'adobe',
            'apk' => 'android',
            'drv','dmg','app' => 'apple',
            'html' => 'browser',
            default => $fileExtension
        };
    }


    /**
     * Get all Admin
     *
     * @return Collection
     */
    public static function getAdmins(): Collection
    {
        return User::where('role_id', '=', 40)
            ->get();
    }

    /**
     * Get all Moder
     *
     * @return Collection
     */
    public static function getModers(): Collection
    {
        return User::where('role_id', '=', 30)
            ->get();
    }
}
