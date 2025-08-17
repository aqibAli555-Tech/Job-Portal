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
                <h1 class="display-5 fw-bold">Elevate Kuwait’s Lounge & Beverage Service with Top Talent</h1>
                <p class="lead mt-3">Connecting skilled hospitality professionals with Kuwait’s luxury hotel lounges, private clubs, and premium beverage venues.</p>
                <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=1" class="btn btn-block-sm button-blue mt-2">Employers: Find Beverage Service Staff</a>
                <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=2" class="btn btn-block-sm button-blue mt-2">Job Seekers: Explore Lounge Careers</a>
            </div>
        </div>
    </div>
   <div class="container">
      
      <div class="py-5">
         <h2 class="section-title">Industry Overview – Lounges & Beverage Service in Kuwait</h2>
         <p class="text-muted">Kuwait’s upscale hospitality sector offers thriving opportunities in non-alcoholic beverage service and luxury lounges, with demand driven by:</p>
         <ul>
            <li class="mb-2">✔ <strong>Five-star hotel expansions</strong> requiring polished lounge staff</li>
            <li class="mb-2">✔ <strong>Growth of specialty coffee/tea lounges</strong> and mocktail bars</li>
            <li class="mb-2">✔ <strong>Private members’ clubs</strong> seeking bilingual service professionals</li>
            <li class="mb-2">✔ <strong>Premium customer service standards</strong> in high-end venues</li>
         </ul>
         <p class="text-muted">Hungry for Jobs specializes in staffing Kuwait’s sophisticated beverage service venues with qualified, culturally attuned talent.</p>
      </div>
      <div class="py-2">
         <h2 class="section-title mb-4">Who We Help</h2>
         <div class="row g-4">
            <div class="col-md-6">
               <div class="border rounded p-4 h-100">
                  <h3>For Employers</h3>
                  <p>We simplify hiring for:</p>
                  <ul class="ml-2">
                     <li class="mb-2">• <strong>Hotel Lounges: </strong>Find trained beverage servers and hosts</li>
                     <li class="mb-2">• <strong>Private Clubs: </strong>Recruit discreet, professional staff</li>
                     <li class="mb-2">• <strong>Specialty Cafés: </strong>Hire mocktail mixologists and baristas</li>
                     <li class="mb-2">• <strong>Event Venues: </strong>Source temporary staff for gatherings</li>
                  </ul>
                  <p>Our advantages:</p>
                    <ul>
                        <li class="mb-2">✔ Pre-screened candidates familiar with Kuwaiti cultural norms</li>
                        <li class="mb-2">✔ Staff trained in luxury non-alcoholic beverage service</li>
                        <li class="mb-2">✔ Compliance with local hospitality regulations</li>
                    </ul>
                  <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=1" class="btn btn-block-sm button-blue mt-3">Post Your Opening</a>
               </div>
            </div>
            <div class="col-md-6 mt-2 mt-md-0">
               <div class="border rounded p-4 h-100">
                  <h3>For Job Seekers</h3>
                  <p>Access roles at:</p>
                    <ul class="list-unstyled">
                        <li class="mb-2">✅ Luxury hotel lounges</li>
                        <li class="mb-2">✅ Private business clubs</li>
                        <li class="mb-2">✅ High-end mocktail bars</li>
                        <li class="mb-2">✅ Specialty tea/coffee houses</li>
                    </ul>
                    <p>Perks:</p>
                    <ul class="ml-2">
                        <li class="mb-2">• Competitive salaries with service bonuses</li>
                        <li class="mb-2">• Career growth to supervisory roles</li>
                     </ul>
                  <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=2" class="btn btn-block-sm button-blue mt-3">Apply Now</a>
               </div>
            </div>
         </div>
      </div>
      <!-- Popular Roles -->
      <div class="py-5">
         <h2 class="section-title mb-4">Popular Roles</h2>
         <div class="row g-3">
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🛋️ <strong>Lounge Attendant</strong></div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🥤 <strong>Beverage Server</strong> (specialty coffee/tea/mocktails)</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🎩 <strong>Host/Hostess</strong> (VIP guest management)</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">👨‍💼 <strong>Lounge Supervisor</strong></div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">☕ <strong>Barista/Mocktail Mixologist</strong></div>
            </div>
         </div>
      </div>
      <div class="py-2">
         <h2 class="section-title mb-4">Why Choose Hungry for Jobs? </h2>
         <ul class="list-unstyled">
            <li class="mb-2">✔ <strong>Exclusive partnerships </strong>with Kuwait’s top hotels and clubs</li>
            <li class="mb-2">✔ <strong>Cultural sensitivity training </strong>for all staff</li>
            <li class="mb-2">✔ <strong>Fast placements – </strong>75% of roles filled within 2 weeks</li>
            <li class="mb-2">✔ <strong>Strict compliance </strong>with Kuwaiti hospitality laws</li>
         </ul>
      </div>
   </div>
</div>
@endsection