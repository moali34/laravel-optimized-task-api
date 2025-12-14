<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cache
        Cache::flush();
        
        // Disable foreign key checks for truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('tasks')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Sample tasks
        $seniorTasks = [
            [
                'id' => 1, // Explicit ID to avoid auto-increment issues
                'title' => 'Database Query Optimization',
                'description' => 'Implement indexes and optimize slow queries for 10k+ records. Use EXPLAIN to analyze query performance.',
                'status' => 'in_progress',
                'priority' => 'high',
                'due_date' => now()->addDays(3)->format('Y-m-d'),
                'tags' => json_encode(['mysql', 'performance', 'indexing', 'senior']),
                'is_recurring' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'title' => 'Redis Caching Implementation',
                'description' => 'Add Redis cache layer with proper cache invalidation strategy for API responses.',
                'status' => 'pending',
                'priority' => 'urgent',
                'due_date' => now()->addDays(1)->format('Y-m-d'),
                'tags' => json_encode(['redis', 'cache', 'performance', 'scalability']),
                'is_recurring' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 3,
                'title' => 'API Rate Limiting Setup',
                'description' => 'Configure throttling middleware to prevent API abuse. Implement per-IP and per-user limits.',
                'status' => 'completed',
                'priority' => 'medium',
                'due_date' => now()->subDays(2)->format('Y-m-d'),
                'tags' => json_encode(['security', 'api', 'throttling', 'protection']),
                'is_recurring' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 4,
                'title' => 'Bulk Operations Handler',
                'description' => 'Implement chunk-based bulk update/delete operations for processing large datasets efficiently.',
                'status' => 'in_progress',
                'priority' => 'high',
                'due_date' => now()->addDays(5)->format('Y-m-d'),
                'tags' => json_encode(['performance', 'bulk', 'optimization', 'chunking']),
                'is_recurring' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 5,
                'title' => 'Soft Delete Implementation',
                'description' => 'Add soft delete functionality with data recovery feature and cleanup scheduler.',
                'status' => 'completed',
                'priority' => 'low',
                'due_date' => now()->subDays(7)->format('Y-m-d'),
                'tags' => json_encode(['database', 'soft-delete', 'recovery', 'maintenance']),
                'is_recurring' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 6,
                'title' => 'API Versioning Strategy',
                'description' => 'Implement API versioning to maintain backward compatibility for clients.',
                'status' => 'pending',
                'priority' => 'medium',
                'due_date' => now()->addDays(10)->format('Y-m-d'),
                'tags' => json_encode(['api', 'versioning', 'compatibility', 'rest']),
                'is_recurring' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 7,
                'title' => 'Database Transaction Management',
                'description' => 'Implement proper transaction handling for data integrity in multi-step operations.',
                'status' => 'in_progress',
                'priority' => 'high',
                'due_date' => now()->addDays(4)->format('Y-m-d'),
                'tags' => json_encode(['database', 'transactions', 'integrity', 'acid']),
                'is_recurring' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 8,
                'title' => 'Query Performance Monitoring',
                'description' => 'Set up monitoring for slow queries with automated alerts and optimization suggestions.',
                'status' => 'pending',
                'priority' => 'medium',
                'due_date' => now()->addDays(7)->format('Y-m-d'),
                'tags' => json_encode(['monitoring', 'performance', 'alerts', 'optimization']),
                'is_recurring' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 9,
                'title' => 'Background Job Queue Setup',
                'description' => 'Implement Laravel queues with Redis for processing time-consuming tasks in background.',
                'status' => 'completed',
                'priority' => 'medium',
                'due_date' => now()->subDays(3)->format('Y-m-d'),
                'tags' => json_encode(['queues', 'redis', 'background-jobs', 'performance']),
                'is_recurring' => false,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 10,
                'title' => 'API Response Compression',
                'description' => 'Implement GZIP compression for API responses to reduce bandwidth usage.',
                'status' => 'in_progress',
                'priority' => 'low',
                'due_date' => now()->addDays(14)->format('Y-m-d'),
                'tags' => json_encode(['performance', 'compression', 'bandwidth', 'optimization']),
                'is_recurring' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
        
        // Insert tasks
        DB::table('tasks')->insert($seniorTasks);
        
        $this->command->info('✅ Created 10 senior-level tasks!');
        $this->command->info('🎯 Tasks include: Database optimization, Caching, Rate limiting, Bulk operations');
    }
}