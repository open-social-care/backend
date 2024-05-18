<?php

namespace App\Support;

class DocumentValidator
{
    public static function validateCnpj(string $cnpj): bool
    {
        $cnpj = preg_replace('/\D/', '', $cnpj);
        $num = [];

        for ($i = 0; $i < (strlen($cnpj)); $i++) {

            $num[] = $cnpj[$i];
        }

        if (count($num) != 14) {
            return false;
        }

        if ($num[0] == 0 && $num[1] == 0 && $num[2] == 0
            && $num[3] == 0 && $num[4] == 0 && $num[5] == 0
            && $num[6] == 0 && $num[7] == 0 && $num[8] == 0
            && $num[9] == 0 && $num[10] == 0 && $num[11] == 0) {
            return false;
        } else {
            $j = 5;
            for ($i = 0; $i < 4; $i++) {
                $multiplica[$i] = $num[$i] * $j;
                $j--;
            }
            $soma = array_sum($multiplica);
            $j = 9;
            for ($i = 4; $i < 12; $i++) {
                $multiplica[$i] = $num[$i] * $j;
                $j--;
            }
            $soma = array_sum($multiplica);
            $resto = $soma % 11;
            if ($resto < 2) {
                $dg = 0;
            } else {
                $dg = 11 - $resto;
            }
            if ($dg != $num[12]) {
                return false;
            }
        }

        $j = 6;
        for ($i = 0; $i < 5; $i++) {
            $multiplica[$i] = $num[$i] * $j;
            $j--;
        }
        $soma = array_sum($multiplica);
        $j = 9;
        for ($i = 5; $i < 13; $i++) {
            $multiplica[$i] = $num[$i] * $j;
            $j--;
        }
        $soma = array_sum($multiplica);
        $resto = $soma % 11;
        if ($resto < 2) {
            $dg = 0;
        } else {
            $dg = 11 - $resto;
        }
        if ($dg != $num[13]) {
            return false;
        } else {
            return true;
        }
    }

    public static function validateCpf(string $cpf): bool
    {
        if (empty($cpf)) {
            return false;
        }

        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        if (strlen($cpf) != 11) {
            return false;
        } elseif (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        } else {
            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf[$c] * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf[$c] != $d) {
                    return false;
                }
            }

            return true;
        }
    }

    public static function validateRg(string $rg): bool
    {
        if (empty($rg)) {
            return false;
        }

        $rg = preg_replace('/[^0-9Xx]/', '', $rg);

        if (strlen($rg) < 8 || strlen($rg) > 9) {
            return false;
        }

        if (! preg_match('/^[0-9]{8,9}$|^[0-9]{8}X$/i', $rg)) {
            return false;
        }

        return true;
    }
}
