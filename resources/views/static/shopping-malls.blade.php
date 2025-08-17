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
                <h1 class="display-5 fw-bold">Staff Your Mall or Launch Your Retail Management Career in Kuwait</h1>
                <p class="lead mt-3">Connecting mall operators, retailers, and customer service professionals with Kuwait's premier shopping destinations.</p>
                <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=1" class="btn btn-block-sm button-blue mt-2">Employers: Find Mall Talent Now</a>
                <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=2" class="btn btn-block-sm button-blue mt-2">Job Seekers: Browse Mall Careers</a>
            </div>
        </div>
    </div>
   <div class="container">
      
      <div class="py-5">
         <h2 class="section-title">Industry Overview – Shopping Malls in Kuwait</h2>
         <p class="text-muted">Kuwait's mall industry dominates regional retail, with super-regional malls, luxury boutiques, and entertainment complexes requiring:</p>
         <ul class="ml-2">
            <li class="mb-2">✔ Mall operations managers for multi-brand environments</li>
            <li class="mb-2">✔ Retail leasing specialists with brand negotiation skills</li>
            <li class="mb-2">✔ Customer experience ambassadors (Arabic/English)</li>
            <li class="mb-2">✔ Facility technicians for premium maintenance</li>
         </ul>
         <p>Market Trends:</p>
         <ul class="ml-2">
            <li class="mb-2">• 30% growth in experiential retail</li>
            <li class="mb-2">• High demand for Saudi-market savvy staff</li>
            <li class="mb-2">• Expansion of mall residency programs for global retailers</li>
            <li class="mb-2">• Rising need for crisis management-trained security</li>
         </ul>
         <p class="text-muted">Hungry for Jobs is Kuwait's leading mall industry recruitment partner.</p>
      </div>
      <div class="py-2">
         <h2 class="section-title mb-4">Who We Help</h2>
         <div class="row g-4">
            <div class="col-md-6">
               <div class="border rounded p-4 h-100">
                  <h3>For Employers</h3>
                  <p>We staff all mall functions:</p>
                  <ul class="ml-2">
                     <li class="mb-2">• Mall Management: Operations directors, leasing managers</li>
                     <li class="mb-2">• Anchor Stores: Department store supervisors</li>
                     <li class="mb-2">• Luxury Brands: Concierge and VIP hosts</li>
                     <li class="mb-2">• Facility Teams: HVAC specialists, maintenance engineers</li>
                  </ul>
                  <p class="text-muted">Our Advantage:</p>
                  <ul>
                     <li class="mb-2">✔ Candidates experienced in GCC mall standards</li>
                     <li class="mb-2">✔ Ramadan/National Day seasonal staffing experts</li>
                     <li class="mb-2">✔ Mall-certified customer service professionals</li>
                  </ul>
                  <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=1" class="btn btn-block-sm button-blue mt-3">Post Mall Jobs</a>
               </div>
            </div>
            <div class="col-md-6 mt-2 mt-md-0">
               <div class="border rounded p-4 h-100">
                  <h3>For Job Seekers</h3>
                  <p>Access opportunities at:</p>
                    <ul class="list-unstyled">
                        <li class="mb-2">✅ A+ luxury malls (The Avenues, 360 Mall)</li>
                        <li class="mb-2">✅ International flagship stores</li>
                        <li class="mb-2">✅ Entertainment attractions (Snow Park, KidZania)</li>
                        <li class="mb-2">✅ Mall management offices</li>
                    </ul>
                    <p>Career Perks:</p>
                     <ul class="ml-2">
                        <li class="mb-2">• Cross-training across retail/hospitality/operations</li>
                        <li class="mb-2">• Exposure to global retail brands</li>
                        <li class="mb-2">• Paths to mall management executive roles</li>
                     </ul>
                  <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=2" class="btn btn-block-sm button-blue mt-3">Apply for Mall Jobs</a>
               </div>
            </div>
         </div>
      </div>
      <!-- Popular Roles -->
      <div class="py-5">
         <h2 class="section-title mb-4">Popular Mall Roles</h2>
         <div class="row g-3">
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🏬 Mall Operations Manager</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">📄 Retail Leasing Agent</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🤝 Customer Experience Ambassador</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🎩 Luxury Concierge</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🛠️ Facility Maintenance Supervisor</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🖼️ Visual Merchandising Coordinator</div>
            </div>
         </div>
      </div>
      <div class="py-2">
         <h2 class="section-title mb-4">Why Choose Hungry for Jobs?</h2>
         <ul class="list-unstyled">
            <li class="mb-2">✔ Preferred recruiter for 15+ Kuwaiti malls</li>
            <li class="mb-2">✔ Pre-screened candidates who thrive under pressure</li>
            <li class="mb-2">✔ Bilingual candidate pools (Arabic/English/Urdu)</li>
            <li class="mb-2">✔ VIP event staffing experts</li>
         </ul>
      </div>
   </div>
</div>
@endsection