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
                <h1 class="display-5 fw-bold">Find the Perfect Match for Deli Jobs in Kuwait</h1>
                <p class="lead mt-3">Connecting skilled deli staff with Kuwait's growing network of gourmet delicatessens, sandwich shops, and specialty food stores.</p>
                <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=1" class="btn btn-block-sm button-blue mt-2">Employers: Post Your Job Today</a>
                <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=2" class="btn btn-block-sm button-blue mt-2">Job Seekers: Find Your Dream Job Now</a>
            </div>
        </div>
    </div>
   <div class="container">
      
      <div class="py-5">
         <h2 class="section-title">Industry Overview – Delis in Kuwait</h2>
         <p>Kuwait's deli culture is undergoing a delicious revolution. Where once simple sandwich counters sufficed, today's discerning customers demand:</p>
            <ul class="ml-2">
               <li class="mb-2">• Artisan charcuterie boards featuring premium imported meats</li>
               <li class="mb-2">• Gourmet sandwich creations with specialty breads and condiments</li>
               <li class="mb-2">• Interactive counters where skilled staff educate customers about origins and pairings</li>
            </ul>
         <p class="text-muted">This culinary shift has created exciting opportunities for:</p>
         <ul>
            <li class="mb-2">✔ Deli artisans passionate about quality meats and cheeses</li>
            <li class="mb-2">✔ Sandwich crafters who treat each order like a signature dish</li>
            <li class="mb-2">✔ Food ambassadors who can describe the difference between Serrano and Prosciutto</li>
         </ul>
         <p class="text-muted">Hungry for Jobs connects these specialized food professionals with Kuwait's most innovative delis and gourmet markets.</p>
      </div>
      <div class="py-2">
         <h2 class="section-title mb-4">Who We Help</h2>
         <div class="row g-4">
            <div class="col-md-6">
               <div class="border rounded p-4 h-100">
                  <h3>For Employers</h3>
                  <p>We simplify hiring for:</p>
                  <ul class="ml-2">
                     <li class="mb-2">• <strong>Gourmet Delicatessens:</strong> Find knowledgeable counter staff</li>
                     <li class="mb-2">• <strong>Supermarket Deli Counters:</strong> Source trained food handlers</li>
                     <li class="mb-2">• <strong>Specialty Sandwich Shops:</strong> Recruit efficient prep staff</li>
                     <li class="mb-2">• <strong>Imported Food Stores:</strong> Hire product experts</li>
                  </ul>
                  <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=1" class="btn btn-block-sm button-blue mt-3">Register as an Employer</a>
               </div>
            </div>
            <div class="col-md-6 mt-2 mt-md-0">
               <div class="border rounded p-4 h-100">
                  <h3>For Job Seekers</h3>
                  <p>Access opportunities at:</p>
                     <ul class="ml-2">
                        <li class="mb-2">• High-end delicatessens</li>
                        <li class="mb-2">• International sandwich chains</li>
                        <li class="mb-2">• Supermarket deli departments</li>
                        <li class="mb-2">• Specialty food retailers</li>
                     </ul>
                  <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=2" class="btn btn-block-sm button-blue mt-3">Sign Up as a Job Seeker</a>
               </div>
            </div>
         </div>
      </div>
      <!-- Popular Roles -->
      <div class="py-5">
         <h2 class="section-title mb-4">Popular Roles in Delis</h2>
         <div class="row g-3">
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🥪 Deli Counter Staff</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🥙 Sandwich Artist/Prep Cook</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🧀 Cheesemonger</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🔪 Meat Slicer Specialist</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">👨‍💼 Deli Department Manager</div>
            </div>
         </div>
      </div>
      <div class="py-2">
         <h2 class="section-title mb-4">Why Choose Hungry for Jobs?</h2>
         <ul class="list-unstyled">
            <li class="mb-2">✔ Specialized in deli and specialty food recruitment</li>
            <li class="mb-2">✔ Trusted by leading delis in Kuwait</li>
            <li class="mb-2">✔ Quick placements with qualified candidates</li>
            <li class="mb-2">✔ Compliance with food handling regulations</li>
         </ul>
      </div>
   </div>
</div>
@endsection