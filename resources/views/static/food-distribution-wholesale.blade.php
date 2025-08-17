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
                <h1 class="display-5 fw-bold">Staff Your Hospitality Supply Chain or Launch Your F&B Logistics Career</h1>
                <p class="lead mt-3">Connecting food service distributors, wholesale specialists, and supply chain professionals with Kuwait’s hotels, restaurants, and catering companies.</p>
                <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=1" class="btn btn-block-sm button-blue mt-2">Employers: Find F&B Distribution Staff</a>
                <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=2" class="btn btn-block-sm button-blue mt-2">Job Seekers: Explore Hospitality Supply Chain Jobs</a>
            </div>
        </div>
    </div>
   <div class="container">
      
      <div class="py-5">
         <h2 class="section-title">Industry Overview – F&B Distribution for Hospitality</h2>
         <p class="text-muted">Kuwait’s hotels, resorts, and restaurant chains rely on specialized distributors for:</p>
         <ul>
            <li class="mb-2">✔ Premium ingredient sourcing (meat, seafood, imported produce)</li>
            <li class="mb-2">✔ Just-in-time deliveries to commercial kitchens</li>
            <li class="mb-2">✔ Beverage wholesale (bar supplies, non-alcoholic mixes)</li>
            <li class="mb-2">✔ Customized packaging for banquet service</li>
         </ul>
         <p>Sector-Specific Trends:</p>
         <ul class="ml-2">
            <li class="mb-2">• Hotels demand sustainable and organic suppliers</li>
            <li class="mb-2">• Growth of cloud kitchen grocery partnerships</li>
            <li class="mb-2">• Rising need for Halal-certified logistics experts</li>
            <li class="mb-2">• Tech-driven inventory systems (e.g., hotel POS integrations)</li>
         </ul>
         <p class="text-muted">Hungry for Jobs is Kuwait’s leading connector between hospitality distributors and F&B talent.</p>
      </div>
      <div class="py-2">
         <h2 class="section-title mb-4">Who We Help</h2>
         <div class="row g-4">
            <div class="col-md-6">
               <div class="border rounded p-4 h-100">
                  <h3>For Hospitality Distributors & Wholesalers</h3>
                  <p>We staff roles critical to serving hotels/restaurants:</p>
                  <ul class="ml-2">
                     <li class="mb-2">• Hospitality Account Managers (B2B sales to chefs)</li>
                     <li class="mb-2">• Cold Chain Drivers for hotel seafood/meat deliveries</li>
                     <li class="mb-2">• Beverage Inventory Specialists (bar supply experts)</li>
                     <li class="mb-2">• Procurement Coordinators with hotel contract experience</li>
                  </ul>
                  <p class="text-muted">Why Us?</p>
                  <ul>
                     <li class="mb-2">✔ Candidates understand chef-grade quality standards</li>
                     <li class="mb-2">✔ Teams trained in hotel delivery protocols (early AM/late PM)</li>
                     <li class="mb-2">✔ Bilingual staff for international hotel chains</li>
                  </ul>
                  <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=1" class="btn btn-block-sm button-blue mt-3">Post Distribution Jobs</a>
               </div>
            </div>
            <div class="col-md-6 mt-2 mt-md-0">
               <div class="border rounded p-4 h-100">
                  <h3>For Job Seekers</h3>
                  <p>Access careers supplying Kuwait’s top hospitality venues:</p>
                    <ul class="list-unstyled">
                        <li class="mb-2">✅ Luxury hotel group distributors</li>
                        <li class="mb-2">✅ Restaurant franchise suppliers</li>
                        <li class="mb-2">✅ Specialty beverage wholesalers</li>
                        <li class="mb-2">✅ Catering company logistics partners</li>
                    </ul>
                    <p>Career Perks:</p>
                     <ul class="ml-2">
                        <li class="mb-2">• Learn hospitality procurement systems</li>
                        <li class="mb-2">• Exposure to global cuisine ingredients</li>
                        <li class="mb-2">• Paths to purchasing manager roles</li>
                     </ul>
                  <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=2" class="btn btn-block-sm button-blue mt-3">Apply for F&B Logistics Jobs</a>
               </div>
            </div>
         </div>
      </div>
      <!-- Popular Roles -->
      <div class="py-5">
         <h2 class="section-title mb-4">Key Hospitality Distribution Roles</h2>
         <div class="row g-3">
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">📦 Hotel Supply Chain Manager</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🧾 Restaurant Ingredient Buyer</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🥤 Beverage Distribution Supervisor</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🌡️ Cold Storage Quality Controller</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🚚 Banquet Supply Coordinator</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">📑 Import Documentation Specialist</div>
            </div>
         </div>
      </div>
      <div class="py-2">
         <h2 class="section-title mb-4">Why Choose Hungry for Jobs?</h2>
         <ul class="list-unstyled">
            <li class="mb-2">✔ Exclusive focus on hospitality supply chains</li>
            <li class="mb-2">✔ Pre-screened candidates with HACCP/hotel experience</li>
            <li class="mb-2">✔ 70% faster placements for urgent contract wins</li>
            <li class="mb-2">✔ Deep networks with Kuwait’s hotel purchasing departments</li>
         </ul>
      </div>
   </div>
</div>
@endsection