
import React from 'react';
import Link from 'next/link';
import Image from 'next/image';
import { cn } from '@/lib/utils';

interface ArticleCardProps {
  category: string;
  title: string;
  excerpt: string;
  author: string;
  date: string;
  image?: string;
  layout?: 'standard' | 'featured' | 'minimal';
}

export function ArticleCard({ 
  category, 
  title, 
  excerpt, 
  author, 
  date, 
  image, 
  layout = 'standard' 
}: ArticleCardProps) {
  if (layout === 'featured') {
    return (
      <article className="group flex flex-col md:flex-row gap-8 py-8 border-b border-border">
        <div className="md:w-3/5">
          <Link href="/article/modern-indian-renaissance" className="block overflow-hidden">
            {image && (
              <div className="aspect-[16/9] relative">
                <Image 
                  src={image} 
                  alt={title} 
                  fill 
                  className="object-cover transition-transform duration-700 group-hover:scale-105" 
                />
              </div>
            )}
          </Link>
        </div>
        <div className="md:w-2/5 flex flex-col justify-center">
          <span className="text-primary uppercase text-xs font-bold tracking-widest mb-3 inline-block">
            {category}
          </span>
          <Link href="/article/modern-indian-renaissance">
            <h2 className="text-4xl md:text-5xl font-bold mb-4 group-hover:text-primary transition-colors">
              {title}
            </h2>
          </Link>
          <p className="text-muted-foreground text-lg mb-6 leading-relaxed">
            {excerpt}
          </p>
          <div className="flex items-center gap-4 text-xs font-bold uppercase tracking-wider">
            <span>By {author}</span>
            <span className="w-1 h-1 bg-border rounded-full" />
            <span className="text-muted-foreground">{date}</span>
          </div>
        </div>
      </article>
    );
  }

  if (layout === 'minimal') {
    return (
      <article className="group py-6 border-b border-border last:border-0">
        <span className="text-accent uppercase text-[10px] font-bold tracking-[0.2em] mb-2 block">
          {category}
        </span>
        <Link href="/article/minimal-news">
          <h3 className="text-xl font-bold mb-2 group-hover:text-primary transition-colors leading-snug">
            {title}
          </h3>
        </Link>
        <div className="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">
          {author} • {date}
        </div>
      </article>
    );
  }

  return (
    <article className="group py-8 border-b border-border">
      {image && (
        <Link href="/article/standard-story" className="block mb-4 overflow-hidden">
          <div className="aspect-[3/2] relative">
            <Image 
              src={image} 
              alt={title} 
              fill 
              className="object-cover transition-transform duration-700 group-hover:scale-105" 
            />
          </div>
        </Link>
      )}
      <span className="text-primary uppercase text-[10px] font-bold tracking-[0.2em] mb-2 block">
        {category}
      </span>
      <Link href="/article/standard-story">
        <h3 className="text-2xl font-bold mb-3 group-hover:text-primary transition-colors">
          {title}
        </h3>
      </Link>
      <p className="text-muted-foreground text-sm mb-4 leading-relaxed line-clamp-3">
        {excerpt}
      </p>
      <div className="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">
        By {author}
      </div>
    </article>
  );
}
