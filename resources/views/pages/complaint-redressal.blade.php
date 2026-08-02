@extends('layouts.app')

@section('title', 'Complaint Redressal Policy - Nazaara Circle')

@section('content')
<style>
    .section-title {
        font-size: 32px;
        font-weight: 800;
        margin: 15px 0 25px;
        color: #000;
        text-transform: uppercase;
        letter-spacing: -0.5px;
        font-family: 'Poppins', sans-serif;
        position: relative;
        display: inline-block;
    }
    .section-title::after {
        content: '';
        display: block;
        width: 60%;
        height: 4px;
        background: #E50914;
        margin-top: 5px;
    }
    html.dark .section-title, .dark .section-title {
        color: #fff;
    }
    .content-card {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 25px;
        margin-bottom: 30px;
    }
    html.dark .content-card, .dark .content-card {
        background: #1a1a1a;
        border-color: #333;
    }
    .who-we-are {
        background-color: #000;
        color: #fff;
        padding: 40px;
        text-align: center;
        border-radius: 8px;
        margin-top: 50px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }
    .who-we-are::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #E50914, #ff4d4d);
    }
    .who-title {
        font-size: 28px;
        font-weight: 800;
        text-transform: uppercase;
        margin-bottom: 20px;
        font-family: 'Poppins', sans-serif;
        letter-spacing: 1px;
        color: #fff;
    }
    .who-text {
        font-size: 16px;
        line-height: 1.6;
        max-width: 800px;
        margin: 0 auto 30px;
        color: #ccc;
    }
    .who-btn {
        display: inline-block;
        padding: 12px 30px;
        background-color: #E50914;
        color: #fff;
        font-weight: 700;
        text-decoration: none;
        border-radius: 4px;
        text-transform: uppercase;
        transition: background-color 0.3s;
    }
    .who-btn:hover {
        background-color: #ff0f1f;
        color: #fff;
    }
</style>
<div class="w-full px-4 sm:px-6 lg:px-8 xl:px-12 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:!text-white mb-8" style="font-family: 'Poppins', sans-serif; font-weight: 700;">
            Complaint Redressal Policy
        </h1>

        <div class="bg-white dark:!bg-bg-card border border-gray-200 dark:!border-border-secondary rounded-lg p-6 md:p-8 space-y-8" style="font-family: 'Poppins', sans-serif;">
            <!-- Introduction -->
            <section>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4 text-lg" style="font-weight: 400;">
                    At <strong class="text-accent">Nazaara Circle</strong>, we are committed to addressing and resolving complaints from our readers, contributors, and users in a fair, timely, and transparent manner. This Complaint Redressal Policy outlines our process for handling complaints and ensuring satisfactory resolution.
                </p>
            </section>

            <!-- Types of Complaints -->
            <section class="border-t border-gray-200 dark:!border-border-secondary pt-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-4" style="font-weight: 700;">
                    Types of Complaints We Handle
                </h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    We accept and address the following types of complaints:
                </p>
                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-accent/10 rounded-full flex items-center justify-center mt-1">
                            <span class="text-accent font-bold">1</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:!text-white mb-2" style="font-weight: 600;">Content-Related Complaints</h3>
                            <ul class="list-disc list-inside text-gray-700 dark:!text-text-secondary text-sm space-y-1 ml-4" style="font-weight: 400;">
                                <li>Factual errors or inaccuracies in articles</li>
                                <li>Plagiarism or copyright infringement</li>
                                <li>Misleading or false information</li>
                                <li>Inappropriate or offensive content</li>
                                <li>Bias or unfair representation</li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-accent/10 rounded-full flex items-center justify-center mt-1">
                            <span class="text-accent font-bold">2</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:!text-white mb-2" style="font-weight: 600;">Editorial Complaints</h3>
                            <ul class="list-disc list-inside text-gray-700 dark:!text-text-secondary text-sm space-y-1 ml-4" style="font-weight: 400;">
                                <li>Violation of editorial standards</li>
                                <li>Ethical concerns about content</li>
                                <li>Editorial bias or unfair treatment</li>
                                <li>Failure to correct errors</li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-accent/10 rounded-full flex items-center justify-center mt-1">
                            <span class="text-accent font-bold">3</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:!text-white mb-2" style="font-weight: 600;">User Experience Complaints</h3>
                            <ul class="list-disc list-inside text-gray-700 dark:!text-text-secondary text-sm space-y-1 ml-4" style="font-weight: 400;">
                                <li>Website functionality issues</li>
                                <li>Account-related problems</li>
                                <li>Comment moderation concerns</li>
                                <li>Accessibility issues</li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-accent/10 rounded-full flex items-center justify-center mt-1">
                            <span class="text-accent font-bold">4</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:!text-white mb-2" style="font-weight: 600;">Privacy & Data Complaints</h3>
                            <ul class="list-disc list-inside text-gray-700 dark:!text-text-secondary text-sm space-y-1 ml-4" style="font-weight: 400;">
                                <li>Privacy policy violations</li>
                                <li>Data handling concerns</li>
                                <li>Unauthorized use of personal information</li>
                            </ul>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-8 h-8 bg-accent/10 rounded-full flex items-center justify-center mt-1">
                            <span class="text-accent font-bold">5</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:!text-white mb-2" style="font-weight: 600;">Service-Related Complaints</h3>
                            <ul class="list-disc list-inside text-gray-700 dark:!text-text-secondary text-sm space-y-1 ml-4" style="font-weight: 400;">
                                <li>Advertising or sponsorship concerns</li>
                                <li>Partnership issues</li>
                                <li>Technical support problems</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- How to File a Complaint -->
            <section class="border-t border-gray-200 dark:!border-border-secondary pt-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-4" style="font-weight: 700;">
                    How to File a Complaint
                </h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    To file a complaint, please provide the following information:
                </p>
                <div class="bg-gray-50 dark:!bg-bg-card-hover rounded-lg p-6">
                    <h3 class="font-semibold text-gray-900 dark:!text-white mb-3" style="font-weight: 600;">Required Information:</h3>
                    <ul class="list-disc list-inside text-gray-700 dark:!text-text-secondary space-y-2 ml-4" style="font-weight: 400;">
                        <li><strong>Your Name:</strong> Full name and contact information</li>
                        <li><strong>Email Address:</strong> Valid email for communication</li>
                        <li><strong>Complaint Category:</strong> Type of complaint (from list above)</li>
                        <li><strong>Detailed Description:</strong> Clear explanation of the issue</li>
                        <li><strong>Supporting Evidence:</strong> Links, screenshots, or documents</li>
                        <li><strong>Relevant URLs:</strong> Links to specific articles or pages</li>
                        <li><strong>Desired Resolution:</strong> What outcome you are seeking</li>
                    </ul>
                </div>
                <div class="mt-6">
                    <h3 class="font-semibold text-gray-900 dark:!text-white mb-3" style="font-weight: 600;">Ways to Submit a Complaint:</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="border border-gray-200 dark:!border-border-primary rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 dark:!text-white mb-2" style="font-weight: 600;">Email</h4>
                            <p class="text-gray-700 dark:!text-text-secondary text-sm mb-3" style="font-weight: 400;">
                                Send your complaint to:
                            </p>
                            <a href="mailto:muhamamdmaaz65@gmail.com?subject=Complaint" class="text-accent hover:text-accent-light underline font-semibold" style="font-weight: 600;">muhamamdmaaz65@gmail.com</a>
                            <p class="text-gray-600 dark:!text-text-secondary text-xs mt-2" style="font-weight: 400;">
                                Please include "Complaint" in the subject line
                            </p>
                        </div>
                        <div class="border border-gray-200 dark:!border-border-primary rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 dark:!text-white mb-2" style="font-weight: 600;">Contact Form</h4>
                            <p class="text-gray-700 dark:!text-text-secondary text-sm mb-3" style="font-weight: 400;">
                                Use our contact form:
                            </p>
                            <a href="{{ route('contact') }}?subject=Complaint" class="text-accent hover:text-accent-light underline font-semibold" style="font-weight: 600;">Submit Complaint</a>
                            <p class="text-gray-600 dark:!text-text-secondary text-xs mt-2" style="font-weight: 400;">
                                Select "Complaint" as the subject
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Complaint Resolution Process -->
            <section class="border-t border-gray-200 dark:!border-border-secondary pt-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-4" style="font-weight: 700;">
                    Complaint Resolution Process
                </h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    Our complaint resolution process follows these steps:
                </p>
                <ol class="list-decimal list-inside text-gray-700 dark:!text-text-secondary space-y-4 ml-4" style="font-weight: 400;">
                    <li>
                        <strong>Receipt & Acknowledgment:</strong> We acknowledge receipt of your complaint within 2 business days via email. You will receive a unique complaint reference number for tracking.
                    </li>
                    <li>
                        <strong>Initial Review:</strong> Our team reviews your complaint to understand the issue and determine the appropriate department for handling it.
                    </li>
                    <li>
                        <strong>Investigation:</strong> We conduct a thorough investigation, which may include:
                        <ul class="list-disc list-inside ml-6 mt-2 space-y-1">
                            <li>Reviewing relevant content and documentation</li>
                            <li>Consulting with editorial team or subject matter experts</li>
                            <li>Verifying facts and checking sources</li>
                            <li>Examining our policies and procedures</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Resolution:</strong> Based on our investigation, we determine the appropriate resolution, which may include:
                        <ul class="list-disc list-inside ml-6 mt-2 space-y-1">
                            <li>Correction of factual errors</li>
                            <li>Content updates or removal</li>
                            <li>Apology or clarification</li>
                            <li>Policy or process improvements</li>
                            <li>Other appropriate remedial actions</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Response:</strong> We provide a written response explaining our findings and any actions taken. This typically occurs within 7-14 business days.
                    </li>
                    <li>
                        <strong>Follow-up:</strong> If you are not satisfied with the resolution, you may request escalation or provide additional information.
                    </li>
                </ol>
            </section>

            <!-- Response Timelines -->
            <section class="border-t border-gray-200 dark:!border-border-secondary pt-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-4" style="font-weight: 700;">
                    Response Timelines
                </h2>
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-200 dark:!border-border-primary">
                        <thead>
                            <tr class="bg-gray-100 dark:!bg-bg-card-hover">
                                <th class="border border-gray-200 dark:!border-border-primary px-4 py-3 text-left font-semibold text-gray-900 dark:!text-white" style="font-weight: 600;">Issue Type</th>
                                <th class="border border-gray-200 dark:!border-border-primary px-4 py-3 text-left font-semibold text-gray-900 dark:!text-white" style="font-weight: 600;">Acknowledgment</th>
                                <th class="border border-gray-200 dark:!border-border-primary px-4 py-3 text-left font-semibold text-gray-900 dark:!text-white" style="font-weight: 600;">Resolution Target</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-gray-200 dark:!border-border-primary px-4 py-3 text-gray-700 dark:!text-text-secondary">Content & Editorial</td>
                                <td class="border border-gray-200 dark:!border-border-primary px-4 py-3 text-gray-700 dark:!text-text-secondary">48 hours</td>
                                <td class="border border-gray-200 dark:!border-border-primary px-4 py-3 text-gray-700 dark:!text-text-secondary">7-14 business days</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-200 dark:!border-border-primary px-4 py-3 text-gray-700 dark:!text-text-secondary">Privacy & Data</td>
                                <td class="border border-gray-200 dark:!border-border-primary px-4 py-3 text-gray-700 dark:!text-text-secondary">24 hours</td>
                                <td class="border border-gray-200 dark:!border-border-primary px-4 py-3 text-gray-700 dark:!text-text-secondary">5-10 business days</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-200 dark:!border-border-primary px-4 py-3 text-gray-700 dark:!text-text-secondary">Technical Issues</td>
                                <td class="border border-gray-200 dark:!border-border-primary px-4 py-3 text-gray-700 dark:!text-text-secondary">24 hours</td>
                                <td class="border border-gray-200 dark:!border-border-primary px-4 py-3 text-gray-700 dark:!text-text-secondary">3-7 business days</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

<th class="border border-gray-200 dark:!border-border-primary px-4 py-3 text-left font-semibold text-gray-900 dark:!text-white" style="font-weight: 600;">Acknowledgment</th>
                                <th class="border border-gray-200 dark:!border-border-primary px-4 py-3 text-left font-semibold text-gray-900 dark:!text-white" style="font-weight: 600;">Resolution</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-gray-200 dark:!border-border-primary px-4 py-3 text-gray-700 dark:!text-text-secondary">Content Errors</td>
                                <td class="border border-gray-200 dark:!border-border-primary px-4 py-3 text-gray-700 dark:!text-text-secondary">2 business days</td>
                                <td class="border border-gray-200 dark:!border-border-primary px-4 py-3 text-gray-700 dark:!text-text-secondary">7-14 business days</td>
                            </tr>
                            <tr class="bg-gray-50 dark:!bg-bg-card-hover">
                                <td class="border border-gray-200 dark:!border-border-primary px-4 py-3 text-gray-700 dark:!text-text-secondary">Editorial Concerns</td>
                                <td class="border border-gray-200 dark:!border-border-primary px-4 py-3 text-gray-700 dark:!text-text-secondary">2 business days</td>
                                <td class="border border-gray-200 dark:!border-border-primary px-4 py-3 text-gray-700 dark:!text-text-secondary">10-21 business days</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-200 dark:!border-border-primary px-4 py-3 text-gray-700 dark:!text-text-secondary">Technical Issues</td>
                                <td class="border border-gray-200 dark:!border-border-primary px-4 py-3 text-gray-700 dark:!text-text-secondary">1 business day</td>
                                <td class="border border-gray-200 dark:!border-border-primary px-4 py-3 text-gray-700 dark:!text-text-secondary">3-7 business days</td>
                            </tr>
                            <tr class="bg-gray-50 dark:!bg-bg-card-hover">
                                <td class="border border-gray-200 dark:!border-border-primary px-4 py-3 text-gray-700 dark:!text-text-secondary">Privacy/Data</td>
                                <td class="border border-gray-200 dark:!border-border-primary px-4 py-3 text-gray-700 dark:!text-text-secondary">1 business day</td>
                                <td class="border border-gray-200 dark:!border-border-primary px-4 py-3 text-gray-700 dark:!text-text-secondary">5-10 business days</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-200 dark:!border-border-primary px-4 py-3 text-gray-700 dark:!text-text-secondary">Complex Issues</td>
                                <td class="border border-gray-200 dark:!border-border-primary px-4 py-3 text-gray-700 dark:!text-text-secondary">2 business days</td>
                                <td class="border border-gray-200 dark:!border-border-primary px-4 py-3 text-gray-700 dark:!text-text-secondary">21-30 business days</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p class="text-gray-600 dark:!text-text-secondary text-sm mt-4" style="font-weight: 400;">
                    <em>Note: Complex complaints requiring extensive investigation may take longer. We will keep you informed of any delays.</em>
                </p>
            </section>

            <!-- Escalation Process -->
            <section class="border-t border-gray-200 dark:!border-border-secondary pt-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-4" style="font-weight: 700;">
                    Escalation Process
                </h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    If you are not satisfied with the initial resolution, you can escalate your complaint:
                </p>
                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-6 h-6 bg-accent rounded-full flex items-center justify-center text-white font-bold text-sm">1</div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:!text-white mb-1" style="font-weight: 600;">Request Review</h3>
                            <p class="text-gray-700 dark:!text-text-secondary text-sm" style="font-weight: 400;">
                                Reply to the initial response explaining why you are not satisfied and request a review by senior management.
                            </p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-6 h-6 bg-accent rounded-full flex items-center justify-center text-white font-bold text-sm">2</div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:!text-white mb-1" style="font-weight: 600;">Senior Review</h3>
                            <p class="text-gray-700 dark:!text-text-secondary text-sm" style="font-weight: 400;">
                                Your complaint will be reviewed by senior editorial or management staff, who will provide a final response within 14 business days.
                            </p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-6 h-6 bg-accent rounded-full flex items-center justify-center text-white font-bold text-sm">3</div>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:!text-white mb-1" style="font-weight: 600;">Final Decision</h3>
                            <p class="text-gray-700 dark:!text-text-secondary text-sm" style="font-weight: 400;">
                                The senior review decision is final. We will provide a detailed explanation of our decision and any actions taken.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- What We Cannot Address -->
            <section class="border-t border-gray-200 dark:!border-border-secondary pt-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-4" style="font-weight: 700;">
                    What We Cannot Address
                </h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    While we strive to address all legitimate complaints, we cannot address:
                </p>
                <ul class="list-disc list-inside text-gray-700 dark:!text-text-secondary space-y-2 ml-4" style="font-weight: 400;">
                    <li>Complaints that are abusive, threatening, or contain offensive language</li>
                    <li>Complaints that are clearly frivolous or made in bad faith</li>
                    <li>Complaints about third-party content or external websites</li>
                    <li>Legal matters that require formal legal proceedings</li>
                    <li>Complaints that violate our Terms of Service or Community Guidelines</li>
                    <li>Anonymous complaints without sufficient information to investigate</li>
                </ul>
            </section>

            <!-- Confidentiality -->
            <section class="border-t border-gray-200 dark:!border-border-secondary pt-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-4" style="font-weight: 700;">
                    Confidentiality
                </h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    We treat all complaints with confidentiality:
                </p>
                <ul class="list-disc list-inside text-gray-700 dark:!text-text-secondary space-y-2 ml-4" style="font-weight: 400;">
                    <li>Your personal information is protected in accordance with our Privacy Terms</li>
                    <li>Complaints are handled by authorized personnel only</li>
                    <li>We do not disclose complaint details publicly without your consent</li>
                    <li>We may share anonymized information for quality improvement purposes</li>
                </ul>
            </section>

            <!-- Contact Information -->
            <section class="border-t border-gray-200 dark:!border-border-secondary pt-6">
                <div class="bg-accent/10 border border-accent/20 rounded-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-4" style="font-weight: 700;">
                        Contact Us for Complaints
                    </h2>
                    <div class="space-y-3">
                        <div>
                            <p class="text-gray-700 dark:!text-text-secondary mb-2" style="font-weight: 400;">
                                <strong>Email:</strong> <a href="mailto:muhamamdmaaz65@gmail.com?subject=Complaint" class="text-accent hover:text-accent-light underline font-semibold" style="font-weight: 600;">muhamamdmaaz65@gmail.com</a>
                            </p>
                            <p class="text-gray-600 dark:!text-text-secondary text-sm" style="font-weight: 400;">
                                Please include "Complaint" in the subject line
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-700 dark:!text-text-secondary mb-2" style="font-weight: 400;">
                                <strong>Contact Form:</strong> <a href="{{ route('contact') }}?subject=Complaint" class="text-accent hover:text-accent-light underline font-semibold" style="font-weight: 600;">Submit via Contact Form</a>
                            </p>
                        </div>
                        <div class="mt-4 pt-4 border-t border-accent/20">
                            <p class="text-gray-700 dark:!text-text-secondary text-sm" style="font-weight: 400;">
                                <strong>Response Time:</strong> We aim to acknowledge all complaints within 2 business days and provide a resolution within 7-21 business days, depending on the complexity of the issue.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Policy Updates -->
            <section class="border-t border-gray-200 dark:!border-border-secondary pt-6">
                <div class="bg-gray-50 dark:!bg-bg-card-hover rounded-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-3" style="font-weight: 700;">
                        Policy Updates
                    </h2>
                    <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                        This Complaint Redressal Policy may be updated periodically to reflect changes in our practices or legal requirements. Significant changes will be communicated to users.
                    </p>
                    <p class="text-gray-700 dark:!text-text-secondary" style="font-weight: 400;">
                        <strong>Last Updated:</strong> {{ date('F Y') }}
                    </p>
                </div>
            </section>

            <!-- Related Links -->
            <section class="border-t border-gray-200 dark:!border-border-secondary pt-6">
                <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-4" style="font-weight: 700;">
                    Related Information
                </h2>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('ethics') }}" class="text-accent hover:text-accent-light underline font-semibold" style="font-weight: 600;">Editorial Ethics</a>
                    <a href="{{ route('editorial-policy') }}" class="text-accent hover:text-accent-light underline font-semibold" style="font-weight: 600;">Editorial Policy</a>
                    <a href="{{ route('privacy') }}" class="text-accent hover:text-accent-light underline font-semibold" style="font-weight: 600;">Privacy Terms</a>
                    <a href="{{ route('terms') }}" class="text-accent hover:text-accent-light underline font-semibold" style="font-weight: 600;">Terms of Service</a>
                    <a href="{{ route('contact') }}" class="text-accent hover:text-accent-light underline font-semibold" style="font-weight: 600;">Contact Us</a>
                </div>
            </section>
        </div>

        <!-- Who We Are Section -->
        <div class="who-we-are">
            <h2 class="who-title">Who We Are</h2>
            <p class="who-text">
                Nazaara Circle is your ultimate destination for everything entertainment. From the latest drama reviews and movie blockbusters to exclusive celebrity biographies and trending industry news, we bring the spotlight to you. Our team of passionate writers and critics is dedicated to delivering fresh, engaging, and honest content that keeps you connected to the pulse of the entertainment world.
            </p>
            <a href="{{ route('about') }}" class="who-btn">Read More About Us</a>
        </div>
    </div>
</div>
@endsection

