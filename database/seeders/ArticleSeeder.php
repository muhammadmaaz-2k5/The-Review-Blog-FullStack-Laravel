<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create an author
        $authorEmail = env('AUTHOR_EMAIL', 'author@example.com');
        $author = User::firstOrCreate(
            ['email' => $authorEmail],
            [
                'name' => 'Admin User',
                'password' => bcrypt(env('AUTHOR_PASSWORD', 'ChangeThisPassword123!')),
                'is_author' => true,
                'role' => 'admin',
            ]
        );

        // Get Categories
        $bioCategory = Category::firstOrCreate(['slug' => 'biographies'], ['name' => 'Biographies', 'color' => '#6366F1']);
        $musicCategory = Category::firstOrCreate(['slug' => 'ost-music'], ['name' => 'OST & Music', 'color' => '#14B8A6']);
        $webSeriesCategory = Category::firstOrCreate(['slug' => 'web-series'], ['name' => 'Web Series', 'color' => '#8B5CF6']);
        $celebCategory = Category::firstOrCreate(['slug' => 'celebrity-news'], ['name' => 'Celebrity News', 'color' => '#F97316']);
        $movieCategory = Category::firstOrCreate(['slug' => 'movie-reviews'], ['name' => 'Movie Reviews', 'color' => '#3B82F6']);

        // Get Tags (Helper)
        $getTag = fn($name) => Tag::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name]);

        // 1. Jim Curtis (Existing)
        $this->createArticle([
            'slug' => 'biography-jim-curtis',
            'title' => 'Jim Curtis: The Man, The Myth, The Legend',
            'excerpt' => 'Exploring the life and career of Jim Curtis, from his humble beginnings to becoming a powerhouse in the entertainment industry.',
            'content' => $this->getJimCurtisContent(),
            'featured_image' => 'https://via.placeholder.com/800x600.png?text=Jim+Curtis',
            'category_id' => $bioCategory->id,
            'author_id' => $author->id,
            'reading_time' => 5,
            'tags' => [$getTag('Jim Curtis'), $getTag('Biography'), $getTag('Hollywood')],
        ]);

        // 2. Harry Styles
        $this->createArticle([
            'slug' => 'harry-styles-amex-presale-tickets',
            'title' => 'Harry Styles Amex Presale: Securing Tickets for Madison Square Garden',
            'excerpt' => 'Everything you need to know about the Harry Styles presale, Amex benefits, and how to get tickets for his upcoming shows.',
            'content' => $this->getHarryStylesContent(),
            'featured_image' => 'https://via.placeholder.com/800x600.png?text=Harry+Styles',
            'category_id' => $musicCategory->id,
            'author_id' => $author->id,
            'reading_time' => 4,
            'tags' => [$getTag('Harry Styles'), $getTag('Amex Presale'), $getTag('Concert'), $getTag('Tickets'), $getTag('Madison Square Garden')],
        ]);

        // 3. Patrick Dempsey
        $this->createArticle([
            'slug' => 'patrick-dempsey-memory-of-a-killer',
            'title' => 'Patrick Dempsey Returns in "Memory of a Killer": Cast, Episodes, and Watch Guide',
            'excerpt' => 'Grey\'s Anatomy star Patrick Dempsey takes on a chilling new role. Here is the cast list, episode guide, and where to watch "Memory of a Killer".',
            'content' => $this->getPatrickDempseyContent(),
            'featured_image' => 'https://via.placeholder.com/800x600.png?text=Patrick+Dempsey',
            'category_id' => $webSeriesCategory->id,
            'author_id' => $author->id,
            'reading_time' => 6,
            'tags' => [$getTag('Patrick Dempsey'), $getTag('Memory of a Killer'), $getTag('Grey\'s Anatomy'), $getTag('New Show')],
        ]);

        // 4. Travis Scott
        $this->createArticle([
            'slug' => 'travis-scott-odyssey-movie-rumors',
            'title' => 'Is Travis Scott in Christopher Nolan\'s "The Odyssey"? Rumors Debunked',
            'excerpt' => 'Rumors are swirling about Travis Scott joining the cast of a Christopher Nolan movie. We investigate the "Odyssey" connections.',
            'content' => $this->getTravisScottContent(),
            'featured_image' => 'https://via.placeholder.com/800x600.png?text=Travis+Scott',
            'category_id' => $movieCategory->id,
            'author_id' => $author->id,
            'reading_time' => 5,
            'tags' => [$getTag('Travis Scott'), $getTag('The Odyssey'), $getTag('Christopher Nolan'), $getTag('Movie Review')],
        ]);

        // 5. Selma Blair
        $this->createArticle([
            'slug' => 'selma-blair-resilience-icon',
            'title' => 'Selma Blair: A Portrait of Strength and Grace',
            'excerpt' => 'An in-depth look at Selma Blair\'s journey, her advocacy, and her continued impact on Hollywood.',
            'content' => $this->getSelmaBlairContent(),
            'featured_image' => 'https://via.placeholder.com/800x600.png?text=Selma+Blair',
            'category_id' => $bioCategory->id,
            'author_id' => $author->id,
            'reading_time' => 7,
            'tags' => [$getTag('Selma Blair'), $getTag('Biography'), $getTag('Hollywood')],
        ]);

        // 6. Quinton Aaron
        $this->createArticle([
            'slug' => 'quinton-aaron-sandra-bullock-reunion',
            'title' => 'Quinton Aaron on Sandra Bullock and The Blind Side Legacy',
            'excerpt' => 'Quinton Aaron opens up about his relationship with co-star Sandra Bullock and how "The Blind Side" changed his life forever.',
            'content' => $this->getQuintonAaronContent(),
            'featured_image' => 'https://via.placeholder.com/800x600.png?text=Quinton+Aaron',
            'category_id' => $celebCategory->id,
            'author_id' => $author->id,
            'reading_time' => 5,
            'tags' => [$getTag('Quinton Aaron'), $getTag('Sandra Bullock'), $getTag('The Blind Side'), $getTag('Hollywood')],
        ]);

        $this->command->info('✅ All entertainment articles seeded successfully!');
    }

    private function createArticle(array $data): void
    {
        $tags = $data['tags'] ?? [];
        unset($data['tags']);

        $articleData = array_merge([
            'status' => 'published',
            'is_featured' => true,
            'meta_description' => $data['excerpt'],
            'published_at' => Carbon::now(),
        ], $data);

        $article = Article::updateOrCreate(
            ['slug' => $data['slug']],
            $articleData
        );

        if (!empty($tags)) {
            $article->tags()->sync(collect($tags)->pluck('id'));
        }
        
        $this->command->info("   - Seeded: {$data['title']}");
    }

    private function getJimCurtisContent(): string
    {
        return '<p><strong>Jim Curtis</strong> is a name that resonates with versatility and charisma in the entertainment world. Known for his dynamic roles and philanthropic efforts, Curtis has carved a niche for himself that few can rival.</p>
        <h2>Early Life</h2>
        <p>Born in a small town, Jim always had big dreams. His passion for the arts was evident from a young age, often participating in local theater productions and school plays. His teachers recall him as a "bright spark" who could command a room with his presence.</p>
        <h2>Rise to Fame</h2>
        <p>Jim\'s breakout role came in the late 90s, where he played the lead in the critically acclaimed drama <em>"Echoes of Tomorrow"</em>. The performance earned him his first major award nomination and put him on the radar of top directors.</p>
        <h2>Notable Works</h2>
        <ul>
            <li><strong>"The Last Horizon" (2005):</strong> An action-packed thriller that showcased his physical prowess.</li>
            <li><strong>"Silent Whispers" (2012):</strong> A psychological drama where he played a mute artist, a role cited as his most challenging.</li>
            <li><strong>"Comedy Central Roast" (2018):</strong> Displaying his impeccable comic timing and ability to laugh at himself.</li>
        </ul>
        <h2>Legacy</h2>
        <p>Beyond the screen, Jim Curtis is a dedicated advocate for arts education. He established the <em>Curtis Foundation</em>, which provides scholarships to aspiring young actors. His legacy is not just in his filmography, but in the lives he continues to touch.</p>';
    }

    private function getHarryStylesContent(): string
    {
        return '<p>The wait is over for Harries everywhere! <strong>Harry Styles</strong> has announced additional dates for his massive tour, and fans are scrambling to secure their spots at <strong>Madison Square Garden</strong>.</p>
        <h2>Amex Presale Details</h2>
        <p>American Express cardholders get exclusive early access. If you have an Amex card, you can access the presale starting Tuesday at 10 AM local time. Make sure your card is linked to your Ticketmaster account beforehand to avoid any last-minute hiccups.</p>
        <h2>How to Get Tickets</h2>
        <ul>
            <li><strong>Register for Verified Fan:</strong> This is your best bet to fight the bots. Registration closes 24 hours before the sale.</li>
            <li><strong>Be Ready Early:</strong> Log in at least 10 minutes before the queue opens.</li>
            <li><strong>Check Internet Connection:</strong> You don\'t want to disconnect right when you\'re selecting seats!</li>
        </ul>
        <p>Madison Square Garden is set to be transformed into "Harry\'s House" once again. Don\'t miss out on what promises to be the concert event of the year.</p>';
    }

    private function getPatrickDempseyContent(): string
    {
        return '<p><strong>Patrick Dempsey</strong>, forever known as McDreamy to <em>Grey\'s Anatomy</em> fans, is shedding his scrubs for a darker role in the new series <strong>"Memory of a Killer"</strong>.</p>
        <h2>The Plot</h2>
        <p>Dempsey plays a seasoned hitman struggling with early-onset Alzheimer\'s. As his memory fades, his conscience awakens, leading him to turn against his employers. It\'s a gritty, high-stakes thriller that showcases Dempsey\'s range beyond romantic leads.</p>
        <h2>Cast & Crew</h2>
        <ul>
            <li><strong>Patrick Dempsey</strong> as the Hitman</li>
            <li><strong>Michael Imperioli</strong> as the Detective</li>
            <li>Directed by visionary filmmakers known for noir thrillers.</li>
        </ul>
        <h2>Where to Watch</h2>
        <p>The series is available for streaming exclusively on Fox\'s digital platforms and partner networks. Check your local listings for the premiere date. Critics are already calling it a career-defining performance for Dempsey.</p>';
    }

    private function getTravisScottContent(): string
    {
        return '<p>The internet is buzzing with rumors connecting rapper <strong>Travis Scott</strong> to <strong>Christopher Nolan\'s</strong> upcoming project, tentatively titled <em>"The Odyssey"</em>.</p>
        <h2>The Rumor Mill</h2>
        <p>Speculation began after Scott was spotted near a filming location for the movie. Fans have been theorizing that he might have a cameo or a soundtrack contribution. Given Nolan\'s history of unexpected casting choices, nothing is impossible.</p>
        <h2>What We Know About "The Odyssey" (2026)</h2>
        <p>Nolan\'s new film is a sci-fi epic set in deep space. While the main cast includes heavy hitters, the addition of a music icon like Travis Scott would certainly draw a younger demographic. However, as of now, neither Nolan\'s camp nor Scott\'s representatives have confirmed any official involvement.</p>
        <p>Is it just a fan theory, or is Travis Scott about to make his big-screen acting debut? Only time will tell.</p>';
    }

    private function getSelmaBlairContent(): string
    {
        return '<p><strong>Selma Blair</strong> has always been a force in Hollywood, but her recent journey has made her an icon of resilience. From her breakout roles in the 90s to her public battle with Multiple Sclerosis, Blair has handled it all with grace and honesty.</p>
        <h2>A Career of Diversity</h2>
        <p>Blair has starred in cult classics and mainstream hits alike. Her ability to switch between comedy and drama has kept her relevant for decades.</p>
        <h2>Advocacy and Strength</h2>
        <p>Since her diagnosis, Selma has been an open book, sharing the highs and lows of her condition. Her documentary gave the world an intimate look at her life, inspiring millions dealing with chronic illnesses. She continues to work, proving that physical challenges do not define one\'s ability to create art.</p>';
    }

    private function getQuintonAaronContent(): string
    {
        return '<p>It has been years since <strong>"The Blind Side"</strong> touched hearts worldwide, but the bond between <strong>Quinton Aaron</strong> and <strong>Sandra Bullock</strong> remains a topic of warmth in Hollywood.</p>
        <h2>Life After "The Blind Side"</h2>
        <p>Quinton Aaron\'s portrayal of Big Mike was nothing short of legendary. In recent interviews, he has spoken about how the role catapulted him into stardom and the pressures that came with it.</p>
        <h2>Reunion with Sandra Bullock</h2>
        <p>"She is family," Aaron said in a recent press junket. He described Bullock as a mentor and a friend who supported him long after the cameras stopped rolling. Fans are eager to see if the duo will ever share the screen again.</p>
        <p>Aaron is currently working on new projects that focus on community upliftment and anti-bullying campaigns, staying true to the gentle giant persona that the world fell in love with.</p>';
    }
}
