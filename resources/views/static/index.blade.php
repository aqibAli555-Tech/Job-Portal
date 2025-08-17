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
                <h1 class="display-5 fw-bold">Find the Perfect Match for Hotels & Resorts Jobs in Kuwait</h1>
                <p class="lead mt-3">Whether you’re an employer seeking top talent or a job seeker looking for your next opportunity, Hungry for Jobs is your trusted partner in the Hotels & Resorts industry.</p>
                <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=1" class="btn btn-block-sm button-blue mt-2">Employers: Post Your Job Today</a>
                <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=2" class="btn btn-block-sm button-blue mt-2">Job Seekers: Find Your Dream Job Now</a>
            </div>
        </div>
    </div>
   <div class="container">
      
      <div class="py-5">
         <h2 class="section-title">Industry Overview – Hotels & Resorts in Kuwait</h2>
         <p class="text-muted">Kuwait’s hospitality sector is thriving, driven by the country’s growing tourism and leisure industry. From 3-star hotels to luxury 5-star resorts, the demand for skilled professionals is at an all-time high. With a focus on delivering exceptional guest experiences, hotels and resorts are seeking bilingual candidates, customer service experts, and specialized staff to meet evolving industry standards.</p>
         <p class="text-muted">As the Gulf region continues to attract global travelers, the need for qualified talent in housekeeping, front office, food and beverage, and concierge services is surging. Hungry for Jobs bridges this gap by connecting employers with pre-vetted candidates and helping job seekers find rewarding careers in Kuwait’s booming hospitality sector.</p>
      </div>
      <div class="py-2">
         <h2 class="section-title mb-4">Who We Help</h2>
         <div class="row g-4">
            <div class="col-md-6">
               <div class="border rounded p-4 h-100">
                  <h3>For Employers</h3>
                  <p>Hiring for hotels and resorts can be challenging, but Hungry for Jobs makes it simple. We specialize in providing fast, reliable staffing solutions for urgent, seasonal, and permanent hiring needs.</p>
                  <p>With a pre-vetted candidate database, we ensure you get access to skilled professionals across all hotel departments, including:</p>
                  <ul>
                     <li>✅ Food & Beverage</li>
                     <li>✅ Housekeeping</li>
                     <li>✅ Front Office</li>
                     <li>✅ Kitchen Staff</li>
                     <li>✅ Security</li>
                  </ul>
                  <p>Our platform is designed to save you time and effort, so you can focus on delivering exceptional guest experiences.</p>
                  <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=1" class="btn btn-block-sm button-blue mt-3">Register as an Employer Today</a>
               </div>
            </div>
            <div class="col-md-6 mt-2 mt-md-0">
               <div class="border rounded p-4 h-100">
                  <h3>For Job Seekers</h3>
                  <p>Looking for hotel jobs in Kuwait? <a target="_blank" href="{{ url('/') }}">Hungry for Jobs</a> is your go-to platform for finding opportunities in luxury hotels, boutique stays, and resorts.</p>
                  <p>Our platform is free, easy to use, and tailored to help you land your dream role in the hospitality industry. Whether you’re a housekeeper, chef, or front office professional, we connect you with top employers in Kuwait and the Gulf region.</p>
                  <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=2" class="btn btn-block-sm button-blue mt-3">Sign Up and Start Your Job Search</a>
               </div>
            </div>
         </div>
      </div>
      <!-- Popular Roles -->
      <div class="py-5">
         <h2 class="section-title mb-4">Popular Roles in Hotels & Resorts</h2>
         <p>Explore exciting opportunities in the Hotels & Resorts sector:</p>
         <div class="row g-3">
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🏨 Housekeeping Jobs in Kuwait</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">📞 Front Office Jobs in Hotels</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">👨‍🍳 Chef and Kitchen Staff Roles</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🎩 Concierge and Guest Services</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🍽️ Food & Beverage Staff</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🔒 Security and Maintenance</div>
            </div>
         </div>
         <br>
         <p>Find your next role with Hungry for Jobs, the leading hotel career platform in Kuwait.</p>
      </div>
      <div class="py-2">
         <h2 class="section-title mb-4">Why Choose Hungry for Jobs for Hotel Staffing?</h2>
         <p>With years of experience in hospitality recruitment, Hungry for Jobs is trusted by major hotel chains and boutique resorts across Kuwait and the GCC.</p>
         <p>Here’s why employers and job seekers choose us:</p>
         <ul class="list-unstyled">
            <li class="mb-2">✅ Extensive experience in hotel staffing</li>
            <li class="mb-2">✅ Fast placements with pre-screened candidates</li>
            <li class="mb-2">✅ Trusted by leading hotels and resorts in Kuwait</li>
            <li class="mb-2">✅ Full compliance with local labor laws</li>
            <li class="mb-2">✅ End-to-end recruitment assistance</li>
         </ul>
         <p>Whether you’re hiring or job hunting, Hungry for Jobs is your reliable partner in the Hotels & Resorts industry.</p>
      </div>
   </div>
</div>
@endsection