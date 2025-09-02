<?php

use Illuminate\Broadcasting\BroadcastEvent;
use Illuminate\Events\CallQueuedListener;
use Illuminate\Mail\SendQueuedMailable;
use Illuminate\Notifications\SendQueuedNotifications;
use Illuminate\Queue\CallQueuedClosure;
use Spatie\Multitenancy\Actions\ForgetCurrentTenantAction;
use Spatie\Multitenancy\Actions\MakeQueueTenantAwareAction;
use Spatie\Multitenancy\Actions\MakeTenantCurrentAction;
use Spatie\Multitenancy\Actions\MigrateTenantAction;
use Illuminate\Support\Facades\Log;
// use Exception;

return [
    'tenant_finder' => \Spatie\Multitenancy\TenantFinder\DomainTenantFinder::class,
    'tenant_model' => \App\Models\Tenant::class,
    'tenant_model_config' => [
        'table' => 'tenants',
        'identifier_column' => 'domain',
    ],
    // 'domain' => env('APP_URL', 'http://localhost'),

    'switch_tenant_tasks' => [
        // For single DB you usually keep this empty, or optionally:
        // \Spatie\Multitenancy\Tasks\PrefixCacheTask::class, // only if you want tenant-specific cache
        // \Spatie\Multitenancy\Tasks\ApplyTenantScopesTask::class,
        // function () {
        //     Log::info('Tenant current: ' . optional(\Spatie\Multitenancy\Models\Tenant::current())->id);
        // },
    ],
    'tenant_database_connection_name' => env('DB_CONNECTION', 'mysql'),
    'landlord_database_connection_name' => env('DB_CONNECTION', 'mysql'),

    'queues_are_tenant_aware_by_default' => true,
    'current_tenant_container_key' => 'currentTenant',

    'shared_routes_cache' => false,

    'actions' => [
        'make_tenant_current_action' => MakeTenantCurrentAction::class,
        'forget_current_tenant_action' => ForgetCurrentTenantAction::class,
        'make_queue_tenant_aware_action' => MakeQueueTenantAwareAction::class,
        'migrate_tenant' => MigrateTenantAction::class,
    ],

    'queueable_to_job' => [
        SendQueuedMailable::class => 'mailable',
        SendQueuedNotifications::class => 'notification',
        CallQueuedClosure::class => 'closure',
        CallQueuedListener::class => 'class',
        BroadcastEvent::class => 'event',
    ],

    'tenant_aware_jobs' => [],
    'not_tenant_aware_jobs' => [],
];