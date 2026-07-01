import type {ApiMediaVideo} from '@/lib/api';

function formatDuration(seconds: number | null): string {
  if (!seconds || seconds <= 0) {
    return '';
  }

  const mins = Math.floor(seconds / 60);
  const secs = seconds % 60;

  return `${mins}:${secs.toString().padStart(2, '0')}`;
}

export function VideoCard({video}: {video: ApiMediaVideo}) {
  const title = video.title?.trim() || 'Untitled video';

  return (
    <article className="media-card">
      <div className="media-card-player">
        <video
          src={video.video_url}
          poster={video.thumbnail_url ?? undefined}
          controls
          preload="metadata"
          playsInline
          className="media-card-video"
        />
      </div>
      <div className="media-card-body">
        {video.category ? <p className="media-card-category">{video.category}</p> : null}
        <h3 className="media-card-title">{title}</h3>
        {video.description ? <p className="media-card-description">{video.description}</p> : null}
        {video.duration_seconds ? (
          <p className="media-card-duration">{formatDuration(video.duration_seconds)}</p>
        ) : null}
      </div>
    </article>
  );
}
