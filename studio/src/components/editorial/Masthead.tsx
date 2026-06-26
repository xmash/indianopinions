import React from 'react';
import Link from 'next/link';

export function Masthead() {
  const today = new Date().toLocaleDateString('en-GB', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });

  const menuItems = [
    { name: 'Politics', path: '/hubs/politics' },
    { name: 'Economy', path: '/hubs/economy' },
    { name: 'Foreign Affairs', path: '/hubs/foreign-affairs' },
    { name: 'Society', path: '/hubs/society' },
    { name: 'Technology', path: '/hubs/technology' },
    { name: 'Diaspora', path: '/hubs/diaspora' },
    { name: 'Opinion', path: '/hubs/opinion' },
    { name: 'Archive', path: '/hubs/archive' },
  ];

  return (
    <header className="w-full pt-10 pb-6 border-b border-foreground/10">
      <div className="container mx-auto px-4 text-center">
        <div className="flex justify-between items-center text-[10px] uppercase tracking-[0.2em] font-medium mb-4 text-muted-foreground border-b border-border pb-2">
          <span>Strategic Intelligence • Independent Editorial</span>
          <span className="hidden md:block text-primary font-bold italic">RECLAIMING THE NARRATIVE</span>
          <span>New Delhi • London • New York</span>
        </div>
        
        <Link href="/" className="inline-block group">
          <h1 className="font-headline text-6xl md:text-9xl tracking-tighter mb-2 transition-colors group-hover:text-primary leading-none">
            IndianOpinions
          </h1>
          <p className="font-headline italic text-lg md:text-2xl text-muted-foreground mt-2">
            Critical Perspectives for the Global Sub-continent
          </p>
        </Link>
        
        <div className="mt-10 flex justify-between items-center border-y border-foreground py-3 text-xs font-bold uppercase tracking-widest">
          <span className="flex-1 text-left hidden md:block">Volume CIV • No. 427</span>
          <span className="flex-1 text-center font-headline italic tracking-normal">{today}</span>
          <span className="flex-1 text-right hidden md:block text-primary tracking-[0.2em]">Intelligence Engine Active</span>
        </div>

        <nav className="mt-4 flex justify-center gap-6 md:gap-12 overflow-x-auto whitespace-nowrap py-2 no-scrollbar">
          {menuItems.map((item) => (
            <Link 
              key={item.name} 
              href={item.path}
              className="text-sm font-bold uppercase tracking-widest hover:text-primary transition-colors border-b border-transparent hover:border-primary pb-1"
            >
              {item.name}
            </Link>
          ))}
        </nav>
      </div>
    </header>
  );
}
