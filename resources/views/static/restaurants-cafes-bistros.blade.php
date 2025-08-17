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
                <h1 class="display-5 fw-bold">Find Top Talent or Your Next Job in Kuwait’s Thriving Food & Beverage Scene</h1>
                <p class="lead mt-3">Whether you’re a restaurant hiring staff or a job seeker looking for F&B roles, Hungry for Jobs connects you with the best opportunities in Kuwait’s cafes, bistros, and eateries.</p>
                <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=1" class="btn btn-block-sm button-blue mt-2">Employers: Post a Job & Hire Fast</a>
                <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=2" class="btn btn-block-sm button-blue mt-2">Job Seekers: Explore F&B Jobs Now</a>
            </div>
        </div>
    </div>
   <div class="container">
      
      <div class="py-5">
         <h2 class="section-title">Hire or Get Hired for Restaurants, Cafes & Bistros in Kuwait</h2>
         <p class="text-muted">Kuwait’s food and beverage industry is booming, with a surge in cafes, fine-dining restaurants, and casual bistros catering to locals and expats alike. The demand for skilled chefs, waitstaff, baristas, and kitchen crew is higher than ever, driven by Kuwait’s vibrant dining culture and growing tourism.</p>
         <p>
            Trends like artisanal coffee shops, cloud kitchens, and international cuisine are reshaping the market, increasing the need for:
         </p>
         <ul>
            <li class="mb-2">✔ Multilingual staff (Arabic/English) for guest service</li>
            <li class="mb-2">✔ Trained chefs (continental, Arabic, pastry, etc.)</li>
            <li class="mb-2">✔ Quick hires for seasonal/peak hours</li>
         </ul>
         <p class="text-muted">Hungry for Jobs streamlines hiring for restaurants and helps job seekers land roles in Kuwait’s fast-paced F&B sector.</p>
      </div>
      <div class="py-2">
         <h2 class="section-title mb-4">Who We Help</h2>
         <div class="row g-4">
            <div class="col-md-6">
               <div class="border rounded p-4 h-100">
                  <h3>For Employers</h3>
                  <p>Hiring for restaurants and cafes is time-sensitive—Hungry for Jobs delivers pre-screened, experienced staff fast. We specialize in:</p>
                  <ul class="ml-2">
                     <li class="mb-2">• Urgent hiring for peak shifts, weekends, and events</li>
                     <li class="mb-2">• Permanent & part-time roles (waiters, chefs, cashiers, etc.)</li>
                     <li class="mb-2">• Pre-vetted talent for:</li>
                        <ul class="ml-4">
                           <li class="mb-2">• Kitchen Staff (Chefs, Line Cooks, Bakers)</li>
                           <li class="mb-2">• Front-of-House (Waiters, Hosts, Baristas)</li>
                           <li class="mb-2">• Management (Supervisors, Restaurant Managers)</li>
                        </ul>
                  </ul>
                  <p>Stop struggling with staffing gaps—let us handle recruitment so you can focus on service.</p>
                  <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=1" class="btn btn-block-sm button-blue mt-3">Post a Job Opening Now</a>
               </div>
            </div>
            <div class="col-md-6 mt-2 mt-md-0">
               <div class="border rounded p-4 h-100">
                  <h3>For Job Seekers</h3>
                  <p>Want to work in Kuwait’s top restaurants, luxury hotels, or trendy cafes? Hungry for Jobs offers:</p>
                     <ul class="list-unstyled">
                        <li class="mb-2">✅ Free access to the best F&B job listings</li>
                        <li class="mb-2">✅ Flexible roles (full-time, part-time, seasonal)</li>
                        <li class="mb-2">✅ Quick placements in venues that match your skills</li>
                     </ul>
                     <p>From fine dining to casual bistros, we help you find the right fit.</p>

                  <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=2" class="btn btn-block-sm button-blue mt-3">Upload Your Resume & Apply Today</a>
               </div>
            </div>
         </div>
      </div>
      <!-- Popular Roles -->
      <div class="py-5">
         <h2 class="section-title mb-4">Popular Roles in Restaurants & Cafes</h2>
         <p>(List key jobs with SEO-friendly phrasing)</p>
         <div class="row g-3">
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">👨‍🍳 Chef & Kitchen Staff Jobs (Sous Chef, Pastry Chef, Grill Cook)</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🎩 Waiter/Waitress & Host/Hostess Roles</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">☕ Barista & Café Staff Jobs in Kuwait</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">👨‍💼 Restaurant Manager & Supervisor Positions</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🍔 Fast-Food & Counter Service Jobs</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🛵 Food Delivery & Cloud Kitchen Opportunities</div>
            </div>
         </div>
         <br>
         <p>Browse the latest openings and apply in minutes!</p>
      </div>
      <div class="py-2">
         <h2 class="section-title mb-4">Why Choose Hungry for Jobs for F&B Staffing?</h2>
         <p>Hungry for Jobs is Kuwait’s top recruitment partner for restaurants and cafes, trusted by:</p>
         <ul class="list-unstyled">
            <li class="mb-2">✔ Local eateries, international chains, and boutique cafés</li>
            <li class="mb-2">✔ Job seekers looking for fair wages and growth</li>
         </ul>
         <p>Our advantages:</p>
         <ul class="ml-2">
            <li class="mb-2">• Deep industry expertise in F&B hiring trends</li>
            <li class="mb-2">• Rapid placements—fill vacancies within days</li>
            <li class="mb-2">• Compliance with Kuwait labor laws (visas, contracts, etc.)</li>
            <li class="mb-2">• Dedicated support for employers and candidates</li>
         </ul>
         <p>Whether you’re hiring a barista or searching for chef jobs, we make it effortless.</p>
      </div>
   </div>
</div>
@endsection