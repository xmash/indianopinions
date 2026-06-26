'use server';
/**
 * @fileOverview An AI agent for summarizing long-form articles into concise briefings.
 *
 * - summarizeArticle - A function that handles the article summarization process.
 * - SummarizeArticleInput - The input type for the summarizeArticle function.
 * - SummarizeArticleOutput - The return type for the summarizeArticle function.
 */

import {ai} from '@/ai/genkit';
import {z} from 'genkit';

const SummarizeArticleInputSchema = z.object({
  articleContent: z
    .string()
    .describe(
      'The full text content of the long-form article or report to be summarized.'
    ),
});
export type SummarizeArticleInput = z.infer<typeof SummarizeArticleInputSchema>;

const SummarizeArticleOutputSchema = z.object({
  briefing: z
    .string()
    .describe(
      'A concise, actionable 60-second intelligence briefing summarizing the key insights of the article.'
    ),
});
export type SummarizeArticleOutput = z.infer<
  typeof SummarizeArticleOutputSchema
>;

export async function summarizeArticle(
  input: SummarizeArticleInput
): Promise<SummarizeArticleOutput> {
  return summarizeArticleFlow(input);
}

const prompt = ai.definePrompt({
  name: 'summarizeArticlePrompt',
  input: {schema: SummarizeArticleInputSchema},
  output: {schema: SummarizeArticleOutputSchema},
  prompt: `You are an expert analyst specialized in distilling complex long-form articles and reports into actionable intelligence briefings.

Your task is to summarize the provided article content into a concise briefing that can be consumed in approximately 60 seconds by busy professionals. Focus on extracting the most critical information, key insights, and actionable takeaways.

Article Content: {{{articleContent}}}`,
});

const summarizeArticleFlow = ai.defineFlow(
  {
    name: 'summarizeArticleFlow',
    inputSchema: SummarizeArticleInputSchema,
    outputSchema: SummarizeArticleOutputSchema,
  },
  async input => {
    const {output} = await prompt(input);
    return output!;
  }
);
