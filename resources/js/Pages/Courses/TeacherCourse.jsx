import React from 'react';
import ReactDOM from 'react-dom';

const TeacherCourse = () => {
    const data = window.reactData;

    return (
        <div>
            <h1>Data from Laravel</h1>
            <a href="{{'add_course_page'}}">Create A New Course</a>
             <a href="#">Modify A Current Course</a> 
            <pre>{JSON.stringify(data, null, 2)}</pre>
        </div>
    );
};

ReactDOM.render(<TeacherCourse />, document.getElementById('root'));
