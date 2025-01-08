<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidacaoCpf implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->cpfValido($value)) {
            $fail("O CPF informado não é válido");
        }
    }

    /**
     * Valida se o CPF é válido.
     *
     * @param string $cpf
     * @return bool
     */
    private function cpfValido(string $cpf): bool
    {
        // Remove os valores não numéricos (. -)
        $cpf = preg_replace('/\D/', '', $cpf);

        // Verifica se todos os dígitos são iguais
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        if (strlen($cpf) != 11) {
            return false;
        }

        $somaDigVer1 = 0;
        $somaDigVer2 = 0;

        // Calcular o primeiro dígito verificador
        for ($i = 0, $peso = 10; $i < 9; $i++, $peso--) {
            $somaDigVer1 += $cpf[$i] * $peso;
        }

        for ($i = 0, $peso = 11; $i < 10; $i++, $peso--) {
            $somaDigVer2 += $cpf[$i] * $peso;
        }

        $digVerificador1 = ($somaDigVer1 * 10) % 11;
        $digVerificador1 = $digVerificador1 == 10 ? 0 : $digVerificador1;

        $digVerificador2 = ($somaDigVer2 * 10) % 11;
        $digVerificador2 = $digVerificador2 == 10 ? 0 : $digVerificador2;

        if ($digVerificador1 != $cpf[9] || $digVerificador2 != $cpf[10]) {
            return false;
        }

        return true;
    }
}
