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
                <h1 class="display-5 fw-bold">Find the Perfect Match for Salon & Spa Jobs in Kuwait</h1>
                <p class="lead mt-3">Whether you're an employer seeking beauty professionals or a job seeker looking for salon careers, Hungry for Jobs connects talent with Kuwait's top spas and salons.</p>
                <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=1" class="btn btn-block-sm button-blue mt-2">Employers: Post Your Job Today</a>
                <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=2" class="btn btn-block-sm button-blue mt-2">Job Seekers: Find Your Dream Job Now</a>
            </div>
        </div>
    </div>
   <div class="container">
      
      <div class="py-5">
         <h2 class="section-title">Industry Overview – Salon & Spa in Kuwait</h2>
         <p class="text-muted">Kuwait’s beauty and wellness industry is thriving, with luxury salons, medical spas, and neighborhood nail bars serving a growing clientele of locals and expats. The demand for skilled hairstylists, estheticians, nail technicians, and spa therapists continues to rise, fueled by Kuwait’s strong beauty culture and increasing disposable income.</p>
         <p class="text-muted">Trends like organic skincare, microblading, and men’s grooming services are transforming the market, creating demand for:</p>
         <ul>
            <li class="mb-2">✔ Licensed professionals with international certifications</li>
            <li class="mb-2">✔ Bilingual staff (Arabic/English) for high-end clientele</li>
            <li class="mb-2">✔ Specialized technicians for lash extensions, nail art, and non-invasive treatments</li>
            <li class="mb-2">✔ Flexible part-time talent for peak hours and weekends</li>
         </ul>
         <p class="text-muted">Hungry for Jobs connects top salons and spas with qualified beauty professionals across Kuwait.</p>
      </div>
      <div class="py-2">
         <h2 class="section-title mb-4">Who We Help</h2>
         <div class="row g-4">
            <div class="col-md-6">
               <div class="border rounded p-4 h-100">
                  <h3>For Employers</h3>
                  <p>We simplify hiring for:</p>
                  <ul class="ml-2">
                     <li class="mb-2">• <strong>Hair Salons:</strong> Stylists, colorists, shampoo technicians</li>
                     <li class="mb-2">• <strong>Spas:</strong> Massage therapists, facial specialists</li>
                     <li class="mb-2">• <strong>Nail Studios:</strong> Manicurists, pedicurists</li>
                     <li class="mb-2">• <strong>Medical Spas:</strong> Laser technicians, dermatology assistants</li>
                  </ul>
                  <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=1" class="btn btn-block-sm button-blue mt-3">Register as an Employer</a>
               </div>
            </div>
            <div class="col-md-6 mt-2 mt-md-0">
               <div class="border rounded p-4 h-100">
                  <h3>For Job Seekers</h3>
                  <p>Access opportunities in:</p>
                     <ul class="ml-2">
                        <li class="mb-2">• <strong>Luxury hotel spas</strong></li>
                        <li class="mb-2">• <strong>High-end beauty salons</strong></li>
                        <li class="mb-2">• <strong>Barber shops</strong></li>
                        <li class="mb-2">• <strong>Nail boutiques</strong></li>
                     </ul>
                  <a target="_blank" href="https://hungryforjobs.com/register?user_type_id=2" class="btn btn-block-sm button-blue mt-3">Sign Up as a Job Seeker</a>
               </div>
            </div>
         </div>
      </div>
      <!-- Popular Roles -->
      <div class="py-5">
         <h2 class="section-title mb-4">Popular Roles in Salon & Spa</h2>
         <div class="row g-3">
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">💇‍♀️ Hairstylist & Colorist Jobs</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">💅 Nail Technician Roles</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">💆‍♀️ Spa Therapist Positions</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">🧖‍♀️ Salon Manager Openings</div>
            </div>
            <div class="col-md-4">
               <div class="border rounded p-3 h-100">💄 Beauty Consultant Jobs</div>
            </div>
         </div>
      </div>
      <div class="py-2">
         <h2 class="section-title mb-4">Why Choose Hungry for Jobs?</h2>
         <ul class="list-unstyled">
            <li class="mb-2">✔ Specialized in the <strong>beauty industry recruitment</strong></li>
            <li class="mb-2">✔ Trusted by <strong>leading salons</strong> in Kuwait</li>
            <li class="mb-2">✔ Fast placements with <strong>qualified candidates</strong></li>
            <li class="mb-2">✔ Compliance with <strong>local regulations</strong></li>
         </ul>
      </div>
   </div>
</div>
@endsection