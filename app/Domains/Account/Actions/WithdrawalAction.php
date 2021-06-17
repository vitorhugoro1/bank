<?php

namespace App\Domains\Account\Actions;

use App\Domains\Account\DataTransferObject\WithdrawalRequestData;
use App\Domains\Account\DataTransferObject\WithdrawalResponseData;
use App\Domains\Account\Exceptions\BalanceAccountIsLowException;
use App\Domains\Account\Models\Account;
use App\Domains\Account\Exceptions\NotHasNoteOptionException;
use App\Domains\Account\Exceptions\NotCanWithdrawalSelectedAmountException;
use App\Domains\Reports\Actions\IssueOperation;
use App\Domains\Reports\Enums\ReportOperationEnum;

class WithdrawalAction
{
    public const TWENTY_NOTE = 20;
    public const FIFTY_NOTE = 50;
    public const ONE_HUNDRED_NOTE = 100;

    private const WITHDRAWAL_ACCEPTED_NOTES = [self::TWENTY_NOTE, self::FIFTY_NOTE, self::ONE_HUNDRED_NOTE];

    public function __construct(private IssueOperation $issueOperation)
    {
        //
    }

    public function execute(Account $account, WithdrawalRequestData $withdrawalRequestData)
    {
        // validate if user has balance for withdrawal
        throw_unless(
            $this->hasBalanceForWithdrawal($account, $withdrawalRequestData),
            BalanceAccountIsLowException::class
        );

        // validate if does not has notes options for selected value
        throw_unless(
            $this->hasMinimumNoteAmount($withdrawalRequestData->amount),
            NotHasNoteOptionException::class
        );

        $notes = $this->selectNotesForAmount($withdrawalRequestData->amount);

        $account->balance = $account->balance - $withdrawalRequestData->amount;
        $account->save();

        $this->issueOperation->onQueue()->execute(
            $account,
            ReportOperationEnum::withdrawal(),
            $withdrawalRequestData->amount,
            $account->balance
        );

        return new WithdrawalResponseData(
            withdrawalAmount: $withdrawalRequestData->amount,
            balance: $account->balance,
            notes: $notes
        );
    }

    protected function selectNotesForAmount(int $amount): array
    {
        $options = [];
        $acceptedNotes = self::WITHDRAWAL_ACCEPTED_NOTES;

        rsort($acceptedNotes);

        // Percorrer todos as opções de notas
        // começar da maior para a menor
        foreach ($acceptedNotes as $acceptedNote) {
            if ($amount <= 0) {
                break;
            }

            $noteQuantity = intval($amount / $acceptedNote);

            // fazer a divisão do valor sacado pela opção de nota percorrida
            // se não der um número maior que 1, ignorar a nota
            if ($noteQuantity < 1) {
                continue;
            }

            for ($addedNote = 0; $addedNote < $noteQuantity; $addedNote++) {
                $options[] = $acceptedNote;
            }

            // se der pegar o número inteiro de notas correspondente
            // multiplicar a quantidade, e subtrair o valor do $amount
            // ex. 240 / 100 = 2,4. Então 2 é quantidade, (2 * 100) - 240 = 40
            $amount -= ($noteQuantity * $acceptedNote);
        }

        // no fim de todas as opções não pode sobrar restos
        // se sobrar disparar exceção que não é possível sacar o valor
        throw_if($amount > 0, NotCanWithdrawalSelectedAmountException::class);

        return $options;
    }

    protected function hasBalanceForWithdrawal(Account $account, WithdrawalRequestData $withdrawalRequestData): bool
    {
        return $account->balance >= $withdrawalRequestData->amount;
    }

    protected function hasMinimumNoteAmount(int $amount): bool
    {
        return $amount >= min(self::WITHDRAWAL_ACCEPTED_NOTES);
    }
}
