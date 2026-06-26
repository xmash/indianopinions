"use client";

import React from 'react';
import { Mail, ArrowRight } from 'lucide-react';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';

export function WeeklyLetter() {
  return (
    <section className="py-20 border-y border-border my-16 bg-[#5D0000] text-white">
      <div className="container mx-auto px-4 max-w-4xl text-center">
        <div className="inline-flex items-center gap-2 mb-6">
          <Mail className="h-5 w-5 text-accent" strokeWidth={1.5} />
          <span className="text-sm font-bold uppercase tracking-[0.4em] text-accent">The Weekly Letter</span>
        </div>
        <h2 className="text-4xl md:text-5xl font-headline font-bold mb-6">
          Cultivating India’s most insightful civic community.
        </h2>
        <p className="text-lg opacity-80 mb-10 max-w-2xl mx-auto font-body">
          Join 85,000+ policy makers, analysts, and leaders receiving our Sunday long-form intelligence briefing. No noise, just perspective.
        </p>
        
        <form className="flex flex-col md:flex-row gap-4 max-w-lg mx-auto" onSubmit={(e) => e.preventDefault()}>
          <Input 
            type="email" 
            placeholder="Your professional email" 
            className="bg-white/10 border-white/20 text-white placeholder:text-white/40 h-12"
          />
          <Button type="submit" className="h-12 bg-accent hover:bg-accent/90 text-white font-bold uppercase tracking-widest">
            Subscribe <ArrowRight className="ml-2 h-4 w-4" />
          </Button>
        </form>
        <p className="mt-6 text-[10px] uppercase tracking-widest opacity-40">
          Privacy matters. No spam. One click unsubscribe.
        </p>
      </div>
    </section>
  );
}
