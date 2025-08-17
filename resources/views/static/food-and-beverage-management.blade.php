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
                <h1 class="display-5 fw-bold">Build Your F&B Management Dream Team in Kuwait</h1>
                <p class="lead mt-3">Connecting hospitality professionals with Kuwait's leading food service management companies operating hotels, resorts, and corporate cafeterias.</p>
                <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=1" class="btn btn-block-sm button-blue mt-2">Employers: Find F&B Management Talent</a>
                <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=2" class="btn btn-block-sm button-blue mt-2">Job Seekers: Explore Hospitality Careers</a>
            </div>
        </div>
    </div>
   <div class="container">
      
      <div class="py-5">
         <h2 class="section-title">Industry Overview – F&B Management in Kuwait</h2>
         <p class="text-muted">Kuwait's food service management sector is expanding rapidly, with international hotel groups, catering giants, and facility management companies requiring skilled professionals to oversee:</p>
         <ul>
            <li class="mb-2">✔ Luxury hotel restaurants and banquet operations</li>
            <li class="mb-2">✔ Corporate dining facilities for oil/gas companies</li>
            <li class="mb-2">✔ Hospital and educational institution cafeterias</li>
            <li class="mb-2">✔ Government contract catering services</li>
         </ul>
         <p>Industry Drivers:</p>
         <ul class="ml-2">
            <li class="mb-2">• Growing outsourcing trend among Kuwaiti businesses</li>
            <li class="mb-2">• Rising demand for certified hygiene managers</li>
            <li class="mb-2">• Expansion of 5-star hotel F&B outlets</li>
            <li class="mb-2">• Need for bilingual supervisors (Arabic/English)</li>
         </ul>
         <p class="text-muted">Hungry for Jobs is Kuwait's premier recruitment partner for F&B management talent.</p>
      </div>
      <div class="py-2">
         <h2 class="section-title mb-4">Who We Help</h2>
         <div class="row g-4">
            <div class="col-md-6">
               <div class="border rounded p-4 h-100">
                  <h3>For Management Companies</h3>
                  <p>We staff all levels of F&B operations:</p>
                  <ul class="ml-2">
                     <li class="mb-2">• Executive Chefs for multi-outlet operations</li>
                     <li class="mb-2">• Food Service Directors with P&L experience</li>
                     <li class="mb-2">• Hospitality graduates for management trainee programs</li>
                     <li class="mb-2">• HACCP-certified hygiene auditors</li>
                  </ul>
                  <p class="text-muted">Our Value:</p>
                  <ul>
                     <li class="mb-2">✔ Candidates trained in international F&B standards</li>
                     <li class="mb-2">✔ Professionals experienced in Kuwaiti labor laws</li>
                     <li class="mb-2">✔ Fast placements for new contract launches</li>
                  </ul>
                  <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=1" class="btn btn-block-sm button-blue mt-3">Request Staffing Solutions</a>
               </div>
            </div>
            <div class="col-md-6 mt-2 mt-md-0">
               <div class="border rounded p-4 h-100">
                  <h3>For Job Seekers</h3>
                  <p>Access prestigious opportunities with:</p>
                    <ul class="list-unstyled">
                        <li class="mb-2">✅ Global hotel management groups</li>
                        <li class="mb-2">✅ Corporate catering leaders</li>
                        <li class="mb-2">✅ Healthcare food service providers</li>
                        <li class="mb-2">✅ Government contract operators</li>
                    </ul>
                    <p>Career Advantages:</p>
                     <ul class="ml-2">
                        <li class="mb-2">• Structured career paths to F&B Director roles</li>
                        <li class="mb-2">• Cross-training in multiple cuisine types</li>
                        <li class="mb-2">• Competitive expat packages for senior roles</li>
                     </ul>
                  <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=2" class="btn btn-block-sm button-blue mt-3">Apply for F&B Management Jobs</a>
               </div>
            </div>
         </div>
      </div>
      <!-- Popular Roles -->
      <div class="py-5">
         <h2 class="section-title mb-4">Key Positions</h2>
         <div class="row g-3">
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">👨‍💼 <strong>F&B Operations Manager</strong></div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">👨‍🍳 <strong>Executive Chef (Corporate Accounts)</strong></div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🥂 <strong>Banquet Service Director</strong></div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">📈 <strong>Catering Sales Manager</strong></div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🥗 <strong>Nutrition Planning Specialist</strong></div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🧼 <strong>Hygiene Compliance Officer</strong></div>
            </div>
         </div>
      </div>
      <div class="py-2">
         <h2 class="section-title mb-4">Why Choose Hungry for Jobs?</h2>
         <ul class="list-unstyled">
            <li class="mb-2">✔ Exclusive partnerships with 25+ management companies</li>
            <li class="mb-2">✔ Specialized in bulk hiring for new contract wins</li>
            <li class="mb-2">✔ Rigorous screening for certification compliance</li>
            <li class="mb-2">✔ Deep understanding of Kuwaiti F&B regulations</li>
         </ul>
      </div>
   </div>
</div>
@endsection