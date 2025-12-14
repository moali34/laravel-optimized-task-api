<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
        $this->middleware('throttle:60,1');
    }

    
    public function index(Request $request)
    {
        $cacheKey = 'tasks_' . md5(serialize($request->all()));
        
        $tasks = Cache::remember($cacheKey, 300, function () use ($request) {
            $query = Task::query();
            
            // Filters
            if ($request->status) {
                $query->where('status', $request->status);
            }
            
            if ($request->priority) {
                $query->where('priority', $request->priority);
            }
            
            // Search
           if ($request->search) {
    $search = $request->search;
    $query->where(function($q) use ($search) {
        $q->where('title', 'LIKE', "%{$search}%")
          ->orWhere('description', 'LIKE', "%{$search}%")
          ->orWhereJsonContains('tags', $search); // Search in JSON tags too!
    });
}
            
            // Sorting
            $query->orderBy($request->sort_by ?? 'created_at', $request->sort_order ?? 'desc');
            
            // Pagination with limit
            return $query->paginate(min($request->per_page ?? 20, 100));
        });
        
        return response()->json([
            'data' => $tasks,
            'meta' => [
                'cached' => Cache::has($cacheKey),
                'total' => $tasks->total()
            ]
        ]);
    }

    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'in:low,medium,high,urgent',
            'due_date' => 'nullable|date|after:today',
            'tags' => 'nullable|array'
        ]);
        
        $task = Task::create($validated);
        
        // Clear cache
        Cache::forget('tasks_' . md5(serialize([])));
        
        return response()->json([
            'message' => 'Task created',
            'data' => $task
        ], 201);
    }

    
    public function show($id)
    {
        $cacheKey = 'task_' . $id;
        
        $task = Cache::remember($cacheKey, 600, function () use ($id) {
            return Task::findOrFail($id);
        });
        
        return response()->json([
            'data' => $task,
            'cached' => Cache::has($cacheKey)
        ]);
    }

    
    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        
        DB::transaction(function () use ($request, $task) {
            $task->update($request->all());
            
            // Log update
            DB::table('task_logs')->insert([
                'task_id' => $task->id,
                'action' => 'updated',
                'details' => json_encode($request->all()),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        });
        
        // Clear both caches
        Cache::forget('task_' . $id);
        Cache::forget('tasks_' . md5(serialize([])));
        
        return response()->json(['message' => 'Task updated', 'data' => $task]);
    }

    
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete(); // Soft delete
        
        Cache::forget('task_' . $id);
        Cache::forget('tasks_' . md5(serialize([])));
        
        return response()->json(['message' => 'Task deleted']);
    }

    
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'status' => 'required|in:pending,in_progress,completed'
        ]);
        
        // Process in chunks for performance
        collect($request->ids)->chunk(100)->each(function ($chunk) use ($request) {
            Task::whereIn('id', $chunk)->update(['status' => $request->status]);
        });
        
        Cache::flush(); // Clear all cache
        
        return response()->json(['message' => 'Bulk update completed']);
    }

    
    public function performance()
    {
        $start = microtime(true);
        
        // Heavy query
        $stats = DB::table('tasks')
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();
        
        $time = microtime(true) - $start;
        
        return response()->json([
            'performance' => [
                'execution_time' => round($time, 4) . 's',
                'memory_used' => round(memory_get_peak_usage(true) / 1024 / 1024, 2) . 'MB',
                'queries_executed' => count(DB::getQueryLog() ?? [])
            ],
            'stats' => $stats
        ]);
    }
}