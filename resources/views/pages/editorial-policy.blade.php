@extends('layouts.app')

@section('title', 'Editorial Policy - Nazaara Circle')

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
        <h1 class="section-title">
            Editorial Policy
        </h1>

        <div class="content-card space-y-8">
            <!-- Introduction -->
            <section>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4 text-lg" style="font-weight: 400;">
                    This Editorial Policy outlines the principles, standards, and practices that guide our content creation and publication at <strong class="text-accent">Nazaara Circle</strong>. It serves as a framework for our editorial team, contributors, and readers to understand how we create, curate, and present content.
                </p>
            </section>

            <!-- Content Standards -->
            <section class="border-t border-gray-200 dark:!border-border-secondary pt-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-4" style="font-weight: 700;">
                    Content Standards
                </h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    All content published on Nazaara Circle must meet our high standards for quality, accuracy, and value.
                </p>
                <ul class="list-disc list-inside text-gray-700 dark:!text-text-secondary space-y-2 ml-4" style="font-weight: 400;">
                    <li><strong>Accuracy:</strong> All factual claims must be verified and supported by reliable sources</li>
                    <li><strong>Relevance:</strong> Content must be relevant to our entertainment-focused audience</li>
                    <li><strong>Originality:</strong> Content should be original or provide unique insights and perspectives</li>
                    <li><strong>Clarity:</strong> Articles must be well-written, clear, and accessible to our target audience</li>
                    <li><strong>Value:</strong> Content must provide genuine value, whether educational, informative, or analytical</li>
                    <li><strong>Timeliness:</strong> We prioritize current, relevant information and trends</li>
                </ul>
            </section>

            <!-- Content Categories -->
            <section class="border-t border-gray-200 dark:!border-border-secondary pt-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-4" style="font-weight: 700;">
                    Content Categories
                </h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    We publish various types of content, each with specific editorial guidelines:
                </p>
                <div class="space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:!text-white mb-2" style="font-weight: 600;">News & Updates</h3>
                        <p class="text-gray-700 dark:!text-text-secondary text-sm" style="font-weight: 400;">
                            Timely reporting on entertainment news, movie premieres, and industry developments. Must be factual, balanced, and sourced from credible outlets.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:!text-white mb-2" style="font-weight: 600;">Biographies & Profiles</h3>
                        <p class="text-gray-700 dark:!text-text-secondary text-sm" style="font-weight: 400;">
                            Detailed accounts of celebrities' lives and careers. Information must be fact-checked against multiple reliable sources.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:!text-white mb-2" style="font-weight: 600;">Explained & Analysis</h3>
                        <p class="text-gray-700 dark:!text-text-secondary text-sm" style="font-weight: 400;">
                            In-depth breakdowns of movie plots, TV series endings, and pop culture phenomena. Analysis should be insightful and clearly reasoned.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:!text-white mb-2" style="font-weight: 600;">Opinion & Commentary</h3>
                        <p class="text-gray-700 dark:!text-text-secondary text-sm" style="font-weight: 400;">
                            Thoughtful analysis and opinion pieces on entertainment trends and industry issues. Must be clearly labeled as opinion and supported by reasoning.
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:!text-white mb-2" style="font-weight: 600;">Research & Analysis</h3>
                        <p class="text-gray-700 dark:!text-text-secondary text-sm" style="font-weight: 400;">
                            Data-driven articles based on box office numbers, ratings analysis, or viewer surveys. Must include methodology and sources.
                        </p>
                    </div>
                </div>
            </section>

            <!-- Editorial Process -->
            <section class="border-t border-gray-200 dark:!border-border-secondary pt-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-4" style="font-weight: 700;">
                    Editorial Process
                </h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    All content goes through a structured editorial process before publication:
                </p>
                <ol class="list-decimal list-inside text-gray-700 dark:!text-text-secondary space-y-3 ml-4" style="font-weight: 400;">
                    <li><strong>Submission:</strong> Content is submitted by authors or assigned by editors</li>
                    <li><strong>Initial Review:</strong> Editorial team reviews for basic quality, relevance, and adherence to guidelines</li>
                    <li><strong>Fact-Checking:</strong> Factual claims are verified against reliable sources</li>
                    <li><strong>Editing:</strong> Content is edited for clarity, grammar, style, and structure</li>
                    <li><strong>Content Review:</strong> Content is reviewed by subject matter experts when necessary</li>
                    <li><strong>Final Approval:</strong> Senior editors approve content for publication</li>
                    <li><strong>Publication:</strong> Content is published with appropriate categorization and tagging</li>
                    <li><strong>Post-Publication:</strong> Content is monitored for accuracy and updated as needed</li>
                </ol>
            </section>

            <!-- Author Guidelines -->
            <section class="border-t border-gray-200 dark:!border-border-secondary pt-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-4" style="font-weight: 700;">
                    Author Guidelines
                </h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    Authors contributing to Nazaara Circle must adhere to the following guidelines:
                </p>
                <ul class="list-disc list-inside text-gray-700 dark:!text-text-secondary space-y-2 ml-4" style="font-weight: 400;">
                    <li>Maintain high standards of accuracy and fact-checking</li>
                    <li>Disclose any potential conflicts of interest</li>
                    <li>Properly cite and attribute all sources</li>
                    <li>Respect copyright and intellectual property rights</li>
                    <li>Write in a clear, accessible style appropriate for the target audience</li>
                    <li>Respond to editorial feedback and revision requests</li>
                    <li>Update content when new information becomes available</li>
                    <li>Engage respectfully with readers in comments and discussions</li>
                </ul>
            </section>

            <!-- Source Attribution -->
            <section class="border-t border-gray-200 dark:!border-border-secondary pt-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-4" style="font-weight: 700;">
                    Source Attribution & Citations
                </h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    We believe in transparent and proper attribution of sources:
                </p>
                <ul class="list-disc list-inside text-gray-700 dark:!text-text-secondary space-y-2 ml-4" style="font-weight: 400;">
                    <li>All sources must be credible and reliable</li>
                    <li>Direct quotes must be properly attributed</li>
                    <li>Statistics and data must include source citations</li>
                    <li>External links should be to reputable sources</li>
                    <li>Images and media must be properly licensed and attributed</li>
                    <li>We prefer primary sources when available</li>
                    <li>Anonymous sources are used only when necessary and with editorial approval</li>
                </ul>
            </section>

            <!-- Corrections Policy -->
            <section class="border-t border-gray-200 dark:!border-border-secondary pt-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-4" style="font-weight: 700;">
                    Corrections Policy
                </h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    We are committed to accuracy and transparency in corrections:
                </p>
                <ul class="list-disc list-inside text-gray-700 dark:!text-text-secondary space-y-2 ml-4" style="font-weight: 400;">
                    <li>Errors are corrected promptly upon discovery</li>
                    <li>Significant corrections are noted at the top or bottom of articles</li>
                    <li>Corrections explain what was wrong and what has been corrected</li>
                    <li>We maintain a public record of significant corrections</li>
                    <li>Readers can report errors through our contact page</li>
                    <li>We do not remove or hide corrected content without clear justification</li>
                </ul>
            </section>

            <!-- Editorial Independence -->
            <section class="border-t border-gray-200 dark:!border-border-secondary pt-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-4" style="font-weight: 700;">
                    Editorial Independence & Content Policy
                </h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    We maintain clear editorial independence as we no longer accept advertising:
                </p>
                <ul class="list-disc list-inside text-gray-700 dark:!text-text-secondary space-y-2 ml-4" style="font-weight: 400;">
                    <li>All content is editorially independent with no sponsored content</li>
                    <li>Sponsored content does not influence editorial coverage</li>
                    <li>Editorial decisions are made independently without advertising considerations</li>
                    <li>We do not accept advertising or payment for editorial coverage</li>
                    <li>Affiliate links are disclosed when present</li>
                    <li>Sponsored content must still meet our quality and accuracy standards</li>
                </ul>
            </section>

            <!-- Comment Moderation -->
            <section class="border-t border-gray-200 dark:!border-border-secondary pt-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-4" style="font-weight: 700;">
                    Comment Moderation Policy
                </h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    We encourage constructive dialogue while maintaining community standards:
                </p>
                <ul class="list-disc list-inside text-gray-700 dark:!text-text-secondary space-y-2 ml-4" style="font-weight: 400;">
                    <li>Comments should be relevant to the article topic</li>
                    <li>Respectful and constructive criticism is welcome</li>
                    <li>Hate speech, harassment, and personal attacks are not tolerated</li>
                    <li>Spam, promotional content, and off-topic comments may be removed</li>
                    <li>We reserve the right to moderate or remove comments that violate our community standards</li>
                    <li>Repeated violations may result in comment privileges being revoked</li>
                </ul>
            </section>

            <!-- Content Updates -->
            <section class="border-t border-gray-200 dark:!border-border-secondary pt-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-4" style="font-weight: 700;">
                    Content Updates & Revisions
                </h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    We regularly update content to maintain accuracy and relevance:
                </p>
                <ul class="list-disc list-inside text-gray-700 dark:!text-text-secondary space-y-2 ml-4" style="font-weight: 400;">
                    <li>"Updated" dates are displayed on articles that have been significantly revised</li>
                    <li>Minor updates (typos, formatting) may not be noted</li>
                    <li>Archived content is labeled as such if it is no longer current but kept for historical record</li>
                    <li>We review our evergreen content periodically to ensure it remains accurate</li>
                </ul>
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
                <ul class="list-disc list-inside text-gray-700 dark:!text-text-secondary space-y-2 ml-4" style="font-weight: 400;">
                    <li>Articles are reviewed periodically for accuracy and relevance</li>
                    <li>Outdated information is updated when new information becomes available</li>
                    <li>Significant updates are noted in the article</li>
                    <li>We may archive or remove content that is no longer relevant or accurate</li>
                    <li>Readers are encouraged to report outdated information</li>
                </ul>
            </section>

            <!-- Editorial Independence -->
            <section class="border-t border-gray-200 dark:!border-border-secondary pt-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-4" style="font-weight: 700;">
                    Editorial Independence
                </h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    Our editorial team operates independently:
                </p>
                <ul class="list-disc list-inside text-gray-700 dark:!text-text-secondary space-y-2 ml-4" style="font-weight: 400;">
                    <li>Editorial decisions are made by the editorial team alone without external influence</li>
                    <li>We no longer work with advertisers or sponsors</li>
                    <li>We cover topics based on editorial merit and reader interest</li>
                    <li>We maintain editorial independence even when covering partners or sponsors</li>
                    <li>Conflicts of interest are disclosed when they cannot be avoided</li>
                </ul>
            </section>

            <!-- Diversity & Inclusion -->
            <section class="border-t border-gray-200 dark:!border-border-secondary pt-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-4" style="font-weight: 700;">
                    Diversity & Inclusion
                </h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    We are committed to diversity and inclusion in our content and contributors:
                </p>
                <ul class="list-disc list-inside text-gray-700 dark:!text-text-secondary space-y-2 ml-4" style="font-weight: 400;">
                    <li>We seek diverse perspectives and voices in our content</li>
                    <li>We are committed to inclusive language and representation</li>
                    <li>We welcome contributors from diverse backgrounds</li>
                    <li>We cover topics relevant to diverse audiences</li>
                    <li>We actively work to reduce bias in our coverage</li>
                </ul>
            </section>

            <!-- Contact & Feedback -->
            <section class="border-t border-gray-200 dark:!border-border-secondary pt-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-4" style="font-weight: 700;">
                    Questions & Feedback
                </h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    We welcome feedback on our editorial policy and practices. If you have questions, concerns, or suggestions, please contact us:
                </p>
                <div class="bg-gray-50 dark:!bg-bg-card-hover rounded-lg p-6 mt-4">
                    <p class="text-gray-700 dark:!text-text-secondary mb-2" style="font-weight: 400;">
                        <strong>Email:</strong> <a href="mailto:muhamamdmaaz65@gmail.com" class="text-accent hover:text-accent-light underline font-semibold" style="font-weight: 600;">muhamamdmaaz65@gmail.com</a>
                    </p>
                    <p class="text-gray-700 dark:!text-text-secondary" style="font-weight: 400;">
                        You can also use our <a href="{{ route('contact') }}" class="text-accent hover:text-accent-light underline font-semibold" style="font-weight: 600;">contact form</a> to reach out.
                    </p>
                </div>
            </section>

            <!-- Policy Updates -->
            <section class="border-t border-gray-200 dark:!border-border-secondary pt-6">
                <div class="bg-accent/10 border border-accent/20 rounded-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-3" style="font-weight: 700;">
                        Policy Updates
                    </h2>
                    <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                        This Editorial Policy is a living document that may be updated periodically to reflect changes in our practices, industry standards, or legal requirements. We will notify readers of significant changes to this policy.
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
                    <a href="{{ route('privacy') }}" class="text-accent hover:text-accent-light underline font-semibold" style="font-weight: 600;">Privacy Terms</a>
                    <a href="{{ route('terms') }}" class="text-accent hover:text-accent-light underline font-semibold" style="font-weight: 600;">Terms of Service</a>
                    <a href="{{ route('contact') }}" class="text-accent hover:text-accent-light underline font-semibold" style="font-weight: 600;">Contact Us</a>
                </div>
            </section>
        </div>

        {{-- Who We Are Section --}}
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

