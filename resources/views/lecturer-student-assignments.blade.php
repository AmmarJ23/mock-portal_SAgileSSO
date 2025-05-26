@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Lecturer-Student Assignments</h2>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="staffId" class="form-label">Lecturer Staff ID</label>
                                <input type="text" class="form-control" id="staffId" name="staffId" 
                                       value="{{ $staffId }}" placeholder="Enter staff ID" form="searchForm">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="matricNumber" class="form-label">Student Matric Number</label>
                                <input type="text" class="form-control" id="matricNumber" name="matricNumber" 
                                       value="{{ $matricNumber }}" placeholder="Enter matric number" form="searchForm">
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-end gap-2">
                            <form id="searchForm" action="{{ route('lecturer.student.assignments') }}" method="GET">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                    <a href="{{ route('lecturer.student.assignments') }}" class="btn btn-secondary">Clear</a>
                                </div>
                            </form>
                            
                            @if($staffId && $matricNumber)
                                <form action="{{ route('lecturer.student.assignments.store') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success" name="lecturer_staff_id" value="{{ $staffId }}">Create Assignment</button>
                                    <input type="text" name="lecturer_staff_id" value="{{ $staffId }}" readonly class="d-none">
                                    <input type="text" name="student_matric_number" value="{{ $matricNumber }}" readonly class="d-none">
                                </form>
                            @endif
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Lecturer Staff ID</th>
                                    <th>Student Matric Number</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($assignments as $assignment)
                                    <tr>
                                        <td>{{ $assignment->id }}</td>
                                        <td>{{ $assignment->lecturer_staff_id }}</td>
                                        <td>{{ $assignment->student_matric_number }}</td>
                                        <td>{{ $assignment->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td>{{ $assignment->updated_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No assignments found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 