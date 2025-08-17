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
                <h1 class="display-5 fw-bold">Staff Your Supermarket or Launch Your Retail Career in Kuwait</h1>
                <p class="lead mt-3">Connecting supermarket chains, hypermarkets, and local grocers with skilled retail professionals across Kuwait.</p>
                <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=1" class="btn btn-block-sm button-blue mt-2">Employers: Find Supermarket Staff Now</a>
                <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=2" class="btn btn-block-sm button-blue mt-2">Job Seekers: Browse Grocery Jobs</a>
            </div>
        </div>
    </div>
   <div class="container">
      
      <div class="py-5">
         <h2 class="section-title">Industry Overview – Supermarkets in Kuwait</h2>
         <p class="text-muted">Kuwait’s supermarket sector is rapidly modernizing, with hypermarkets, specialty grocers, and 24-hour convenience stores requiring:</p>
         <ul class="ml-2">
            <li class="mb-2">✔ Checkout supervisors for fast-paced environments</li>
            <li class="mb-2">✔ Fresh department specialists (butchers, bakers, produce)</li>
            <li class="mb-2">✔ Inventory controllers with ERP system experience</li>
            <li class="mb-2">✔ Bilingual customer service staff (Arabic/English/Urdu)</li>
         </ul>
         <p>Industry Trends:</p>
         <ul class="ml-2">
            <li class="mb-2">• 35% growth in organic and imported food sections</li>
            <li class="mb-2">• Expansion of click-and-collect services</li>
            <li class="mb-2">• Rising demand for halal-certified product experts</li>
            <li class="mb-2">• Government push for Kuwaiti staff in supervisory roles</li>
         </ul>
         <p class="text-muted">Hungry for Jobs bridges the gap between Kuwait’s grocery retailers and qualified talent.</p>
      </div>
      <div class="py-2">
         <h2 class="section-title mb-4">Who We Help</h2>
         <div class="row g-4">
            <div class="col-md-6">
               <div class="border rounded p-4 h-100">
                  <h3>For Employers</h3>
                  <p>We staff all supermarket departments:</p>
                  <ul class="ml-2">
                     <li class="mb-2">• Fresh Foods: Butchers, fishmongers, bakery staff</li>
                     <li class="mb-2">• Dry Goods: Stock controllers, aisle managers</li>
                     <li class="mb-2">• Customer Service: Cashiers, greeters, loyalty program staff</li>
                     <li class="mb-2">• Online Operations: Picking/packing teams, delivery drivers</li>
                  </ul>
                  <p class="text-muted">Our Specialty:</p>
                  <ul>
                     <li class="mb-2">✔ Pre-trained staff on POS systems</li>
                     <li class="mb-2">✔ Ramadan/peak season staffing solutions</li>
                     <li class="mb-2">✔ Food safety-certified candidates</li>
                  </ul>
                  <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=1" class="btn btn-block-sm button-blue mt-3">Post Supermarket Jobs</a>
               </div>
            </div>
            <div class="col-md-6 mt-2 mt-md-0">
               <div class="border rounded p-4 h-100">
                  <h3>For Job Seekers</h3>
                  <p>Access opportunities at:</p>
                    <ul class="list-unstyled">
                        <li class="mb-2">✅ International chains</li>
                        <li class="mb-2">✅ Local Kuwaiti supermarket brands</li>
                        <li class="mb-2">✅ Specialty organic grocers</li>
                        <li class="mb-2">✅ Online grocery platforms</li>
                    </ul>
                    <p>Career Benefits:</p>
                     <ul class="ml-2">
                        <li class="mb-2">• Department-specific training (bakery, butchery, etc.)</li>
                        <li class="mb-2">• Flexible shifts for students/part-timers</li>
                        <li class="mb-2">• Growth paths to store management</li>
                     </ul>
                  <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=2" class="btn btn-block-sm button-blue mt-3">Apply for Supermarket Jobs</a>
               </div>
            </div>
         </div>
      </div>
      <!-- Popular Roles -->
      <div class="py-5">
         <h2 class="section-title mb-4">Popular Supermarket Roles</h2>
         <div class="row g-3">
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🧊 Department Manager (Fresh/Frozen/Dry)</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🧾 Checkout Supervisor</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🔪 Butcher/Seafood Specialist</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🛒 Online Grocery Picker</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">📦 Inventory Control Specialist</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">💬 Customer Service Ambassador</div>
            </div>
         </div>
      </div>
      <div class="py-2">
         <h2 class="section-title mb-4">Why Choose Hungry for Jobs?</h2>
         <ul class="list-unstyled">
            <li class="mb-2">✔ Kuwait’s #1 grocery industry recruiter</li>
            <li class="mb-2">✔ Same-day placements for urgent staffing needs</li>
            <li class="mb-2">✔ Compliance with MOH food handling regulations</li>
            <li class="mb-2">✔ Specialized in multinational supermarket chains</li>
         </ul>
      </div>
   </div>
</div>
@endsection