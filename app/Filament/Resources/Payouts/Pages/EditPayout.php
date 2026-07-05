<?php

namespace App\Filament\Resources\Payouts\Pages;

use App\Filament\Resources\Payouts\PayoutResource;
use App\Services\PayoutService;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditPayout extends EditRecord
{
    protected static string $resource = PayoutResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['bank_details']) && is_array($data['bank_details'])) {
            $data['bank_details'] = array_filter($data['bank_details']);
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        $user = auth()->user();
        $payout = $this->record;
        $actions = [];

        if ($user->can('approve', $payout)) {
            $actions[] = $this->buildApproveAction();
        }

        if ($user->can('markPaid', $payout)) {
            $actions[] = $this->buildMarkPaidAction();
        }

        if ($user->can('markFailed', $payout)) {
            $actions[] = $this->buildMarkFailedAction();
        }

        return $actions;
    }

    private function buildApproveAction(): Action
    {
        return Action::make('approve')
            ->label('Approve & Process')
            ->color('warning')
            ->icon('heroicon-o-check-circle')
            ->requiresConfirmation()
            ->modalHeading('Approve payout request')
            ->modalDescription('Are you sure you want to approve this payout? The company wallet will remain debited while the payout is processed.')
            ->modalSubmitActionLabel('Yes, approve')
            ->action(function (): void {
                $this->handleServiceCall(
                    fn () => app(PayoutService::class)->approve($this->record, auth()->user()),
                    'Payout approved successfully.',
                );
            });
    }

    private function buildMarkPaidAction(): Action
    {
        return Action::make('markPaid')
            ->label('Mark as Paid')
            ->color('success')
            ->icon('heroicon-o-check-circle')
            ->requiresConfirmation()
            ->modalHeading('Mark payout as paid')
            ->modalDescription('This action records that the funds have been sent to the company. The company will receive an invoice.')
            ->modalSubmitActionLabel('Yes, mark as paid')
            ->form([
                FileUpload::make('payment_proof')
                    ->label('Payment Screenshot')
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(2048)
                    ->directory('payment-proofs')
                    ->disk('public')
                    ->required(),
            ])
            ->action(function (array $data): void {
                $proof = $data['payment_proof'] ?? null;

                $this->handleServiceCall(
                    fn () => app(PayoutService::class)->markPaid($this->record, $proof),
                    'Payout marked as paid.',
                );
            });
    }

    private function buildMarkFailedAction(): Action
    {
        return Action::make('markFailed')
            ->label('Mark Failed')
            ->color('danger')
            ->icon('heroicon-o-x-circle')
            ->requiresConfirmation()
            ->modalHeading('Mark payout as failed')
            ->modalDescription('The payout amount will be released back to the company wallet.')
            ->modalSubmitActionLabel('Yes, mark failed')
            ->form([
                Textarea::make('reason')
                    ->label('Failure reason')
                    ->required()
                    ->maxLength(500),
            ])
            ->action(function (array $data): void {
                $this->handleServiceCall(
                    fn () => app(PayoutService::class)->markFailed($this->record, $data['reason']),
                    'Payout marked as failed.',
                );
            });
    }

    private function handleServiceCall(callable $callback, string $successMessage): void
    {
        try {
            $callback();

            $this->record->refresh();
            $this->fillForm();

            Notification::make()
                ->success()
                ->title($successMessage)
                ->send();
        } catch (\RuntimeException $e) {
            Notification::make()
                ->danger()
                ->title($e->getMessage())
                ->send();
        }
    }
}
