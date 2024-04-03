<?php

namespace App\Events;

use App\DTO\Shared\AuditDTO;
use App\Enums\AuditEventTypesEnum;
use App\Models\Audit;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AuditCreateEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Model $model;
    public User $user;
    public AuditEventTypesEnum $eventType;
    public string $ipAddress;
    public ?string $eventContext;
    public ?array $data;

    /**
     * Create a new event instance.
     */
    public function __construct(Model $model, User $user, AuditEventTypesEnum $eventType,
                                string $ipAddress, ?string $eventContext = null, ?array $data = null)
    {
        $this->model = $model;
        $this->user = $user;
        $this->eventType = $eventType;
        $this->ipAddress = $ipAddress;
        $this->eventContext = $eventContext;
        $this->data = $data;

        $this->handle();
    }

    /**
     * @return void
     */
    private function handle(): void
    {
        $auditDTO = new AuditDTO([
            'model_type' => get_class($this->model),
            'model_id' => $this->model->id,
            'user_id' => $this->user->id,
            'event_type' => $this->eventType->name,
            'ip_address' => $this->ipAddress,
            'event_context' => $this->eventContext,
            'data' => $this->data,
        ]);

        Audit::query()->create($auditDTO->toArray());
    }
}
