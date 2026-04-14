<?php

namespace app\db;

use think\Db;

class Seeder
{
    public static function run()
    {
        self::seedRoles();
        self::seedUsers();
        self::seedViolationRules();
        self::seedActivities();
        self::seedOrders();
        echo "Seed data completed!\n";
    }

    protected static function seedRoles()
    {
        $roles = [
            [
                'name' => 'administrator',
                'description' => 'Full system access',
                'permissions' => json_encode([
                    'users.*', 'activities.*', 'orders.*', 'shipments.*', 
                    'violations.*', 'tasks.*', 'staffing.*', 'dashboard.*', 
                    'audit.read', 'index.rebuild'
                ]),
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'operations_staff',
                'description' => 'Manage activities and orders',
                'permissions' => json_encode([
                    'activities.read', 'activities.create', 'activities.update',
                    'orders.*', 'shipments.*', 'search.read'
                ]),
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'team_lead',
                'description' => 'Lead activities and manage team',
                'permissions' => json_encode([
                    'activities.read', 'tasks.*', 'staffing.*', 'checklists.*'
                ]),
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'reviewer',
                'description' => 'Review violations and appeals',
                'permissions' => json_encode([
                    'violations.review', 'violations.read', 'audit.read'
                ]),
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'regular_user',
                'description' => 'Basic user access',
                'permissions' => json_encode([
                    'activities.read', 'activities.signup', 
                    'orders.read', 'notifications.read'
                ]),
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        foreach ($roles as $role) {
            Db::name('roles')->insert($role);
        }
    }

    protected static function seedUsers()
    {
        $users = [
            ['username' => 'admin', 'role' => 'administrator', 'password' => 'Admin@12345678'],
            ['username' => 'ops1', 'role' => 'operations_staff', 'password' => 'Ops@12345678'],
            ['username' => 'ops2', 'role' => 'operations_staff', 'password' => 'Ops@12345678'],
            ['username' => 'lead1', 'role' => 'team_lead', 'password' => 'Lead@12345678'],
            ['username' => 'reviewer1', 'role' => 'reviewer', 'password' => 'Review@12345678'],
            ['username' => 'user1', 'role' => 'regular_user', 'password' => 'User@12345678'],
            ['username' => 'user2', 'role' => 'regular_user', 'password' => 'User@12345678'],
            ['username' => 'user3', 'role' => 'regular_user', 'password' => 'User@12345678'],
            ['username' => 'user4', 'role' => 'regular_user', 'password' => 'User@12345678'],
            ['username' => 'user5', 'role' => 'regular_user', 'password' => 'User@12345678'],
        ];

        foreach ($users as $data) {
            $salt = bin2hex(random_bytes(16));
            $passwordHash = password_hash($data['password'] . $salt, PASSWORD_BCRYPT);
            
            Db::name('users')->insert([
                'username' => $data['username'],
                'password_hash' => $passwordHash,
                'salt' => $salt,
                'role' => $data['role'],
                'status' => 'active',
                'failed_attempts' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    protected static function seedViolationRules()
    {
        $rules = [
            ['name' => 'Late Arrival', 'description' => 'Arriving late to activity', 'points' => 2, 'category' => 'attendance'],
            ['name' => 'Early Departure', 'description' => 'Leaving early without approval', 'points' => 2, 'category' => 'attendance'],
            ['name' => 'No Show', 'description' => 'Not showing up for signed-up activity', 'points' => 5, 'category' => 'attendance'],
            ['name' => 'Missing Supplies', 'description' => 'Failed to bring required supplies', 'points' => 3, 'category' => 'equipment'],
            ['name' => 'Positive Contribution', 'description' => 'Extra help or contribution', 'points' => -5, 'category' => 'bonus'],
        ];

        foreach ($rules as $rule) {
            Db::name('violation_rules')->insert(array_merge($rule, [
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]));
        }
    }

    protected static function seedActivities()
    {
        $adminId = Db::name('users')->where('username', 'admin')->value('id');
        
        $activities = [
            [
                'title' => 'Campus Cleanup Day',
                'body' => 'Join us for a campus-wide cleanup event!',
                'tags' => json_encode(['volunteer', 'outdoor']),
                'state' => 'published',
                'max_headcount' => 50,
                'signup_start' => date('Y-m-d H:i:s', strtotime('-1 week')),
                'signup_end' => date('Y-m-d H:i:s', strtotime('+1 week')),
            ],
            [
                'title' => 'Welcome Orientation',
                'body' => 'New member orientation session',
                'tags' => json_encode(['orientation', 'mandatory']),
                'state' => 'draft',
                'max_headcount' => 100,
                'signup_start' => null,
                'signup_end' => null,
            ],
            [
                'title' => 'Summer Festival 2024',
                'body' => 'Annual summer festival planning meeting',
                'tags' => json_encode(['festival', 'planning']),
                'state' => 'completed',
                'max_headcount' => 20,
                'signup_start' => date('Y-m-d H:i:s', strtotime('-2 weeks')),
                'signup_end' => date('Y-m-d H:i:s', strtotime('-1 week')),
            ],
        ];

        foreach ($activities as $activity) {
            $groupId = Db::name('activity_groups')->insertGetId([
                'created_by' => $adminId,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            $versionData = array_merge($activity, [
                'group_id' => $groupId,
                'version_number' => 1,
                'eligibility_tags' => '[]',
                'required_supplies' => '[]',
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            if ($activity['state'] === 'published') {
                $versionData['published_at'] = date('Y-m-d H:i:s');
            }

            Db::name('activity_versions')->insert($versionData);
        }
    }

    protected static function seedOrders()
    {
        $adminId = Db::name('users')->where('username', 'admin')->value('id');
        
        $orders = [
            ['state' => 'placed', 'amount' => 150.00, 'items' => json_encode(['T-Shirt x 10', 'Badge x 10'])],
            ['state' => 'paid', 'amount' => 500.00, 'items' => json_encode(['Banner x 2', 'Flyers x 500'])],
            ['state' => 'ticketed', 'amount' => 200.00, 'items' => json_encode(['Sound System']), 'ticket_number' => 'TKT-001'],
            ['state' => 'canceled', 'amount' => 75.00, 'items' => json_encode(['Refreshments'])],
        ];

        foreach ($orders as $order) {
            Db::name('orders')->insert([
                'created_by' => $adminId,
                'state' => $order['state'],
                'amount' => $order['amount'],
                'items' => $order['items'],
                'notes' => '',
                'payment_method' => $order['state'] === 'paid' ? 'cash' : '',
                'ticket_number' => $order['ticket_number'] ?? '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}