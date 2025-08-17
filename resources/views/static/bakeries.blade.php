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
                <h1 class="display-5 fw-bold">Find the Perfect Match for Bakery Jobs in Kuwait</h1>
                <p class="lead mt-3">Connecting skilled bakers, pastry chefs, and bakery staff with Kuwait's growing network of artisanal bakeries and commercial kitchens.</p>
                <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=1" class="btn btn-block-sm button-blue mt-2">Employers: Post Your Job Today</a>
                <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=2" class="btn btn-block-sm button-blue mt-2">Job Seekers: Find Your Dream Job Now</a>
            </div>
        </div>
    </div>
   <div class="container">
      <div class="py-5">
         <h2 class="section-title">Industry Overview - Bakeries in Kuwait</h2>
         <p class="text-muted">Kuwait's bakery sector is experiencing strong growth, with traditional Arabic bakeries, French patisseries, and industrial bread factories all expanding their operations. The demand for skilled baking professionals continues to rise due to:</p>
         <ul class="ml-2">
            <li class="mb-2">• Increasing consumer preference for artisanal and specialty breads</li>
            <li class="mb-2">• Growth of cafe-bakery hybrid concepts</li>
            <li class="mb-2">• Expansion of large-scale industrial bakeries</li>
         </ul>
         <p class="text-muted">Key hiring needs include:</p>
         <ul>
            <li class="mb-2">✔ Certified bakers with specialty skills</li>
            <li class="mb-2">✔ Decorators for custom cakes and pastries</li>
            <li class="mb-2">✔ Production staff for high-volume bakeries</li>
            <li class="mb-2">✔ Quality control specialists</li>
         </ul>
         <p class="text-muted">Hungry for Jobs helps bakery owners find qualified staff and connects baking professionals with rewarding career opportunities.</p>
      </div>
      <div class="py-2">
         <h2 class="section-title mb-4">Who We Help</h2>
         <div class="row g-4">
            <div class="col-md-6">
               <div class="border rounded p-4 h-100">
                  <h3>For Employers</h3>
                  <p>We simplify hiring for:</p>
                  <ul class="ml-2">
                     <li class="mb-2">• <strong>Artisanal Bakeries:</strong> Find skilled bakers and decorators</li>
                     <li class="mb-2">• <strong>Industrial Bakeries:</strong> Source production line staff</li>
                     <li class="mb-2">• <strong>Hotel Bakeries:</strong> Recruit pastry chefs and specialists</li>
                     <li class="mb-2">• <strong>Retail Bakeries:</strong> Hire sales and customer service staff</li>
                  </ul>
                  <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=1" class="btn btn-block-sm button-blue mt-3">Register as an Employer</a>
               </div>
            </div>
            <div class="col-md-6 mt-2 mt-md-0">
               <div class="border rounded p-4 h-100">
                  <h3>For Job Seekers</h3>
                  <p>Access opportunities at:</p>
                     <ul class="ml-2">
                        <li class="mb-2">• <strong>High-end patisseries</strong></li>
                        <li class="mb-2">• <strong>Traditional Arabic bakeries</strong></li>
                        <li class="mb-2">• <strong>Industrial baking facilities</strong></li>
                        <li class="mb-2">• <strong>Hotel bakery departments</strong></li>
                     </ul>
                  <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=2" class="btn btn-block-sm button-blue mt-3">Sign Up as a Job Seeker</a>
               </div>
            </div>
         </div>
      </div>
      <!-- Popular Roles -->
      <div class="py-5">
         <h2 class="section-title mb-4">Popular Roles in Bakeries</h2>
         <div class="row g-3">
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🧁 Head Baker/Pastry Chef</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🥖 Baking Assistant</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🎂 Cake Decorator</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🥐 Bakery Production Worker</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🧪 Quality Control Technician</div>
            </div>
         </div>
      </div>
      <div class="py-2">
         <h2 class="section-title mb-4">Why Choose Hungry for Jobs?</h2>
         <ul class="list-unstyled">
            <li class="mb-2">✔ Specialized in the bakery industry recruitment</li>
            <li class="mb-2">✔ Trusted by leading bakeries in Kuwait</li>
            <li class="mb-2">✔ Quick placements with qualified candidates</li>
            <li class="mb-2">✔ Compliance with food safety regulations</li>
         </ul>
      </div>
   </div>
</div>
@endsection