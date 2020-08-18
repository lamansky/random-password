<?php
namespace Lamansky\RandomPassword;
use function Lamansky\SecureShuffle\shuffled;

class RandomPasswordFactory {

    // In the unambiguous character sets, we omit letters or numbers that are
    // easily confused with each other. For example, uppercase G looks like 6
    // and lowercase g looks like 9.
    protected const LOWER = 'abcdefghijklmnopqrstuvwxyz';
    protected const UNAMBIG_LOWER = 'abcdefhjkmnpqrstuvwxyz'; // Letters i, g, l, and o are omitted.
    protected const UPPER = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    protected const UNAMBIG_UPPER = 'ABCDEFHJKLMNPQRSTUVWXYZ'; // Letters I, G, and O are omitted.
    protected const NUMBERS = '1234567890';
    protected const UNAMBIG_NUMBERS = '2345789'; // Numbers 1, 6, and 0 are omitted.
    protected const SAFE_SYMBOLS = '!@#^*_';

    protected static function getAvailableSets (string $custom_set = '') : array {
        return [
            'L' => static::LOWER,
            'l' => static::UNAMBIG_LOWER,
            'U' => static::UPPER,
            'u' => static::UNAMBIG_UPPER,
            'N' => static::NUMBERS,
            'n' => static::UNAMBIG_NUMBERS,
            's' => static::SAFE_SYMBOLS,
            'c' => $custom_set,
        ];
    }

    protected const DEFAULT_SET_CODES = 'u l n';

    public static function alpha () : self {
        return new static('u l');
    }

    public static function numeric () : self {
        return new static('N');
    }

    public static function alphanumeric () : self {
        return new static('u l n');
    }

    public static function loweralphanumeric () : self {
        return new static('l n');
    }

    public static function ascii () : self {
        return new static('u l n s');
    }

    public static function custom (string $custom_set) : self {
        return new static('c', $custom_set);
    }

    protected $sets;

    public function __construct (string $set_codes = null, string $custom_set = '') {
        $this->sets = $this->getSets($set_codes, $custom_set);
    }

    private function getSets (string $set_codes = null, string $custom_set = '') : array {
        if (is_null($set_codes)) { $set_codes = static::DEFAULT_SET_CODES; }

        $available_sets = static::getAvailableSets($custom_set);
        $sets = [];
        foreach (explode(' ', $set_codes) as $set_code_set) {
            $set = '';
            foreach (str_split($set_code_set) as $set_code) {
                if (isset($available_sets[$set_code]) && strlen($available_sets[$set_code]) > 0) {
                    $set .= $available_sets[$set_code];
                }
            }
            if (strlen($set)) { $sets[] = $set; }
        }
        return $sets;
    }

    public function generate (int $length, string $set_codes = null, string $custom_set = '') : string {
        $sets = shuffled(is_null($set_codes) ? $this->sets : $this->getSets($set_codes, $custom_set));

        $password = '';
        reset($sets);
        for ($i = 0; $i < $length; $i++) {
            $characters = current($sets);
            $password .= $characters[random_int(0, strlen($characters) - 1)];
            if (next($sets) === false) { reset($sets); }
        }

        return $password;
    }
}
