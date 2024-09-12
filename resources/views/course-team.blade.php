<!DOCTYPE html>
<head></head>
<body>
<table class="table table-bordered">
                                <thead>
                                <tr>
                                <th scope="col">Sr.No</th>
                                <th scope="col">Teacher Name</th>
                                <th scope="col">Teacher Role</th>
                                <th scope="col">Remove</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($team as $row)

                                <tr scope="row">
                                <td>{{$loop ->iteration}} </td>
                                
                                <td>{{$row->name}} </td>
                                <td>{{$row->role}} </td>
                                <td></td>
                                      
                                </tr>
                                @endforeach                                                               
                                </tbody>
                                </table>
</body>