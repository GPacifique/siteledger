<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Project;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Payment;
use App\Models\Employee;
use App\Models\Client;
use App\Models\Worker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyDataFilteringTest extends TestCase
{
    use RefreshDatabase;

    protected Tenant $tenant1;
    protected Tenant $tenant2;
    protected User $user1;
    protected User $user2;

    protected function setUp(): void
    {
        parent::setUp();

        // Create two tenants
        $this->tenant1 = Tenant::create(['name' => 'Company A', 'domain' => 'company-a.local']);
        $this->tenant2 = Tenant::create(['name' => 'Company B', 'domain' => 'company-b.local']);

        // Create users for each tenant
        $this->user1 = User::create([
            'name' => 'User A',
            'email' => 'user@company-a.com',
            'password' => bcrypt('password'),
            'current_tenant_id' => $this->tenant1->id,
        ]);

        $this->user2 = User::create([
            'name' => 'User B',
            'email' => 'user@company-b.com',
            'password' => bcrypt('password'),
            'current_tenant_id' => $this->tenant2->id,
        ]);

        // Assign users to tenants
        $this->user1->tenants()->attach($this->tenant1);
        $this->user2->tenants()->attach($this->tenant2);
    }

    public function test_projects_are_filtered_by_company()
    {
        // Create projects for different tenants
        $project1 = Project::create([
            'tenant_id' => $this->tenant1->id,
            'client_id' => 1,
            'name' => 'Project A',
            'start_date' => now(),
        ]);

        $project2 = Project::create([
            'tenant_id' => $this->tenant2->id,
            'client_id' => 1,
            'name' => 'Project B',
            'start_date' => now(),
        ]);

        // User 1 should only see their company's project
        $this->actingAs($this->user1);
        app()->instance('currentTenant', $this->tenant1);

        $userProjects = Project::all();
        $this->assertCount(1, $userProjects);
        $this->assertEquals($project1->id, $userProjects->first()->id);

        // User 2 should only see their company's project
        $this->actingAs($this->user2);
        app()->instance('currentTenant', $this->tenant2);

        $userProjects = Project::all();
        $this->assertCount(1, $userProjects);
        $this->assertEquals($project2->id, $userProjects->first()->id);
    }

    public function test_expenses_are_filtered_by_company()
    {
        // Create expenses for different tenants
        $expense1 = Expense::create([
            'tenant_id' => $this->tenant1->id,
            'amount' => 100,
            'date' => now(),
        ]);

        $expense2 = Expense::create([
            'tenant_id' => $this->tenant2->id,
            'amount' => 200,
            'date' => now(),
        ]);

        // User 1 should only see their company's expenses
        $this->actingAs($this->user1);
        app()->instance('currentTenant', $this->tenant1);

        $userExpenses = Expense::all();
        $this->assertCount(1, $userExpenses);
        $this->assertEquals($expense1->id, $userExpenses->first()->id);

        // User 2 should only see their company's expenses
        $this->actingAs($this->user2);
        app()->instance('currentTenant', $this->tenant2);

        $userExpenses = Expense::all();
        $this->assertCount(1, $userExpenses);
        $this->assertEquals($expense2->id, $userExpenses->first()->id);
    }

    public function test_income_is_filtered_by_company()
    {
        // Create incomes for different tenants
        $income1 = Income::create([
            'tenant_id' => $this->tenant1->id,
            'project_id' => 1,
            'amount_received' => 500,
            'received_at' => now(),
        ]);

        $income2 = Income::create([
            'tenant_id' => $this->tenant2->id,
            'project_id' => 1,
            'amount_received' => 600,
            'received_at' => now(),
        ]);

        // User 1 should only see their company's income
        $this->actingAs($this->user1);
        app()->instance('currentTenant', $this->tenant1);

        $userIncomes = Income::all();
        $this->assertCount(1, $userIncomes);
        $this->assertEquals($income1->id, $userIncomes->first()->id);

        // User 2 should only see their company's income
        $this->actingAs($this->user2);
        app()->instance('currentTenant', $this->tenant2);

        $userIncomes = Income::all();
        $this->assertCount(1, $userIncomes);
        $this->assertEquals($income2->id, $userIncomes->first()->id);
    }

    public function test_payments_are_filtered_by_company()
    {
        // Create payments for different tenants
        $payment1 = Payment::create([
            'tenant_id' => $this->tenant1->id,
            'amount' => 150,
            'method' => 'bank_transfer',
        ]);

        $payment2 = Payment::create([
            'tenant_id' => $this->tenant2->id,
            'amount' => 250,
            'method' => 'cash',
        ]);

        // User 1 should only see their company's payments
        $this->actingAs($this->user1);
        app()->instance('currentTenant', $this->tenant1);

        $userPayments = Payment::all();
        $this->assertCount(1, $userPayments);
        $this->assertEquals($payment1->id, $userPayments->first()->id);

        // User 2 should only see their company's payments
        $this->actingAs($this->user2);
        app()->instance('currentTenant', $this->tenant2);

        $userPayments = Payment::all();
        $this->assertCount(1, $userPayments);
        $this->assertEquals($payment2->id, $userPayments->first()->id);
    }

    public function test_employees_are_filtered_by_company()
    {
        // Create employees for different tenants
        $employee1 = Employee::create([
            'tenant_id' => $this->tenant1->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@company-a.com',
        ]);

        $employee2 = Employee::create([
            'tenant_id' => $this->tenant2->id,
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane@company-b.com',
        ]);

        // User 1 should only see their company's employees
        $this->actingAs($this->user1);
        app()->instance('currentTenant', $this->tenant1);

        $userEmployees = Employee::all();
        $this->assertCount(1, $userEmployees);
        $this->assertEquals($employee1->id, $userEmployees->first()->id);

        // User 2 should only see their company's employees
        $this->actingAs($this->user2);
        app()->instance('currentTenant', $this->tenant2);

        $userEmployees = Employee::all();
        $this->assertCount(1, $userEmployees);
        $this->assertEquals($employee2->id, $userEmployees->first()->id);
    }

    public function test_workers_are_filtered_by_company()
    {
        // Create workers for different tenants
        $worker1 = Worker::create([
            'tenant_id' => $this->tenant1->id,
            'first_name' => 'Bob',
            'last_name' => 'Builder',
            'email' => 'bob@company-a.com',
        ]);

        $worker2 = Worker::create([
            'tenant_id' => $this->tenant2->id,
            'first_name' => 'Alice',
            'last_name' => 'Worker',
            'email' => 'alice@company-b.com',
        ]);

        // User 1 should only see their company's workers
        $this->actingAs($this->user1);
        app()->instance('currentTenant', $this->tenant1);

        $userWorkers = Worker::all();
        $this->assertCount(1, $userWorkers);
        $this->assertEquals($worker1->id, $userWorkers->first()->id);

        // User 2 should only see their company's workers
        $this->actingAs($this->user2);
        app()->instance('currentTenant', $this->tenant2);

        $userWorkers = Worker::all();
        $this->assertCount(1, $userWorkers);
        $this->assertEquals($worker2->id, $userWorkers->first()->id);
    }

    public function test_clients_are_filtered_by_company()
    {
        // Create clients for different tenants
        $client1 = Client::create([
            'tenant_id' => $this->tenant1->id,
            'name' => 'Client A',
            'contact_person' => 'Contact 1',
            'email' => 'contact@client-a.com',
            'phone' => '123456789',
        ]);

        $client2 = Client::create([
            'tenant_id' => $this->tenant2->id,
            'name' => 'Client B',
            'contact_person' => 'Contact 2',
            'email' => 'contact@client-b.com',
            'phone' => '987654321',
        ]);

        // User 1 should only see their company's clients
        $this->actingAs($this->user1);
        app()->instance('currentTenant', $this->tenant1);

        $userClients = Client::all();
        $this->assertCount(1, $userClients);
        $this->assertEquals($client1->id, $userClients->first()->id);

        // User 2 should only see their company's clients
        $this->actingAs($this->user2);
        app()->instance('currentTenant', $this->tenant2);

        $userClients = Client::all();
        $this->assertCount(1, $userClients);
        $this->assertEquals($client2->id, $userClients->first()->id);
    }
}
