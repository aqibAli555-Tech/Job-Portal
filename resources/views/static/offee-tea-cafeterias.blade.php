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
                <h1 class="display-5 fw-bold">Specialty Coffee Talent & Café Jobs in Kuwait – Brewing Careers, Filling Roles</h1>
                <p class="lead mt-3">From baristas to café managers, we connect Kuwait’s thriving coffee shops and cafeterias with skilled professionals who elevate the customer experience.</p>
                <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=1" class="btn btn-block-sm button-blue mt-2">Employers: Find Baristas & Café Staff Fast</a>
                <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=2" class="btn btn-block-sm button-blue mt-2">Job Seekers: Explore Café Jobs Now</a>
            </div>
        </div>
    </div>
   <div class="container">
      
      <div class="py-5">
         <h2 class="section-title">Coffee & Tea Culture in Kuwait</h2>
         <p class="text-muted">Kuwait’s specialty coffee and tea market is booming, with artisanal cafés, international chains, and traditional Arabic tea houses driving demand for skilled professionals. The sector requires:</p>
         <ul>
            <li class="mb-2">✔ <strong>Certified baristas</strong> trained in espresso techniques & latte art</li>
            <li class="mb-2">✔ <strong>Bilingual staff</strong> (Arabic/English) for premium service</li>
            <li class="mb-2">✔ <strong>Quick hires</strong> for morning rushes and seasonal peaks</li>
            <li class="mb-2">✔ <strong>Café managers</strong> with F&B operations expertise</li>
         </ul>
         <p class="text-muted">Trends like third-wave coffee, specialty tea blends, and digital ordering are reshaping hiring needs. Hungry for Jobs bridges the gap between Kuwait’s café employers and passionate hospitality talent.</p>
      </div>
      <div class="py-2">
         <h2 class="section-title mb-4">Who We Help</h2>
         <div class="row g-4">
            <div class="col-md-6">
               <div class="border rounded p-4 h-100">
                  <h3>For Café Owners & Employers</h3>
                  <p>Staffing challenges hurting your café’s service? We provide:</p>
                  <ul class="ml-2">
                     <li class="mb-2">• <strong>Pre-trained baristas</strong> (manual brew, equipment maintenance)</li>
                     <li class="mb-2">• <strong>Cashiers & servers</strong> with POS system experience</li>
                     <li class="mb-2">• <strong>Bakery/pastry staff</strong> for companion food service</li>
                     <li class="mb-2">• <strong>Flexible hires:</strong> Full-time, part-time, peak-hour staff</li>
                  </ul>
                  <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=1" class="btn btn-block-sm button-blue mt-3">Post Your Café Job →</a>
               </div>
            </div>
            <div class="col-md-6 mt-2 mt-md-0">
               <div class="border rounded p-4 h-100">
                  <h3>For Job Seekers</h3>
                  <p>Launch your career in Kuwait’s dynamic café scene:</p>
                    <ul class="list-unstyled">
                        <li class="mb-2">✅ Work with premium coffee machines & specialty teas</li>
                        <li class="mb-2">✅ Opportunities in:</li>
                            <ul class="ml-2">
                                <li class="mb-2">• Specialty coffee houses</li>
                                <li class="mb-2">• Hotel cafés & bakery-café hybrids</li>
                                <li class="mb-2">• Arabic tea house attendants</li>
                            </ul>
                        <li class="mb-2">✅ Career growth to shift supervisor or café manager</li>
                    </ul>
                  <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=2" class="btn btn-block-sm button-blue mt-3">Upload Resume for Café Jobs →</a>
               </div>
            </div>
         </div>
      </div>
      <!-- Popular Roles -->
      <div class="py-5">
         <h2 class="section-title mb-4">Popular Café Roles</h2>
         <div class="row g-3">
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">☕ <strong>Barista Jobs in Kuwait</strong> (Espresso, Cold Brew, Latte Art)</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">💵 <strong>Café Cashier & Customer Service Roles</strong></div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🧁 <strong>Pastry Chef & Bakery Staff Positions</strong></div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">👨‍💼 <strong>Café Supervisor & Manager Openings</strong></div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">☕ <strong>Tea Specialists & Arabic Coffee Servers</strong></div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🥗 <strong>Cafeteria Assistants (Corporate/Educational)</strong></div>
            </div>
         </div>
         <br>
         <p>Barista certification? Highlight it in your profile!</p>
      </div>
      <div class="py-2">
         <h2 class="section-title mb-4">Why Choose Hungry for Jobs?</h2>
         <ul class="list-unstyled">
            <li class="mb-2">✔ <strong>Kuwait’s #1 Café Staffing Partner</strong></li>
            <li class="mb-2">✔ <strong>Pre-screened candidates</strong> with beverage service tests</li>
            <li class="mb-2">✔ <strong>70% faster hiring</strong> vs. traditional methods</li>
            <li class="mb-2">✔ <strong>Compliance with Kuwait labor laws</strong></li>
            <li class="mb-2">✔ <strong>Dedicated account managers</strong> for high-volume chains</li>
         </ul>
      </div>
   </div>
</div>
@endsection