import Link from 'next/link';

type EmptyStateProps = {
  title: string;
  message: string;
  action?: {
    label: string;
    href: string;
  };
};

export function EmptyState({ title, message, action }: EmptyStateProps) {
  return (
    <div className="empty-state" role="status">
      <h2 className="empty-state-title">{title}</h2>
      <p className="empty-state-message">{message}</p>
      {action && (
        <Link href={action.href} className="btn-outline empty-state-action">
          {action.label}
        </Link>
      )}
    </div>
  );
}
