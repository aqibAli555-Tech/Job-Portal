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
                <h1 class="display-5 fw-bold">Bloom Your Team or Start Your Floral Career in Kuwait</h1>
                <p class="lead mt-3">Connecting florists, designers, and retail staff with Kuwait’s premier flower shops, event decorators, and garden centers.</p>
                <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=1" class="btn btn-block-sm button-blue mt-2">Employers: Find Floral Talent Now</a>
                <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=2" class="btn btn-block-sm button-blue mt-2">Job Seekers: Browse Flower Shop Jobs</a>
            </div>
        </div>
    </div>
   <div class="container">
      
      <div class="py-5">
         <h2 class="section-title">Industry Overview – Flower Shops in Kuwait</h2>
         <p class="text-muted">Kuwait’s floral industry is blossoming, with luxury flower boutiques, wedding decor specialists, and online delivery services requiring:</p>
         <ul>
            <li class="mb-2">✔ Creative florists skilled in European/Arabic arrangements</li>
            <li class="mb-2">✔ Retail staff knowledgeable about exotic flowers</li>
            <li class="mb-2">✔ Event designers for corporate/galas/weddings</li>
            <li class="mb-2">✔ Delivery drivers with careful handling expertise</li>
         </ul>
         <p>Market Trends:</p>
         <ul class="ml-2">
            <li class="mb-2">• 40% surge in subscription flower services</li>
            <li class="mb-2">• High demand for luxury wedding installations</li>
            <li class="mb-2">• Rising popularity of sustainable/eco-friendly floristry</li>
            <li class="mb-2">• Seasonal peaks during Eid, Valentine’s, and National Day</li>
         </ul>
         <p class="text-muted">Hungry for Jobs helps Kuwait’s floral businesses grow with top talent.</p>
      </div>
      <div class="py-2">
         <h2 class="section-title mb-4">Who We Help</h2>
         <div class="row g-4">
            <div class="col-md-6">
               <div class="border rounded p-4 h-100">
                  <h3>For Employers</h3>
                  <p>We provide staff for:</p>
                  <ul class="ml-2">
                     <li class="mb-2">• High-End Florists: Master floral designers</li>
                     <li class="mb-2">• Garden Centers: Plant care specialists</li>
                     <li class="mb-2">• Event Companies: Installation teams</li>
                     <li class="mb-2">• Online Flower Services: Customer experience staff</li>
                  </ul>
                  <p class="text-muted">Our Specialty:</p>
                  <ul>
                     <li class="mb-2">✔ Candidates trained in international floral techniques</li>
                     <li class="mb-2">✔ Seasonal staffing for peak periods</li>
                     <li class="mb-2">✔ Bilingual teams for luxury clientele</li>
                  </ul>
                  <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=1" class="btn btn-block-sm button-blue mt-3">Post Floral Jobs</a>
               </div>
            </div>
            <div class="col-md-6 mt-2 mt-md-0">
               <div class="border rounded p-4 h-100">
                  <h3>For Job Seekers</h3>
                  <p>Grow your career at:</p>
                    <ul class="list-unstyled">
                        <li class="mb-2">✅ Luxury flower boutiques</li>
                        <li class="mb-2">✅ Five-star hotel florists</li>
                        <li class="mb-2">✅ Wedding decoration companies</li>
                        <li class="mb-2">✅ Organic plant nurseries</li>
                    </ul>
                    <p>Career Perks:</p>
                     <ul class="ml-2">
                        <li class="mb-2">• Learn from international floral artists</li>
                        <li class="mb-2">• Tips from high-end clients</li>
                        <li class="mb-2">• Creative work environment</li>
                     </ul>
                  <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=2" class="btn btn-block-sm button-blue mt-3">Apply for Floral Jobs</a>
               </div>
            </div>
         </div>
      </div>
      <!-- Popular Roles -->
      <div class="py-5">
         <h2 class="section-title mb-4">Popular Roles</h2>
         <div class="row g-3">
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">💐 <strong>Floral Designer</strong></div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🏵️ <strong>Flower Shop Manager</strong></div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">💒 <strong>Wedding Installation Specialist</strong></div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🌱 <strong>Plant Care Advisor</strong></div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🚚 <strong>Floral Delivery Driver</strong></div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🤝 <strong>Customer Experience Associate</strong></div>
            </div>
         </div>
      </div>
      <div class="py-2">
         <h2 class="section-title mb-4">Why Choose Hungry for Jobs?</h2>
         <ul class="list-unstyled">
            <li class="mb-2">✔ Kuwait’s #1 floral industry recruiter</li>
            <li class="mb-2">✔ Pre-screened creatives with portfolio reviews</li>
            <li class="mb-2">✔ Same-day placements for urgent events</li>
            <li class="mb-2">✔ Deep networks with luxury wedding planners</li>
         </ul>
      </div>
   </div>
</div>
@endsection