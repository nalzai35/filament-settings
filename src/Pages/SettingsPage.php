<?php

namespace Nalzai35\FilamentSettings\Pages;

use Filament\Pages\Page;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Pages\Concerns\HasUnsavedDataChangesAlert;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Facades\FilamentView;
use Nalzai35\FilamentSettings\Models\Setting;
use Throwable;

use function Filament\Support\is_app_url;

class SettingsPage extends Page
{
    use CanUseDatabaseTransactions;
    use Concerns\InteractsWithFormActions;
    use HasUnsavedDataChangesAlert;

    protected static string $view = 'filament-settings::pages.settings-page';
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static ?string $model = Setting::class;

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    private static function getModel()
    {
        return new self::$model;
    }

    public function mount(): void
    {
        $this->fillForm();
    }

    protected function fillForm(): void
    {
        $this->callHook('beforeFill');

        $settings = static::getSettings();

        $data = $this->mutateFormDataBeforeFill($settings);

        $this->form->fill($data);

        $this->callHook('afterFill');
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $data;
    }

    public function save(): void
    {
        try {
            $this->beginDatabaseTransaction();

            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeSave($data);

            $this->callHook('beforeSave');

            setting($data);

            $this->callHook('afterSave');

            $this->commitDatabaseTransaction();
        } catch (Halt $exception) {
            $exception->shouldRollbackDatabaseTransaction() ?
                $this->rollBackDatabaseTransaction() :
                $this->commitDatabaseTransaction();

            return;
        } catch (Throwable $exception) {
            $this->rollBackDatabaseTransaction();

            throw $exception;
        }

        $this->rememberData();

        $this->getSavedNotification()?->send();

        if ($redirectUrl = $this->getRedirectUrl()) {
            $this->redirect($redirectUrl, navigate: FilamentView::hasSpaMode() && is_app_url($redirectUrl));
        }
    }

    public function getSavedNotification(): ?Notification
    {
        $title = $this->getSavedNotificationTitle();

        if (blank($title)) {
            return null;
        }

        return Notification::make()
            ->success()
            ->title($title);
    }

    public function getSavedNotificationTitle(): ?string
    {
        return __('filament-settings::pages/settings-page.notifications.saved.title');
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $data;
    }

    public static function getSettings(): array
    {
        $model = static::getModel();

        return $model::query()
            ->pluck('value', 'key')
            ->toArray();
    }

    /**
     * @return array<Action | ActionGroup>
     */
    public function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
        ];
    }

    public function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label(__('filament-settings::pages/settings-page.form.actions.save.label'))
            ->submit('save')
            ->keyBindings(['mod+s']);
    }

    public function getSubmitFormAction(): Action
    {
        return $this->getSaveFormAction();
    }

    public function form(Form $form): Form
    {
        return $form;
    }

    /**
     * @return array<int | string, string | Form>
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema($this->getFormSchema())
                    ->statePath('data')
                    ->columns(2)
                    ->inlineLabel($this->hasInlineLabels()),
            ),
        ];
    }

    public function getRedirectUrl(): ?string
    {
        return null;
    }
}
