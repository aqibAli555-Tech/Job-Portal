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
                <h1 class="display-5 fw-bold">Sweeten Your Team or Launch Your Dessert Career in Kuwait</h1>
                <p class="lead mt-3">Connecting talented pastry chefs, bakers, and dessert specialists with Kuwait's thriving patisseries, ice cream parlors, and sweet shops.</p>
                <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=1" class="btn btn-block-sm button-blue mt-2">Employers: Find Dessert Experts Now</a>
                <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=2" class="btn btn-block-sm button-blue mt-2">Job Seekers: Browse Sweet Job Opportunities</a>
            </div>
        </div>
    </div>
   <div class="container">
      
      <div class="py-5">
         <h2 class="section-title">Industry Overview – Dessert Shops in Kuwait</h2>
         <p class="text-muted">Kuwait's dessert industry is experiencing a sugar rush, with artisanal patisseries, luxury ice cream brands, and traditional Arabic sweet shops expanding across the country. The sector demands:</p>
         <ul>
            <li class="mb-2">✔ <strong>Skilled pastry chefs</strong> specializing in French/Arabic fusion desserts</li>
            <li class="mb-2">✔ <strong>Creative decorators</strong> for custom cakes and dessert displays</li>
            <li class="mb-2">✔ <strong>Gelato and ice cream artisans</strong> with unique flavor expertise</li>
            <li class="mb-2">✔ <strong>Counter staff</strong> with product knowledge and customer service skills</li>
         </ul>
         <p>Market Trends:</p>
         <ul class="ml-2">
            <li class="mb-2">• Rising popularity of Instagrammable desserts (3D cakes, liquid nitrogen ice cream)</li>
            <li class="mb-2">• Growing demand for halal-certified and premium ingredients</li>
            <li class="mb-2">• Expansion of 24-hour dessert delivery services</li>
         </ul>
         <p class="text-muted">Hungry for Jobs is Kuwait's leading platform connecting dessert businesses with sweet talent.</p>
      </div>
      <div class="py-2">
         <h2 class="section-title mb-4">Who We Help</h2>
         <div class="row g-4">
            <div class="col-md-6">
               <div class="border rounded p-4 h-100">
                  <h3>For Employers</h3>
                  <p>We help:</p>
                  <ul class="ml-2">
                     <li class="mb-2">• <strong>High-End Patisseries:</strong> Find pastry chefs and decorators</li>
                     <li class="mb-2">• <strong>Ice Cream Chains:</strong> Source flavor specialists and scoopers</li>
                     <li class="mb-2">• <strong>Traditional Sweet Shops:</strong> Hire kunafa and baklava experts</li>
                     <li class="mb-2">• <strong>Dessert Cafés:</strong> Recruit baristas with dessert pairing knowledge</li>
                  </ul>
                  <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=1" class="btn btn-block-sm button-blue mt-3">Post Your Dessert Job</a>
               </div>
            </div>
            <div class="col-md-6 mt-2 mt-md-0">
               <div class="border rounded p-4 h-100">
                  <h3>For Job Seekers</h3>
                  <p>Access opportunities at:</p>
                    <ul class="list-unstyled">
                        <li class="mb-2">✅ <strong>Luxury hotel pastry departments</strong></li>
                        <li class="mb-2">✅ <strong>Trendy dessert boutiques</strong></li>
                        <li class="mb-2">✅ <strong>Gourmet ice cream brands</strong></li>
                        <li class="mb-2">✅ <strong>Traditional Arabic sweet shops</strong></li>
                    </ul>
                    <p>Career Perks:</p>
                     <ul class="ml-2">
                        <li class="mb-2">• Learn from international pastry chefs</li>
                        <li class="mb-2">• Flexible shifts (evening/night positions available)</li>
                        <li class="mb-2">• Creative work environment</li>
                     </ul>
                  <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=2" class="btn btn-block-sm button-blue mt-3">Apply for Dessert Jobs</a>
               </div>
            </div>
         </div>
      </div>
      <!-- Popular Roles -->
      <div class="py-5">
         <h2 class="section-title mb-4">Popular Roles</h2>
         <div class="row g-3">
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🧁 Head Pastry Chef</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🎂 Cake Decorator</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🍨 Ice Cream/Gelato Maker</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🧾 Dessert Counter Staff</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🥖 Bakery Assistant</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🔍 Quality Control Specialist</div>
            </div>
         </div>
      </div>
      <div class="py-2">
         <h2 class="section-title mb-4">Why Choose Hungry for Jobs?</h2>
         <ul class="list-unstyled">
            <li class="mb-2">✔ <strong>Specialized in the dessert industry recruitment</strong></li>
            <li class="mb-2">✔ <strong>Trusted by Kuwait's top sweet brands</strong></li>
            <li class="mb-2">✔ <strong>Quick placements -</strong> satisfy your staffing cravings fast</li>
            <li class="mb-2">✔ <strong>Compliance with food safety standards</strong></li>
         </ul>
      </div>
   </div>
</div>
@endsection