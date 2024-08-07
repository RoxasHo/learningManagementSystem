<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Counter;

class ClickController extends Controller
{
    public function index()
    {
        $counters = DB::table('test')->orderBy('counter', 'desc')->get();

        $topCounters = DB::table('crontest')
            ->orderBy('counter', 'desc')
            ->limit(3)
            ->get();
        
        return view('clicks', compact('counters', 'topCounters'));
    }

    public function update(Request $request)
    {
        $name = $request->input('name');

        // Update the counter for the given name
        DB::table('test')->where('name', $name)->increment('counter');

        return response()->json(['success' => true]);
    }

    public function store(Request $request)
    {
        $name = $request->input('name');

        // Check if the container already exists
        $exists = DB::table('test')->where('name', $name)->exists();

        if (!$exists) {
            // Insert a new container with an initial counter value of 0
            DB::table('test')->insert([
                'name' => $name,
                'counter' => 0
            ]);
        }

        return response()->json(['redirect' => url('/click')]);
    }

    public function reset()
    {
        // Reset all counters to 0
        DB::table('test')->update(['counter' => 0]);

        return response()->json(['success' => true]);
    }
    public function addContainer(Request $request)
    {
        $name = $request->input('name');

        // Validate input
        if (!$name) {
            return response()->json(['success' => false, 'message' => 'Container name is required']);
        }

        // Check if the container already exists
        if (Counter::where('name', $name)->exists()) {
            return response()->json(['success' => false, 'message' => 'Container already exists']);
        }

        // Create the new container
        Counter::create(['name' => $name, 'counter' => 0]);

        return response()->json(['success' => true]);
    }

    public function processQuestionnaire(Request $request)
    {
        $favoriteContainer = $request->input('favoriteContainer');
        $question2 = $request->input('question2');

        // Add 5 points to the counter of the selected container
        if ($favoriteContainer) {
            DB::table('test')->where('name', $favoriteContainer)->increment('counter', 5);
        }

        return response()->json(['success' => true]);
    }

    public function incrementCounter(Request $request)
    {
        $name = $request->input('name');

        // Log input for debugging
        \Log::info('Increment request received', ['name' => $name]);

        // Validate input
        if (!$name) {
            return response()->json(['success' => false, 'message' => 'Invalid container name']);
        }

        try {
            $counter = Counter::where('name', $name)->first();

            if ($counter) {
                $counter->increment('counter');
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Container not found']);
            }
        } catch (\Exception $e) {
            // Log the exception message
            \Log::error('Error incrementing counter: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred']);
        }
    }


}
