<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<x-layout>
    <div class="main-content container" id="main-content" style="padding-top: 150px; min-height: 700px;">
        
        <!-- Success Message -->
        @if (\Session::has('success'))
            <div class="alert alert-success">
                <ul class="mb-0">
                    <li>{!! \Session::get('success') !!}</li>
                </ul>
            </div>
        @endif
        @if (\Session::has('failed'))
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <li>{!! \Session::get('failed') !!}</li>
                </ul>
            </div>
        @endif

        <!-- Course Heading -->
        
        <h1 class="mb-4">Course Name : {{$course_name}}</h1>

        <!-- Teacher Table -->
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">No.</th>
                    <th scope="col">Teacher Name</th>
                    <th scope="col" width="200">Role</th>
                    @if($role == 'leader')
                        <th scope="col" width="200">Remove</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($team as $row)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$row->name}}</td>
                        <td>{{$row->role}}</td>
                        @if($role == 'leader')
                            <td>
                                <form method="POST" action="{{url('remove-teacher')}}">
                                    @csrf
                                    <input type="hidden" name="teacher_id" value="{{$row->teacher_id}}">
                                    <input type="hidden" name="course_id" value="{{$course_id}}">
                                    <button type="submit" class="btn btn-danger">Remove</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Add Teacher Form -->
        @if($role == 'leader')
            <form action="{{url('add-teacher')}}" method="POST" class="mt-4">
                @csrf
                <div class="form-group">
                    <label for="new_teacher_email">Teacher Email</label>
                    <input type="hidden" name="course_id" value="{{$course_id}}">
                    <input name="new_teacher_email" type="email" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Teacher</button>
            </form>
        @endif

    </div>
</x-layout>
