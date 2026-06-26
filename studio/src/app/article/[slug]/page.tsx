import React from 'react';
import { Masthead } from '@/components/editorial/Masthead';
import { IntelligenceBriefing } from '@/components/editorial/IntelligenceBriefing';
import { ReadingProgressBar } from '@/components/editorial/ReadingProgressBar';
import Image from 'next/image';
import { PlaceHolderImages } from '@/lib/placeholder-images';
import { WeeklyLetter } from '@/components/editorial/WeeklyLetter';
import { Bookmark, Share2, Printer, MessageSquare } from 'lucide-react';
import { Button } from '@/components/ui/button';

const ARTICLE_DATA: Record<string, any> = {
  'modern-indian-renaissance': {
    category: 'Cover Story • Policy',
    title: 'The Modern Indian Renaissance: Beyond the GDP Metric',
    author: 'Vikram Sethi',
    role: 'Principal Analyst, Intelligence Hub',
    date: 'March 12, 2024',
    image: 'hero-policy',
    caption: 'Visual Narrative: Abstract interpretation of India\'s growth architecture.',
    content: [
      "The historical narrative of India has often been written through the lens of external observers or distant elites. However, as we move deeper into the 21st century, a new phenomenon is emerging—the Modern Indian Renaissance. This is not merely an economic uptick driven by service sectors and manufacturing; it is a profound shift in the collective consciousness of a billion people.",
      "At the heart of this change is the decentralisation of influence. For decades, the intellectual life of the nation was concentrated in the drawing rooms of Lutyens' Delhi or the high-rises of South Mumbai. Today, a developer in Indore, a designer in Kochi, and a social entrepreneur in Guwahati are building the digital and physical infrastructure of tomorrow.",
      "This renaissance is characterised by three distinct pillars: Intellectual Sovereignty, Technological Empowerment, and Civilisational Confidence. As Gandhi wrote in the original Young India over a century ago, the goal was never just to replace one set of rulers with another, but to enable every Indian to realise their full potential."
    ],
    tags: ['Governance', 'Renaissance', 'Digital', 'Strategic Autonomy']
  }
};

export default async function ArticlePage({ params }: { params: Promise<{ slug: string }> }) {
  const { slug } = await params;
  const article = ARTICLE_DATA[slug] || ARTICLE_DATA['modern-indian-renaissance'];
  const heroImageUrl = PlaceHolderImages.find(img => img.id === article.image)?.imageUrl || "https://picsum.photos/seed/default/1200/600";

  return (
    <div className="min-h-screen bg-background selection:bg-primary/10">
      <ReadingProgressBar />
      <Masthead />
      
      <main className="container mx-auto px-4 py-16 max-w-6xl">
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-12">
          
          {/* Left Sidebar: Strategy Tools */}
          <aside className="hidden lg:block lg:col-span-1">
            <div className="sticky top-24 flex flex-col items-center gap-6 py-8 border-r border-border/50 h-fit">
              <Button variant="ghost" size="icon" title="Save Insight">
                <Bookmark className="h-5 w-5 text-muted-foreground hover:text-primary transition-colors" />
              </Button>
              <Button variant="ghost" size="icon" title="Share Thesis">
                <Share2 className="h-5 w-5 text-muted-foreground hover:text-primary transition-colors" />
              </Button>
              <Button variant="ghost" size="icon" title="Print Archive">
                <Printer className="h-5 w-5 text-muted-foreground hover:text-primary transition-colors" />
              </Button>
              <Button variant="ghost" size="icon" title="Discuss">
                <MessageSquare className="h-5 w-5 text-muted-foreground hover:text-primary transition-colors" />
              </Button>
            </div>
          </aside>

          {/* Main Content Column */}
          <article className="lg:col-span-8">
            <header className="mb-12">
              <div className="flex items-center gap-3 mb-6">
                <span className="bg-primary/10 text-primary px-3 py-1 text-[10px] font-bold uppercase tracking-[0.2em] rounded">
                  {article.category}
                </span>
                <span className="text-[10px] text-muted-foreground uppercase tracking-widest">
                  12 min read • {article.date}
                </span>
              </div>
              <h1 className="text-5xl md:text-7xl font-bold mb-8 leading-[1.1] tracking-tight">
                {article.title}
              </h1>
              <div className="flex items-center gap-4 border-b border-border pb-8">
                <div className="h-12 w-12 rounded-full bg-accent flex items-center justify-center text-white font-bold text-xl">
                  {article.author[0]}
                </div>
                <div>
                  <div className="text-lg font-bold uppercase tracking-widest text-foreground">
                    {article.author}
                  </div>
                  <div className="text-xs text-muted-foreground uppercase tracking-widest">
                    {article.role}
                  </div>
                </div>
              </div>
            </header>

            <div className="relative aspect-[21/9] w-full mb-12 overflow-hidden rounded-sm shadow-2xl">
              <Image 
                src={heroImageUrl} 
                alt={article.title} 
                fill 
                className="object-cover"
                priority
              />
              <div className="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-6 text-white text-[10px] uppercase tracking-widest font-bold">
                {article.caption}
              </div>
            </div>

            <div className="prose prose-lg max-w-none">
              <div className="serif-body first-letter:text-8xl first-letter:font-bold first-letter:text-primary first-letter:mr-4 first-letter:float-left first-letter:font-headline first-letter:leading-[0.8]">
                {article.content[0]}
              </div>

              <IntelligenceBriefing content={article.content.join('\n')} />

              {article.content.slice(1).map((para: string, i: number) => (
                <p key={i} className="serif-body mt-8 leading-relaxed text-foreground/90">{para}</p>
              ))}

              <blockquote className="my-16 px-12 py-8 bg-stone-100/50 border-l-4 border-primary italic">
                <p className="text-2xl font-headline font-medium leading-relaxed text-foreground/80">
                  "Real progress is when the tools of the future are used to honor the wisdom of the past, creating a synthesis of digital speed and civilisational depth."
                </p>
                <cite className="block mt-4 text-xs font-bold uppercase tracking-widest text-muted-foreground not-italic">
                  — Editorial Board, IndianOpinions
                </cite>
              </blockquote>
              
              <div className="grid grid-cols-1 md:grid-cols-2 gap-8 my-12 pt-12 border-t border-border">
                {article.content.slice(0, 2).map((_, i) => (
                  <p key={i} className="text-sm italic text-muted-foreground leading-relaxed">
                    This analysis is part of our ongoing series on the Intellectual Sovereignty of the Global Sub-continent.
                  </p>
                ))}
              </div>
            </div>
          </article>

          {/* Right Sidebar: Related Intelligence */}
          <aside className="lg:col-span-3">
            <div className="sticky top-24 space-y-12">
              <div className="bg-secondary p-6 rounded-sm border border-border/50">
                <h4 className="text-[10px] font-bold uppercase tracking-widest text-primary mb-4 border-b border-primary/20 pb-2">Editorial Context</h4>
                <p className="text-sm text-muted-foreground leading-relaxed italic">
                  "The 'New India' is not a geographical term, but a psychological state defined by the intersection of high-technology and ancient ethical frameworks."
                </p>
              </div>

              <div>
                <h4 className="text-[10px] font-bold uppercase tracking-widest text-accent mb-4 border-b border-accent/20 pb-2">Keywords</h4>
                <div className="flex flex-wrap gap-2">
                  {article.tags.map((tag: string) => (
                    <span key={tag} className="text-[10px] font-bold uppercase tracking-widest bg-stone-200 px-2 py-1 rounded">
                      {tag}
                    </span>
                  ))}
                </div>
              </div>

              <div className="border-t border-border pt-8">
                <h4 className="text-[10px] font-bold uppercase tracking-widest text-muted-foreground mb-4">Related Dives</h4>
                <ul className="space-y-6">
                  {['Digital Public Infrastructure', 'The Indo-Pacific Pivot', 'The Urban Nomad'].map((item) => (
                    <li key={item} className="group cursor-pointer">
                      <div className="text-[10px] uppercase text-primary font-bold tracking-widest mb-1">Strategic Note</div>
                      <div className="text-sm font-bold group-hover:text-primary transition-colors leading-snug">
                        {item}: Why tactical necessity is the mother of sovereignty.
                      </div>
                    </li>
                  ))}
                </ul>
              </div>
            </div>
          </aside>
        </div>
      </main>

      <WeeklyLetter />

      <footer className="bg-secondary py-16">
        <div className="container mx-auto px-4 text-center">
          <h2 className="font-headline text-3xl font-bold mb-4">IndianOpinions</h2>
          <p className="text-xs uppercase tracking-widest opacity-50 italic">Rigorous Analysis • Strategic Autonomy • Independent Editorial</p>
          <p className="mt-8 text-[10px] uppercase tracking-widest opacity-30">© 2024 Strategic Intelligence Trust</p>
        </div>
      </footer>
    </div>
  );
}
