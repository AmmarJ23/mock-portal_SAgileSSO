<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LecturerStudentAssignment;
use Illuminate\Support\Facades\DB;

class LecturerStudentAssignmentController extends Controller
{
    public function index(Request $request)
    {
        $staffId = $request->input('staffId');
        $matricNumber = $request->input('matricNumber');
        
        $query = LecturerStudentAssignment::query();
        
        if ($staffId) {
            $query->where('lecturer_staff_id', $staffId);
        }
        
        if ($matricNumber) {
            $query->where('student_matric_number', $matricNumber);
        }
        
        $assignments = $query->orderBy('created_at', 'desc')->get();
        
        return view('lecturer-student-assignments', [
            'assignments' => $assignments,
            'staffId' => $staffId,
            'matricNumber' => $matricNumber
        ]);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $validated = $request->validate([
                'lecturer_staff_id' => 'required|string',
                'student_matric_number' => 'required|string'
            ]);

            // Check if assignment already exists
            $exists = LecturerStudentAssignment::where('lecturer_staff_id', $validated['lecturer_staff_id'])
                ->where('student_matric_number', $validated['student_matric_number'])
                ->exists();

            if ($exists) {
                DB::rollBack();
                return redirect()->route('lecturer.student.assignments', [
                    'staffId' => $validated['lecturer_staff_id'],
                    'matricNumber' => $validated['student_matric_number']
                ])->with('error', 'This assignment already exists.');
            }

            // Create new assignment
            LecturerStudentAssignment::create($validated);

            DB::commit();
            return redirect()->route('lecturer.student.assignments', [
                'staffId' => $validated['lecturer_staff_id'],
                'matricNumber' => $validated['student_matric_number']
            ])->with('success', 'Assignment created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('lecturer.student.assignments', [
                'staffId' => $request->lecturer_staff_id,
                'matricNumber' => $request->student_matric_number
            ])->with('error', 'Failed to create assignment. Please try again.');
        }
    }
} 