@extends('layouts.parent')

@section('title', 'My Children')

@section('content')
<div class="page-intro">
    <h4>My Children</h4>
    <p>View the student profiles linked to your parent account.</p>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Student Number</th>
                    <th>Name</th>
                    <th>Grade Level</th>
                    <th>Section</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>STU-AB12CD34</td>
                    <td>Maria Santos</td>
                    <td>Grade 11</td>
                    <td>St. Luke</td>
                </tr>
                <tr>
                    <td>STU-EF56GH78</td>
                    <td>John Santos</td>
                    <td>Grade 8</td>
                    <td>St. Mark</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection