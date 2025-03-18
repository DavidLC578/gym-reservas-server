<?php

namespace App\Http\Controllers;

use App\Models\GymClass;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    /**
     * List all classes
     */
    public function list()
    {
        $classes = GymClass::all();
        return response()->json($classes);
    }

    /**
     * List one class
     * @param int $id
     */
    public function listOne($id)
    {
        $class = GymClass::findOrFail($id);
        return response()->json($class);
    }

    /**
     * Store a new class
     * @param Request $request
     */
    public function store(Request $request)
    {
        $class = new GymClass();
        $class->name = $request->name;
        $class->description = $request->description;
        $class->location = $request->location;
        $class->price = (float) $request->price;
        $class->duration = (int) $request->duration;
        $class->start_time = $request->start_time;
        $class->end_time = $request->end_time;
        $class->max_participants = (int) $request->max_participants;

        $class->save();

        return response()->json([
            'message' => 'Class created successfully',
            'class' => $class
        ]);
    }

    /**
     * Update a class
     * @param Request $request
     * @param int $id
     */
    public function update(Request $request, $id)
    {
        $class = GymClass::findOrFail($id);
        $class->name = $request->name;
        $class->description = $request->description;
        $class->location = $request->location;
        $class->price = (float) $request->price;
        $class->duration = (int) $request->duration;
        $class->start_time = $request->start_time;
        $class->end_time = $request->end_time;
        $class->max_participants = (int) $request->max_participants;

        $class->save();

        return response()->json([
            'message' => 'Class updated successfully',
            'class' => $class
        ]);
    }

    /**
     * Delete a class
     * @param int $id
     */
    public function delete($id)
    {
        $class = GymClass::findOrFail($id);
        $class->delete();

        return response()->json([
            'message' => 'Class deleted successfully'
        ]);
    }
}
