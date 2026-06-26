import React from 'react';
import { notFound } from 'next/navigation';
import { Masthead } from '@/components/editorial/Masthead';
import { ArticleCard } from '@/components/editorial/ArticleCard';
import { WeeklyLetter } from '@/components/editorial/WeeklyLetter';
import { PlaceHolderImages } from '@/lib/placeholder-images';

const HUB_TITLES: Record<string, string> = {
  'politics': 'Politics & Governance',
  'economy': 'Economy & Industry',
  'foreign-affairs': 'Foreign Affairs & Strategy',
  'society': 'Society & Culture',
  'technology': 'Technology & Innovation',
  'diaspora': 'The Global Indian',
  'opinion': 'Commentary & Opinion',
  'archive': 'Historical Archive',
};

const HUB_DESCRIPTIONS: Record<string, string> = {
  'politics': 'Navigating the legislative currents and institutional frameworks of the subcontinent.',
  'economy': 'A macro and micro analysis of the financial architectures driving India’s trajectory.',
  'foreign-affairs': 'Strategic assessments of India’s evolving footprint in a multipolar world order.',
  'society': 'Exploring the sociological shifts and cultural revivals across regional boundaries.',
  'technology': 'From deep-tech to digital public infrastructure: the engines of modern India.',
  'diaspora': 'Voices and influence of the 32-million strong Indian community worldwide.',
  'opinion': 'Divergent viewpoints from leading analysts, diplomats, and industry stalwarts.',
  'archive': 'Accessing the historical records and turning points that shaped the modern state.',
};

export default async function HubPage({ params }: { params: Promise<{ slug: string }> }) {
  const { slug } = await params;
  const title = HUB_TITLES[slug];
  
  if (!title) {
    notFound();
  }

  const seed = slug.split('').reduce((acc, char) => acc + char.charCodeAt(0), 0);
  const imageIndex = seed % (PlaceHolderImages.length || 1);
  const featuredImage = PlaceHolderImages[imageIndex]?.imageUrl || "https://picsum.photos/seed/default/1200/600";

  const featuredArticle = {
    category: title,
    title: `The 2024 Perspective: Analyzing ${title}`,
    excerpt: `As we cross critical thresholds in national development, the sphere of ${slug} is undergoing a fundamental transformation that requires nuanced analysis.`,
    author: 'IndianOpinions Analysts',
    date: 'March 15, 2024',
    image: featuredImage,
  };

  const stories = [
    {
      category: title,
      title: `Deciphering the ${slug} Framework`,
      excerpt: 'An investigation into the foundational shifts currently impacting the regional and national landscape.',
      author: 'Naina Varma',
      date: 'March 14, 2024',
      image: PlaceHolderImages[0]?.imageUrl,
    },
    {
      category: title,
      title: `The Future of ${title}`,
      excerpt: 'Strategic forecasts and early indicators that will define the next decade of development.',
      author: 'Arjun Singh',
      date: 'March 13, 2024',
      image: PlaceHolderImages[1]?.imageUrl,
    },
    {
      category: title,
      title: `Strategic Impacts in ${title}`,
      excerpt: 'Assessing how global trends are intersecting with local realities to create new challenges.',
      author: 'Dr. Kabir Das',
      date: 'March 12, 2024',
      image: PlaceHolderImages[2]?.imageUrl,
    }
  ];

  return (
    <div className="min-h-screen">
      <Masthead />
      
      <main className="container mx-auto px-4 py-16">
        <header className="mb-16 border-b border-border pb-12 max-w-4xl">
          <span className="text-primary uppercase text-xs font-bold tracking-[0.3em] mb-4 inline-block">
            Intelligence Hub
          </span>
          <h1 className="text-6xl md:text-8xl font-bold mb-6 tracking-tighter">
            {title}
          </h1>
          <p className="text-xl md:text-2xl text-muted-foreground leading-relaxed font-headline italic">
            {HUB_DESCRIPTIONS[slug]}
          </p>
        </header>

        <section className="mb-20">
          <ArticleCard {...featuredArticle} layout="featured" />
        </section>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-12">
          {stories.map((story, idx) => (
            <ArticleCard key={idx} {...story} />
          ))}
        </div>
      </main>

      <WeeklyLetter />
      
      <footer className="bg-background border-t border-border py-12">
        <div className="container mx-auto px-4 text-center">
          <h2 className="font-headline text-2xl font-bold mb-2">IndianOpinions</h2>
          <p className="text-[10px] uppercase tracking-widest text-muted-foreground">Analytical Rigor • Global Perspective</p>
        </div>
      </footer>
    </div>
  );
}
