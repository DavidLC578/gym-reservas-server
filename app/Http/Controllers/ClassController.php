<?php

namespace App\Http\Controllers;

use App\Models\GymClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * ClassController handles CRUD operations for GymClass model.
 * It also handles user enrollment and cancellation for classes.
 */
class ClassController extends Controller
{
    /**
     * Get all classes
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function list()
    {
        // Retrieve all classes from the database
        $classes = GymClass::all();

        // Return the list of classes as JSON response
        return response()->json($classes);
    }

    /**
     * Get a specific class by ID
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function listOne($id)
    {
        // Retrieve a class by ID from the database
        $class = GymClass::findOrFail($id);

        // Return the class as JSON response
        return response()->json($class);
    }

    /**
     * Create a new class
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Check if user is not authenticated or has admin/god role
        if (Auth::check() && Auth::user()->hasAnyRole(['god', 'admin'])) {
            // Return unauthorized response if user has admin/god role
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Create a new instance of GymClass model
        $class = new GymClass();

        // Set class properties from the request data
        $class->name = $request->name;
        $class->description = $request->description;
        $class->location = $request->location;
        $class->price = (float) $request->price;
        $class->duration = (int) $request->duration;
        $class->start_time = $request->start_time;
        $class->end_time = $request->end_time;
        $class->max_participants = (int) $request->max_participants;

        // Save the class to the database
        $class->save();

        // Return a success response with the created class
        return response()->json([
            'message' => 'Class created successfully',
            'class' => $class
        ], 200);
    }

    /**
     * Update an existing class
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Check if user is not authenticated or has admin/god role
        if (Auth::check() && Auth::user()->hasAnyRole(['god', 'admin'])) {
            // Return unauthorized response if user has admin/god role
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Retrieve a class by ID from the database
        $class = GymClass::findOrFail($id);

        // Update class properties from the request data
        $class->name = $request->name;
        $class->description = $request->description;
        $class->location = $request->location;
        $class->price = (float) $request->price;
        $class->duration = (int) $request->duration;
        $class->start_time = $request->start_time;
        $class->end_time = $request->end_time;
        $class->max_participants = (int) $request->max_participants;

        // Save the updated class to the database
        $class->save();

        // Return a success response with the updated class
        return response()->json([
            'message' => 'Class updated successfully',
            'class' => $class
        ], 200);
    }

    /**
     * Delete a class
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        // Check if user is not authenticated or has admin/god role
        if (Auth::check() && Auth::user()->hasAnyRole(['god', 'admin'])) {
            // Return unauthorized response if user has admin/god role
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Retrieve a class by ID from the database
        $class = GymClass::findOrFail($id);

        // Delete the class from the database
        $class->delete();

        // Return a success response
        return response()->json([
            'message' => 'Class deleted successfully'
        ], 200);
    }

    /**
     * Enroll a user in a class
     *
     * @param Request $request
     * @param int $classId
     * @return \Illuminate\Http\JsonResponse
     */
    public function enroll(Request $request, $classId)
    {
        // Check if user is not authenticated or has admin/god role
        if (!Auth::check()) {
            // Return unauthorized response if user has admin/god role
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Retrieve a class by ID from the database
        $class = GymClass::findOrFail($classId);

        // Get the authenticated user
        $user = Auth::guard('api')->user();

        // Check if user is already enrolled in the class
        if ($class->users()->where('user_id', $user->id)->exists()) {
            // Return an error response if user is already enrolled
            return response()->json([
                'message' => 'User is already enrolled in this class'
            ], 400);
        }

        // Check if the class is full
        $currentParticipants = $class->users()->count();
        if ($currentParticipants >= $class->max_participants) {
            // Return an error response if the class is full
            return response()->json([
                'message' => 'Class is full'
            ], 400);
        }

        // Check if the class has already started
        if (now() > $class->start_time) {
            // Return an error response if the class has already started
            return response()->json([
                'message' => 'Class has already started'
            ], 400);
        }

        // Enroll the user in the class
        $class->users()->attach($user->id);

        // Return a success response with the class
        return response()->json([
            'message' => 'Successfully enrolled in class',
            'class' => $class
        ], 200);
    }

    /**
     * Get list of participants for a class
     *
     * @param int $classId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getClassParticipants($classId)
    {
        // Check if user is not authenticated or has admin/god role
        if (Auth::check() && Auth::user()->hasAnyRole(['god', 'admin'])) {
            // Return unauthorized response if user has admin/god role
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Retrieve a class by ID from the database with its participants
        $class = GymClass::with('users')->findOrFail($classId);

        // Return a success response with the class and its participants
        return response()->json([
            'class' => $class,
            'participants' => $class->users
        ], 200);
    }

    /**
     * Cancel user's enrollment from a class
     *
     * @param int $classId
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelEnrollment($classId)
    {
        // Check if user is not authenticated or has admin/god role
        if (Auth::check() && Auth::user()->hasAnyRole(['god', 'admin'])) {
            // Return unauthorized response if user has admin/god role
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Retrieve a class by ID from the database
        $class = GymClass::findOrFail($classId);

        // Get the authenticated user
        $user = Auth::user();

        // Check if user is enrolled in the class
        if (!$class->users()->where('user_id', $user->id)->exists()) {
            // Return an error response if user is not enrolled
            return response()->json([
                'message' => 'User is not enrolled in this class'
            ], 400);
        }

        // Cancel the user's enrollment from the class
        $class->users()->detach($user->id);

        // Return a success response with the class
        return response()->json([
            'message' => 'Successfully canceled enrollment',
            'class' => $class
        ], 200);
    }
}
