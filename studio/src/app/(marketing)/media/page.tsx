import {VideoCard} from '@/components/media/VideoCard';
import {EmptyState} from '@/components/sections/EmptyState';
import {getMediaVideos} from '@/lib/api';

export default async function MediaPage() {
  const videos = await getMediaVideos();
  const featured = videos.find((video) => video.featured) ?? videos[0];
  const rest = videos.filter((video) => video.id !== featured?.id);

  return (
    <div className="media-page">
      {!videos.length ? (
        <EmptyState
          title="No videos yet"
          message="Editorial video will appear here once published from the admin Videos library."
          action={{label: 'Back to homepage', href: '/'}}
        />
      ) : (
        <>
          {featured ? (
            <section className="section media-featured">
              <VideoCard video={featured} />
            </section>
          ) : null}

          {rest.length > 0 ? (
            <section className="section">
              <h2 className="section-title">More video</h2>
              <div className="media-grid">
                {rest.map((video) => (
                  <VideoCard key={video.id} video={video} />
                ))}
              </div>
            </section>
          ) : null}
        </>
      )}
    </div>
  );
}
