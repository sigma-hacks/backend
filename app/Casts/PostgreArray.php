<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class PostgreArray implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $this->pgArrayParse($value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $array = $this->removeKeys($value);
        $array = json_encode($array, JSON_UNESCAPED_UNICODE);

        return str_replace('[', '{', str_replace(']', '}', str_replace('"', '', $array)));
    }

    /**
     * Remove named keys from arrays
     * @param array $array
     * @return array
     */
    private function removeKeys(array $array): array
    {
        $array = array_values($array);
        foreach ($array as &$value) {
            if (is_array($value)) $value = static::removeKeys($value);
        }

        return $array;
    }

    private function pgArrayParse($s, $start = 0, &$end = null): ?array
    {
        if (empty($s) || $s[0] != '{') return null;
        $return = [];
        $string = false;
        $quote = '';
        $len = strlen($s);
        $v = '';
        for ($i = $start + 1; $i < $len; $i++) {
            $ch = $s[$i];

            if (!$string && $ch == '}') {
                if ($v !== '' || !empty($return)) {
                    $return[] = $v;
                }
                $end = $i;
                break;
            } else
                if (!$string && $ch == '{') {
                    $v = $this->pgArrayParse($s, $i, $i);
                } else
                    if (!$string && $ch == ',') {
                        $return[] = $v;
                        $v = '';
                    } else
                        if (!$string && ($ch == '"' || $ch == "'")) {
                            $string = true;
                            $quote = $ch;
                        } else
                            if ($string && $ch == $quote && $s[$i - 1] == "\\") {
                                $v = substr($v, 0, -1) . $ch;
                            } else
                                if ($string && $ch == $quote && $s[$i - 1] != "\\") {
                                    $string = false;
                                } else {
                                    $v .= $ch;
                                }
        }

        foreach ($return as &$r) {
            if (is_numeric($r)) {
                if (ctype_digit($r)) $r = (int)$r;
                else $r = (float)$r;
            }
        }
        return $return;
    }
}
