@extends('static.layouts.master')
@section('content')
<style>
  body {
    background: #f4f4f4;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
 }
 .primary-color {
    color: #615583;
 }
 .primary-bg {
    background-color: #615583;
 }
 .btn-primary {
    background-color: #615583;
    border-color: #615583;
 }
 .btn-primary:hover {
    background-color: #4e456a;
    border-color: #4e456a;
 }
 .main-header {
    background-color: #615583;
    color: #fff;
    padding: 60px 0;
    text-align: center;
 }
 .section-title {
    color: #615583;
    font-weight: bold;
 }
 .button-blue{
    background:#0bd3fa;
    color: #fff;
    text-decoration:none;
    border-radius:7px;
    text-transform: uppercase !important;
    max-width: 1000px;
    font-size:14px;
    display: inline-block;
    padding: 15px 30px;
 }
 .button-blue:hover,.button-blue:focus{
    background:#0bd3fa !important;
    color: #fff !important;
    text-decoration:none;
    border-radius:7px;
    text-transform: uppercase !important;
    max-width: 1000px;
    font-size:14px;
 }
 .custom-list li::before {
    content: "✔️ ";
    margin-right: 8px;
 }
</style>
<div class="main-container">
    <div class="primary-bg mt-lg-5 pt-2">
        <div class="container">
            <div class="text-center py-5 text-white rounded-3">
                <h1 class="display-5 fw-bold">Find the Perfect Match for Gym & Fitness Jobs in Kuwait</h1>
                <p class="lead mt-3">Whether you're an employer seeking qualified trainers or a job seeker looking for fitness careers, Hungry for Jobs connects talent with Kuwait's top gyms and wellness centers.</p>
                <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=1" class="btn btn-block-sm button-blue mt-2">Employers: Post Your Job Today</a>
                <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=2" class="btn btn-block-sm button-blue mt-2">Job Seekers: Find Your Dream Job Now</a>
            </div>
        </div>
    </div>
   <div class="container">
      
      <div class="py-5">
         <h2 class="section-title">Industry Overview – Gyms & Fitness in Kuwait</h2>
         <p class="text-muted">Kuwait's fitness industry is rapidly expanding, with gyms, health clubs, and wellness centers requiring skilled professionals to meet growing demand. Key trends include:</p>
         <ul class="ml-2">
            <li class="mb-2">• Rising popularity of specialized training (HIIT, yoga, CrossFit)</li>
            <li class="mb-2">• Increased need for certified personal trainers and nutrition advisors</li>
            <li class="mb-2">• Expansion of women-only fitness facilities</li>
            <li class="mb-2">• Demand for bilingual staff (Arabic/English) in luxury gyms</li>
         </ul>
         <p class="text-muted">Hungry for Jobs bridges the gap between qualified professionals and fitness employers across Kuwait.</p>
      </div>
      <div class="py-2">
         <h2 class="section-title mb-4">Who We Help</h2>
         <div class="row g-4">
            <div class="col-md-6">
               <div class="border rounded p-4 h-100">
                  <h3>For Employers</h3>
                  <p>We simplify hiring for:</p>
                  <ul class="ml-2">
                     <li class="mb-2">• <strong>Gyms & Health Clubs:</strong> Find certified trainers, front-desk staff, and managers</li>
                     <li class="mb-2">• <strong>Boutique Studios:</strong> Recruit niche instructors (pilates, cycling, martial arts)</li>
                     <li class="mb-2">• <strong>Corporate Wellness Programs:</strong> Source fitness coaches for employee health initiatives</li>
                  </ul>
                  <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=1" class="btn btn-block-sm button-blue mt-3">Register as an Employer</a>
               </div>
            </div>
            <div class="col-md-6 mt-2 mt-md-0">
               <div class="border rounded p-4 h-100">
                  <h3>For Job Seekers</h3>
                  <p>Access opportunities in:</p>
                     <ul class="ml-2">
                        <li class="mb-2">• <strong>Luxury gyms</strong> and hotel fitness centers</li>
                        <li class="mb-2">• <strong>Women-only training facilities</strong></li>
                        <li class="mb-2">• <strong>Sports clubs</strong> and rehabilitation centers</li>
                     </ul>
                  <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=2" class="btn btn-block-sm button-blue mt-3">Sign Up as a Job Seeker</a>
               </div>
            </div>
         </div>
      </div>
      <!-- Popular Roles -->
      <div class="py-5">
         <h2 class="section-title mb-4">Popular Roles in Gyms & Fitness</h2>
         <div class="row g-3">
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">💪 Personal Trainer Jobs</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🏋️‍♂️ Gym Manager Positions</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🤸 Group Fitness Instructor Roles</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🥗 Nutritionist & Wellness Coach Openings</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">📋  Front Desk & Membership Sales Jobs</div>
            </div>
         </div>
      </div>
      <div class="py-2">
         <h2 class="section-title mb-4">Why Choose Hungry for Jobs?</h2>
         <ul class="list-unstyled">
            <li class="mb-2">✔ Specializing in the fitness industry recruitment</li>
            <li class="mb-2">✔ Trusted by major gym chains in Kuwait</li>
            <li class="mb-2">✔ Fast placements with pre-screened candidates</li>
            <li class="mb-2">✔ Compliance with local labor regulations</li>
         </ul>
      </div>
   </div>
</div>
@endsection