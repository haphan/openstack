<?php

namespace OpenStack\Test\Identity\v3;

use GuzzleHttp\Message\Response;
use OpenStack\Identity\v3\Api;
use OpenStack\Identity\v3\Models\Domain;
use OpenStack\Test\TestCase;

class DomainTest extends TestCase
{
    private $domain;

    public function setUp()
    {
        $this->rootFixturesDir = dirname(__DIR__);

        parent::setUp();

        $this->domain = new Domain($this->client->reveal(), new Api());
        $this->domain->id = 'DOMAIN_ID';
    }

    public function test_it_lists_user_roles()
    {
        $fn = $this->createFn($this->domain, 'listUserRoles', ['userId' => 'USER_ID']);
        $this->listTest($fn, 'domains/DOMAIN_ID/users/USER_ID/roles', 'Role', 'roles-user');
    }

    public function test_it_grants_user_role()
    {
        $request = $this->setupMockRequest('PUT', 'domains/DOMAIN_ID/users/USER_ID/roles/ROLE_ID');
        $this->setupMockResponse($request, new Response(204));
        $this->assertNull($this->domain->grantUserRole(['userId' => 'USER_ID', 'roleId' => 'ROLE_ID']));
    }

    public function test_it_checks_user_role()
    {
        $request = $this->setupMockRequest('HEAD', 'domains/DOMAIN_ID/users/USER_ID/roles/ROLE_ID');
        $this->setupMockResponse($request, new Response(200));
        $this->assertTrue($this->domain->checkUserRole(['userId' => 'USER_ID', 'roleId' => 'ROLE_ID']));
    }

    public function test_it_checks_nonexistent_user_role()
    {
        $request = $this->setupMockRequest('HEAD', 'domains/DOMAIN_ID/users/USER_ID/roles/ROLE_ID');
        $this->setupMockResponse($request, new Response(404));
        $this->assertFalse($this->domain->checkUserRole(['userId' => 'USER_ID', 'roleId' => 'ROLE_ID']));
    }

    public function test_it_revokes_user_role()
    {
        $request = $this->setupMockRequest('DELETE', 'domains/DOMAIN_ID/users/USER_ID/roles/ROLE_ID');
        $this->setupMockResponse($request, new Response(204));
        $this->domain->revokeUserRole(['userId' => 'USER_ID', 'roleId' => 'ROLE_ID']);
    }

    public function test_it_lists_group_roles()
    {
        $fn = $this->createFn($this->domain, 'listGroupRoles', ['groupId' => 'GROUP_ID']);
        $this->listTest($fn, 'domains/DOMAIN_ID/groups/GROUP_ID/roles', 'Role', 'domain-group-roles');
    }

    public function test_it_grants_group_role()
    {
        $request = $this->setupMockRequest('PUT', 'domains/DOMAIN_ID/groups/GROUP_ID/roles/ROLE_ID');
        $this->setupMockResponse($request, new Response(204));
        $this->assertNull($this->domain->grantGroupRole(['groupId' => 'GROUP_ID', 'roleId' => 'ROLE_ID']));
    }

    public function test_it_checks_group_role()
    {
        $request = $this->setupMockRequest('HEAD', 'domains/DOMAIN_ID/groups/GROUP_ID/roles/ROLE_ID');
        $this->setupMockResponse($request, new Response(404));
        $this->assertFalse($this->domain->checkGroupRole(['groupId' => 'GROUP_ID', 'roleId' => 'ROLE_ID']));
    }

    public function test_it_revokes_group_role()
    {
        $request = $this->setupMockRequest('DELETE', 'domains/DOMAIN_ID/groups/GROUP_ID/roles/ROLE_ID');
        $this->setupMockResponse($request, new Response(204));
        $this->domain->revokeGroupRole(['groupId' => 'GROUP_ID', 'roleId' => 'ROLE_ID']);
    }
}