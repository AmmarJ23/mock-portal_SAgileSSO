<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LecturerStudentAssignment extends Model
{
    protected $table = 'lecturer_student_assignments';
    
    protected $fillable = [
        'lecturer_staff_id',
        'student_matric_number'
    ];
} 