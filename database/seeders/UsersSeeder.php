<?php

namespace Database\Seeders;

use App\Helpers\MainHelper;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{

    public static array $data = [
        'firstnames' => [
            ['Стрижков','Бондаренко','Ильин','Иванов','Петров','Судаков','Андреев','Льичев','Лычев','Петросян','Агоненок','Пламенов','Пышкин','Сергеенюк','Валенов','Димиденов','Арахмидов','Ахмедов','Самсонов','Мишустин','Лионов','Мармеладов','Константинов','Крпитографов','Лейбниц','Маркс','Дриноков'],
            ['Стрижкова','Лысова','Мариенко','Кострамова','Алипова','Нинтендова','Москвина','Барнаульская','Петрова','Волосатова','Игнатьева','Марьенко','Ланардова','Минская','Ахмедова','Домодедова','Петрова','Александрова','Олихова','Нинаева','Минаева','Алиева','Клининградко','Китчюмова']
        ],
        'names' => [
            ['Александр','Артем','Борис','Богдан','Андрей','Петр','Сергей','Генадий','Григорий','Василий','Бернард','Константин','Станислав','Кирилл','Магомед','Антон','Валерий','Дмитрий','Евгений','Роман','Илья','Михаил','Иван'],
            ['Алефтина','Ирина','Инна','Мария','Марина','Александра','Ксения','Ольга','Василиса','Клеопатра','Екатерина','Анастасия','Валерия','Лия','Риана','Галина','Зоя','Вера','Надежда','Татьяна','Дарья','Светлана']
        ],
        'lastnames' => [
            ['Александрович','Сергеевич','Дмитриевич','Васильевич','Станиславович','Артемович','Генадьевич','Андреевич','Борисович','Кириллович','Магомедович','Антонович','Валерьевич','Петрович','Иванович','Романович'],
            ['Александровна','Сергеевна','Дмитриевна','Васильевна','Станиславовна','Артемовна','Генадьевна','Андреевна','Борисовна','Кирилловна','Магомедовна','Антоновна','Валерьевна','Петровна','Ивановна','Романовна'],
        ],
        'birthdate_start' => '1923-10-01 00:00:00',
        'phone' => ['from' => 79001008800, 'to' => 79999998811],
        'email' => ['pattern' => '[a-z]{1-2}[a-z1-10]{4-12}@(mail.ru|gmail.com|ya.ru|bk.ru)']
    ];

    private array $usedCardNumbers = [];

    public function generateName(array $data):string {

        $gender = rand(0, 1);

        return MainHelper::getRandomValue($data['firstnames'][$gender]) . ' ' .
            MainHelper::getRandomValue($data['names'][$gender]) . ' ' .
            MainHelper::getRandomValue($data['lastnames'][$gender]);
    }

    public function generatePhone($data): int {
        return rand($data['phone']['from'], $data['phone']['to']);
    }

    public function generateCardNumber(): int {
        global $usedCardNumbers;

        do {
            $number = '2200' . rand(100000000000, 999999999999);
        } while (in_array($number, $this->usedCardNumbers));

        $usedCardNumbers[] = $number;
        return $number;
    }

    public function getAge($birthdate): int {
        $dob = Carbon::parse($birthdate);
        return $dob->age;
    }

    public function generateCardForUser($userId, $birthdate): array {
        $cardsCount = rand(1, 2);
        $cards = [];
        $activeCardIndex = rand(0, $cardsCount - 1);

        $age = $this->getAge($birthdate);

        $availableTariffs = [];
        if ($age >= 6 && $age <= 19) {
            $availableTariffs[] = 1;
        }
        if ($age >= 45) {
            $availableTariffs[] = 2;
        }
        if ($age <= 150) {
            $availableTariffs[] = 3;
        }

        for ($i = 0; $i < $cardsCount; $i++) {
            $expireYears = (rand(0, 1) === 1) ? rand(2026, 2030) : rand(date('Y'), 2025);
            $tarifExpireYears = (rand(0, 1) === 1) ? $expireYears : rand(date('Y'), $expireYears);

            $tariff_id = (rand(0, 1) === 1 && count($availableTariffs) > 0) ? $availableTariffs[array_rand($availableTariffs)] : null;

            $cards[] = [
                'expired_at' => date('Y-m-d', strtotime("$expireYears-12-31")),
                'identifier' => $this->generateCardNumber(),
                'tariff_expired_at' => ($tariff_id !== null && rand(0, 1) === 1) ? date('Y-m-d', strtotime("$tarifExpireYears-12-31")) : null,
                'tariff_id' => $tariff_id,
                'user_id' => $userId,
                'is_active' => ($i === $activeCardIndex)
            ];
        }

        return $cards;
    }

    public function generateEmail(array $data): string {
        $pattern = $data['email']['pattern'];

        // Генерация первой части электронного адреса
        $part1 = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, rand(1, 2));

        // Генерация второй части электронного адреса
        $characters = 'abcdefghijklmnopqrstuvwxyz1234567890';
        $part2 = substr(str_shuffle($characters), 0, rand(4, 12));

        // Выбор домена для электронной почты
        preg_match('/\(([^)]+)\)/', $pattern, $matches);
        $domains = explode('|', $matches[1]);
        $domain = MainHelper::getRandomValue($domains);

        return $part1 . $part2 . "@" . $domain;
    }

    public function generatePassword():string {
        return substr(str_shuffle('01234_56789abcdefghijk-lmnopqrstuvwxy%zABCDEFGHIJKL.MNOPQRST^UVWXYZ'), 0, rand(6, 12));
    }

    public function generateBirthdate($start_date): string {
        $start_timestamp = strtotime($start_date);
        $end_timestamp = time();

        $random_timestamp = rand($start_timestamp, $end_timestamp);
        return date('Y-m-d', $random_timestamp);
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        for ($i = 0; $i < 300000; $i++) {
            $name = $this->generateName(self::$data);

            $user = [
                'role_id' => 0,
                'name' => $name,
                'code' => MainHelper::cyr2lat($name) . '_' . rand(0, 1000000),
                'phone' => $this->generatePhone(self::$data),
                'email' => $this->generateEmail(self::$data),
                'password' => $this->generatePassword(),
                'birth_date' => $this->generateBirthdate(self::$data['birthdate_start']),
                'photo' => '',
                'pin' => '',
                'identify' => '',
                'employee_card' => ''
            ];

            try {
                $userId = DB::table('users')->insertGetId($user);

                $cards = $this->generateCardForUser($userId, $user['birth_date']);

                try {
                    foreach ($cards as $card) {
                        DB::table('cards')->insert($card);
                    }
                } catch (Exception $e) {
                    print_r($e->getMessage());
                }
            } catch (Exception $e) {
                print_r($e->getMessage());
            }
        }
    }
}
