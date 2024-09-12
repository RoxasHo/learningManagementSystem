
import React, { useEffect, useState } from 'react';
//import { BrowserRouter as Router, Routes,Route ,useNavigate,Navigate } from 'react-router-dom';
import './App.css';
import Header from './PagesComponent/Header';
import Sidebar from './PagesComponent/Sidebar';
import Footer from './PagesComponent/Footer';
import ProfilePage from './ProfilePageComponent/ProfilePage';
import HomePage from './HomePageComponent/HomePage';
import CourseManagePage from './TeacherCourseMangementComponent/CourseManagePage';
import CourseEditPage from './TeacherCourseMangementComponent/CourseEditPage';
import LoginPage from './ProfilePageComponent/Auth/LoginPage';

/*
function App() {
 
  
  return (


    
    <Router>
    <div className="App">
      <Header />
      
      <div className="main-content">
      <Sidebar/>  
        <div className="content">
        <Routes>
              <Route path ="/" element = {<HomePage />}/>
              <Route path="/ProfilePage" element={<ProfilePage />} />
              <Route path="/CourseManagePage" element={<CourseManagePage />} />
              <Route path ="/CourseEditPage" element={<CourseEditPage />} />
              <Route path="*" element={<Navigate to="/" replace />} />
        </Routes>
          
        </div>
      </div>
      <Footer />
    </div>
    </Router>
    
  );
  
}

export default App;

 */
 /// Check if elements exist in the DOM and render the corresponding React components
if (document.getElementById('header')) {
    ReactDOM.render(<Header />, document.getElementById('header'));
}

if (document.getElementById('sidebar')) {
    ReactDOM.render(<Sidebar />, document.getElementById('sidebar'));
}

if (document.getElementById('footer')) {
    ReactDOM.render(<Footer />, document.getElementById('footer'));
}

if (document.getElementById('profile-page')) {
    ReactDOM.render(<ProfilePage />, document.getElementById('profile-page'));
}

if (document.getElementById('home-page')) {
    ReactDOM.render(<HomePage />, document.getElementById('home-page'));
}

if (document.getElementById('course-manage-page')) {
    ReactDOM.render(<CourseManagePage />, document.getElementById('course-manage-page'));
}

if (document.getElementById('course-edit-page')) {
    ReactDOM.render(<CourseEditPage />, document.getElementById('course-edit-page'));
}