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
                <h1 class="display-5 fw-bold">Staff Your Mobile Kitchen or Launch Your Fast-Food Career in Kuwait</h1>
                <p class="lead mt-3">Connecting skilled cooks, cashiers, and food service professionals with Kuwait's booming food truck and quick-service restaurant sector.</p>
                <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=1" class="btn btn-block-sm button-blue mt-2">Employers: Find Fast-Food Staff Now</a>
                <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=2" class="btn btn-block-sm button-blue mt-2">Job Seekers: Browse Quick-Service Jobs</a>
            </div>
        </div>
    </div>
   <div class="container">
      
      <div class="py-5">
         <h2 class="section-title">Industry Overview – Food Trucks & Fast-Food in Kuwait</h2>
         <p class="text-muted">Kuwait's mobile food and quick-service industry is sizzling hot, with gourmet food trucks, international burger chains, and local shawarma stands revolutionizing casual dining.</p>
         <p>The sector demands:</p>
         <ul class="ml-2">
            <li class="mb-2">✔ Grill masters specializing in Kuwaiti/American fusion</li>
            <li class="mb-2">✔ Speedy cashiers with POS system expertise</li>
            <li class="mb-2">✔ Food prep chefs for high-volume kitchens</li>
            <li class="mb-2">✔ Multi-skilled staff for food truck operations</li>
         </ul>
         <p>Market Trends:</p>
         <ul class="ml-2">
            <li class="mb-2">• Explosion of themed food truck parks (Kuwait City, Salmiya)</li>
            <li class="mb-2">• Growing demand for halal-certified fast casual concepts</li>
            <li class="mb-2">• Popularity of 24-hour drive-thru and delivery models</li>
            <li class="mb-2">• Rising need for hygiene specialists in quick-service</li>
         </ul>
         <p class="text-muted">Hungry for Jobs fuels Kuwait's fast-food revolution with top-tier talent.</p>
      </div>
      <div class="py-2">
         <h2 class="section-title mb-4">Who We Help</h2>
         <div class="row g-4">
            <div class="col-md-6">
               <div class="border rounded p-4 h-100">
                  <h3>For Employers</h3>
                  <p>We help staff:</p>
                  <ul class="ml-2">
                     <li class="mb-2">• Food Truck Startups: Find compact kitchen crews</li>
                     <li class="mb-2">• Burger Chains: Source flame-grill experts</li>
                     <li class="mb-2">• Shawarma Stands: Hire vertical spit masters</li>
                     <li class="mb-2">• Coffee Kiosks: Recruit barista-speed servers</li>
                  </ul>
                  <p class="text-muted">Our specialty:</p>
                  <ul>
                     <li class="mb-2">✔ Candidates trained for fast-paced environments</li>
                     <li class="mb-2">✔ Staff experienced in mobile POS systems</li>
                     <li class="mb-2">✔ Bilingual teams (Arabic/English) for tourist areas</li>
                  </ul>
                  <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=1" class="btn btn-block-sm button-blue mt-3">Post Your Fast-Food Job</a>
               </div>
            </div>
            <div class="col-md-6 mt-2 mt-md-0">
               <div class="border rounded p-4 h-100">
                  <h3>For Job Seekers</h3>
                  <p>Access sizzling opportunities at:</p>
                    <ul class="list-unstyled">
                        <li class="mb-2">✅ Gourmet food truck collectives</li>
                        <li class="mb-2">✅ International burger franchises</li>
                        <li class="mb-2">✅ Local quick-service icons (shawarma, falafel)</li>
                        <li class="mb-2">✅ 24-hour drive-thru operations</li>
                    </ul>
                    <p>Career Perks:</p>
                     <ul class="ml-2">
                        <li class="mb-2">• Learn multiple stations (grill, prep, cashier)</li>
                        <li class="mb-2">• Flexible shifts (part-time/night positions)</li>
                        <li class="mb-2">• Quick promotion to shift leader roles</li>
                     </ul>
                  <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=2" class="btn btn-block-sm button-blue mt-3">Apply for Fast-Food Jobs</a>
               </div>
            </div>
         </div>
      </div>
      <!-- Popular Roles -->
      <div class="py-5">
         <h2 class="section-title mb-4">Popular Roles</h2>
         <div class="row g-3">
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🔥 Grill Cook/Chef</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">💵 Food Truck Cashier</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🍢 Shawarma Spit Operator</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🍟 Fry Station Specialist</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">👨‍✈️ Shift Supervisor</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🧼 Hygiene Compliance Officer</div>
            </div>
         </div>
      </div>
      <div class="py-2">
         <h2 class="section-title mb-4">Why Choose Hungry for Jobs?</h2>
         <ul class="list-unstyled">
            <li class="mb-2">✔ Kuwait's #1 fast-food recruiter</li>
            <li class="mb-2">✔ Pre-screened candidates who thrive under pressure</li>
            <li class="mb-2">✔ 75% faster hiring than industry average</li>
            <li class="mb-2">✔ Full compliance with MOH food safety regulations</li>
         </ul>
      </div>
   </div>
</div>
@endsection