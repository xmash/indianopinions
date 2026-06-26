
"use client";

import React from 'react';
import { 
  BarChart, 
  Bar, 
  XAxis, 
  YAxis, 
  CartesianGrid, 
  Tooltip, 
  ResponsiveContainer,
  LineChart,
  Line
} from 'recharts';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { ArrowUpRight, Database } from 'lucide-react';

const gdpData = [
  { year: '2019', value: 2.8 },
  { year: '2020', value: 2.6 },
  { year: '2021', value: 3.1 },
  { year: '2022', value: 3.4 },
  { year: '2023', value: 3.7 },
  { year: '2024', value: 4.1 },
];

const infrastructureData = [
  { name: 'Aviation', val: 75 },
  { name: 'Roads', val: 92 },
  { name: 'Rail', val: 68 },
  { name: 'Tech', val: 88 },
];

export function DataLabModule() {
  return (
    <section className="bg-stone-100 p-8 rounded-lg border border-border my-16">
      <div className="flex items-center justify-between mb-8">
        <div>
          <div className="flex items-center gap-2 mb-2">
            <Database className="h-4 w-4 text-accent" strokeWidth={1.5} />
            <h2 className="text-xs font-bold uppercase tracking-[0.3em] text-accent">Data Lab</h2>
          </div>
          <h3 className="text-3xl font-bold">The Real Indicators of India's Future</h3>
        </div>
        <button className="flex items-center gap-2 text-xs font-bold uppercase tracking-widest hover:text-primary transition-colors">
          View Full Dashboard <ArrowUpRight className="h-4 w-4" />
        </button>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <Card className="bg-background border-none shadow-sm">
          <CardHeader>
            <CardTitle className="text-sm uppercase tracking-widest">GDP Nominal (Trillion USD)</CardTitle>
          </CardHeader>
          <CardContent className="h-[200px]">
            <ResponsiveContainer width="100%" height="100%">
              <LineChart data={gdpData}>
                <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#eee" />
                <XAxis dataKey="year" fontSize={10} axisLine={false} tickLine={false} />
                <YAxis hide />
                <Tooltip 
                  contentStyle={{ backgroundColor: '#F7F3EC', border: '1px solid #AC0000', fontSize: '10px' }}
                  labelStyle={{ fontWeight: 'bold' }}
                />
                <Line type="monotone" dataKey="value" stroke="#AC0000" strokeWidth={2} dot={{ fill: '#AC0000', r: 4 }} />
              </LineChart>
            </ResponsiveContainer>
          </CardContent>
        </Card>

        <Card className="bg-background border-none shadow-sm">
          <CardHeader>
            <CardTitle className="text-sm uppercase tracking-widest">Digital Infrastructure Expansion</CardTitle>
          </CardHeader>
          <CardContent className="h-[200px]">
            <ResponsiveContainer width="100%" height="100%">
              <BarChart data={infrastructureData}>
                <XAxis dataKey="name" fontSize={10} axisLine={false} tickLine={false} />
                <YAxis hide />
                <Tooltip 
                  contentStyle={{ backgroundColor: '#F7F3EC', border: '1px solid #AC0000', fontSize: '10px' }}
                />
                <Bar dataKey="val" fill="#9A7A43" radius={[4, 4, 0, 0]} />
              </BarChart>
            </ResponsiveContainer>
          </CardContent>
        </Card>

        <div className="flex flex-col gap-4">
          <div className="p-6 bg-background rounded-lg shadow-sm border-l-4 border-primary">
            <div className="text-[10px] font-bold uppercase tracking-widest text-muted-foreground mb-1">Rural Tech Adoption</div>
            <div className="text-3xl font-bold">+24% <span className="text-sm font-normal text-muted-foreground">YoY</span></div>
          </div>
          <div className="p-6 bg-background rounded-lg shadow-sm border-l-4 border-accent">
            <div className="text-[10px] font-bold uppercase tracking-widest text-muted-foreground mb-1">Global Capability Centers</div>
            <div className="text-3xl font-bold">1,600+ <span className="text-sm font-normal text-muted-foreground">units</span></div>
          </div>
        </div>
      </div>
    </section>
  );
}
