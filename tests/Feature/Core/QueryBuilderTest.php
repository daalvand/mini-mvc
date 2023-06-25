<?php

namespace Tests\Feature\Core;

use PDOException;
use Tests\TestCase;
use Tests\Traits\RefreshDatabase;

class QueryBuilderTest extends TestCase
{

    use RefreshDatabase;

    public function testSelect(): void
    {
        $testUser = $this->createTestUser();
        $this->assertDatabaseHas('users', $testUser);
        $result         = query_builder()->table('users')->select(['first_name', 'email'])->get();
        $expectedResult = [
             'email'      => $testUser['email'],
             'first_name' => $testUser['first_name'],
        ];

        $this->assertEquals($expectedResult, $result[0]);
        $this->assertIsArray($result);

    }

    public function testWhere(): void
    {
        $testUser = $this->createTestUser();
        $this->assertDatabaseHas('users', $testUser);
        $result = query_builder()->table('users')
                                 ->where('first_name', 'like', '%es%')
                                 ->get();

        $this->assertNotEmpty($result);
        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertSame($testUser['email'], $result[0]['email']);


        $result = query_builder()->table('users')
                                 ->where('email', $testUser['email'])
                                 ->get();

        $this->assertNotEmpty($result);
        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertSame($testUser['email'], $result[0]['email']);

        //not found filter
        $result = query_builder()->table('users')
                                 ->where('first_name', 'like', '%invalid%')
                                 ->get();

        $this->assertEmpty($result);
        $this->assertCount(0, $result);

        $result = query_builder()->table('users')
                                 ->where('email', 'not_exists@example.com')
                                 ->get();

        $this->assertEmpty($result);
        $this->assertCount(0, $result);
    }

    public function testInsert(): void
    {
        $data   = [
             'email'      => 'InsertTest@gmail.com',
             'first_name' => 'InsertTest',
             'last_name'  => 'InsertTest',
             'password'   => 'InsertPass',
        ];
        $result = query_builder()->table('users')->insert($data);
        $this->assertDatabaseHas('users', $data);

        $this->assertTrue($result);
    }

    public function testUpdate(): void
    {
        $testUser = $this->createTestUser();
        $this->assertDatabaseHas('users', $testUser);
        $updateData = ['first_name' => 'UpdateTest'];
        $result     = query_builder()->table('users')->where('id', $testUser['id'])->update($updateData);
        $this->assertTrue($result);

        $this->assertDatabaseMissing('users', $testUser);
        $testUser = array_merge($testUser, $updateData);
        $this->assertDatabaseHas('users', $testUser);
    }

    public function testDelete(): void
    {
        $testUser = $this->createTestUser();
        $this->assertDatabaseHas('users', $testUser);
        $result = query_builder()->table('users')->where('id', $testUser['id'])->delete();
        $this->assertTrue($result);
        $this->assertDatabaseMissing('users', $testUser);
    }


    public function testLimit(): void
    {
        $testUsers = $this->createTestUser(10);
        foreach ($testUsers as $testUser) {
            $this->assertDatabaseHas('users', $testUser);
        }
        $result = query_builder()->table('users')->limit(5)->get();

        $this->assertCount(5, $result);

        $result = query_builder()->table('users')->limit(2)->get();
        $this->assertCount(2, $result);
    }

    public function testOffset(): void
    {
        $users       = $this->createTestUser(2);
        $queryResult = query_builder()->table('users')->limit(1)->offset(0)->get();
        $this->assertCount(1, $queryResult);
        $this->assertEquals($users[0]['id'], $queryResult[0]['id']);

        $queryResult = query_builder()->table('users')->limit(1)->offset(1)->get();
        $this->assertCount(1, $queryResult);
        $this->assertEquals($users[1]['id'], $queryResult[0]['id']);

        $queryResult = query_builder()->table('users')->limit(1)->offset(2)->get();
        $this->assertEmpty($queryResult);

        $this->expectException(PDOException::class);

        query_builder()->table('users')->offset(10)->get();
    }

    public function testOrderByASC(): void
    {
        $users        = $this->createTestUser(10);
        $result       = query_builder()->table('users')->orderBy('first_name', 'ASC')->get();
        $firstNames   = array_column($users, 'first_name');
        $DbFirstNames = array_column($result, 'first_name');
        $this->assertNotSame($firstNames, $DbFirstNames);
        natcasesort($firstNames);
        $this->assertSame(array_values($firstNames), $DbFirstNames);
    }

    public function testOrderByDESC(): void
    {
        $users        = $this->createTestUser(10);
        $result       = query_builder()->table('users')->orderBy('first_name', 'DESC')->get();
        $firstNames   = array_column($users, 'first_name');
        $DbFirstNames = array_column($result, 'first_name');
        $this->assertNotSame($firstNames, $DbFirstNames);
        natcasesort($firstNames);
        $firstNames = array_reverse($firstNames);
        $this->assertSame($firstNames, $DbFirstNames);
    }

    public function testCount(): void
    {
        $this->createTestUser(3);
        $count = query_builder()->table('users')->count();
        $this->assertIsInt($count);
        $this->assertSame(3, $count);
    }

    public function testExists(): void
    {
        $this->createTestUser(2);
        $exists = query_builder()->table('users')->where('id', 1)->exists();
        $this->assertTrue($exists);

        $exists = query_builder()->table('users')->where('id', 2)->exists();
        $this->assertTrue($exists);

        $exists = query_builder()->table('users')->where('id', 3)->exists();
        $this->assertFalse($exists);
    }

    public function testSum(): void
    {
        $items       = $this->createTestItems(10);
        $expectedSum = array_sum(array_column($items, 'price'));
        $sum         = query_builder()->table('items')->sum('price');
        $this->assertIsFloat($sum);
        $this->assertEquals($expectedSum, $sum);
    }

    public function testAvg(): void
    {
        $items       = $this->createTestItems(10);
        $expectedAvg = array_sum(array_column($items, 'price')) / count($items);
        $avg         = query_builder()->table('items')->avg('price');
        $this->assertIsFloat($avg);
        $this->assertEquals($expectedAvg, $avg);
    }

    public function testMin(): void
    {
        $items       = $this->createTestItems(10);
        $expectedMin = min(array_column($items, 'price'));
        $min         = query_builder()->table('items')->min('price');
        $this->assertIsFloat($min);
        $this->assertEquals($expectedMin, $min);
    }

    public function testMax(): void
    {
        $items       = $this->createTestItems(10);
        $expectedMax = max(array_column($items, 'price'));
        $max         = query_builder()->table('items')->max('price');
        $this->assertIsFloat($max);
        $this->assertEquals($expectedMax, $max);
    }

    public function testTruncate(): void
    {
        $result = query_builder()->table('users')
                                 ->truncate();

        $this->assertTrue($result);
    }

    public function testRawQuery(): void
    {
        $items  = $this->createTestUser(10);
        $result = query_builder()->raw("SELECT * FROM `users` WHERE id = ?", [1])->get();

        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
        $this->assertIsArray($result);
    }

    protected function createTestUser(int $count = 1): array
    {
        if ($count === 1) {
            $data       = $this->getUserArray();
            $data['id'] = (int)query_builder()->table('users')->insertGetId($data);
            return $data;
        }

        $data = [];
        for ($i = 1; $i <= $count; $i++) {
            $datum       = $this->getUserArray();
            $datum['id'] = query_builder()->table('users')->insertGetId($datum);
            $data[]      = $datum;
        }
        return $data;
    }

    protected function getUserArray(array $attributes = []): array
    {
        $array = [
             'email'      => 'Test_' . generate_random_string() . "@example.com",
             'first_name' => 'Test_' . generate_random_string(),
             'last_name'  => 'Test_' . generate_random_string(),
             'password'   => password_hash('password', PASSWORD_DEFAULT),
        ];
        return array_merge($array, $attributes);
    }


    protected function createTestItems(int $count = 1): array
    {
        if ($count === 1) {
            $data       = $this->getItemArray();
            $data['id'] = (int)query_builder()->table('items')->insertGetId($data);
            return $data;
        }

        $data = [];
        for ($i = 1; $i <= $count; $i++) {
            $datum       = $this->getItemArray();
            $datum['id'] = query_builder()->table('items')->insertGetId($datum);
            $data[]      = $datum;
        }
        return $data;
    }

    protected function getItemArray(array $attributes = []): array
    {
        $array = [
             'title'       => 'Test Title ' . generate_random_string(),
             'image'       => 'Test Url ' . generate_random_string(),
             'description' => 'Test Description ' . generate_random_string(),
             'price'       => random_int(1, 100) * 1000,
        ];
        return array_merge($array, $attributes);
    }

}
