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
                <h1 class="display-5 fw-bold">Staff Your Entertainment Company or Launch Your Creative Career in Kuwait</h1>
                <p class="lead mt-3">Connecting event producers, talent agencies, and entertainment companies with skilled performers, technicians, and production staff.</p>
                <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=1" class="btn btn-block-sm button-blue mt-2">Employers: Find Entertainment Talent Now</a>
                <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=2" class="btn btn-block-sm button-blue mt-2">Job Seekers: Browse entertainment Company Jobs</a>
            </div>
        </div>
    </div>
   <div class="container">
      
      <div class="py-5">
         <h2 class="section-title">Industry Overview – Entertainment Companies in Kuwait</h2>
         <p class="text-muted">Kuwait’s entertainment sector is thriving, with event organizers, production houses, and talent agencies requiring:</p>
         <ul>
            <li class="mb-2">✔ Event producers for concerts and festivals</li>
            <li class="mb-2">✔ Technical crew (lighting/sound/stage)</li>
            <li class="mb-2">✔ Creative directors for immersive experiences</li>
            <li class="mb-2">✔ Talent scouts with regional market knowledge</li>
         </ul>
         <p>Industry Trends:</p>
         <ul class="ml-2">
            <li class="mb-2">• 40% growth in corporate entertainment demand</li>
            <li class="mb-2">• Rising need for Arabic/English bilingual hosts</li>
            <li class="mb-2">• Government investments in cultural festivals</li>
            <li class="mb-2">• Expansion of family entertainment centers</li>
         </ul>
         <p class="text-muted">Hungry for Jobs powers Kuwait’s entertainment industry with top creative talent.</p>
      </div>
      <div class="py-2">
         <h2 class="section-title mb-4">Who We Help</h2>
         <div class="row g-4">
            <div class="col-md-6">
               <div class="border rounded p-4 h-100">
                  <h3>For Employers</h3>
                  <p>We staff all entertainment verticals:</p>
                  <ul class="ml-2">
                     <li class="mb-2">• Concert Promoters: Stage managers, artist liaisons</li>
                     <li class="mb-2">• Production Houses: Camera crews, editors</li>
                     <li class="mb-2">• Talent Agencies: Scouts, booking agents</li>
                     <li class="mb-2">• Event Companies: Logistics coordinators</li>
                  </ul>
                  <p class="text-muted">Our Edge:</p>
                  <ul>
                     <li class="mb-2">✔ Pre-vetted performers and technicians</li>
                     <li class="mb-2">✔ Ramadan/National Day seasonal staffing</li>
                     <li class="mb-2">✔ Cultural compliance experts</li>
                  </ul>
                  <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=1" class="btn btn-block-sm button-blue mt-3">Post Entertainment Jobs</a>
               </div>
            </div>
            <div class="col-md-6 mt-2 mt-md-0">
               <div class="border rounded p-4 h-100">
                  <h3>For Job Seekers</h3>
                  <p>Access opportunities with:</p>
                    <ul class="list-unstyled">
                        <li class="mb-2">✅ Major concert promoters</li>
                        <li class="mb-2">✅ TV/film production companies</li>
                        <li class="mb-2">✅ Event management firms</li>
                        <li class="mb-2">✅ Talent agencies</li>
                    </ul>
                    <p>Career Perks:</p>
                     <ul class="ml-2">
                        <li class="mb-2">• Project-based flexibility</li>
                        <li class="mb-2">• Exposure to international acts</li>
                        <li class="mb-2">• Paths to creative direction roles</li>
                     </ul>
                  <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=2" class="btn btn-block-sm button-blue mt-3">Apply for Entertainment Jobs</a>
               </div>
            </div>
         </div>
      </div>
      <!-- Popular Roles -->
      <div class="py-5">
         <h2 class="section-title mb-4">Key Entertainment Roles</h2>
         <div class="row g-3">
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🎬 <strong>Event Producer</strong></div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🎭 <strong>Stage Manager</strong></div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">💡 <strong>Lighting Technician</strong></div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">📅 <strong>Talent Booker</strong></div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🎨 <strong>Creative Director</strong></div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🎤 <strong>Entertainment Host</strong></div>
            </div>
         </div>
      </div>
      <div class="py-2">
         <h2 class="section-title mb-4">Why Choose Hungry for Jobs?</h2>
         <ul class="list-unstyled">
            <li class="mb-2">✔ Kuwait’s premier entertainment recruiter</li>
            <li class="mb-2">✔ Exclusive talent networks</li>
            <li class="mb-2">✔ Fast placements for last-minute events</li>
            <li class="mb-2">✔ Deep understanding of GCC entertainment regulations</li>
         </ul>
      </div>
   </div>
</div>
@endsection