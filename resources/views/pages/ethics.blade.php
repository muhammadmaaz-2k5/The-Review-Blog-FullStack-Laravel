@extends('layouts.app')

@section('title', 'Editorial Ethics & Standards - Nazaara Circle')

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
        Editorial Ethics & Standards
    </h1>

    <div class="content-card space-y-8">
            <!-- Introduction -->
            <section>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4 text-lg" style="font-weight: 400;">
                    At <strong class="text-accent">Nazaara Circle</strong>, we are committed to maintaining the highest standards of editorial integrity, accuracy, and ethical journalism. Our editorial ethics guide our content creation, publication, and community engagement practices.
                </p>
            </section>

            <!-- Editorial Independence -->
            <section class="border-t border-gray-200 dark:!border-border-secondary pt-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-4" style="font-weight: 700;">
                    Editorial Independence
                </h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    We maintain complete editorial independence in all our content decisions. Our editorial team makes decisions based solely on journalistic merit, reader value, and editorial standards. As we no longer accept advertising, our content is completely independent without any external influence.
                </p>
                <ul class="list-disc list-inside text-gray-700 dark:!text-text-secondary space-y-2 ml-4" style="font-weight: 400;">
                    <li>Editorial content is completely independent without any advertising influence</li>
                    <li>All content is editorially driven with no sponsored content</li>
                    <li>Our writers and editors operate independently without commercial interests</li>
                    <li>We do not accept payment or incentives for coverage</li>
                </ul>
            </section>

            <!-- Accuracy & Fact-Checking -->
            <section class="border-t border-gray-200 dark:!border-border-secondary pt-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-4" style="font-weight: 700;">
                    Accuracy & Fact-Checking
                </h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    We are committed to providing accurate, reliable, and well-researched information to our readers.
                </p>
                <ul class="list-disc list-inside text-gray-700 dark:!text-text-secondary space-y-2 ml-4" style="font-weight: 400;">
                    <li>All factual claims are verified through multiple reliable sources</li>
                    <li>Technical information is reviewed by subject matter experts when necessary</li>
                    <li>We correct errors promptly and transparently</li>
                    <li>Sources are cited and credited appropriately</li>
                    <li>We distinguish between verified facts and opinions or analysis</li>
                </ul>
            </section>

            <!-- Transparency & Disclosure -->
            <section class="border-t border-gray-200 dark:!border-border-secondary pt-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-4" style="font-weight: 700;">
                    Transparency & Disclosure
                </h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    We believe in transparency with our readers about our practices, relationships, and potential conflicts of interest.
                </p>
                <ul class="list-disc list-inside text-gray-700 dark:!text-text-secondary space-y-2 ml-4" style="font-weight: 400;">
                    <li>Sponsored content, affiliate links, and partnerships are clearly disclosed</li>
                    <li>We disclose any potential conflicts of interest</li>
                    <li>Review units, samples, or products received for review are disclosed</li>
                    <li>Financial relationships that could influence content are transparently communicated</li>
                    <li>We clearly label opinion pieces, analysis, and editorial content</li>
                </ul>
            </section>

            <!-- Fairness & Impartiality -->
            <section class="border-t border-gray-200 dark:!border-border-secondary pt-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-4" style="font-weight: 700;">
                    Fairness & Impartiality
                </h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    We strive to present balanced perspectives and treat all subjects fairly in our coverage.
                </p>
                <ul class="list-disc list-inside text-gray-700 dark:!text-text-secondary space-y-2 ml-4" style="font-weight: 400;">
                    <li>We provide opportunities for response when covering controversial topics</li>
                    <li>Multiple perspectives are included in our coverage when relevant</li>
                    <li>We avoid bias and present information objectively</li>
                    <li>Personal opinions are clearly distinguished from factual reporting</li>
                    <li>We respect diverse viewpoints and encourage constructive dialogue</li>
                </ul>
            </section>

            <!-- Privacy & Confidentiality -->
            <section class="border-t border-gray-200 dark:!border-border-secondary pt-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-4" style="font-weight: 700;">
                    Privacy & Confidentiality
                </h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    We respect the privacy of individuals and protect confidential sources and information.
                </p>
                <ul class="list-disc list-inside text-gray-700 dark:!text-text-secondary space-y-2 ml-4" style="font-weight: 400;">
                    <li>We protect the identity of confidential sources when requested</li>
                    <li>Personal information is handled in accordance with our Privacy Terms</li>
                    <li>We obtain consent before publishing personal information or images</li>
                    <li>We respect embargoes and off-the-record agreements</li>
                    <li>User data is protected and used only as disclosed in our Privacy Terms</li>
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
  <!-- Plagiarism & Attribution -->
            <section class="border-t border-gray-200 dark:!border-border-secondary pt-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-4" style="font-weight: 700;">
                    Plagiarism & Attribution
                </h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    We maintain strict standards against plagiarism and ensure proper attribution of all sources.
                </p>
                <ul class="list-disc list-inside text-gray-700 dark:!text-text-secondary space-y-2 ml-4" style="font-weight: 400;">
                    <li>All content is original or properly attributed to its source</li>
                    <li>We do not tolerate plagiarism in any form</li>
                    <li>Quotes and excerpts are properly cited</li>
                    <li>Images, graphics, and media are used with proper permissions and attribution</li>
                    <li>We respect intellectual property rights and copyright laws</li>
                </ul>
            </section>

            <!-- Corrections & Updates -->
            <section class="border-t border-gray-200 dark:!border-border-secondary pt-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-4" style="font-weight: 700;">
                    Corrections & Updates
                </h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    When errors occur, we correct them promptly and transparently.
                </p>
                <ul class="list-disc list-inside text-gray-700 dark:!text-text-secondary space-y-2 ml-4" style="font-weight: 400;">
                    <li>Corrections are made promptly upon discovery of errors</li>
                    <li>Significant corrections are noted at the top or bottom of articles</li>
                    <li>We maintain a public record of significant corrections</li>
                    <li>Readers can report errors through our contact page</li>
                    <li>We update articles when new information becomes available</li>
                </ul>
            </section>

            <!-- Community Standards -->
            <section class="border-t border-gray-200 dark:!border-border-secondary pt-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-4" style="font-weight: 700;">
                    Community Standards
                </h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    We foster a respectful and inclusive community for all readers and contributors.
                </p>
                <ul class="list-disc list-inside text-gray-700 dark:!text-text-secondary space-y-2 ml-4" style="font-weight: 400;">
                    <li>We promote respectful dialogue and constructive criticism</li>
                    <li>Hate speech, harassment, and discriminatory content are not tolerated</li>
                    <li>Comments and discussions are moderated to maintain community standards</li>
                    <li>We respect diverse perspectives and encourage civil discourse</li>
                    <li>We protect community members from abuse and harmful behavior</li>
                </ul>
            </section>

            <!-- Conflicts of Interest -->
            <section class="border-t border-gray-200 dark:!border-border-secondary pt-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-4" style="font-weight: 700;">
                    Conflicts of Interest
                </h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    We identify and manage potential conflicts of interest to maintain editorial integrity.
                </p>
                <ul class="list-disc list-inside text-gray-700 dark:!text-text-secondary space-y-2 ml-4" style="font-weight: 400;">
                    <li>Writers disclose personal or financial relationships that could influence coverage</li>
                    <li>We avoid assignments where conflicts of interest exist</li>
                    <li>When conflicts cannot be avoided, they are fully disclosed to readers</li>
                    <li>Editorial staff are prohibited from accepting gifts or favors that could influence coverage</li>
                    <li>We maintain clear separation between editorial and commercial activities</li>
                </ul>
            </section>

            <!-- Reporting Concerns -->
            <section class="border-t border-gray-200 dark:!border-border-secondary pt-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:!text-white mb-4" style="font-weight: 700;">
                    Reporting Ethical Concerns
                </h2>
                <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mb-4" style="font-weight: 400;">
                    If you believe we have violated our ethical standards, we encourage you to report your concerns.
                </p>
                <div class="bg-gray-50 dark:!bg-bg-card-hover rounded-lg p-6 mt-4">
                    <p class="text-gray-700 dark:!text-text-secondary mb-4" style="font-weight: 400;">
                        Please contact us at <a href="mailto:muhamamdmaaz65@gmail.com" class="text-accent hover:text-accent-light underline font-semibold" style="font-weight: 600;">muhamamdmaaz65@gmail.com</a> with:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 dark:!text-text-secondary space-y-2 ml-4" style="font-weight: 400;">
                        <li>Details of the ethical concern</li>
                        <li>Links to specific articles or content in question</li>
                        <li>Any supporting evidence or documentation</li>
                        <li>Your contact information (optional, but helpful for follow-up)</li>
                    </ul>
                    <p class="text-gray-700 dark:!text-text-secondary mt-4" style="font-weight: 400;">
                        We take all ethical concerns seriously and will investigate them promptly. You can also use our <a href="{{ route('contact') }}" class="text-accent hover:text-accent-light underline font-semibold" style="font-weight: 600;">contact form</a> to report concerns.
                    </p>
                </div>
            </section>

            <!-- Commitment Statement -->
            <section class="border-t border-gray-200 dark:!border-border-secondary pt-6">
                <div class="bg-accent/10 border border-accent/20 rounded-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:!text-white mb-3" style="font-weight: 700;">
                        Our Commitment
                    </h2>
                    <p class="text-gray-700 dark:!text-text-secondary leading-relaxed" style="font-weight: 400;">
                        These ethical standards are not just guidelines—they are fundamental principles that guide everything we do at Nazaara Circle. We are committed to maintaining the trust of our readers through transparency, accuracy, and ethical journalism. We regularly review and update these standards to ensure they reflect best practices in digital journalism and content creation.
                    </p>
                    <p class="text-gray-700 dark:!text-text-secondary leading-relaxed mt-4" style="font-weight: 400;">
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
                    <a href="{{ route('privacy') }}" class="text-accent hover:text-accent-light underline font-semibold" style="font-weight: 600;">Privacy Terms</a>
                    <a href="{{ route('terms') }}" class="text-accent hover:text-accent-light underline font-semibold" style="font-weight: 600;">Terms of Service</a>
                    <a href="{{ route('contact') }}" class="text-accent hover:text-accent-light underline font-semibold" style="font-weight: 600;">Contact Us</a>
                    <a href="{{ route('about') }}" class="text-accent hover:text-accent-light underline font-semibold" style="font-weight: 600;">About Us</a>
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

