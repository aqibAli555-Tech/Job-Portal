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
                <h1 class="display-5 fw-bold">Staff Your Fashion Brand or Launch Your Retail Career in Kuwait</h1>
                <p class="lead mt-3">Connecting retail professionals with Kuwait’s leading fashion houses, department stores, and boutique brands.</p>
                <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=1" class="btn btn-block-sm button-blue mt-2">Employers: Find Retail Talent Now</a>
                <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=2" class="btn btn-block-sm button-blue mt-2">Job Seekers: Browse Fashion Jobs</a>
            </div>
        </div>
    </div>
   <div class="container">
      
      <div class="py-5">
         <h2 class="section-title">Industry Overview – Fashion & Retail in Kuwait</h2>
         <p class="text-muted">Kuwait’s retail sector is a regional fashion hub, with luxury malls, local designers, and international brands requiring:</p>
         <ul>
            <li class="mb-2">✔ Bilingual sales associates (Arabic/English)</li>
            <li class="mb-2">✔ Visual merchandisers for high-impact displays</li>
            <li class="mb-2">✔ E-commerce specialists for omnichannel retail</li>
            <li class="mb-2">✔ Buyers with Middle Eastern market expertise</li>
         </ul>
         <p>Market Trends:</p>
         <ul class="ml-2">
            <li class="mb-2">• 25% growth in modest fashion segments</li>
            <li class="mb-2">• Surge in pop-up stores and limited-edition launches</li>
            <li class="mb-2">• Rising demand for Saudi-market trained staff</li>
            <li class="mb-2">• Luxury brands prioritizing VIP client specialists</li>
         </ul>
         <p class="text-muted">Hungry for Jobs bridges Kuwait’s fashion employers with customer-focused talent.</p>
      </div>
      <div class="py-2">
         <h2 class="section-title mb-4">Who We Help</h2>
         <div class="row g-4">
            <div class="col-md-6">
               <div class="border rounded p-4 h-100">
                  <h3>For Employers</h3>
                  <p>We staff all retail tiers:</p>
                  <ul class="ml-2">
                     <li class="mb-2">• Luxury Fashion Houses: Personal shoppers & stylists</li>
                     <li class="mb-2">• Department Stores: Floor managers & beauty advisors</li>
                     <li class="mb-2">• Local Boutiques: Trend-savvy sales staff</li>
                     <li class="mb-2">• E-Commerce Teams: Digital merchandisers</li>
                  </ul>
                  <p class="text-muted">Our Advantage:</p>
                  <ul>
                     <li class="mb-2">✔ Candidates trained in GCC luxury service standards</li>
                     <li class="mb-2">✔ Seasonal staffing for Ramadan/summer collections</li>
                     <li class="mb-2">✔ Mall-certified sales professionals</li>
                  </ul>
                  <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=1" class="btn btn-block-sm button-blue mt-3">Post Retail Jobs</a>
               </div>
            </div>
            <div class="col-md-6 mt-2 mt-md-0">
               <div class="border rounded p-4 h-100">
                  <h3>For Job Seekers</h3>
                  <p>Access opportunities at:</p>
                    <ul class="list-unstyled">
                        <li class="mb-2">✅ Global luxury brands</li>
                        <li class="mb-2">✅ Kuwaiti designer boutiques</li>
                        <li class="mb-2">✅ Mega-mall flagship stores</li>
                        <li class="mb-2">✅ E-commerce fashion platforms</li>
                    </ul>
                    <p>Career Perks:</p>
                     <ul class="ml-2">
                        <li class="mb-2">• Employee discounts (30-50% at partner brands)</li>
                        <li class="mb-2">• Commission structures for top sellers</li>
                        <li class="mb-2">• Path to buyer/store manager roles</li>
                     </ul>
                  <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=2" class="btn btn-block-sm button-blue mt-3">Apply for Retail Jobs</a>
               </div>
            </div>
         </div>
      </div>
      <!-- Popular Roles -->
      <div class="py-5">
         <h2 class="section-title mb-4">Popular Roles</h2>
         <div class="row g-3">
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">👜 <strong>Luxury Sales Associate</strong></div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🖼️ <strong>Visual Merchandiser</strong></div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">👗 <strong>Fashion Buyer (Women’s/Men’s)</strong></div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">💻 <strong>E-Commerce Stylist</strong></div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🏬 <strong>Mall Operations Supervisor</strong></div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">👨‍💼 <strong>Beauty Counter Manager</strong></div>
            </div>
         </div>
      </div>
      <div class="py-2">
         <h2 class="section-title mb-4">Why Choose Hungry for Jobs?</h2>
         <ul class="list-unstyled">
            <li class="mb-2">✔ Kuwait’s top fashion recruiter</li>
            <li class="mb-2">✔ Exclusive partnerships with 50+ brands</li>
            <li class="mb-2">✔ Arabic/English bilingual screening</li>
            <li class="mb-2">✔ Ramadan/summer collection staffing experts</li>
         </ul>
      </div>
   </div>
</div>
@endsection