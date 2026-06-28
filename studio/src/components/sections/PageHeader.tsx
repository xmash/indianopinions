import type { ReactNode } from 'react';

type PageHeaderSize = 'hub' | 'article' | 'default';

type PageHeaderProps = {
  eyebrow?: string;
  title: string;
  description?: string;
  meta?: ReactNode;
  size?: PageHeaderSize;
  align?: 'left' | 'center';
  children?: ReactNode;
  className?: string;
};

const titleClass: Record<PageHeaderSize, string> = {
  hub: 'page-header-title-hub',
  article: 'page-header-title-article',
  default: 'page-header-title-default',
};

export function PageHeader({
  eyebrow,
  title,
  description,
  meta,
  size = 'default',
  align = 'left',
  children,
  className = '',
}: PageHeaderProps) {
  return (
    <header
      className={`page-header page-header-${align}${className ? ` ${className}` : ''}`}
    >
      {eyebrow && <span className="page-header-eyebrow">{eyebrow}</span>}
      <h1 className={titleClass[size]}>{title}</h1>
      {description && <p className="page-header-description">{description}</p>}
      {meta && <div className="page-header-meta">{meta}</div>}
      {children}
    </header>
  );
}
