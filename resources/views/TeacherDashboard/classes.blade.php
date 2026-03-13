@extends('layouts.teacher')

@section('title', 'My Classes')

@section('content')
<div class="page-intro">
    <h4>My Classes</h4>
    <p>View and manage your assigned classes and sections.</p>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Section</th>
                    <th>Schedule</th>
                    <th>Students</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Mathematics</td>
                    <td>Grade 10 - St. Mark</td>
                    <td>Mon/Wed 8:00 AM</td>
                    <td>35</td>
                </tr>
                <tr>
                    <td>Science</td>
                    <td>Grade 9 - St. Luke</td>
                    <td>Tue/Thu 10:00 AM</td>
                    <td>38</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection