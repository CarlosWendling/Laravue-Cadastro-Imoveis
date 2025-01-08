<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use function Illuminate\Support\defer;

class ValidacaoIdade implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->maioridade($value)) {
            $fail("A pessoa precisa ter no mínimo 18 anos");
        }
    }

    /**
     * Valida se a pessoa possui mais de 18 anos.
     *
     * @param string $nascimento
     * @return bool
     */
    public function maioridade (string $nascimento): bool
    {
        list($anoNascimento, $mesNascimento, $diaNascimento) = explode('-', $nascimento);

        $dataAtual = date('Y-m-d');
        list($anoAtual, $mesAtual, $diaAtual) = explode('-', $dataAtual);

        if ($anoAtual - $anoNascimento > 18) {
            return true;
        // Caso seja no ano que a pessoa completa 18 anos
        } elseif ($anoAtual - $anoNascimento == 18) {
            if ($mesAtual > $mesNascimento) {
                return true;
            } elseif ($mesAtual == $mesNascimento) {
                if ($diaAtual >= $diaNascimento) {
                    return true;
                }
            }
        }
        return false;
    }
}
