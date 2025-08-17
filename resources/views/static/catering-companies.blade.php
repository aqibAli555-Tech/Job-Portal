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
                <h1 class="display-5 fw-bold">Staff Your Catering Business or Launch Your Event Career in Kuwait</h1>
                <p class="lead mt-3">Connecting skilled chefs, servers, and event professionals with Kuwait's premier catering companies and banquet providers.</p>
                <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=1" class="btn btn-block-sm button-blue mt-2">Employers: Find Catering Staff Now</a>
                <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=2" class="btn btn-block-sm button-blue mt-2">Job Seekers: Browse Event Hospitality Jobs</a>
            </div>
        </div>
    </div>
   <div class="container">
      
      <div class="py-5">
         <h2 class="section-title">Industry Overview – Catering in Kuwait</h2>
         <p class="text-muted">Kuwait's catering sector is experiencing unprecedented demand, with wedding planners, corporate event organizers, and government contractors requiring specialized talent for:</p>
         <ul>
            <li class="mb-2">✔ Luxury wedding banquets (500+ guest events)</li>
            <li class="mb-2">✔ Oil & gas company galas and executive dining</li>
            <li class="mb-2">✔ Ramadan and Eid feast preparations</li>
            <li class="mb-2">✔ High-profile diplomatic functions</li>
         </ul>
         <p>Industry Trends:</p>
         <ul class="ml-2">
            <li class="mb-2">• Growing preference for themed culinary experiences</li>
            <li class="mb-2">• Rising demand for halal-certified gourmet catering</li>
            <li class="mb-2">• Expansion of plant-based and allergy-friendly menus</li>
            <li class="mb-2">• Need for Arabic/English bilingual event staff</li>
         </ul>
         <p class="text-muted">Hungry for Jobs is Kuwait's leading recruitment partner for catering talent.</p>
      </div>
      <div class="py-2">
         <h2 class="section-title mb-4">Who We Help</h2>
         <div class="row g-4">
            <div class="col-md-6">
               <div class="border rounded p-4 h-100">
                  <h3>For Catering Companies</h3>
                  <p>We provide:</p>
                  <ul class="ml-2">
                     <li class="mb-2">• Executive Chefs with large-scale banquet experience</li>
                     <li class="mb-2">• Event Captains skilled in VIP service</li>
                     <li class="mb-2">• Mobile Kitchen Teams for off-site catering</li>
                     <li class="mb-2">• <strong>Specialized Roles:</strong></li>
                     <ul class="ml-2">
                        <li class="mb-2">• Arabic coffee ceremony experts</li>
                        <li class="mb-2">• Live cooking station chefs</li>
                        <li class="mb-2">• Dietary restriction specialists</li>
                     </ul>
                  </ul>
                  <p>Our Advantage:</p>
                    <ul>
                        <li class="mb-2">✔ Staff trained in Kuwaiti hospitality standards</li>
                        <li class="mb-2">✔ Teams experienced in events up to 1,000+ guests</li>
                        <li class="mb-2">✔ Last-minute staffing for unexpected contracts</li>
                    </ul>
                  <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=1" class="btn btn-block-sm button-blue mt-3">Post Catering Jobs</a>
               </div>
            </div>
            <div class="col-md-6 mt-2 mt-md-0">
               <div class="border rounded p-4 h-100">
                  <h3>For Job Seekers</h3>
                  <p>Access opportunities with:</p>
                    <ul class="list-unstyled">
                        <li class="mb-2">✅ Five-star hotel catering divisions</li>
                        <li class="mb-2">✅ Government-approved caterers</li>
                        <li class="mb-2">✅ Luxury wedding specialists</li>
                        <li class="mb-2">✅ Corporate event providers</li>
                     </ul>
                     <p>Career Benefits:</p>
                     <ul class="ml-2">
                        <li class="mb-2">• Learn from international catering chefs</li>
                        <li class="mb-2">• Flexible schedules (project-based work available)</li>
                        <li class="mb-2">• Tips and bonuses for large events</li>
                     </ul>
                  <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=2" class="btn btn-block-sm button-blue mt-3">Apply for Catering Jobs</a>
               </div>
            </div>
         </div>
      </div>
      <!-- Popular Roles -->
      <div class="py-5">
         <h2 class="section-title mb-4">Popular Catering Roles</h2>
         <div class="row g-3">
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">👨‍🍳 Banquet Chef</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">👨‍💼 Event Service Manager</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🍽️ Buffet Attendant</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">☕ Arabic Coffee Specialist</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🧁 Pastry Chef (Large Quantity Production)</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">📦 Logistics Coordinator</div>
            </div>
         </div>
      </div>
      <div class="py-2">
         <h2 class="section-title mb-4">Why Choose Hungry for Jobs?</h2>
         <ul class="list-unstyled">
            <li class="mb-2">✔ #1 Catering Staffing Platform in Kuwait</li>
            <li class="mb-2">✔ Pre-screened teams with event experience</li>
            <li class="mb-2">✔ 80% faster hiring for urgent contracts</li>
            <li class="mb-2">✔ Full compliance with MOH food safety standards</li>
         </ul>
      </div>
   </div>
</div>
@endsection