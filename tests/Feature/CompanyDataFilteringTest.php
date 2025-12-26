<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Project;

class CompanyDataFilteringTest extends TestCase
{
    public function test_belongs_to_tenant_trait_is_applied_to_models()
    {
        $traits = class_uses_recursive(Project::class);
        $this->assertArrayHasKey('App\Traits\BelongsToTenant', $traits);
    }

    public function test_tenant_data_middleware_exists()
    {
        $this->assertTrue(class_exists('App\Http\Middleware\TenantDataMiddleware'));
    }

    public function test_base_controller_has_ensure_tenant_id_method()
    {
        $this->assertTrue(method_exists('App\Http\Controllers\Controller', 'ensureTenantId'));
    }

    public function test_key_models_have_belongs_to_tenant_trait()
    {
        $models = [
            'App\Models\Project',
            'App\Models\Expense',
            'App\Models\Income',
            'App\Models\Payment',
            'App\Models\Employee',
            'App\Models\Client',
            'App\Models\Worker',
        ];

        foreach ($models as $modelClass) {
            if (class_exists($modelClass)) {
                $traits = class_uses_recursive($modelClass);
                $this->assertArrayHasKey('App\Traits\BelongsToTenant', $traits,
                    "$modelClass should have BelongsToTenant trait");
            }
        }
    }

    public function test_global_scope_is_registered_on_models()
    {
        $project = new Project();
        $scopes = $project->getGlobalScopes();
        $this->assertNotEmpty($scopes, 'Models should have global scopes');
    }
}
