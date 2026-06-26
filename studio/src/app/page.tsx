import React from 'react';
import { Masthead } from '@/components/editorial/Masthead';
import { ArticleCard } from '@/components/editorial/ArticleCard';
import { DataLabModule } from '@/components/editorial/DataLabModule';
import { WeeklyLetter } from '@/components/editorial/WeeklyLetter';
import { PlaceHolderImages } from '@/lib/placeholder-images';

export default function Home() {
  const heroArticle = {
    category: 'Foreign Affairs',
    title: 'The New Indo-Pacific Doctrine: Strategic Autonomy in the 21st Century',
    excerpt: 'As global power structures shift, New Delhi is navigating a complex multipolar world. We explore the pillars of a doctrine that balances tradition with tactical necessity.',
    author: 'Ambassador S. Jaishankar (Retd.)',
    date: 'March 18, 2024',
    image: PlaceHolderImages.find(img => img.id === 'hero-policy')?.imageUrl,
  };

  const secondaryArticles = [
    {
      category: 'Economy',
      title: 'Digital Public Infrastructure: The Export of the Decade',
      excerpt: 'How India’s stack is becoming the blueprint for the global south, bypassing legacy financial architectures.',
      author: 'Naina Varma',
      date: 'March 17, 2024',
      image: PlaceHolderImages.find(img => img.id === 'economy-data')?.imageUrl,
    },
    {
      category: 'Politics',
      title: 'The Decentralization Dilemma: Federalism at a Crossroads',
      excerpt: 'Investigating the growing fiscal divide between northern and southern states and the impact on national policy.',
      author: 'Prof. Arjun Singh',
      date: 'March 16, 2024',
      image: PlaceHolderImages.find(img => img.id === 'global-indian')?.imageUrl,
    }
  ];

  const briefStories = [
    {
      category: 'Technology',
      title: 'Quantum Computing: New Delhi’s Quiet Push',
      author: 'Dr. Vikram Shah',
      date: '12 min read'
    },
    {
      category: 'Society',
      title: 'The Rise of the Urban Nomad',
      author: 'Kavita Iyer',
      date: '9 min read'
    },
    {
      category: 'Diaspora',
      title: 'Remittances and Beyond: Wealth in the GCC',
      author: 'Sameer Sheikh',
      date: '15 min read'
    },
    {
      category: 'Opinion',
      title: 'The Ethics of Algorithmic Justice',
      author: 'Justice Rohini Bose',
      date: '10 min read'
    }
  ];

  return (
    <div className="min-h-screen">
      <Masthead />
      
      <main className="container mx-auto px-4 py-8">
        <div className="mb-12">
          <ArticleCard {...heroArticle} layout="featured" />
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-12 gap-12">
          <div className="lg:col-span-8">
            <h2 className="text-xs font-bold uppercase tracking-[0.3em] text-primary border-b border-primary/20 pb-2 mb-6">
              Strategic Analysis
            </h2>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-x-12">
              {secondaryArticles.map((article, idx) => (
                <ArticleCard key={idx} {...article} />
              ))}
            </div>
            
            <div className="mt-12 p-8 border border-accent/20 bg-accent/5 rounded-lg flex flex-col md:flex-row gap-8 items-center">
              <div className="flex-1">
                <h3 className="text-xl font-bold mb-2">The Intelligence Network</h3>
                <p className="text-sm text-muted-foreground leading-relaxed">
                  Join our pool of contributing analysts, diplomats, and industry leaders. We value qualitative rigor over quantitative noise.
                </p>
              </div>
              <button className="whitespace-nowrap bg-primary text-white px-6 py-2 rounded text-xs font-bold uppercase tracking-widest">
                Submit Thesis
              </button>
            </div>
          </div>

          <div className="lg:col-span-4 border-l border-border pl-12 hidden lg:block">
            <h2 className="text-xs font-bold uppercase tracking-[0.3em] text-accent border-b border-accent/20 pb-2 mb-6">
              The Daily Brief
            </h2>
            <div className="space-y-2">
              {briefStories.map((article, idx) => (
                <ArticleCard key={idx} {...article} layout="minimal" />
              ))}
            </div>
            
            <div className="mt-12 bg-secondary p-6 rounded-lg">
              <h4 className="font-headline font-bold text-lg mb-2">Strategy Note</h4>
              <p className="text-sm italic text-muted-foreground mb-4 leading-relaxed">
                "In foreign policy, there are no permanent friends or enemies, only permanent interests."
              </p>
              <div className="text-[10px] font-bold uppercase tracking-widest">— Editorial Board, IndianOpinions</div>
            </div>
          </div>
        </div>

        <DataLabModule />
      </main>

      <WeeklyLetter />

      <footer className="bg-background border-t border-border py-12">
        <div className="container mx-auto px-4">
          <div className="flex flex-col md:flex-row justify-between items-center gap-8">
            <div className="text-center md:text-left">
              <h2 className="font-headline text-3xl font-bold mb-1">IndianOpinions</h2>
              <p className="text-xs uppercase tracking-widest text-muted-foreground">© 2024 Strategic Intelligence Trust</p>
            </div>
            <nav className="flex gap-8 text-[10px] font-bold uppercase tracking-widest">
              <a href="#" className="hover:text-primary transition-colors">Editorial Ethics</a>
              <a href="#" className="hover:text-primary transition-colors">Strategic Partners</a>
              <a href="#" className="hover:text-primary transition-colors">Contact</a>
              <a href="#" className="hover:text-primary transition-colors">LinkedIn</a>
            </nav>
          </div>
        </div>
      </footer>
    </div>
  );
}
